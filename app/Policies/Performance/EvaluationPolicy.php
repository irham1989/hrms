<?php

namespace App\Policies\Performance;

use App\Models\Performance\Evaluation;
use App\Models\User;
use App\Services\Performance\AssignmentService;

class EvaluationPolicy
{
    public function __construct(private AssignmentService $svc) {}

    public function view(User $user, Evaluation $ev): bool
    {
        if ($ev->pyd_id === $user->id) return true;
        if ($user->hasPermission('skt.view_all')) return true;
        if ($this->svc->isPPP($user->id, $ev->evaluation_period_id, $ev->pyd_id)) return true;
        if ($this->svc->isPPK($user->id, $ev->evaluation_period_id, $ev->pyd_id)) return true;
        return false;
    }

    public function updateByPYD(User $user, Evaluation $ev): bool
    {
        return $user->hasPermission('skt.update')
            && $ev->pyd_id === $user->id
            && in_array($ev->status, [Evaluation::STATUS_DRAFT_PYD, Evaluation::STATUS_RETURNED_PYD]);
    }

    public function submitByPYD(User $user, Evaluation $ev): bool
    {
        return $user->hasPermission('skt.submit')
            && $ev->pyd_id === $user->id
            && in_array($ev->status, [Evaluation::STATUS_DRAFT_PYD, Evaluation::STATUS_RETURNED_PYD]);
    }

    public function reviewByPPP(User $user, Evaluation $ev): bool
    {
        return $user->hasPermission('skt.review_ppp')
            && $this->svc->isPPP($user->id, $ev->evaluation_period_id, $ev->pyd_id)
            && in_array($ev->status, [Evaluation::STATUS_SUBMITTED_PYD, Evaluation::STATUS_RETURNED_PPP]);
    }

    public function reviewByPPK(User $user, Evaluation $ev): bool
    {
        return $user->hasPermission('skt.review_ppk')
            && $this->svc->isPPK($user->id, $ev->evaluation_period_id, $ev->pyd_id)
            && in_array($ev->status, [Evaluation::STATUS_REVIEWED_PPP, Evaluation::STATUS_RETURNED_PPK]);
    }

    public function finalize(User $user, Evaluation $ev): bool
    {
        return $user->hasPermission('skt.finalize')
            && $this->svc->isPPK($user->id, $ev->evaluation_period_id, $ev->pyd_id)
            && in_array($ev->status, [Evaluation::STATUS_REVIEWED_PPK]);
    }
}
