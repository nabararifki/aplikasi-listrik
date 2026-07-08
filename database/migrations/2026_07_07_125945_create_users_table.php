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
        Schema::create('users', function (Blueprint $table) {
            // Kolom Infrastruktur Laravel Breeze (Wajib untuk Stabilitas Sistem)
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            // Kolom Spesifikasi Dokumen
            $table->string('username', 12)->unique();
            $table->string('password', 255);
            $table->tinyInteger('access_level'); // Menampung peran Admin (1) atau Officer (2)

            // Kolom Infrastruktur Laravel Breeze (Lanjutan)
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
