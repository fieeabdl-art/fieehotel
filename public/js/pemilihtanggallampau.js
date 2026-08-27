// File: pemilihtanggallampau.js
// Fungsi: mengatur atribut tanggal pada input startDate/endDate
//        serta menangani tampilan modal sukses dan modal error setelah booking
// Catatan: Jangan hapus file ini bila masih digunakan di halaman room_details.blade.php

$(function () {

    // ===== inisialisasi tanggal minimal (format YYYY-MM-DD) =====
    var today = new Date();

    var month = today.getMonth() + 1;
    var day = today.getDate();
    var year = today.getFullYear();

    if (month < 10) {
        month = '0' + month; // tambahkan 0 di depan jika bulan < 10
    }

    if (day < 10) {
        day = '0' + day; // tambahkan 0 di depan jika tanggal < 10
    }

    var minDate = year + '-' + month + '-' + day; // hasil "YYYY-MM-DD"

    // Start Date minimal hari ini
    $('#startDate').attr('min', minDate); // set atribut min pada input #startDate

    // End Date minimal hari ini
    $('#endDate').attr('min', minDate); // set atribut min pada input #endDate

    // Ketika Start Date dipilih, update min End Date
    $('#startDate').on('change', function () {

        var startDate = $(this).val(); // ambil nilai startDate dari input

        // End Date tidak boleh sebelum Start Date
        $('#endDate').attr('min', startDate); // set min endDate sama dengan startDate

        // Jika nilai endDate sudah lebih kecil dari startDate, kosongkan nilai endDate
        if ($('#endDate').val() < startDate) {
            $('#endDate').val(''); // kosongkan input endDate supaya user memilih ulang
        }

    });

    // ===== tampilkan modal sukses booking (centered) jika ada pesan dari server =====
    // Blade membuat elemen tersembunyi <div id="booking-success-message" data-message="..." data-duration="..."> saat session('success') ada
    var bookingMsgEl = $('#booking-success-message'); // cari elemen hidden yang membawa pesan
    if (bookingMsgEl.length) {
        // ambil pesan dari atribut data-message (direkomendasikan)
        var msg = bookingMsgEl.data('message') || bookingMsgEl.attr('data-message') || bookingMsgEl.text();
        if (msg) {
            var $modal = $('#bookingModal'); // wrapper modal (overlay)
            var $modalMsg = $('#bookingModalMessage'); // elemen untuk pesan dinamis
            var $modalCountdown = $('#bookingModalCountdown'); // elemen hitung mundur di modal
            var $modalClose = $('#bookingModalClose'); // tombol tutup manual di modal

            $modalMsg.text(msg); // isi pesan ke dalam modal

            // baca durasi dari attribute data-duration jika ada, default 10 detik
            // parseInt mengubah string ke integer; jika gagal, fallback ke 10
            var duration = parseInt(bookingMsgEl.data('duration')) || 10; // durasi dalam detik (ubah di Blade jika perlu)
            var remaining = duration; // sisa detik
            $modalCountdown.text(remaining); // tampilkan nilai awal hitung mundur

            // tampilkan modal dengan efek sederhana
            $modal.fadeIn(200);

            // mulai interval hitung mundur, update setiap 1 detik
            var countdownInterval = setInterval(function () {
                remaining -= 1; // kurangi 1 detik
                if (remaining < 0) remaining = 0; // jangan negatif
                $modalCountdown.text(remaining); // update teks hitung mundur
                if (remaining <= 0) {
                    clearInterval(countdownInterval); // hentikan interval
                    $modal.fadeOut(200); // sembunyikan modal
                }
            }, 1000);

            // handler tombol tutup: hentikan countdown dan sembunyikan modal
            $modalClose.on('click', function () {
                clearInterval(countdownInterval);
                $modal.fadeOut(200);
            });

            // jika pengguna klik di area overlay (di luar isi modal), tutup juga
            $modal.on('click', function (e) {
                if (e.target === this) {
                    clearInterval(countdownInterval);
                    $modal.fadeOut(200);
                }
            });
        }
    }

    // ===== tampilkan modal error booking jika ada pesan error dari server =====
    // NOTE: blok ini adalah SALINAN LOGIKA modal sukses namun menargetkan elemen error
    // Blade membuat elemen tersembunyi <div id="booking-error-message" data-message="..." data-duration="..."> saat session('error') ada
    var bookingErrorEl = $('#booking-error-message'); // cari elemen hidden untuk error
    if (bookingErrorEl.length) {
        var emsg = bookingErrorEl.data('message') || bookingErrorEl.attr('data-message') || bookingErrorEl.text();
        if (emsg) {
            var $emodal = $('#bookingErrorModal'); // wrapper modal error (struktur sama persis dengan modal sukses)
            var $emodalMsg = $('#bookingErrorModalMessage'); // elemen pesan error
            var $emodalCountdown = $('#bookingErrorModalCountdown'); // elemen hitung mundur error
            var $emodalClose = $('#bookingErrorModalClose'); // tombol tutup error

            // set pesan error ke dalam modal (dinamis dari session)
            $emodalMsg.text(emsg);

            // baca durasi dari data-duration jika ada, default 10 detik
            var eduration = parseInt(bookingErrorEl.data('duration')) || 10;
            var eremaining = eduration;
            $emodalCountdown.text(eremaining);

            // tampilkan modal error (fadeIn sama seperti modal sukses untuk konsistensi)
            $emodal.fadeIn(200);

            // mulai interval hitung mundur untuk modal error (sama persis dengan modal sukses)
            var ecountdownInterval = setInterval(function () {
                eremaining -= 1;
                if (eremaining < 0) eremaining = 0;
                $emodalCountdown.text(eremaining);
                if (eremaining <= 0) {
                    clearInterval(ecountdownInterval);
                    $emodal.fadeOut(200);
                }
            }, 1000);

            // tombol tutup handler untuk modal error: hentikan countdown dan sembunyikan modal
            $emodalClose.on('click', function () {
                clearInterval(ecountdownInterval);
                $emodal.fadeOut(200);
            });

            // klik di overlay untuk menutup modal error (sama seperti modal sukses)
            $emodal.on('click', function (e) {
                if (e.target === this) {
                    clearInterval(ecountdownInterval);
                    $emodal.fadeOut(200);
                }
            });
        }
    }

});