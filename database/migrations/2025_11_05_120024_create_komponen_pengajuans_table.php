<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponen_pengajuan', function (Blueprint $table) {
            $table->id('id_komponen_pengajuan');
            $table->string('nama_komponen', 100);
            $table->string('tipe', 50);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('opsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen_pengajuan');
    }
};
