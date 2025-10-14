<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class StaffHomeController extends Controller
{
    // Helper kecil: guna route kalau ada; kalau tak, '#'
    private function routeOr(string $name, array $params = [], string $default = '#'): string
    {
        return \Illuminate\Support\Facades\Route::has($name)
            ? route($name, $params, false)
            : $default;
    }

    public function index()
    {
        $user   = auth()->user();
        $userId = $user->id;

        if (!$userId) {
    abort(401); // atau redirect()->route('login');
}

        // Ambil rekod cuti staf dengan selamat (elak null error)
        $leave = optional(optional(optional($user->getStaff)->getStaffPosition)->getStaffLeave);

        // Data donut – Cuti Tahunan
        $labels = ['Baki Cuti', 'Telah Diambil'];
        $data   = [
            (int) ($leave->leave_balance ?? 0),
            (int) ($leave->leave_taken   ?? 0),
        ];

        // Data donut – MC (opsyenal)
        $mcLabels = ['Baki MC', 'MC Diambil'];
        $mcData   = [
            (int) ($leave->mc_balance ?? 0),
            (int) ($leave->mc_taken   ?? 0),
        ];

        // Shortcut/tiles tindakan pantas (boleh komen kalau tak mahu)
        $tiles = [
            [
                'label' => 'Profil', 'desc' => 'Lihat & kemas kini', 'icon' => '🧑',
                'href'  => $this->routeOr('staff.profile', ['user_id' => $userId, 'page' => 'main']),
            ],
            [
                'label' => 'Permohonan Cuti', 'desc' => 'Mohon cuti', 'icon' => '🗓️',
                'href'  => $this->routeOr('staff.leave.new-request', ['user_id' => $userId]),
            ],
            [
                'label' => 'Senarai Permohonan', 'desc' => 'Status & tindakan', 'icon' => '📄',
                'href'  => $this->routeOr('staff.leave.request', ['user_id' => $userId]),
            ],
            [
                'label' => 'Sejarah Perkhidmatan', 'desc' => 'Lihat rekod', 'icon' => '📜',
                'href'  => $this->routeOr('staff.profile', ['user_id' => $userId, 'page' => 'position_history']),
            ],
            [
                'label' => 'Akademik', 'desc' => 'Rekod & sijil', 'icon' => '🎓',
                'href'  => $this->routeOr('staff.profile', ['user_id' => $userId, 'page' => 'academic']),
            ],
            [
                'label' => 'Maklumat Keluarga', 'desc' => 'Tambah/kemas kini', 'icon' => '👪',
                'href'  => $this->routeOr('staff.profile', ['user_id' => $userId, 'page' => 'family']),
            ],
        ];

        return view('staff.launcher.index', [
            'user'      => $user,
            'labels'    => $labels,
            'data'      => $data,
            'mcLabels'  => $mcLabels,
            'mcData'    => $mcData,
            'tiles'     => $tiles,   // komen baris ini jika mahu “carta sahaja”
        ]);
    }
}
