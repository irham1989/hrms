<?php

namespace App\Services\Performance;

use App\Models\Performance\EvaluationAssignment;
use Carbon\Carbon;

class AssignmentService
{
    public function currentAssignment(int $periodId, int $staffId): ?EvaluationAssignment
    {
        return EvaluationAssignment::where('evaluation_period_id', $periodId)
            ->where('staff_id', $staffId)
            ->where(function ($q) {
                $q->whereNull('effective_to')
                  ->orWhere('effective_to', '>=', Carbon::today()->toDateString());
            })
            ->orderByDesc('effective_from')
            ->first();
    }

    public function isPPP(int $userId, int $periodId, int $staffId): bool
    {
        $a = $this->currentAssignment($periodId, $staffId);
        return $a && (int)$a->ppp_id === (int)$userId;
    }

    public function isPPK(int $userId, int $periodId, int $staffId): bool
    {
        $a = $this->currentAssignment($periodId, $staffId);
        return $a && (int)$a->ppk_id === (int)$userId;
    }
}
