<x-staff-layout>
    {{-- Include Chart.js and the Datalabels Plugin --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <div class="py-8 bg-[#f8f9fc] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- INTERACTIVE METRIC BUTTONS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <a href="{{ route('staff.pickup-return') }}" class="group block bg-white p-6 rounded-3xl border-2 border-transparent shadow-sm hover:shadow-xl hover:border-blue-500 hover:-translate-y-1 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="p-3 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 mr-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <span class="text-gray-400 font-bold text-[10px] uppercase tracking-[0.15em]">Action Required</span>
                                {{-- Changed from font-black to font-bold --}}
                                <h3 class="text-gray-900 font-bold text-lg uppercase leading-tight">Pickup today</h3>
                            </div>
                        </div>
                        <div class="text-right">
                            {{-- Kept count prominent but changed to font-bold --}}
                            <span class="text-4xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $pickupsToday }}</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('staff.pickup-return') }}" class="group block bg-white p-6 rounded-3xl border-2 border-transparent shadow-sm hover:shadow-xl hover:border-green-500 hover:-translate-y-1 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="p-3 rounded-2xl bg-green-50 text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300 mr-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            </div>
                            <div>
                                <span class="text-gray-400 font-bold text-[10px] uppercase tracking-[0.15em]">Active Status</span>
                                {{-- Changed from font-black to font-bold --}}
                                <h3 class="text-gray-900 font-bold text-lg uppercase leading-tight">Return today</h3>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-4xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">{{ $returnsToday }}</span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- RECENT BOOKINGS SECTION --}}
            <div class="mb-10">
                <div class="flex justify-between items-center mb-6">
                    {{-- Changed from font-black to font-bold --}}
                    <h3 class="font-bold text-xl text-gray-800 uppercase tracking-tight">RECENT BOOKINGS</h3>
                    <a href="{{ route('staff.bookingmanagement') }}" class="inline-flex items-center px-5 py-2 bg-red-50 text-red-600 font-bold text-[10px] uppercase tracking-widest rounded-full hover:bg-red-600 hover:text-white shadow-sm transition-all duration-200">
                        View All
                        <svg class="ml-2 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @forelse($recentBookings as $index => $booking)
                        @if($index == 0)
                            <div class="lg:col-span-2 bg-gradient-to-r from-blue-600 to-cyan-400 rounded-3xl p-8 relative overflow-hidden text-white shadow-lg">
                                <div class="relative z-10">
                                    <h4 class="text-2xl font-bold mb-1 uppercase">{{ $booking->modelName }}</h4>
                                    <p class="text-blue-50 font-medium mb-4 tracking-wider uppercase">{{ $booking->plateNumber }}</p>
                                    <div class="text-4xl font-bold mb-6">MYR {{ number_format($booking->totalPrice, 2) }}</div>
                                    <span class="bg-white/20 px-4 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest">{{ $booking->paymentStat ?? 'Paid' }}</span>
                                </div>
                            </div>
                        @else
                            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
                                <div class="w-14 h-14 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400">
                                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H11.05a2.5 2.5 0 014.9 0H17a1 1 0 001-1V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0011.586 3H4a1 1 0 00-1 1zm1 1h6.586L15 9.414V14H4V5z" /></svg>
                                </div>
                                <div>
                                    <h5 class="font-bold text-gray-800 text-sm leading-tight uppercase">{{ $booking->modelName }}</h5>
                                    <p class="text-gray-400 text-[10px] font-bold mb-1 uppercase">{{ $booking->plateNumber }}</p>
                                    <span class="text-[9px] font-bold text-green-500 bg-green-50 px-2 py-0.5 rounded uppercase">PAID</span>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="lg:col-span-3 text-center py-10 bg-white rounded-3xl text-gray-400 border-2 border-dashed border-gray-100 italic font-medium">No recent bookings found.</div>
                    @endforelse
                </div>
            </div>

            {{-- PIE CHART SECTION --}}
            <div class="mb-10">
                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-8">
                        {{-- Changed from font-black to font-bold --}}
                        <h3 class="font-bold text-xl text-gray-800 uppercase tracking-tight">Customer Distribution by College</h3>
                        <div class="flex items-center space-x-2">
                             <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                             </span>
                             <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Live Data</span>
                        </div>
                    </div>
                    
                    <div class="relative w-full flex justify-center" style="height: 500px;">
                        <canvas id="collegePieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Chart.register(ChartDataLabels);
            const ctx = document.getElementById('collegePieChart').getContext('2d');
            const collegeData = @json($collegeDistribution);
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: Object.keys(collegeData),
                    datasets: [{
                        data: Object.values(collegeData),
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#00D1FF', '#4D5360', '#2ecc71', '#e67e22'
                        ],
                        hoverOffset: 30,
                        borderWidth: 4,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 30,
                                usePointStyle: true,
                                font: { weight: 'bold', size: 12, family: 'Inter' }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            formatter: (value) => value > 3 ? value + '%' : '',
                            {{-- Changed datalabels font to font-bold (700) instead of 900 --}}
                            font: { weight: '700', size: 14 },
                            textShadowColor: 'rgba(0,0,0,0.3)',
                            textShadowBlur: 4
                        }
                    }
                }
            });
        });
    </script>
</x-staff-layout>