<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Queue;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware will be applied in routes
    }

    /**
     * Show the doctor dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Data untuk dashboard
        $data = [
            'user' => Auth::user(),
            'totalPatients' => Patient::count(),
            'newPatientsThisWeek' => Patient::where('created_at', '>=', now()->subWeek())->count(),
            'totalMedicalRecords' => MedicalRecord::count(),
            'updatedRecordsToday' => MedicalRecord::where('updated_at', '>=', now()->startOfDay())->count(),
            'todayQueue' => Queue::whereDate('created_at', today())->count(),
            'waitingQueue' => Queue::whereDate('created_at', today())->where('status', 'waiting')->count(),
            'todayVisits' => Visit::whereDate('created_at', today())->count(),
            'pendingVisits' => Visit::whereDate('created_at', today())->where('status', 'pending')->count(),
            'currentQueue' => Queue::whereDate('created_at', today())
                ->where('status', 'waiting')
                ->with('patient')
                ->take(3)
                ->get()
        ];

        return view('dashboard-dokter', $data);
    }
}