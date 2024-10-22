<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    public function index()
    {

        return view('admin.dashboard');
    }

    public function getDemographicData()
    {
        $appointmentsToday = Appointment::whereDate('appointment_date', today())->count();
        $totalPatients = User::count();
        $totalDoctors = Employee::where('role', 'doctor')->count();

        return response()->json([
            'appointmentsToday' => $appointmentsToday,
            'totalPatients' => $totalPatients,
            'totalDoctors' => $totalDoctors,
        ]);
    }
    public function getChartData()
    {
        // Fetch user registration data by day of the current month
        $userData = Appointment::whereMonth('appointment_date', Carbon::now()->month)
            ->selectRaw('DAY(appointment_date) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Convert day numbers to day names (e.g., 1 -> January)
        $labels = $userData->map(function ($item) {
            return date("j", mktime(0, 0, 0, Carbon::now()->month, $item->day));
        });

        $counts = $userData->pluck('count')->toArray();

        // Fetch appointment data for the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $doughnutData = Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        Log::info('Labels:', $labels->toArray());
        Log::info('Appointment Counts:', $counts);
        Log::info('Doughnut Data:', $doughnutData);

        // Return JSON data for charts
        return response()->json([
            'labels' => $labels,
            'appointmentCounts' => $counts,
            'doughnutData' => $doughnutData
        ]);
    }
}
