<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogVerifikatorPengajuan extends Model
{
    use HasFactory;

    protected $table = 'log_verifikator_pengajuan';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_pengajuan',
        'nama_verifikator',
        'verifikator_id',
        'status_awal',
        'status_baru',
        'catatan_verifikator',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    // Jika ingin otomatis created_at dan updated_at
    public $timestamps = true;

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanIzin::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id', 'id');
    }
}
