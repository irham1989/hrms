<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Performance\EvaluationAssignment;
use App\Models\Performance\EvaluationPeriod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:skt.assign_evaluator']);
    }

    public function index()
    {
        $periods = EvaluationPeriod::orderBy('year','desc')->get();
        $staff = User::orderBy('name')->get(['id','name']);
        return view('performance.assign.index', compact('periods','staff'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'evaluation_period_id' => ['required','integer','exists:evaluation_periods,id'],
            'staff_id' => ['required','integer','exists:users,id'],
            'ppp_id' => ['nullable','integer','exists:users,id','different:staff_id'],
            'ppk_id' => ['nullable','integer','exists:users,id','different:staff_id','different:ppp_id'],
            'effective_from' => ['nullable','date'],
            'effective_to' => ['nullable','date','after_or_equal:effective_from'],
        ]);

        DB::transaction(function () use ($data) {
            EvaluationAssignment::where('evaluation_period_id', $data['evaluation_period_id'])
                ->where('staff_id', $data['staff_id'])
                ->whereNull('effective_to')
                ->update(['effective_to' => $data['effective_from'] ?? now()->toDateString()]);

            EvaluationAssignment::create($data);
        });

        return back()->with('success', 'Assignment berjaya disimpan.');
    }
}
