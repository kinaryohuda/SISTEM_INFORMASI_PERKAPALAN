<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenPengajuan extends Model
{
    use HasFactory;

    protected $table = 'komponen_pengajuan';
    protected $primaryKey = 'id_komponen_pengajuan';

    protected $fillable = [
        'nama_komponen',
        'tipe',
        'is_required',
        'is_active',
        'opsi',
    ];

    // 🔥 tambahkan ini:
    protected $casts = [
        'opsi' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Opsional: accessor label agar tampilan rapi di Blade
    public function getKewajibanLabelAttribute()
    {
        return $this->is_required ? 'Wajib' : 'Opsional';
    }

    public function getStatusLabelAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }
}
