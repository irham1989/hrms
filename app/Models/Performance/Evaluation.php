<?php

namespace App\Models\Performance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_period_id',
        'pyd_id',
        'kegiatan_sumbangan',
        'latihan_dihadiri',
        'latihan_diperlukan',
        'tempoh_penilaian_ppp_mula',
        'tempoh_penilaian_ppp_tamat',
        'ulasan_keseluruhan_ppp',
        'kemajuan_kerjaya_ppp',
        'tempoh_penilaian_ppk_mula',
        'tempoh_penilaian_ppk_tamat',
        'ulasan_keseluruhan_ppk',
        'status',
    ];

    // Status constants
    const STATUS_DRAFT_PYD       = 'draf_pyd';
    const STATUS_SUBMITTED_PYD   = 'submitted_pyd';
    const STATUS_RETURNED_PYD    = 'returned_pyd';
    const STATUS_RETURNED_PPP    = 'returned_ppp';
    const STATUS_REVIEWED_PPP    = 'reviewed_ppp';
    const STATUS_RETURNED_PPK    = 'returned_ppk';
    const STATUS_REVIEWED_PPK    = 'reviewed_ppk';
    const STATUS_FINALIZED       = 'finalized';

    public function period()
    {
        return $this->belongsTo(EvaluationPeriod::class, 'evaluation_period_id');
    }

    public function pyd()
    {
        return $this->belongsTo(\App\Models\User::class, 'pyd_id');
    }
}
