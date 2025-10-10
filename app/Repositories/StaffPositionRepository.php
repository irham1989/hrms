<?php

namespace App\Repositories;

use App\Models\BranchPosition;
use App\Models\StaffPosition;
use App\Models\StaffPositionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffPositionRepository
{
    public function checkExistRecord($staff_id){
        $m = StaffPosition::where('staff_id',$staff_id)->first();
        if(!$m){
            $m = new StaffPosition();
            $m->staff_id = $staff_id;
            $m->save();
        }

        return $m;
    }

    public function storeUpdatePosition(Request $request){
        $staff_id = $request->staff_id;
        $branch_select = $request->branch_select;
        $position_select = $request->position_select;
        $position_start_date = $request->position_start_date;

        DB::beginTransaction();
        try{
            $m = $this->getStaffPosition($staff_id);
            $m->branch_position_id = $position_select;
            $m->branch_id = $branch_select;
            $m->save();

            $branchPosition = BranchPosition::find($position_select);

            $sLeave = $m->getStaffLeave;
            $sLeave->staff_position_id = $m->id;
            $sLeave->leave_total = $branchPosition->default_holiday;
            $sLeave->leave_balance = $branchPosition->default_holiday;
            $sLeave->mc_total = 14;
            $sLeave->mc_balance = 14;
            $sLeave->save();

            $this->storeUpdateToHistory($m, $position_start_date);

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Jawatan Berjaya Dikemaskini'
        ];
    }

    public function getStaffPosition($staff_id){
        return StaffPosition::with('getStaff', 'getStaffLeave', 'getStaffLeaveEntries')->where('staff_id', $staff_id)->first();
    }

    public function storeUpdateToHistory(StaffPosition $sp, $start_date = null){
        $checkHistory = new StaffPositionHistory();
        $checkHistory->staff_id = $sp->staff_id;
        $checkHistory->branch_position_id = $sp->branch_position_id;
        $checkHistory->branch_id = $sp->branch_id;
        $checkHistory->start_date = $start_date ? date('Y-m-d', strtotime($start_date)) : null;
        $checkHistory->active = true;
        $checkHistory->save();
    }

    public function setPositionAsActive(Request $request){
        $id = $request->id;

        $m = StaffPositionHistory::find($id);
        $m->active = true;
        $m->save();

        $getOtherPosition = StaffPositionHistory::where('staff_id', $m->staff_id)->where('id', '!=', $m->id)->get();

        if(count($getOtherPosition) > 0){
            foreach($getOtherPosition as $otherPosition){
                $otherPosition->active = false;
                $otherPosition->save();
            }
        }

        $staffPosition = StaffPosition::where('staff_id', $m->staff_id)->first();
        $staffPosition->branch_position_id = $m->branch_position_id;
        $staffPosition->branch_id = $m->branch_id;
        $staffPosition->save();

        return [
            'status' => 'success',
            'message' => 'Jawatan Ditetapkan Sebagai Aktif'
        ];
     } 
    public function getStaffByRequest(Request $request){
    $staff_name = trim((string) $request->staff_name);
    $ic_no      = trim((string) $request->ic_no);
    $branch     = $request->branch;
    $grade      = $request->grade;
    $year_start = $request->year_start;
    $year_end   = $request->year_end;

    // Normalise IC: buang dash & space
    $ic_no = $ic_no !== '' ? preg_replace('/[\s\-]/', '', $ic_no) : null;

    // Jika IC ada, utamakan IC dan abaikan nama
    $useIc        = !empty($ic_no);
    $useStaffName = !$useIc && !empty($staff_name);

    $clauses = [];
    $bind    = [];

    $sql = '
        SELECT
            u.name,
            u.ic_no,
            g.name AS grade,
            p.name AS position,
            b.name AS branch_name,
            sph.start_date,
            sph.end_date
        FROM staff_position_histories sph
            JOIN branch_positions bp ON bp.id = sph.branch_position_id
            JOIN branches b          ON b.id = bp.branch_id
            JOIN grades g            ON g.id = bp.grade_id
            JOIN positions p         ON p.id = bp.position_id
            JOIN staffs s            ON s.id = sph.staff_id
            JOIN users u             ON u.id = s.user_id
        WHERE 1=1
    ';

    if ($branch) {
        $clauses[] = 'AND bp.branch_id = ?';
        $bind[]    = $branch;
    }
    if ($grade) {
        $clauses[] = 'AND bp.grade_id = ?';
        $bind[]    = $grade;
    }
    if ($year_start) {
        // Longgarkan jika mahu kekalkan rekod start_date NULL:
        // $clauses[] = 'AND (YEAR(sph.start_date) >= ? OR sph.start_date IS NULL)';
        $clauses[] = 'AND YEAR(sph.start_date) >= ?';
        $bind[]    = $year_start;
    }
    if ($year_end) {
        // Kira juga rekod aktif (end_date NULL)
        $clauses[] = 'AND (YEAR(sph.end_date) <= ? OR sph.end_date IS NULL)';
        $bind[]    = $year_end;
    }
    if ($useStaffName) {
        $clauses[] = 'AND u.name LIKE ?';
        $bind[]    = '%'.$staff_name.'%';
    }
    if ($useIc) {
        $clauses[] = 'AND REPLACE(u.ic_no,"-","") = REPLACE(?,"-","")';
        $bind[]    = $ic_no;
    }

    // Susunan hasil
    $order = ' ORDER BY sph.start_date ASC, sph.end_date ASC, u.name ASC';

    $sql .= "\n        ".implode("\n        ", $clauses).$order;

    return DB::select($sql, $bind);
}


}
