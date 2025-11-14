<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanIzinDetail extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_izin_detail';
    protected $primaryKey = 'id_pengajuan_detail';

    protected $fillable = [
        'id_pengajuan',
        'id_komponen_pengajuan',
        'nilai',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanIzin::class, 'id_pengajuan');
    }

    public function komponen()
    {
        return $this->belongsTo(KomponenPengajuan::class, 'id_komponen_pengajuan');
    }
}
