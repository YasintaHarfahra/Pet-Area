<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\MedicalRecordsExport;
use App\Exports\PatientsExport;

class DoctorReportController extends Controller
{
    public function index()
    {
        return view('dokter.reports.index');
    }

    public function export(Request $request)
    {
        $request->validate([
            'range' => 'required|in:1_month,6_months,all',
            'type' => 'required|in:medical_records,patients',
            'format' => 'required|in:csv,excel,pdf',
        ]);

        $fileName = 'laporan-' . $request->type . '-' . date('Y-m-d');

        // Handle Excel & CSV using Maatwebsite/Excel
        if ($request->format === 'excel') {
            $export = $request->type === 'medical_records' 
                ? new MedicalRecordsExport($request->range) 
                : new PatientsExport($request->range);
            return Excel::download($export, $fileName . '.xlsx');
        }

        if ($request->format === 'csv') {
            $export = $request->type === 'medical_records' 
                ? new MedicalRecordsExport($request->range) 
                : new PatientsExport($request->range);
            return Excel::download($export, $fileName . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        // Handle PDF
        if ($request->format === 'pdf') {
            $data = $this->getDataForPdf($request->type, $request->range);
            $pdf = Pdf::loadView('dokter.reports.pdf_' . $request->type, [
                'data' => $data, 
                'range' => $request->range,
                'date' => date('d-m-Y')
            ]);
            return $pdf->download($fileName . '.pdf');
        }
    }

    private function getDataForPdf($type, $range)
    {
        if ($type === 'medical_records') {
            $query = MedicalRecord::with('patient')->orderBy('created_at', 'desc');
        } else {
            $query = Patient::orderBy('created_at', 'desc');
        }

        if ($range === '1_month') {
            $query->where('created_at', '>=', Carbon::now()->subMonth());
        } elseif ($range === '6_months') {
            $query->where('created_at', '>=', Carbon::now()->subMonths(6));
        }

        return $query->get();
    }
}
