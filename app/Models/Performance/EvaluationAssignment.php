<?php

namespace App\Models\Performance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_period_id',
        'staff_id',
        'ppp_id',
        'ppk_id',
        'effective_from',
        'effective_to',
    ];

    public function period()
    {
        return $this->belongsTo(EvaluationPeriod::class, 'evaluation_period_id');
    }

    public function staff()
    {
        return $this->belongsTo(\App\Models\User::class, 'staff_id');
    }

    public function ppp()
    {
        return $this->belongsTo(\App\Models\User::class, 'ppp_id');
    }

    public function ppk()
    {
        return $this->belongsTo(\App\Models\User::class, 'ppk_id');
    }
}
