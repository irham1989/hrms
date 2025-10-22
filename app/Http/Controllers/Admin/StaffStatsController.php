<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class StaffStatsController extends Controller
{
    public function index()
{
    // Jumlah staf ikut penempatan
    $byBranches = DB::table('staff_positions')
        ->join('branches', 'staff_positions.branch_id', '=', 'branches.id')
        ->select('branches.name as label', DB::raw('COUNT(DISTINCT staff_positions.staff_id) as total'))
        ->whereNotNull('staff_positions.branch_id')
        ->groupBy('branches.name')
        ->orderBy('branches.name')
        ->get();

    // Jumlah staf ikut jawatan (melalui branch_positions)
    $byPositions = DB::table('staff_positions')
        ->join('branch_positions', 'staff_positions.branch_position_id', '=', 'branch_positions.id')
        ->join('positions', 'branch_positions.position_id', '=', 'positions.id')
        ->select('positions.name as label', DB::raw('COUNT(DISTINCT staff_positions.staff_id) as total'))
        ->whereNotNull('staff_positions.branch_position_id')
        ->groupBy('positions.name')
        ->orderBy('positions.name')
        ->get();

    $branchLabels  = $byBranches->pluck('label');
    $branchData    = $byBranches->pluck('total');
    $positionLabels = $byPositions->pluck('label');
    $positionData   = $byPositions->pluck('total');

    // Dropdown untuk penapisan
    $branches  = DB::table('branches')->orderBy('name')->get();
    $positions = DB::table('positions')->orderBy('name')->get();

    return view('admin.staff-stats', compact(
        'byBranches', 'byPositions',
        'branchLabels', 'branchData',
        'positionLabels', 'positionData',
        'branches', 'positions'
    ));
}


public function filterStaff(Request $request)
{
    $branch_id   = $request->branch_id;
    $position_id = $request->position_id;

    // Role dibenarkan & dilarang
    $allow = ['staff','ketua_unit','penolong_pengarah','ketua_pengarah'];
    $deny  = ['admin','super-admin','approval-admin'];

    // Rekod staff_positions TERKINI (per staf)
    $spLatest = DB::table('staff_positions')
        ->selectRaw('MAX(id) AS id, staff_id')
        ->whereNotNull('branch_id')
        ->whereNotNull('branch_position_id')
        ->groupBy('staff_id');

    // ASAS: STAFFS
    $q = DB::table('staffs as s')
        // Join ke users utk nama & role
        ->join('users as u', 'u.id', '=', 's.user_id')

        // Hanya pengguna/staf AKTIF (auto-detect kolum)
        ->when(Schema::hasColumn('users','is_active'), fn($qq)=>$qq->where('u.is_active',1))
        ->when(Schema::hasColumn('users','active'),    fn($qq)=>$qq->orWhere('u.active',1))
        ->when(Schema::hasColumn('users','status'),    fn($qq)=>$qq->orWhere('u.status','AKTIF'))
        ->when(Schema::hasColumn('users','deleted_at'),fn($qq)=>$qq->whereNull('u.deleted_at'))

        // Mesti ada ≥1 role dibenarkan
        ->whereExists(function ($s) use ($allow) {
            $s->from('role_user as ru')
              ->join('roles as r','r.id','=','ru.role_id')
              ->whereColumn('ru.user_id','u.id')
              ->whereIn('r.name', $allow)
              ->select(DB::raw(1));
        })

        // TIADA role dilarang
        ->whereNotExists(function ($s) use ($deny) {
            $s->from('role_user as ru')
              ->join('roles as r','r.id','=','ru.role_id')
              ->whereColumn('ru.user_id','u.id')
              ->whereIn('r.name', $deny)
              ->select(DB::raw(1));
        })

        // Join ke PENEMPATAN TERKINI
        ->leftJoinSub($spLatest, 'spm', fn($j)=>$j->on('spm.staff_id','=','s.id'))
        ->leftJoin('staff_positions as sp', 'sp.id', '=', 'spm.id')
        ->leftJoin('branches as b', 'b.id', '=', 'sp.branch_id')
        ->leftJoin('branch_positions as bp', 'bp.id', '=', 'sp.branch_position_id')
        ->leftJoin('positions as p', 'p.id', '=', 'bp.position_id');

    // Tapisan dropdown (atas rekod TERKINI)
    if (!empty($branch_id))   $q->where('sp.branch_id', $branch_id);
    if (!empty($position_id)) $q->where('bp.position_id', $position_id);

    $staffs = $q->select(
                's.id as staff_id',
                // jika ada column nama dalam staffs (cth s.full_name), tukar COALESCE(s.full_name,u.name)
                DB::raw('COALESCE(u.name, "-") as staff_name'),
                DB::raw('COALESCE(p.name, "-") as position_name'),
                DB::raw('COALESCE(b.name, "-") as branch_name')
             )
             ->orderBy('branch_name')
             ->orderBy('position_name')
             ->orderBy('staff_name')
             ->get();

    return response()->json($staffs);
}


}
