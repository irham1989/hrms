<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class PerformancePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $perms = [
            'skt.create','skt.update','skt.submit',
            'skt.review_ppp','skt.review_ppk','skt.finalize',
            'skt.view_own','skt.view_subordinates','skt.view_all',
            'skt.assign_evaluator','skt.manage_periods',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p], ['display_name' => strtoupper($p)]);
        }

        // Map to existing roles (edit if your role names differ)
        $map = [
            'staf' => ['skt.create','skt.update','skt.submit','skt.view_own'],
            'ketua_unit' => ['skt.review_ppp','skt.view_subordinates'],
            'penolong_pengarah' => ['skt.review_ppk','skt.finalize','skt.view_subordinates'],
            'admin' => $perms,
        ];

        foreach ($map as $roleName => $permList) {
            $role = Role::where('name', $roleName)->first();
            if (!$role) continue;
            $permsModels = Permission::whereIn('name', $permList)->get();
            $role->syncPermissions($permsModels);
        }
    }
}
