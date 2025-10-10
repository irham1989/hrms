# Modul Prestasi (Laratrust) – Pemasangan & Integrasi

Modul ini menambah **period penilaian**, **borang penilaian PYD**, dan **assignment PPP/PPK** menggunakan **Laratrust** (role & permission sedia ada).

## 1. Salin Fail
Salin kandungan zip ini ke root projek anda (ikut struktur folder).

## 2. Daftar Policy
Edit `app/Providers/AuthServiceProvider.php`:
```php
use App\Models\Performance\Evaluation;
use App\Policies\Performance\EvaluationPolicy;

protected $policies = [
    Evaluation::class => EvaluationPolicy::class,
];
```

## 3. Tambah Route
Tampal snippet ini ke `routes/web.php`:
```php
require __DIR__.'/snippets/performance_routes.php';
```
Atau salin kandungan file `routes/snippets/performance_routes.php` terus ke dalam `routes/web.php`.

## 4. Jalankan Migration
```bash
php artisan migrate
```

## 5. Seed Permission ↔ Role (Laratrust)
```bash
php artisan db:seed --class=Database\Seeders\PerformancePermissionSeeder
```
> Jika nama role anda berbeza, ubah di seeder dahulu.

## 6. Menu UI
Tambah link ke menu/sidebar anda:
- **Assign Penilai (Admin/HR)**: `/performance/assign`
- **Penilaian Saya (PYD)**: `/performance/evaluation`

## 7. Aliran Penggunaan
1. Admin/HR set **Period** (insert di DB atau buat form sendiri – jadual `evaluation_periods` disediakan).
2. Admin/HR buka `/performance/assign` untuk tetapkan PPP/PPK bagi staf.
3. Staf (PYD) buka `/performance/evaluation` → cipta draf → kemaskini → **Hantar**.
4. (Langkah semakan PPP/PPK & finalize boleh ditambah pada controller kemudian; Policy sudah tersedia).

## 8. Penyesuaian Lanjut
- Tambah kolum/kriteria penilaian mengikut borang SKT organisasi.
- Wujudkan **Inbox PPP/PPK** (query `evaluation_assignments` + `evaluations` ikut status).
- Integrasi PDF/Excel export jika perlu.

## 9. Nota
- Modul ini guna **Laratrust** (dikesan dari projek anda). Jika anda guna Spatie di projek lain, saya boleh sediakan versi Spatie.
- Layout view menggunakan `layouts.app` (dikesan wujud). Ubah ikut layout anda jika perlu.
