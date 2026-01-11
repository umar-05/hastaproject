@extends('layouts.staff')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
   
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Daily Income</h1>
            <p class="text-gray-600">Track daily revenue and transactions</p>
        </div>
        <!-- Excel Export Button -->
        <button onclick="exportToExcel()" class="flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg shadow transition">
            <span class="text-xl">📊</span>
            <span>Excel</span>
        </button>
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

        // Excel Export Function
        function exportToExcel() {
            // Get data from PHP variables
            const todayIncome = {{ $todayIncome }};
            const yesterdayIncome = {{ $yesterdayIncome }};
            const change = {{ $change }};
            const transactionCount = {{ $transactionCount }};
            const recentTransactions = @json($recentTransactions);
            
            // Create workbook
            const wb = XLSX.utils.book_new();
            
            // Sheet 1: Summary
            const summaryData = [
                ['Daily Income Report'],
                ['Generated on:', new Date().toLocaleString('en-MY', { timeZone: 'Asia/Kuala_Lumpur' })],
                [],
                ['Metric', 'Value'],
                ['Today\'s Income', 'RM ' + todayIncome.toFixed(2)],
                ['Yesterday\'s Income', 'RM ' + yesterdayIncome.toFixed(2)],
                ['Change', (change >= 0 ? '+' : '') + change.toFixed(1) + '%'],
                ['Transactions', transactionCount]
            ];
            
            const ws1 = XLSX.utils.aoa_to_sheet(summaryData);
            
            // Set column widths
            ws1['!cols'] = [
                { wch: 20 },
                { wch: 20 }
            ];
            
            XLSX.utils.book_append_sheet(wb, ws1, 'Summary');
            
            // Sheet 2: Daily Income Chart Data
            const incomeChartData = [
                ['Date', 'Income (RM)'],
                ...incomeData.map(item => [
                    new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
                    parseFloat(item.total).toFixed(2)
                ])
            ];
            
            const ws2 = XLSX.utils.aoa_to_sheet(incomeChartData);
            ws2['!cols'] = [
                { wch: 15 },
                { wch: 15 }
            ];
            
            XLSX.utils.book_append_sheet(wb, ws2, 'Daily Income');
            
            // Sheet 3: Bookings Trend Data
            const bookingsTrendData = [
                ['Date', 'Number of Bookings'],
                ...bookingsData.map(item => [
                    new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
                    item.count
                ])
            ];
            
            const ws3 = XLSX.utils.aoa_to_sheet(bookingsTrendData);
            ws3['!cols'] = [
                { wch: 15 },
                { wch: 20 }
            ];
            
            XLSX.utils.book_append_sheet(wb, ws3, 'Bookings Trend');
            
            // Sheet 4: Recent Transactions
            if (recentTransactions && recentTransactions.length > 0) {
                const transactionsData = [
                    ['Booking ID', 'Customer', 'Amount (RM)', 'Date', 'Time', 'Payment Method'],
                    ...recentTransactions.map(t => [
                        t.booking_id,
                        t.customer,
                        parseFloat(t.amount).toFixed(2),
                        t.date,
                        t.time,
                        t.payment_method
                    ])
                ];
                
                const ws4 = XLSX.utils.aoa_to_sheet(transactionsData);
                ws4['!cols'] = [
                    { wch: 12 },
                    { wch: 20 },
                    { wch: 15 },
                    { wch: 12 },
                    { wch: 10 },
                    { wch: 18 }
                ];
                
                XLSX.utils.book_append_sheet(wb, ws4, 'Transactions');
            }
            
            // Generate filename with current date
            const today = new Date();
            const filename = `Daily_Income_Report_${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}.xlsx`;
            
            // Save file
            XLSX.writeFile(wb, filename);
            
            // Optional: Show success message
            alert('Excel file downloaded successfully!');
        }
    </script>
@endsection