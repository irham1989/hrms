<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Library\Datatable\SymTable;
use App\Models\BranchPosition;
use App\Models\StaffAcademic;
use App\Models\StaffFamily;
use App\Models\User;
use App\Repositories\BranchPositionRepository;
use App\Repositories\BranchRepository;
use App\Repositories\StaffLeaveRepository;
use App\Repositories\StaffPositionRepository;
use App\Repositories\StaffRepository;
use App\Traits\CommonTrait;
use App\Traits\LookupTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    use CommonTrait, LookupTrait;
    private StaffRepository $staffRepository;
    private BranchRepository $branchRepository;
    private BranchPositionRepository $branchPositionRepository;
    private StaffPositionRepository $staffPositionRepository;
    private StaffLeaveRepository $staffLeaveRepository;

    public function __construct(StaffRepository $staffRepository, BranchRepository $branchRepository, BranchPositionRepository $branchPositionRepository, StaffPositionRepository $staffPositionRepository, StaffLeaveRepository $staffLeaveRepository){
        $this->staffRepository = $staffRepository;
        $this->branchRepository = $branchRepository;
        $this->branchPositionRepository = $branchPositionRepository;
        $this->staffPositionRepository = $staffPositionRepository;
        $this->staffLeaveRepository = $staffLeaveRepository;
    }
    public function index($user_id, $page, Request $request)
{
    $staff = $this->staffRepository->getStaffProfile($user_id);

    $responseData = [
        'page'    => $page,
        'user_id' => $user_id,
        'staff'   => $staff,
    ];

    if ($page == 'main') {
        // Paparan ringkas (atas sahaja)
        $checkPositionRecord = $this->staffPositionRepository->checkExistRecord($staff->id);
        $this->staffLeaveRepository->checkExistRecord($checkPositionRecord->id);
        $responseData['staff'] = $this->staffRepository->getStaffProfile($user_id);
        // TIADA lookup di sini supaya bahagian bawah tidak dirender
    }
    elseif ($page == 'profile') { // Rekod Peribadi (borang penuh)
        $responseData['country']        = $this->getCountries();
        $responseData['state']          = $this->getStates();
        $responseData['race']           = $this->getRaces();
        $responseData['marital_status'] = $this->getMaritalStatus();
        $responseData['bumiputera']     = $this->getBumiputeras();
        $responseData['religion']       = $this->getReligion();
        $responseData['salutation']     = $this->getSalutations();
        $responseData['gender']         = $this->getGenders();
    }
    elseif ($page == 'academic') {
        $responseData['academic_qualifications'] = $this->getAcademicQualifications();
    }
    elseif ($page == 'position') {
        $responseData['state_select']  = $request->state_select ?? null;
        $responseData['branch_select'] = $request->branch_select ?? null;
        if ($request->branch_select) {
            $responseData['branch_record'] = $this->branchRepository->getBranch($request->branch_select);
        }
        $responseData['state'] = $this->getStates();
    }

    return view('staff.profile.index')->with($responseData);
}

    public function storeUpdateMain(Request $request){
        $m = $this->staffRepository->storeUpdateProfile($request);
        return $this->setDataResponse($m, !($m['status'] == 'error'));
    }

    public function academicList(Request $request){
        $model = $this->staffRepository->getAcademicList($request);

        return SymTable::of($model)
            ->addRowAttr([
                'data-id' => function($data){
                    return $data->id;
                }
            ])
            ->addColumn('level', function($data){
                return $data->qualification;
            })
            ->addColumn('institution', function($data){
                return $data->institution_name;
            })
            ->addColumn('certificate', function($data){
                $pro = $data->certification_professional ? '<a class="text-warning" target="_blank" href="'.asset('uploads/staff/academics/cert_pro/'.$data->certification_professional).'">Papar Sijil</a>' : '';
                $cert = $data->certificate_file ? '<br><a target="_blank" href="'.asset('uploads/staff/academics/cert/'.$data->certificate_file).'">Papar Sijil</a>' : '';

                if($pro == null && $cert == null){
                    return '-';
                }
                return $pro.$cert;
            })
            ->addColumn('specialization', function($data){
                return '<span class="text-primary">'.ucwords($data->major_specialization).'</span>'.($data->minor_specialization ? '<br><span class="text-info">'.ucwords($data->minor_specialization).'</span>' : '');
            })
            ->addColumn('grade', function($data){
                return $data->overall_grade ?? '-';
            })->make();
    }

    public function storeUpdateAcademic(Request $request){
        $m = $this->staffRepository->storeUpdateAcademic($request);
        return $this->setDataResponse($m, !($m['status'] == 'error'));
    }

    public function getAcademicInfo(Request $request) : JsonResponse{
        return $this->setDataResponse($this->staffRepository->getAcademic($request->id));
    }

    public function deleteAcademic(Request $request) : JsonResponse{
        return $this->setResponse($this->setHardDelete(StaffAcademic::class, $request->id, 'Akademik'));
    }

    public function resetPassword(Request $request){
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.required' => 'Kata Laluan Wajib Diisi',
            'password.confirmed' => 'Kata Laluan Tidak Sama',
            'password.min' => 'Kata Laluan Perlu Minima 8 Karakter',
        ]);

        $user_id = $request->user_id;
        $m = User::find($user_id);
        $m->password = Hash::make($request->password);
        $m->save();

        return redirect()->back()->with('success', 'Your action was successful!');
    }

    public function getBranchByState(Request $request){
        return json_encode(['items' => $this->branchRepository->getBranchesByState($request)]);
    }

    public function getPositionByBranch(Request $request){
        return json_encode(['items' => $this->branchPositionRepository->getPositionByBranch($request)]);
    }

    public function storeUpdatePosition(Request $request){
        $m = $this->staffPositionRepository->storeUpdatePosition($request);
        return $this->setResponse($m['message'], !($m['status'] == 'error'));
    }

    public function storeUpdateNewLeaveBalance(Request $request){
        $m = $this->staffLeaveRepository->storeUpdateNewLeaveBalance($request);
        return $this->setResponse($m['message'], !($m['status'] == 'error'));
    }

    public function familyList(Request $request){
        $model = $this->staffRepository->getFamilyList($request);

        return SymTable::of($model)
            ->addRowAttr([
                'data-id' => function($data){
                    return $data->id;
                }
            ])
            ->addColumn('name', function($data){
                return $data->name.'<br>Umur '.(Carbon::parse($data->dob)->age);
            })
            ->addColumn('email', function($data){
                return $data->email.'<br>'.$data->phone;
            })
            ->addColumn('relation', function($data){
                return $data->relation;
            })
            ->addColumn('grade', function($data){
                return $data->overall_grade ?? '-';
            })->addColumn('death', function($data){
                return $data->death_date ? $this->regularDate($data->death_date) : '-';
            })->make();
    }

    public function storeUpdateFamily(Request $request){
        $m = $this->staffRepository->storeUpdateFamily($request);
        return $this->setDataResponse($m, !($m['status'] == 'error'));
    }

    public function getFamilyInfo(Request $request) : JsonResponse{
        return $this->setDataResponse($this->staffRepository->getFamily($request->id));
    }

    public function deleteFamily(Request $request) : JsonResponse{
        return $this->setResponse($this->setHardDelete(StaffFamily::class, $request->id, 'Maklumat Keluarga'));
    }

    public function storeUpdateAppointed(Request $request){
        $m = $this->staffRepository->setAppointedDate($request);
        return $this->setResponse($m['message'], !($m['status'] == 'error'));
    }

    public function storeUpdateWorkStatus(Request $request){
        $m = $this->staffRepository->setWorkStatus($request);
        return $this->setResponse($m['message'], !($m['status'] == 'error'));
    }

    public function getPositionHistoryInfo(Request $request) : JsonResponse{
        return $this->setDataResponse($this->staffRepository->getPositionHistoryInfo($request->id));
    }

    public function storeUpdatePositionHistoryDate(Request $request){
        $m = $this->staffRepository->storeUpdatePositionHistoryDate($request);
        return $this->setResponse($m['message'], !($m['status'] == 'error'));
    }

    public function setPositionAsActive(Request $request){
        $m = $this->staffPositionRepository->setPositionAsActive($request);
        return $this->setResponse($m['message'], !($m['status'] == 'error'));
    }
}
