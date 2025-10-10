<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\EvaluationSaveRequest;
use App\Models\Performance\Evaluation;
use App\Models\Performance\EvaluationPeriod;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        $period = EvaluationPeriod::where('is_active', true)->orderByDesc('year')->first();
        $list = Evaluation::with('period')
            ->where('pyd_id', auth()->id())
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('performance.evaluation.index', compact('list','period'));
    }

    public function create()
    {
        $periods = EvaluationPeriod::orderByDesc('year')->get();
        return view('performance.evaluation.create', compact('periods'));
    }

    public function store(EvaluationSaveRequest $request)
    {
        $data = $request->validated();
        $data['pyd_id'] = auth()->id();
        $ev = Evaluation::create($data);
        return redirect()->route('performance.evaluation.edit', $ev)->with('success','Draf disimpan.');
    }

    public function edit(Evaluation $evaluation)
    {
        $this->authorize('updateByPYD', $evaluation);
        $periods = EvaluationPeriod::orderByDesc('year')->get();
        return view('performance.evaluation.edit', compact('evaluation','periods'));
    }

    public function update(EvaluationSaveRequest $request, Evaluation $evaluation)
    {
        $this->authorize('updateByPYD', $evaluation);
        $evaluation->update($request->validated());
        return back()->with('success','Draf dikemaskini.');
    }

    public function submit(Evaluation $evaluation)
    {
        $this->authorize('submitByPYD', $evaluation);
        $evaluation->status = Evaluation::STATUS_SUBMITTED_PYD;
        $evaluation->save();
        return redirect()->route('performance.evaluation.index')->with('success','Telah dihantar kepada PPP.');
    }
}
