<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room; // Import model Room buat nampung data kamar [9]
use App\Models\Booking;
use Illuminate\Support\Facades\DB; // Digunakan untuk transaction dan locking
use Illuminate\Support\Facades\Auth; // Digunakan untuk helper auth yang dikenali oleh intelephense
use Carbon\Carbon; // Digunakan untuk normalisasi tanggal

class HomeController extends Controller
{
    public function room_details(int $id)
    {
        // Ambil data kamar berdasarkan ID
        $room = Room::find($id);

        // Cek apakah kamar ditemukan
        if (!$room) {
            return redirect()->back()->with('error', 'Kamar tidak ditemukan.');
        }

        // Tampilkan view dengan data kamar
        return view('user.room_details', compact('room'));
    }


    public function inbox()
    {
        // Menampilkan halaman inbox untuk user yang login
        return view('user.inbox');
    }

    public function inbox_mark_read(Request $request, int $id)
    {
        // Tandai entri inbox tertentu sebagai sudah dibaca oleh user yang login
        $entry = \App\Models\InboxEntry::find($id); // cari entri
        $userId = Auth::check() ? Auth::id() : null;

        if ($entry && $entry->user_id == $userId) {
            $entry->is_read = true; // set flag sudah dibaca
            $entry->save();
        }

        return redirect()->back();
    }


    public function home()
    {
        $rooms = Room::all();

        return view('user.index', compact('rooms'));
    }
    // Fungsi untuk menambahkan booking
    public function add_booking(Request $request, int $id)
    {
        // VALIDASI INPUT: pastikan data yang masuk benar (hindari error runtime)
        $validated = $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
        ]);

        // Pastikan kamar (room) ada sebelum melanjutkan
        $room = Room::find($id);
        if (!$room) {
            // Gunakan session('error') sehingga JS/Blade menampilkan modal error
            return redirect()->back()->with('error', 'Kamar tidak ditemukan.');
        }

        // Normalisasi tanggal menggunakan Carbon untuk menghindari masalah format/timezone
        try {
            $startDate = Carbon::parse($validated['startDate'])->toDateString();
            $endDate = Carbon::parse($validated['endDate'])->toDateString();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Format tanggal tidak valid.');
        }

        // Gunakan transaction untuk meminimalkan race condition antara cek dan insert
        try {
            $result = DB::transaction(function () use ($id, $startDate, $endDate, $validated) {
                // Ulangi cek overlap di dalam transaksi dan gunakan lockForUpdate untuk safety
                $isBooked = Booking::where('room_id', $id)
                    ->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $startDate)
                    ->lockForUpdate()
                    ->exists();

                if ($isBooked) {
                    // kembalikan tanda bahwa sudah dibooking
                    return ['booked' => true];
                }

                // Simpan booking baru (gunakan data yang telah tervalidasi)
                $data = new Booking;
                $data->room_id = $id;
                $data->name = $validated['name'];
                $data->email = $validated['email'];
                $data->phone = $validated['phone'];
                $data->start_date = $startDate;
                $data->end_date = $endDate;
                $data->save();

                return ['booked' => false];
            });

            if ($result['booked']) {
                // Jika kamar sudah dipesan maka buat entri inbox untuk user yang mencoba memesan
                $user = \App\Models\User::where('email', $validated['email'])->first(); // cari user pemesan berdasarkan email
                if ($user) {
                    \App\Models\InboxEntry::create([
                        'user_id' => $user->id, // id user penerima
                        'title' => 'Booking Gagal', // judul pesan singkat
                        'message' => 'Ruangan telah dipesan pada tanggal tersebut. Silakan pilih tanggal lain.', // isi pesan
                        'is_read' => false, // belum dibaca
                    ]);
                }

                return redirect()->back()->with('error', 'Ruangan telah dipesan pada tanggal tersebut. Silakan pilih tanggal lain.');
            }

            // Pesan sukses: buat booking dan juga simpan entri inbox untuk konfirmasi kepada user pemesan
            $user = \App\Models\User::where('email', $validated['email'])->first(); // cari user pemesan berdasarkan email
            if ($user) {
                \App\Models\InboxEntry::create([
                    'user_id' => $user->id,
                    'title' => 'Booking Berhasil',
                    'message' => 'Booking kamar berhasil dibuat.',
                    'is_read' => false,
                ]);
            }

            return redirect()->back()->with('success', 'Booking kamar berhasil dibuat.');
        } catch (\Exception $e) {
            // Log error jika perlu (tidak menambahkan dependency logging di sini)
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan booking. Silakan coba lagi.');
        }
    }
}
