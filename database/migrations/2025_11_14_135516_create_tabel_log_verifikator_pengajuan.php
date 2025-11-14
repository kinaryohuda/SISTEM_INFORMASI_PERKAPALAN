<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_verifikator_pengajuan', function (Blueprint $table) {
            $table->id('id_log');

            // Hubungan ke pengajuan
            $table->foreignId('id_pengajuan')
                ->constrained('pengajuan_izin', 'id_pengajuan')
                ->onDelete('cascade');

            // Nama verifikator langsung
            $table->string('nama_verifikator', 100);

            // ID user verifikator
            $table->foreignId('verifikator_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Status sebelum dan sesudah
            $table->enum('status_awal', ['menunggu', 'disetujui', 'ditolak'])->nullable();
            $table->enum('status_baru', ['menunggu', 'disetujui', 'ditolak']);

            // Catatan verifikasi
            $table->text('catatan_verifikator')->nullable();

            // Waktu verifikasi
            $table->timestamp('verified_at')->useCurrent();

            // Created/Updated timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_verifikator_pengajuan');
    }
};
