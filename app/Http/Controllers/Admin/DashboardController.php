<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;


class DashboardController extends Controller
{
    public function index()
    {
        // Senarai role yang mahu dipaparkan dalam pie
        $roleMap = [
            'super-admin'        => 'Super Admin',
            'admin'              => 'Admin',
            'approval-admin'     => 'Approval Admin',
            'ketua_pengarah'     => 'Ketua Pengarah',
            'penolong_pengarah'  => 'Penolong Pengarah',
            'ketua_unit'         => 'Ketua Unit',
            'staff'              => 'Staf',
        ];

        $labels = [];
        $data   = [];

        foreach ($roleMap as $slug => $label) {
            $count = User::whereHas('roles', fn($q) =>
                $q->whereRaw('LOWER(TRIM(name)) = ?', [$slug])
            )->count();

            $labels[] = $label;
            $data[]   = $count;
        }

        // Jika anda masih mahu tunjuk 3 metrik ringkas di atas carta, kira di sini (optional)
        $totalStaffNonAdmin = User::whereHas('roles', fn($q) =>
            $q->whereRaw('LOWER(TRIM(name)) = ?', ['staff'])
        )->count();
        $totalAdmin = User::whereHas('roles', fn($q) =>
            $q->whereRaw('LOWER(TRIM(name)) = ?', ['admin'])
        )->count();
        $totalSuperAdmin = User::whereHas('roles', fn($q) =>
            $q->whereRaw('LOWER(TRIM(name)) = ?', ['super-admin'])
        )->count();

        return view('admin.dashboard.index', [
            'labels'             => $labels,
            'data'               => $data,
            'totalStaffNonAdmin' => $totalStaffNonAdmin, // buang dari blade jika tak mahu
            'totalAdmin'         => $totalAdmin,         // buang dari blade jika tak mahu
            'totalSuperAdmin'    => $totalSuperAdmin,    // buang dari blade jika tak mahu
        ]);
    }
}
