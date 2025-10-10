<?php

namespace App\Http\Controllers\Reporting;

use App\Http\Controllers\Controller;
use App\Repositories\StaffPositionRepository;
use App\Traits\LookupTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

// Tambah import untuk Excel (tanpa Laravel-Excel)
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Cell\DataType;


class ReportingController extends Controller
{
    use LookupTrait;

    public StaffPositionRepository $staffPositionRepository;

    public function __construct()
    {
        $this->staffPositionRepository = new StaffPositionRepository();
    }

    public function index(Request $request){
        $currentYear = date("Y");
        $startYear = $currentYear - 10;

        $yearList = [];
        for ($year = $currentYear; $year >= $startYear; $year--) {
            $yearList[] = $year;
        }

        $gradeList = $this->getGrades();
        $branchList = $this->getBranches();

        $staff_name = $request->staff_name ?? null;
        $ic_no      = $request->ic_no ?? null;      // <-- TAMBAH baris ini
        $branch     = $request->branch ?? null;
        $grade      = $request->grade ?? null;
        $year_start = $request->year_start ?? null;
        $year_end   = $request->year_end ?? null;

        $staffList = [];
        if ($request->post()) {
            $staffList = $this->staffPositionRepository->getStaffByRequest($request);
        }

        // PDF generate (butang "Jana PDF" hantar ke route yang sama)
        if ($request->find_pdf_generate) {
            $pdf = Pdf::loadView('pdf.position_history', [
                'staffList' => $staffList,
            ])->setPaper('a4', 'landscape');

            return $pdf->stream('Staff List '.date('d F Y').'.pdf');
        }

        return view('reporting.position_history', [
            'yearList'    => $yearList,
            'currentYear' => $currentYear,
            'gradeList'   => $gradeList,
            'branchList'  => $branchList,
            'staffList'   => $staffList,
            'year_start'  => $year_start,
            'year_end'    => $year_end,
            'branch'      => $branch,
            'grade'       => $grade,
            'staff_name'  => $staff_name,
             'ic_no'       => $ic_no,       // <-- TAMBAH pass ke view
        ]);
    }

    /**
     * Export Excel tanpa Laravel-Excel (PhpSpreadsheet terus)
     */
    public function excelDownloadRaw(Request $request)
    {
        $rows = $this->staffPositionRepository->getStaffByRequest($request);

        $sheet = new Spreadsheet();
        $ws = $sheet->getActiveSheet();

        // Header
        $ws->fromArray([['Bil','Nama', 'No. IC', 'Jawatan','Gred','Cawangan','Tarikh Lantik','Tarikh Tamat']], null, 'A1');

        // Data
        $r = 2; $i = 1;
        foreach ($rows as $sl) {
            $ws->setCellValue("A{$r}", $i++);
            $ws->setCellValue("B{$r}", ucwords($sl->name ?? ''));
             $ws->setCellValueExplicit("C{$r}", (string)($sl->ic_no ?? ''), DataType::TYPE_STRING);
            $ws->setCellValue("D{$r}", $sl->position ?? '');
            $ws->setCellValue("E{$r}", $sl->grade ?? '');
            $ws->setCellValue("F{$r}", $sl->branch_name ?? '');
            $ws->setCellValue("G{$r}", $sl->start_date ?? '');
            $ws->setCellValue("H{$r}", $sl->end_date ?? '');
            $r++;
        }

        // Auto-size kolum
        foreach (range('A','H') as $col) {
            $ws->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($sheet);
        $filename = 'Staff List '.date('Y-m-d').'.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment;filename=\"{$filename}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
