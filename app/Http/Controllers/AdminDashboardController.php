<?php
// app/Http/Controllers/AdminDashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Transaction;
use App\Models\MedicalRecord;
use App\Models\Queue;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalPatients = Patient::count();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $monthlyRevenue = Transaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                                     ->sum('amount');
        $formattedMonthlyRevenue = 'Rp ' . number_format($monthlyRevenue, 0, ',', '.');
        
        // Follow-up reminders data (H-7 sampai H+1)
        $followUpReminders = MedicalRecord::needingFollowUp(7, 1)
            ->with(['patient', 'doctor'])
            ->take(10)
            ->get();
        $overdueFollowUps = MedicalRecord::overdueFollowUp()->count();
        $upcomingFollowUps = MedicalRecord::upcomingFollowUp(7)->count();
        
        // Today's queue count
        $todayQueueCount = Queue::whereDate('created_at', today())->count();
        $waitingQueueCount = Queue::whereDate('created_at', today())->where('status', 'waiting')->count();
        
        // Recent patients (real data)
        $recentPatients = Patient::with(['medicalRecords' => function($q) {
            $q->latest('visit_date')->limit(1);
        }])->latest()->take(5)->get();

        return view('dashboard-admin', compact(
            'totalPatients', 
            'formattedMonthlyRevenue',
            'followUpReminders',
            'overdueFollowUps',
            'upcomingFollowUps',
            'todayQueueCount',
            'waitingQueueCount',
            'recentPatients'
        ));
    }
}