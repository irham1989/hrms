<?php

namespace Database\Seeders;

use App\Models\AcademicQualification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddNewAcademicQualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Sijil Professional',
            'Bidang Pengkhuhusan Major',
            'Bidang Pengkhuhusan Minor'
        ];

        foreach ($data as $d) {
            $m = new AcademicQualification();
            $m->name = $d;
            $m->save();
        }
    }
}
