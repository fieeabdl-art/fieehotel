<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import model User [2]
use Illuminate\Support\Facades\Auth; // Import facade Auth buat ngecek login [2]
use App\Models\Room; // Import model Room buat nampung data kamar [9]
use App\Models\Booking; // Import model Booking buat nampung data pemesanan [2]

class AdminController extends Controller
{
    public function home() 
{
    // Mengambil semua data dari tabel rooms [2]
    $rooms = Room::all(); 

    // Mengirim data ke view home.index menggunakan compact [1, 3]
    return view('home.index', compact('rooms')); 
}

// Lakukan hal yang sama pada fungsi untuk pengguna yang login (biasanya ind
    public function index()
    {
        // 1. Cek dulu, ada yang login nggak? [2]
        if (Auth::id()) {
            
            // 2. Ambil data 'user_type' dari user yang lagi login [3]
            $user_type = Auth::user()->user_type;

            // 3. Kondisi: Kalau dia user biasa [4]
                    if ($user_type == 'user') {
                        $rooms = Room::all();
                        return view('user.index', compact('rooms')); // Lempar ke dashboard default user [5]
            } 
            
            // 4. Kondisi: Kalau dia admin [4]
            elseif ($user_type == 'admin') {
                return view('admin.index'); // Lempar ke dashboard khusus admin [5]
            } 
            
            // 5. Kalau tipenya aneh-aneh, balikin aja [6]
            else {
                return redirect()->back();
            }
            
        }
    }
    public function create_room() 
{
    return view('admin.create_room'); // [10]
}

// Fungsi buat nampung data dari form tambah kamar [10]
public function add_room(Request $request) 
{
    $data = new Room; // [9]
    $data->room_title = $request->title;
    $data->description = $request->description;
    $data->price = $request->price;
    $data->wifi = $request->wifi;
    $data->room_type = $request->type; // [11-13]

    $image = $request->image; // [14]
    if($image) {
        $imagename = time().'.'.$image->getClientOriginalExtension(); // [15]
        $request->image->move('room', $imagename); // Menyimpan di folder public/room [16]
        $data->image = $imagename;
    }

    $data->save(); // [16, 17]
    return redirect()->back(); // [13]
}
public function view_room() 
{
    // Mengambil semua data dari tabel rooms melalui model Room [4]
    $data = Room::all(); 
    // Mengirimkan data tersebut ke file view admin/view_room.blade.php [5]
    return view('admin.view_room', compact('data')); 
}

public function room_delete($id) 
{
    // Mencari data spesifik di database berdasarkan ID yang dikirim [3]
    $data = Room::find($id); 
    // Menjalankan perintah hapus pada data tersebut di database [6]
    $data->delete(); 
    // Mengarahkan kembali admin ke halaman sebelumnya [6]
    return redirect()->back(); 
}

public function room_update($id) 
{
    $data = Room::find($id); // Mencari data berdasarkan ID
    return view('admin.update_room', compact('data')); // Mengirim data ke view update_room
}

public function edit_room(Request $request, $id) 
{
    $data = Room::find($id);
    $data->room_title = $request->title;
    $data->description = $request->description;
    $data->price = $request->price;
    $data->wifi = $request->wifi;
    $data->room_type = $request->type;

    $image = $request->image;
    if($image) {
        // Logika jika ada gambar baru yang diunggah
        $imagename = time().'.'.$image->getClientOriginalExtension();
        $request->image->move('room', $imagename);
        $data->image = $imagename;
    }

    $data->save(); // Menyimpan semua perubahan
    return redirect()->back();
}

public function bookings() {
    $data = Booking::all(); // Mengambil semua data pemesanan
    return view('admin.booking', compact('data'));
}

public function booking_delete($id)
{
    // Cari data booking berdasarkan ID
    $booking = Booking::find($id);

    // Jika booking tidak ditemukan, kembali ke halaman sebelumnya dengan pesan error
    if (!$booking) {
        return redirect()->back()->with('error', 'Booking tidak ditemukan.');
    }

    // Simpan informasi user terkait (jika ada) sebelum dihapus, supaya dapat diberi notifikasi
    $user = \App\Models\User::where('email', $booking->email)->first(); // cari user berdasarkan email booking

    // Hapus data booking dari database
    $booking->delete();

    // Jika user ditemukan, buat entri inbox bahwa booking dihapus
    if ($user) {
        \App\Models\InboxEntry::create([
            'user_id' => $user->id, // id user penerima
            'title' => 'Booking Dihapus', // judul pesan singkat
            'message' => 'Booking Anda telah dihapus oleh admin.', // isi pesan
            'is_read' => false, // belum dibaca
        ]);
    }

    // Kembali ke halaman list booking setelah data dihapus
    return redirect()->back()->with('success', 'Booking berhasil dihapus.');
}

public function booking_approve($id)
{
    // Cari booking berdasarkan ID sebelum mengubah status
    $booking = Booking::find($id);

    // Jika booking tidak ditemukan, kembalikan error
    if (!$booking) {
        return redirect()->back()->with('error', 'Booking tidak ditemukan.');
    }

    // Ubah status menjadi Diterima agar tampil di list booking dengan status terbaru
    $booking->status = 'Diterima';
    $booking->save();

    // Simpan entri inbox untuk user pemilik booking (jika dapat ditemukan)
    // Cari user berdasarkan email di booking agar dapat dikirimi pesan
    $user = \App\Models\User::where('email', $booking->email)->first(); // cari user penerima pesan berdasarkan email booking
    if ($user) {
        \App\Models\InboxEntry::create([
            'user_id' => $user->id, // id user penerima
            'title' => 'Booking Diterima', // judul pesan singkat
            'message' => 'Hai User Status booking Anda sudah berubah menjadi Diterima.', // isi pesan
            'is_read' => false, // belum dibaca
        ]);
    }

    // Kembali ke halaman sebelumnya dengan pesan sukses
    return redirect()->back()->with('success', 'Booking diterima.');
}

public function booking_reject($id)
{
    // Cari booking berdasarkan ID sebelum mengubah status
    $booking = Booking::find($id);

    // Jika booking tidak ditemukan, kembalikan error
    if (!$booking) {
        return redirect()->back()->with('error', 'Booking tidak ditemukan.');
    }

    // Ubah status menjadi Ditolak agar terlihat di list booking yang ditolak
    $booking->status = 'Ditolak';
    $booking->save();

    // Simpan entri inbox untuk user pemilik booking (jika dapat ditemukan)
    $user = \App\Models\User::where('email', $booking->email)->first(); // cari user penerima pesan berdasarkan email booking
    if ($user) {
        \App\Models\InboxEntry::create([
            'user_id' => $user->id, // id user penerima
            'title' => 'Booking Ditolak', // judul pesan singkat
            'message' => 'Mohon maaf, booking Anda untuk kamar ini Ditolak.', // isi pesan
            'is_read' => false, // belum dibaca
        ]);
    }

    // Kembali ke halaman sebelumnya dengan pesan sukses
    return redirect()->back()->with('success', 'Booking ditolak.');
}


}
