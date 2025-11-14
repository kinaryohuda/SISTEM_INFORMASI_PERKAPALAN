<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class Kapal extends Model
{
    use HasFactory;

    protected $table = 'kapal';
    protected $primaryKey = 'id_kapal';

    protected $fillable = [
        'user_id',
        'nama_pemilik',
        'nik',
        'alamat',
        'nama_kapal',
        'tipe_kapal',
        'kapasitas',
        'nomor_registrasi',

        // Kolom tambahan untuk file kapal
        'foto_public_id',
        'foto_url',
        'dokumen_public_id',
        'dokumen_url',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
    ];

    /**
     * Enkripsi & dekripsi otomatis untuk NIK
     */
    protected function nik(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Crypt::decryptString($value) : null,
            set: fn($value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    /**
     * Enkripsi & dekripsi otomatis untuk alamat
     */
    protected function alamat(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Crypt::decryptString($value) : null,
            set: fn($value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model PengajuanIzin
     */
    public function pengajuan()
    {
        return $this->hasMany(PengajuanIzin::class, 'id_kapal');
    }

    /**
     * Accessor untuk tampilan nama kapal
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->nama_kapal} ({$this->nomor_registrasi})";
    }

    /**
     * Accessor untuk foto kapal — fallback kalau kosong
     */
    public function getFotoUrlAttribute($value): string
    {
        return $value ?? asset('images/default-ship.jpg');
    }

    /**
     * Accessor untuk dokumen kapal — fallback kalau kosong
     */
    public function getDokumenUrlAttribute($value): ?string
    {
        return $value ?? null; // bisa diganti default file jika ingin
    }
}
