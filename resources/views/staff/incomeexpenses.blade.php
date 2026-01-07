<x-layouts.staff>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Income & Expenses</h1>
                    <p class="text-gray-500 font-medium">Track your income, expenses, and profit margins</p>
                </div>
                <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2 transition-all shadow-md">
                    <i class="fas fa-file-export"></i>
                    Export Report
                </button>
            </div>

            {{-- Summary Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                {{-- Changed font-black to font-bold in the RM values below --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center">
                    <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-3">Total Income</p>
                    <div class="flex items-center gap-4">
                        <div class="bg-green-50 text-green-500 p-3 rounded-xl">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-bold text-gray-900">RM 333,600</span>
                            <p class="text-green-500 text-[10px] font-bold mt-1">Last 6 months</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center">
                    <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-3">Total Expenses</p>
                    <div class="flex items-center gap-4">
                        <div class="bg-red-50 text-red-500 p-3 rounded-xl">
                            <i class="fas fa-chart-area text-xl"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-bold text-gray-900">RM 82,100</span>
                            <p class="text-red-500 text-[10px] font-bold mt-1">Last 6 months</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center">
                    <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-3">Net Profit</p>
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-50 text-blue-500 p-3 rounded-xl">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-bold text-gray-900">RM 251,500</span>
                            <p class="text-blue-500 text-[10px] font-bold mt-1">Last 6 months</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center">
                    <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-3">Profit Margin</p>
                    <div class="flex items-center gap-4">
                        <div class="bg-purple-50 text-purple-500 p-3 rounded-xl">
                            <i class="fas fa-percentage text-xl"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-bold text-gray-900">75.4%</span>
                            <p class="text-purple-500 text-[10px] font-bold mt-1 uppercase">Average</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Chart Section --}}
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Income vs Expenses Trend</h3>
                <div class="h-80 w-full">
                    <canvas id="incomeExpensesChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Expense Categories (Left) --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-8">Expense Categories</h3>
                    
                    <div class="space-y-6">
                        @php
                            $expenses = [
                                ['name' => 'Maintenance', 'amount' => 5200, 'percent' => 34, 'color' => 'bg-blue-500'],
                                ['name' => 'Fuel', 'amount' => 3800, 'percent' => 25, 'color' => 'bg-green-500'],
                                ['name' => 'Insurance', 'amount' => 2500, 'percent' => 16, 'color' => 'bg-yellow-500'],
                                ['name' => 'Staff Salaries', 'amount' => 2100, 'percent' => 14, 'color' => 'bg-purple-500'],
                                ['name' => 'Other', 'amount' => 1600, 'percent' => 11, 'color' => 'bg-slate-400'],
                            ];
                        @endphp

                        @foreach($expenses as $item)
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-gray-600">{{ $item['name'] }}</span>
                                    <span class="text-sm font-bold text-gray-900">RM {{ number_format($item['amount']) }} <span class="text-gray-400 font-medium">({{ $item['percent'] }}%)</span></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="{{ $item['color'] }} h-2 rounded-full" style="width: {{ $item['percent'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-md font-bold text-gray-500">Total Expenses</span>
                        {{-- Changed font-black to font-bold --}}
                        <span class="text-xl font-bold text-red-600">RM 15,200</span>
                    </div>
                </div>

                {{-- Monthly Breakdown (Right) --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-8">Monthly Breakdown</h3>
                    
                    <div class="space-y-4 max-h-[420px] overflow-y-auto pr-2 custom-scrollbar">
                        @php
                            $breakdown = [
                                ['month' => 'Jan', 'income' => 45200, 'expenses' => 12500, 'profit' => 32700, 'margin' => '72.3'],
                                ['month' => 'Feb', 'income' => 52100, 'expenses' => 13200, 'profit' => 38900, 'margin' => '74.7'],
                                ['month' => 'Mar', 'income' => 48900, 'expenses' => 12800, 'profit' => 36100, 'margin' => '73.8'],
                                ['month' => 'Apr', 'income' => 61200, 'expenses' => 14500, 'profit' => 46700, 'margin' => '76.3'],
                                ['month' => 'May', 'income' => 58400, 'expenses' => 13900, 'profit' => 44500, 'margin' => '76.2'],
                            ];
                        @endphp

                        @foreach($breakdown as $data)
                            <div class="p-5 border border-gray-50 rounded-2xl hover:bg-gray-50 transition-colors group">
                                <div class="flex justify-between items-center mb-4">
                                    {{-- Changed font-black to font-bold --}}
                                    <span class="text-lg font-bold text-gray-800">{{ $data['month'] }}</span>
                                    <span class="px-3 py-1 rounded-lg font-bold text-[11px] {{ $data['margin'] > 75 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $data['margin'] }}% margin
                                    </span>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Income</p>
                                        {{-- Changed font-black to font-bold --}}
                                        <p class="text-sm font-bold text-green-600">RM {{ number_format($data['income']) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Expenses</p>
                                        <p class="text-sm font-bold text-red-600">RM {{ number_format($data['expenses']) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Profit</p>
                                        <p class="text-sm font-bold text-blue-600">RM {{ number_format($data['profit']) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ChartJS Logic --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('incomeExpensesChart').getContext('2d');
            
            const incomeGradient = ctx.createLinearGradient(0, 0, 0, 400);
            incomeGradient.addColorStop(0, 'rgba(16, 185, 129, 0.1)');
            incomeGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [
                        {
                            label: 'Income',
                            data: [45000, 52000, 49000, 62000, 59000, 68000],
                            borderColor: '#10b981',
                            backgroundColor: incomeGradient,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#10b981',
                            borderWidth: 3
                        },
                        {
                            label: 'Profit',
                            data: [32000, 39000, 35000, 47000, 45000, 53000],
                            borderColor: '#3b82f6',
                            fill: false,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#3b82f6',
                            borderWidth: 3
                        },
                        {
                            label: 'Expenses',
                            data: [13000, 13000, 14000, 15000, 14000, 15000],
                            borderColor: '#ef4444',
                            fill: false,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#ef4444',
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f3f4f6', drawBorder: false },
                            ticks: {
                                color: '#9ca3af',
                                font: { size: 11, weight: '600' }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#4b5563',
                                font: { size: 11, weight: '600' }
                            }
                        }
                    }
                }
            });
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
</x-layouts.staff>