<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        // Menambah kolom status setelah kolom phone dengan nilai default 'waiting'
        $table->string('status')->default('waiting')->after('phone');
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        // Menghapus kolom status jika migrasi dibatalkan
        $table->dropColumn('status');
    });
}
};
