<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboxEntry extends Model
{
    // Menentukan kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'user_id', // id user penerima pesan (nullable jika tidak terhubung ke user)
        'sender_name',
        'sender_email',
        'title',   // judul pesan singkat
        'message', // isi pesan lengkap
        'is_read', // status terbaca (0 = belum, 1 = sudah)
        'is_starred',
        'is_important',
    ];

    // Casting kolom menjadi tipe yang sesuai agar mudah dipakai di Blade/Controller
    protected $casts = [
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
        'is_important' => 'boolean',
    ];

    // Relasi optional ke User bila pesan terhubung ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
