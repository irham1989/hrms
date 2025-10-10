<?php

namespace App\Models\Performance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['name','year','start_date','end_date','is_active'];

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'evaluation_period_id');
    }
}
