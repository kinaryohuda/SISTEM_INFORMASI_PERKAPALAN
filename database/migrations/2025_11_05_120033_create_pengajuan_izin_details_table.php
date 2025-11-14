<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_izin_detail', function (Blueprint $table) {
            $table->id('id_pengajuan_detail');
            $table->foreignId('id_pengajuan')->constrained('pengajuan_izin', 'id_pengajuan')->onDelete('cascade');
            $table->foreignId('id_komponen_pengajuan')->constrained('komponen_pengajuan', 'id_komponen_pengajuan')->onDelete('cascade');
            $table->text('nilai')->nullable(); // nilai input user / path file
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izin_detail');
    }
};
