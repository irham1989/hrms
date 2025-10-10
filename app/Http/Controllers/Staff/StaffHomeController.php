<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class StaffHomeController extends Controller
{
    // Helper: guna route kalau ada, kalau tak, '#'
    private function routeOr(string $name, array $params = [], string $default = '#'): string
    {
        return Route::has($name) ? route($name, $params, false) : $default;
    }

   public function index()
{
    $userId = auth()->id();

    $tiles = [
        [
            'label' => 'Profil', 'desc' => 'Lihat & kemas kini', 'icon' => '🧑',
            'href'  => route('staff.profile', ['user_id' => $userId, 'page' => 'main'], false),
        ],
        [
            'label' => 'Permohonan Cuti', 'desc' => 'Mohon cuti', 'icon' => '🗓️',
            'href'  => route('staff.leave.new-request', ['user_id' => $userId], false),
        ],
        [
            'label' => 'Senarai Permohonan', 'desc' => 'Status & tindakan', 'icon' => '📄',
            'href'  => route('staff.leave.request', ['user_id' => $userId], false),
        ],
        [
            'label' => 'Sejarah Perkhidmatan', 'desc' => 'Lihat rekod', 'icon' => '📜',
            'href'  => route('staff.profile', ['user_id' => $userId, 'page' => 'position_history'], false),
        ],
        [
            'label' => 'Akademik', 'desc' => 'Rekod & sijil', 'icon' => '🎓',
            'href'  => route('staff.profile', ['user_id' => $userId, 'page' => 'academic'], false),
        ],
        [
            'label' => 'Maklumat Keluarga', 'desc' => 'Tambah/kemas kini', 'icon' => '👪',
            'href'  => route('staff.profile', ['user_id' => $userId, 'page' => 'family'], false),
        ],
    ];

    return view('staff.launcher.index', compact('tiles'));
}
}
