<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kapal', function (Blueprint $table) {
            $table->id('id_kapal');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Data pemilik kapal
            $table->string('nama_pemilik', 100);
            $table->string('nik', 255)->unique();
            $table->text('alamat')->nullable();

            // Data kapal
            $table->string('nama_kapal', 100);
            $table->string('tipe_kapal', 50)->nullable();
            $table->integer('kapasitas')->nullable();
            $table->string('nomor_registrasi', 50)->unique();

            // Foto kapal
            $table->string('foto_public_id')->nullable();
            $table->string('foto_url')->nullable(); 

            // Dokumen kapal
            $table->string('dokumen_public_id')->nullable(); 
            $table->string('dokumen_url')->nullable(); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kapal');
    }
};
