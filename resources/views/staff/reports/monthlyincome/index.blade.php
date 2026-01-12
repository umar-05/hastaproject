@extends('layouts.staff') 

@section('content')
{{-- Load Chart.js and SheetJS Libraries --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>

<div class="p-6 bg-gray-50 min-h-screen">
    
    {{-- Page Title with Excel Button --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Monthly Income</h1>
            <p class="text-sm text-gray-500">Track monthly revenue and performance for the year {{ date('Y') }}</p>
        </div>
        {{-- Excel Export Button --}}
        <button onclick="exportToExcel()" class="flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg shadow transition">
            <span class="text-xl">📊</span>
            <span>Excel</span>
        </button>
    </div>

    {{-- Top Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        {{-- Card 1: Current Month --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Current Month</p>
                <p class="text-xl font-bold text-gray-800">RM {{ number_format($cards['current_month'], 2) }}</p>
            </div>
        </div>

        {{-- Card 2: Previous Month --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Previous Month</p>
                <p class="text-xl font-bold text-gray-800">RM {{ number_format($cards['previous_month'], 2) }}</p>
            </div>
        </div>

        {{-- Card 3: Average --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-lg bg-purple-100 text-purple-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Average Monthly</p>
                <p class="text-xl font-bold text-gray-800">RM {{ number_format($cards['average_monthly'], 2) }}</p>
            </div>
        </div>

        {{-- Card 4: Yearly Total --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-lg bg-orange-100 text-orange-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Yearly Total</p>
                <p class="text-xl font-bold text-gray-800">RM {{ number_format($cards['yearly_total'], 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        {{-- Left: Monthly Income Bar Chart --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-gray-800 font-bold">Monthly Income Overview</h3>
                <div class="text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded border">
                    Year: {{ date('Y') }}
                </div>
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
            
            {{-- Dynamic Legend --}}
            <div class="mt-4 space-y-2">
                @php $colors = ['#C53030', '#3182CE', '#38A169', '#D69E2E', '#805AD5']; @endphp
                @foreach($paymentMethods as $methodName => $count)
                <div class="flex justify-between text-sm items-center">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $colors[$loop->index] ?? '#cbd5e0' }};"></span>
                        <span class="text-gray-600">{{ ucfirst($methodName) }}</span>
                    </div>
                    <span class="font-bold text-gray-800">{{ $count }} Txns</span>
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
                            <p class="font-bold text-green-600">RM {{ number_format($row['income'], 2) }}</p>
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
                            @elseif($row['growth'] >= 0)
                                <div class="text-green-500 font-semibold flex items-center">
                                    +{{ number_format($row['growth'], 1) }}%
                                </div>
                            @else
                                <div class="text-red-500 font-semibold flex items-center">
                                    {{ number_format($row['growth'], 1) }}%
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
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Bar Chart Configuration (Monthly Income)
        const ctxBar = document.getElementById('incomeBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: @json(array_column($breakdown, 'month')),
                datasets: [{
                    label: 'Income (RM)',
                    data: @json(array_column($breakdown, 'income')),
                    backgroundColor: '#C53030', 
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'RM ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [2, 4] },
                        ticks: {
                            callback: function(value) { return 'RM ' + value.toLocaleString(); }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Donut Chart Configuration (Payment Methods)
        const ctxDonut = document.getElementById('paymentDonutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($paymentMethods)),
                datasets: [{
                    data: @json(array_values($paymentMethods)),
                    backgroundColor: ['#C53030', '#3182CE', '#38A169', '#D69E2E', '#805AD5'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
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

    // Excel Export Function
    function exportToExcel() {
        // Get data from PHP variables
        const currentMonth = {{ $cards['current_month'] }};
        const previousMonth = {{ $cards['previous_month'] }};
        const averageMonthly = {{ $cards['average_monthly'] }};
        const yearlyTotal = {{ $cards['yearly_total'] }};
        const breakdown = @json($breakdown);
        const paymentMethods = @json($paymentMethods);
        const currentYear = '{{ date("Y") }}';
        
        // Create workbook
        const wb = XLSX.utils.book_new();
        
        // Sheet 1: Summary
        const summaryData = [
            ['Monthly Income Report'],
            ['Year: ' + currentYear],
            ['Generated on:', new Date().toLocaleString('en-MY', { timeZone: 'Asia/Kuala_Lumpur' })],
            [],
            ['Metric', 'Value'],
            ['Current Month', 'RM ' + currentMonth.toFixed(2)],
            ['Previous Month', 'RM ' + previousMonth.toFixed(2)],
            ['Average Monthly', 'RM ' + averageMonthly.toFixed(2)],
            ['Yearly Total', 'RM ' + yearlyTotal.toFixed(2)]
        ];
        
        const ws1 = XLSX.utils.aoa_to_sheet(summaryData);
        ws1['!cols'] = [
            { wch: 20 },
            { wch: 20 }
        ];
        XLSX.utils.book_append_sheet(wb, ws1, 'Summary');
        
        // Sheet 2: Monthly Breakdown
        const breakdownData = [
            ['Month', 'Year', 'Total Income (RM)', 'Bookings', 'Avg Per Booking (RM)', 'Growth (%)'],
            ...breakdown.map(row => [
                row.month,
                row.year,
                parseFloat(row.income).toFixed(2),
                row.bookings,
                row.avg,
                row.growth !== null ? (row.growth >= 0 ? '+' + row.growth.toFixed(1) : row.growth.toFixed(1)) : '-'
            ])
        ];
        
        const ws2 = XLSX.utils.aoa_to_sheet(breakdownData);
        ws2['!cols'] = [
            { wch: 12 },
            { wch: 8 },
            { wch: 18 },
            { wch: 10 },
            { wch: 20 },
            { wch: 12 }
        ];
        XLSX.utils.book_append_sheet(wb, ws2, 'Monthly Breakdown');
        
        // Sheet 3: Payment Methods
        const paymentData = [
            ['Payment Method', 'Number of Transactions'],
            ...Object.entries(paymentMethods).map(([method, count]) => [
                method.charAt(0).toUpperCase() + method.slice(1),
                count
            ])
        ];
        
        const ws3 = XLSX.utils.aoa_to_sheet(paymentData);
        ws3['!cols'] = [
            { wch: 20 },
            { wch: 25 }
        ];
        XLSX.utils.book_append_sheet(wb, ws3, 'Payment Methods');
        
        // Sheet 4: Monthly Income Chart Data
        const chartData = [
            ['Month', 'Income (RM)'],
            ...breakdown.map(row => [
                row.month + ' ' + row.year,
                parseFloat(row.income).toFixed(2)
            ])
        ];
        
        const ws4 = XLSX.utils.aoa_to_sheet(chartData);
        ws4['!cols'] = [
            { wch: 15 },
            { wch: 15 }
        ];
        XLSX.utils.book_append_sheet(wb, ws4, 'Chart Data');
        
        // Generate filename with current date
        const today = new Date();
        const filename = `Monthly_Income_Report_${currentYear}_${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}.xlsx`;
        
        // Save file
        XLSX.writeFile(wb, filename);
        
        // Optional: Show success message
        alert('Excel file downloaded successfully!');
    }
</script>

@endsection