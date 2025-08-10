<?php

namespace App\Http\Controllers;
use App\Models\Application;
use App\Models\Service;
use App\Models\Story;
use App\Models\Value;



use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Applications per month (last 6 months)
    $apps = Application::selectRaw('DATE_FORMAT(created_at, "%b %Y") as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderByRaw('MIN(created_at)')
        ->take(6)
        ->get();
    $applicationDates = $apps->pluck('month');
    $applicationCounts = $apps->pluck('count');

    // Service distribution
    $serviceNames = Service::pluck('title');
    $serviceCounts = Service::withCount('applications')->pluck('applications_count');

    return view('dashboard', [
        'servicesCount' => Service::count(),
        'storiesCount' => Story::count(),
        'valuesCount' => Value::count(),
        'applicationsCount' => Application::count(),
        'applicationDates' => $applicationDates,
        'applicationCounts' => $applicationCounts,
        'serviceNames' => $serviceNames,
        'serviceCounts' => $serviceCounts,
    ]);
}

}
