@extends('layouts.staff')


@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Daily Income</h1>
        <p class="text-gray-600">Track daily revenue and transactions</p>
    </div>


    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Today's Income -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600 text-sm">Today's Income</span>
                <span class="text-2xl">💵</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">
                RM {{ number_format($todayIncome, 2) }}
            </div>
        </div>


        <!-- Yesterday's Income -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600 text-sm">Yesterday</span>
                <span class="text-2xl">📊</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">
                RM {{ number_format($yesterdayIncome, 0) }}
            </div>
        </div>


        <!-- Change -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-{{ $change >= 0 ? 'green' : 'red' }}-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600 text-sm">Change</span>
                <span class="text-2xl">{{ $change >= 0 ? '📈' : '📉' }}</span>
            </div>
            <div class="text-2xl font-bold {{ $change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 1) }}%
            </div>
        </div>


        <!-- Transactions -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600 text-sm">Transactions</span>
                <span class="text-2xl">📝</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">
                {{ $transactionCount }}
            </div>
        </div>
    </div>


    <!-- Charts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Daily Income Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Daily Income Overview</h3>
            <div style="height: 300px;">
                <canvas id="incomeChart"></canvas>
            </div>
        </div>


        <!-- Bookings Trend Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Bookings Trend</h3>
            <div style="height: 300px;">
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>
    </div>


    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Recent Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Method</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentTransactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $transaction['booking_id'] }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $transaction['customer'] }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600">
                            RM {{ number_format($transaction['amount'], 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $transaction['date'] }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $transaction['time'] }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                {{ $transaction['payment_method'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No transactions found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <script>
        // Daily Income Chart
        const incomeCtx = document.getElementById('incomeChart').getContext('2d');
        const incomeData = @json($dailyIncomeChart);
       
        new Chart(incomeCtx, {
            type: 'bar',
            data: {
                labels: incomeData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Daily Income (RM)',
                    data: incomeData.map(item => item.total),
                    backgroundColor: '#dc2626',
                    borderColor: '#dc2626',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'RM ' + value;
                            }
                        }
                    }
                }
            }
        });


        // Bookings Trend Chart
        const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
        const bookingsData = @json($bookingsTrend);
       
        new Chart(bookingsCtx, {
            type: 'line',
            data: {
                labels: bookingsData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Number of Bookings',
                    data: bookingsData.map(item => item.count),
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection
