{{-- filepath: resources/views/dashboard.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-2 flex flex-col items-center justify-center">
    <div class="w-full max-w-7xl">
        <h1 class="text-2xl font-extrabold mb-4 text-gray-800 dark:text-gray-100 tracking-tight">Uplifta CMS Dashboard</h1>
        
        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-600 to-blue-400 dark:from-blue-900 dark:to-blue-700 rounded-xl shadow flex flex-col items-center justify-center p-3 hover:scale-105 transition-transform min-h-[100px]">
                <div class="bg-white/20 rounded-full p-2 mb-1">
                    <i class="fa fa-briefcase fa-lg text-white"></i>
                </div>
                <div class="text-center">
                    <div class="text-xl font-bold text-white">{{ $servicesCount ?? 0 }}</div>
                    <div class="text-blue-100 text-xs">Services</div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-600 to-green-400 dark:from-green-900 dark:to-green-700 rounded-xl shadow flex flex-col items-center justify-center p-3 hover:scale-105 transition-transform min-h-[100px]">
                <div class="bg-white/20 rounded-full p-2 mb-1">
                    <i class="fa fa-star fa-lg text-white"></i>
                </div>
                <div class="text-center">
                    <div class="text-xl font-bold text-white">{{ $storiesCount ?? 0 }}</div>
                    <div class="text-green-100 text-xs">Stories</div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-purple-600 to-purple-400 dark:from-purple-900 dark:to-purple-700 rounded-xl shadow flex flex-col items-center justify-center p-3 hover:scale-105 transition-transform min-h-[100px]">
                <div class="bg-white/20 rounded-full p-2 mb-1">
                    <i class="fa fa-heart fa-lg text-white"></i>
                </div>
                <div class="text-center">
                    <div class="text-xl font-bold text-white">{{ $valuesCount ?? 0 }}</div>
                    <div class="text-purple-100 text-xs">Values</div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-300 dark:from-yellow-700 dark:to-yellow-500 rounded-xl shadow flex flex-col items-center justify-center p-3 hover:scale-105 transition-transform min-h-[100px]">
                <div class="bg-white/20 rounded-full p-2 mb-1">
                    <i class="fa fa-file-alt fa-lg text-white"></i>
                </div>
                <div class="text-center">
                    <div class="text-xl font-bold text-white">{{ $applicationsCount ?? 0 }}</div>
                    <div class="text-yellow-100 text-xs">Applications</div>
                </div>
            </div>
        </div>

        {{-- Graphs Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow flex flex-col justify-between h-[260px] p-4">
                <h2 class="text-base font-semibold mb-2 text-gray-800 dark:text-gray-100">Applications Over Time</h2>
                <div class="flex-1 flex items-center justify-center">
                    <canvas id="applicationsChart" height="120" style="max-width:100%"></canvas>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow flex flex-col justify-between h-[260px] p-4">
                <h2 class="text-base font-semibold mb-2 text-gray-800 dark:text-gray-100">Service Distribution</h2>
                <div class="flex-1 flex items-center justify-center">
                    <canvas id="servicesChart" height="120" style="max-width:100%"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Applications Over Time
    const applicationsChart = document.getElementById('applicationsChart').getContext('2d');
    new Chart(applicationsChart, {
        type: 'line',
        data: {
            labels: {!! json_encode($applicationDates ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
            datasets: [{
                label: 'Applications',
                data: {!! json_encode($applicationCounts ?? [5, 10, 8, 15, 12, 20]) !!},
                borderColor: '#f59e42',
                backgroundColor: 'rgba(251, 191, 36, 0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            plugins: { 
                legend: { display: false },
                tooltip: { backgroundColor: '#2563eb', titleColor: '#fff', bodyColor: '#fff' }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e5e7eb' } },
                x: { grid: { color: '#e5e7eb' } }
            }
        }
    });

    // Service Distribution
    const servicesChart = document.getElementById('servicesChart').getContext('2d');
    new Chart(servicesChart, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($serviceNames ?? ['Microloans', 'Savings', 'Education']) !!},
            datasets: [{
                data: {!! json_encode($serviceCounts ?? [12, 7, 5]) !!},
                backgroundColor: ['#2563eb', '#22c55e', '#f59e42', '#a78bfa', '#f87171'],
                borderWidth: 2
            }]
        },
        options: {
            plugins: { 
                legend: { position: 'bottom', labels: { color: '#374151' } },
                tooltip: { backgroundColor: '#2563eb', titleColor: '#fff', bodyColor: '#fff' }
            },
            cutout: '70%',
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endsection