<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanIzin extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_izin';
    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
        'id_kapal',
        'status',
    ];

    public function kapal()
    {
        return $this->belongsTo(Kapal::class, 'id_kapal');
    }

    public function details()
    {
        return $this->hasMany(PengajuanIzinDetail::class, 'id_pengajuan');
    }

    public function logVerifikator()
    {
        return $this->hasMany(LogVerifikatorPengajuan::class, 'id_pengajuan', 'id_pengajuan');
    }
}
