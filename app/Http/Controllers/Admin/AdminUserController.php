<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\Datatable\SymTable;
use App\Models\Role;
use App\Repositories\AdminUserRepository;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;   // ⬅️ TAMBAH: untuk whereIn + LOWER()

class AdminUserController extends Controller
{
    use CommonTrait;

    private AdminUserRepository $adminUserRepository;

    public function __construct(AdminUserRepository $adminUserRepository){
        $this->adminUserRepository = $adminUserRepository;
    }

    public function index(Request $request){
        $session_id = $request->session_id;
        $user       = Auth::user();

        // Tentukan role yang boleh dipilih ikut peranan pengguna yang login
        // super-admin → boleh lihat semua termasuk admin
        // admin       → tidak boleh lihat super-admin / admin
        $allowedRoleNames = [];

        if ($user && $user->hasRole('super-admin')) {
            $allowedRoleNames = [
                'admin',
                'staff',
                'approval-admin',
                'ketua_unit',
                'penolong_pengarah',
                'ketua_pengarah',
            ];
        } elseif ($user && $user->hasRole('admin')) {
            $allowedRoleNames = [
                'staff',
                'approval-admin',
                'ketua_unit',
                'penolong_pengarah',
                'ketua_pengarah',
            ];
        } else {
            // Jika bukan admin/super-admin, anda boleh abort(403) atau biarkan kosong
            // return abort(403);
            $allowedRoleNames = [];
        }

        // Query secara case-insensitive ikut NAMA role (bukan ID)
        $roles = empty($allowedRoleNames)
            ? collect()
            : Role::whereIn(DB::raw('LOWER(name)'), array_map('strtolower', $allowedRoleNames))
                ->orderBy('name')
                ->get();

        return view('admin.user.list', [
            'roles'      => $roles,
            'session_id' => $session_id,
        ]);
    }

    public function userList(Request $request){
        $model = $this->adminUserRepository->getUserList($request);
        return SymTable::of($model)
            ->addRowAttr([
                'data-id' => function($data){
                    return $data->id;
                }
            ])
            ->addColumn('name', function($data){
                return strtoupper($data->name).'<br>'.$data->ic_no;
            })->addColumn('email', function($data){
                return $data->email;
            })->addColumn('role', function($data){
                $role = explode(',', $data->role_display);
                $str = '';
                if(count($role) > 0){
                    foreach($role as $r){
                        $str .= $r.'<br>';
                    }
                }
                return strtoupper($str);
            })->addColumn('active_display', function($data){
                return strtoupper($data->active_display);
            })->make();
    }

    public function storeUpdateUser(Request $request){
        $m = $this->adminUserRepository->adminStoreUser($request);
        return $this->setResponse($m['message'], !($m['status'] == 'error'));
    }

    public function getInfoUser(Request $request){
        $m = $this->adminUserRepository->getUser($request->id);
        return $this->setDataResponse($m);
    }

    public function userActive(Request $request){
        $m = $this->adminUserRepository->activateUser($request);
        return $this->setDataResponse($m);
    }
}
