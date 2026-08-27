<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InboxController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Buat rute yang manggil function 'index' di AdminController
    Route::get('/home', [AdminController::class, 'index'])->name('home');
    Route::get('/room_details/{id}', [HomeController::class, 'room_details']);
    Route::get('/create_room', [AdminController::class, 'create_room']);
    Route::post('/add_room', [AdminController::class, 'add_room']);
    Route::get('/view_room', [AdminController::class, 'view_room']);
    Route::get('/room_update/{id}', [AdminController::class, 'room_update']);
    Route::post('/edit_room/{id}', [AdminController::class, 'edit_room']);
   Route::get('/dashboard', [HomeController::class, 'home'])
    ->name('dashboard');


// Route untuk menghapus kamar berdasarkan ID
    Route::get('/room_delete/{id}', [AdminController::class, 'room_delete']);

//Halaman Detail Kamar
    Route::get('/room_details/{id}', [HomeController::class, 'room_details']); 

    // Route untuk menampilkan halaman booking
    Route::post('/add_booking/{id}', [HomeController::class, 'add_booking']);
    // menampilkan halaman booking untuk admin
    Route::get('/bookings', [AdminController::class, 'bookings']);
    // menghapus data booking dari database berdasarkan ID
    Route::get('/booking_delete/{id}', [AdminController::class, 'booking_delete']);
    // update status booking: diterima atau ditolak
    Route::get('/booking_approve/{id}', [AdminController::class, 'booking_approve']);
    Route::get('/booking_reject/{id}', [AdminController::class, 'booking_reject']);

    // ================= Inbox untuk user (pesan masuk) =================
    // New InboxController handles list, view, compose, send, toggles and bulk actions
    Route::get('/inbox', [InboxController::class, 'index']);
    Route::get('/inbox/compose', [InboxController::class, 'compose']);
    Route::post('/inbox/send', [InboxController::class, 'send']);
    Route::get('/inbox/{id}', [InboxController::class, 'show']);
    Route::post('/inbox/{id}/toggle-star', [InboxController::class, 'toggleStar']);
    Route::post('/inbox/{id}/toggle-important', [InboxController::class, 'toggleImportant']);
    Route::post('/inbox/bulk-action', [InboxController::class, 'bulkAction']);
    Route::post('/inbox/delete/{id}', [InboxController::class, 'destroy']);

});

