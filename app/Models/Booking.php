<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{// Menentukan nama tabel yang digunakan
    public function room() 
{
    // Menghubungkan room_id di tabel bookings dengan id di tabel rooms
    return $this->hasOne('App\Models\Room', 'id', 'room_id');
}
    //
}
