<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inbox_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // id user penerima pesan, kalau null berarti pesan umum
            $table->string('title')->nullable(); // judul pesan
            $table->text('message')->nullable(); // isi pesan
            $table->boolean('is_read')->default(false); // status terbaca
            $table->timestamps();

            // index untuk query per user
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbox_entries');
    }
};
