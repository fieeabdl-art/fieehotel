<!DOCTYPE html>
<html>
<head>
    <base href="/public"> <!-- Sangat penting agar CSS/JS tetap jalan [19] -->
    @include('user.css')
    <link rel="stylesheet" href="{{ asset('css/room_details.css') }}">
</head>
<body>
    @include('user.header')
{{-- Pesan top-alert dihapus: semua notifikasi sekarang muncul di modal. --}}
{{-- Catatan untuk developer: sebelumnya ada banner/alert di atas halaman yang menampilkan session('message').
     Karena permintaan, banner itu dihapus supaya semua pesan hanya muncul di modal sukses atau modal error. --}}
   <div class="room_details py-5">
    <div class="container">

        <div class="row">

            <!-- Detail Kamar -->
            <div class="col-lg-8">

                <div class="card shadow border-0">

                    <img src="/room/{{$room->image}}"
                        class="card-img-top"
                        style="height:450px; object-fit:cover;">

                    <div class="card-body">

                        <h2 class="mb-3">{{$room->room_title}}</h2>

                        <p class="text-muted">
                            {{$room->description}}
                        </p>

                        <hr>

                        <div class="row">

                            <div class="col-md-6">
                                <h5>📶 Free WiFi</h5>
                                <p>{{$room->wifi}}</p>
                            </div>

                            <div class="col-md-6">
                                <h5>🛏 Room Type</h5>
                                <p>{{$room->room_type}}</p>
                            </div>

                        </div>

                        <h3 class="text-danger mt-3">
                            Rp {{ number_format($room->price,0,',','.') }}
                        </h3>

                    </div>

                </div>

            </div>

            <!-- Form Booking -->
            <div class="col-lg-4">

                <div class="card shadow">

                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Book Room</h4>
                    </div>

                 <div class="card-body">

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('add_booking', $room->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ Auth::check() ? Auth::user()->name : '' }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   value="{{ Auth::check() ? Auth::user()->email : '' }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text"
                   name="phone"
                   class="form-control"
                   value="{{ Auth::check() ? Auth::user()->phone : '' }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Start Date</label>
            <input type="date"
                   name="startDate"
                   id="startDate"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">End Date</label>
            <input type="date"
                   name="endDate"
                   id="endDate"
                   class="form-control"
                   required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Book Room
        </button>

    </form>

</div>
                </div>

            </div>

        </div>

    </div>
</div>
    </div>

@include('user.script')

    {{-- // Elemen tersembunyi yang membawa pesan sukses dari session
         // Blade akan merender elemen ini hanya jika session('success') ada
         // Data-message berisi teks pesan, data-duration (opsional) berisi durasi hitung mundur dalam detik --}}
    @if(session('success'))
        <div id="booking-success-message" data-message="{{ session('success') }}" data-duration="10" style="display:none"></div>
    @endif

    @if(session('error'))
        <div id="booking-error-message" data-message="{{ session('error') }}" data-duration="10" style="display:none"></div>
    @endif

    {{-- // Modal centered untuk menampilkan notifikasi sukses booking (TETAP PERSIS, JANGAN DIUBAH) --}}
    {{-- // PENTING: Modal sukses dipertahankan 100% sesuai desain awal (ukuran, posisi, padding, shadow, countdown, tombol) --}}
    <div id="bookingModal" style="display:none; position:fixed; inset:0; z-index:2000;">
      <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:22px; border-radius:12px; box-shadow:0 12px 32px rgba(0,0,0,0.25); width:360px; max-width:90%; text-align:center;">
        <!-- Check icon -->
        <div style="width:72px; height:72px; margin:0 auto 8px; display:flex; align-items:center; justify-content:center; border-radius:50%; background:#e9f7ef;">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" fill="#2ecc71"/>
            <path d="M7 12.5L10 15.5L17 8.5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>

        {{-- // Judul modal (statis): ubah di sini jika mau teks judul berbeda --}}
        <h5 style="margin:0 0 6px; font-weight:700;">Pesanan berhasil di buat</h5>

        {{-- // Elemen untuk menampilkan pesan dinamis dari controller (session)
             // Isi ini akan di-set oleh pemilihtanggallampau.js --}}
        <p id="bookingModalMessage" style="margin:0 0 12px; color:#444; font-size:14px;">&nbsp;</p>

        {{-- // Baris hitung mundur: angka di dalam #bookingModalCountdown akan diupdate oleh JS --}}
        <div style="font-weight:600; margin-bottom:12px;">Otomatis ditutup dalam <span id="bookingModalCountdown">10</span>s</div>

        {{-- // Tombol tutup manual: klik untuk menutup modal segera --}}
        <div style="display:flex; gap:8px; justify-content:center;">
          <button id="bookingModalClose" type="button" class="btn btn-outline-secondary">Tutup</button>
        </div>
      </div>
    </div>

    {{-- // Modal error (SALINAN PERSIS dari modal sukses)
         // CATATAN: Jangan ubah ukuran/posisi/padding/shadow/struktur – hanya ikon, judul, dan isi pesan berbeda.
         // Struktur ID mengikuti pola: bookingErrorModal, bookingErrorModalMessage, bookingErrorModalCountdown, bookingErrorModalClose
     --}}
    <div id="bookingErrorModal" style="display:none; position:fixed; inset:0; z-index:2000;">
      <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:22px; border-radius:12px; box-shadow:0 12px 32px rgba(0,0,0,0.25); width:360px; max-width:90%; text-align:center;">
        <!-- Error icon: lingkaran merah dengan X putih (warna #dc3545) -->
        <div style="width:72px; height:72px; margin:0 auto 8px; display:flex; align-items:center; justify-content:center; border-radius:50%; background:#f8d7da;">
          <svg width="40" height="40" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" fill="#dc3545"/>
            <path d="M8 8L16 16M16 8L8 16" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </div>

        {{-- // Judul modal error (statis) – berwarna merah untuk menandakan error --}}
        <h5 style="margin:0 0 6px; font-weight:700; color:#dc3545;">Pesanan tidak tersedia</h5>

        {{-- // Elemen untuk menampilkan pesan dinamis error dari controller (session)
             // Isi ini akan di-set oleh pemilihtanggallampau.js --}}
        <p id="bookingErrorModalMessage" style="margin:0 0 12px; color:#444; font-size:14px;">&nbsp;</p>

        {{-- // Baris hitung mundur: angka di dalam #bookingErrorModalCountdown akan diupdate oleh JS --}}
        <div style="font-weight:600; margin-bottom:12px;">Otomatis ditutup dalam <span id="bookingErrorModalCountdown">10</span>s</div>

        {{-- // Tombol tutup manual: klik untuk menutup modal segera --}}
        <div style="display:flex; gap:8px; justify-content:center;">
          <button id="bookingErrorModalClose" type="button" class="btn btn-outline-secondary">Tutup</button>
        </div>
      </div>
    </div>

    {{-- // Load script yang mengatur tanggal dan modal; sudah memastikan jQuery dimuat di @include('user.script') --}}
    <script src="{{ asset('js/pemilihtanggallampau.js') }}"></script>
    @include('user.footer')
</body>
</html>