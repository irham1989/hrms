<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffPositionHistory extends Model
{
    public function getBranch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }

    public function getBranchPosition(){
        return $this->hasOne(BranchPosition::class,'id','branch_position_id');
    }
}
