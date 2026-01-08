@extends('layouts.staff') 

@section('content')
{{-- 1. Load Chart.js Library --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="p-6 bg-gray-50 min-h-screen">
    
    {{-- Page Title --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Monthly Income</h1>
        <p class="text-sm text-gray-500">Track monthly revenue and performance</p>
    </div>

    {{-- Top Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        {{-- Card 1 --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Current Month</p>
                <p class="text-xl font-bold text-gray-800">RM {{ number_format($cards['current_month']) }}</p>
            </div>
        </div>
        {{-- Card 2 --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Previous Month</p>
                <p class="text-xl font-bold text-gray-800">RM {{ number_format($cards['previous_month']) }}</p>
            </div>
        </div>
        {{-- Card 3 --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-lg bg-purple-100 text-purple-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Average Monthly</p>
                <p class="text-xl font-bold text-gray-800">RM {{ number_format($cards['average_monthly']) }}</p>
            </div>
        </div>
        {{-- Card 4 --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-lg bg-orange-100 text-orange-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Yearly Total</p>
                <p class="text-xl font-bold text-gray-800">RM {{ number_format($cards['yearly_total']) }}</p>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        {{-- Left: Monthly Income Bar Chart --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-gray-800 font-bold">Monthly Income Overview</h3>
                <select class="border-gray-300 rounded text-sm text-gray-500 bg-gray-50 p-1">
                    <option>2025</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="incomeBarChart"></canvas>
            </div>
        </div>

        {{-- Right: Payment Methods Donut Chart --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-gray-800 font-bold mb-4">Payment Methods</h3>
            <div class="h-48 relative flex justify-center">
                <canvas id="paymentDonutChart"></canvas>
            </div>
            
            {{-- Custom Legend --}}
            <div class="mt-4 space-y-2">
                @foreach($paymentMethods as $name => $percentage)
                <div class="flex justify-between text-sm items-center">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full mr-2" 
                              style="background-color: 
                              {{ $name == 'Credit Card' ? '#C53030' : '' }} 
                              {{ $name == 'Online Banking' ? '#3182CE' : '' }}
                              {{ $name == 'Cash' ? '#38A169' : '' }}
                              {{ $name == 'E-Wallet' ? '#D69E2E' : '' }};">
                        </span>
                        <span class="text-gray-600">{{ $name }}</span>
                    </div>
                    <span class="font-bold text-gray-800">{{ $percentage }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Monthly Breakdown Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-gray-800 font-bold">Monthly Breakdown</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs text-gray-500 uppercase border-b border-gray-100 bg-gray-50/50">
                        <th class="px-6 py-4 font-semibold">Month</th>
                        <th class="px-6 py-4 font-semibold">Total Income</th>
                        <th class="px-6 py-4 font-semibold">Bookings</th>
                        <th class="px-6 py-4 font-semibold">Avg Per Booking</th>
                        <th class="px-6 py-4 font-semibold">Growth</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @foreach($breakdown as $row)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800">{{ $row['month'] }}</p>
                            <p class="text-xs text-gray-500">{{ $row['year'] }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-green-600">RM {{ number_format($row['income']) }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $row['bookings'] }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            RM {{ $row['avg'] }}
                        </td>
                        <td class="px-6 py-4">
                            @if($row['growth'] === null)
                                <span class="text-gray-400">-</span>
                            @elseif($row['growth'] > 0)
                                <div class="text-green-500 font-semibold flex items-center">
                                    +{{ $row['growth'] }}%
                                </div>
                            @else
                                <div class="text-red-500 font-semibold flex items-center">
                                    {{ $row['growth'] }}%
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Javascript to render the charts --}}
<script>
    // Wait for the DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Bar Chart Configuration
        const ctxBar = document.getElementById('incomeBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Income',
                    data: [45000, 52000, 48000, 61000, 58000, 67000, 72000, 68000, 64000, 71000, 68000, 75000],
                    backgroundColor: '#C53030', 
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Donut Chart Configuration
        const ctxDonut = document.getElementById('paymentDonutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($paymentMethods)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($paymentMethods)) !!},
                    backgroundColor: ['#C53030', '#3182CE', '#38A169', '#D69E2E'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });

    });
</script>

@endsection