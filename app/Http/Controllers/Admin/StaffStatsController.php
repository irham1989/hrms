<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StaffStatsController extends Controller
{
    public function index()
    {
        // 📊 Statistik mengikut penempatan (branch)
        $byBranches = DB::table('staff_positions')
            ->join('branches', 'staff_positions.branch_id', '=', 'branches.id')
            ->select('branches.name as label', DB::raw('COUNT(staff_positions.id) as total'))
            ->groupBy('branches.name')
            ->orderBy('branches.name')
            ->get();

        // 📊 Statistik mengikut jawatan (position)
        $byPositions = DB::table('staff_positions')
            ->join('branch_positions', 'staff_positions.branch_position_id', '=', 'branch_positions.id')
            ->join('positions', 'branch_positions.position_id', '=', 'positions.id')
            ->select('positions.name as label', DB::raw('COUNT(staff_positions.id) as total'))
            ->groupBy('positions.name')
            ->orderBy('positions.name')
            ->get();

        // Data untuk carta
        $branchLabels   = $byBranches->pluck('label');
        $branchData     = $byBranches->pluck('total');
        $positionLabels = $byPositions->pluck('label');
        $positionData   = $byPositions->pluck('total');

        return view('admin.staff-stats', compact(
            'byBranches', 'byPositions',
            'branchLabels', 'branchData',
            'positionLabels', 'positionData'
        ));
    }
}
