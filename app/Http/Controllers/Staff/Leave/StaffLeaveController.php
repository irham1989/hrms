<?php

namespace App\Http\Controllers\Staff\Leave;

use App\Http\Controllers\Controller;
use App\Library\Datatable\SymTable;
use App\Models\StaffLeaveEntry;
use App\Models\User;
use App\Repositories\StaffLeaveEntriesRepository;
use App\Repositories\StaffRepository;
use App\Traits\CommonTrait;
use App\Traits\LookupTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffLeaveController extends Controller
{
    use CommonTrait, LookupTrait;
    private StaffRepository $staffRepository;
    private StaffLeaveEntriesRepository $staffLeaveEntriesRepository;

    public function __construct(StaffRepository $staffRepository, StaffLeaveEntriesRepository $staffLeaveEntriesRepository){
        $this->staffRepository = $staffRepository;
        $this->staffLeaveEntriesRepository = $staffLeaveEntriesRepository;
    }

    public function leaveNewRequest($user_id){
        $staff = $this->staffRepository->getStaffProfile($user_id);
        $leaveCategory = $this->getLeaveCategories();
        return view('staff.leave.new-request', [
            'staff' => $staff,
            'leaveCategory' => $leaveCategory
        ]);
    }

    public function storeUpdateNewRequest(Request $request){
        $m = $this->staffLeaveEntriesRepository->storeNewRequest($request);
        return $this->setResponse($m['message'], !($m['status'] == 'error'));
    }

    public function leaveRequest($user_id){
        $user = User::find($user_id);
        $is_role = [
            'superadmin' => $user->hasRole('super-admin'),
            'admin' => $user->hasRole('admin'),
            'approvaladmin' => $user->hasRole('approval-admin'),
            'staff' => $user->hasRole('staff'),
        ];

        return view('staff.leave.request', [
            'user_id' => $user_id,
            'is_role' => $is_role
        ]);
    }

    public function requestList(Request $request){

        $entries = $this->staffLeaveEntriesRepository->getRequestListByUserId($request);

        return SymTable::of($entries)
            ->addRowAttr([
                'data-id' => function($data){
                    return $data->id;
                }
            ])
            ->addColumn('dates', function($data){
                $timeShow = '';

                if($data->is_half_day){
                    $timeShow = '<br>'.date("g:i a", strtotime($data->start_time)).' - '.date("g:i a", strtotime($data->end_time));
                }
                return $this->regularDate($data->start_date).'<br> Hingga <br> '.$this->regularDate($data->end_date).'<br><br><b class="text-success">'.'<b></b>'.$data->leave_category.$timeShow;
            })->addColumn('days', function($data){
                return $data->is_half_day ? '-' : $data->days.' HARI';
            })->addColumn('status', function($data){
                return strtoupper($data->l_status);
            })->addColumn('approver_name', function($data){
                return strtoupper($data->approver_name);
            })->addColumn('reason', function($data){
                $show_mc = '';
                if($data->is_mc || $data->is_half_day){
                    if($data->mc_upload){
                        $show_mc = '<br>'.'<a target="_blank" href="'.url('uploads/staff/mc/'.$data->mc_upload).'">Papar</a>';
                    }
                }
                return '<b class="text-primary text-decoration-underline">'.(ucwords($data->leave_category).'</b><br>'.($data->reason ? strtoupper($data->reason) : '-')).$show_mc;
            })->make();
    }

    public function requestDelete(Request $request){
       $m = $this->staffLeaveEntriesRepository->deleteRequest($request);

       if($m){
           return $this->setResponse($this->setHardDelete(StaffLeaveEntry::class, $request->id, 'Permohonan'));
       }else{
           return $this->setResponse('WHOOPS', false);
       }
    }

    public function requestApproval(Request $request){
        $m = $this->staffLeaveEntriesRepository->approveRequest($request);
        return $this->setResponse($m['message'], !($m['status'] == 'error'));
    }

    public function getApprover(Request $request){
        return json_encode(['items' => $this->staffLeaveEntriesRepository->getApproverDropdown($request)]);
    }

    public function leaveApproval($user_id){
        $user = User::find($user_id);
        $is_role = [
            'superadmin' => $user->hasRole('super-admin'),
            'admin' => $user->hasRole('admin'),
            'approvaladmin' => $user->hasRole('approval-admin'),
            'staff' => $user->hasRole('staff'),
        ];

        return view('staff.leave.approval', [
            'user_id' => $user_id,
            'is_role' => $is_role
        ]);
    }

    public function approvalList(Request $request){
        $request->request->add(['approval' => true]);
        $entries = $this->staffLeaveEntriesRepository->getRequestListByUserId($request);
        $is_admin = Auth::user()->hasRole('admin');

        return SymTable::of($entries)
            ->addRowAttr([
                'data-id' => function($data){
                    return $data->id;
                },
                'data-requester' =>  function($data){
                    return $data->requester_id;
                },
                'data-branch' =>  function($data){
                    return $data->branch_id;
                }
            ])
            ->addColumn('name', function($data) use ($is_admin){
                return strtoupper($data->request_by).'<br> <b class="text-success">'.$this->regularDate($data->created_at).'<b>'.($is_admin ? '<br><b class="text-info">'.ucwords(strtolower($data->approver_name)).'</b>' : '').'</b></b>';
            })->addColumn('h_date', function($data){
                $timeShow = '';

                if($data->is_half_day){
                    $timeShow = '<br>'.date("g:i a", strtotime($data->start_time)).' - '.date("g:i a", strtotime($data->end_time));
                }
                return $this->regularDate($data->start_date).' <br>Hingga<br> '.$this->regularDate($data->end_date).'<br><b class="text-success">'.$data->leave_category.'<b></b>'.$timeShow;
            })->addColumn('days', function($data){
                return $data->is_half_day ? '-' : $data->days.' HARI';
            })->addColumn('status', function($data){
                return strtoupper($data->l_status);
            })->addColumn('approver_name', function($data){
                return strtoupper($data->approver_name);
            })->addColumn('reason', function($data){
                $show_mc = '';
                if($data->is_mc || $data->is_half_day){
                    if($data->mc_upload){
                        $show_mc = '<br>'.'<a target="_blank" href="'.url('uploads/staff/mc/'.$data->mc_upload).'">Papar</a>';
                    }
                }
                return '<b class="text-primary text-decoration-underline">'.(ucwords($data->leave_category).'</b><br>'.($data->reason ? strtoupper($data->reason) : '-')).$show_mc;
            })->make();
    }

    public function updateApprover(Request $request){
        $m = $this->staffLeaveEntriesRepository->approverUpdate($request);
        return $this->setResponse($m['message'], !($m['status'] == 'error'));
    }

    public function leaveRequestChangeCategory(Request $request){
        $m = $this->staffLeaveEntriesRepository->leaveRequestChangeCategory($request);
        return $this->setResponse($m['message'], !($m['status'] == 'error'));
    }
}
