<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route; // ⬅️ TAMBAH

class DashboardController extends Controller
{
    // Helper kecil: guna route jika wujud, kalau tak -> '#'
    private function routeOr(string $name, array $params = [], string $default = '#'): string
    {
        return Route::has($name) ? route($name, $params, false) : $default;
    }

    public function index()
    {
        // Kiraan ringkas
        $totalStaffNonAdmin = User::whereHas('roles', fn($q) =>
            $q->whereRaw('LOWER(TRIM(name)) = ?', ['staff'])
        )->count();

        $totalAdmin = User::whereHas('roles', fn($q) =>
            $q->whereRaw('LOWER(TRIM(name)) = ?', ['admin'])
        )->count();

        $totalSuperAdmin = User::whereHas('roles', fn($q) =>
            $q->whereRaw('LOWER(TRIM(name)) = ?', ['super-admin'])
        )->count();

        // Tiles (dipetakan hanya ke route yang memang wujud dalam projek anda)
        $tiles = [
            [
                'label' => 'Pengguna', 'desc' => 'Urus pengguna & role', 'icon' => '👥',
                'href'  => $this->routeOr('admin.user.list'),
            ],
            [
                'label' => 'Cuti (Weekend Negeri)', 'desc' => 'Tetapan cuti hujung minggu', 'icon' => '📅',
                'href'  => $this->routeOr('admin.setting.weekendholiday.index'),
            ],
            [
                'label' => 'Cuti Umum', 'desc' => 'Senarai cuti umum', 'icon' => '🗓️',
                'href'  => $this->routeOr('admin.setting.publicholiday.index'),
            ],
            [
                'label' => 'Jawatan/Cawangan', 'desc' => 'Cawangan & jawatan', 'icon' => '🏢',
                'href'  => $this->routeOr('admin.branch.index'),
            ],
            [
                'label' => 'Laporan', 'desc' => 'Laporan & eksport', 'icon' => '📈',
                'href'  => $this->routeOr('admin.reporting.index'),
            ],
            [
                'label' => 'Tetapan Sistem', 'desc' => 'Lookup jawatan & gred', 'icon' => '⚙️',
                // pilih salah satu yang ada; tukar jika perlu
                'href'  => $this->routeOr('admin.setting.position.index'),
            ],
        ];

        return view('admin.dashboard.index', compact(
            'totalStaffNonAdmin', 'totalAdmin', 'totalSuperAdmin', 'tiles'
        ));
    }
}
