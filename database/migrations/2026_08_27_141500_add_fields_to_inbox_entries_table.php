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
        Schema::table('inbox_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('inbox_entries', 'sender_name')) {
                $table->string('sender_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('inbox_entries', 'sender_email')) {
                $table->string('sender_email')->nullable()->after('sender_name');
            }
            if (!Schema::hasColumn('inbox_entries', 'is_starred')) {
                $table->boolean('is_starred')->default(false)->after('is_read');
            }
            if (!Schema::hasColumn('inbox_entries', 'is_important')) {
                $table->boolean('is_important')->default(false)->after('is_starred');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inbox_entries', function (Blueprint $table) {
            if (Schema::hasColumn('inbox_entries', 'sender_name')) {
                $table->dropColumn('sender_name');
            }
            if (Schema::hasColumn('inbox_entries', 'sender_email')) {
                $table->dropColumn('sender_email');
            }
            if (Schema::hasColumn('inbox_entries', 'is_starred')) {
                $table->dropColumn('is_starred');
            }
            if (Schema::hasColumn('inbox_entries', 'is_important')) {
                $table->dropColumn('is_important');
            }
        });
    }
};
