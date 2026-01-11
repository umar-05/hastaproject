<x-staff-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    {{-- Main Container: Fixed height to fill the screen --}}
    <div class="h-[calc(100vh-64px)] bg-[#f8f9fc] p-4 flex flex-col overflow-hidden">
        
        {{-- TOP ROW: METRICS --}}
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-xl bg-blue-50 text-blue-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div>
                    <h3 class="text-gray-800 font-bold text-xs uppercase">Pickup today</h3>
                </div>
                <span class="text-2xl font-bold text-gray-900">{{ $pickupsToday }}</span>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-xl bg-green-50 text-green-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div>
                    <h3 class="text-gray-800 font-bold text-xs uppercase">Return today</h3>
                </div>
                <span class="text-2xl font-bold text-gray-900">{{ $returnsToday }}</span>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-xl bg-purple-50 text-purple-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                    <h3 class="text-gray-800 font-bold text-xs uppercase">Available</h3>
                </div>
                <span class="text-2xl font-bold text-gray-900">{{ $availableCarsCount }}</span>
            </div>
        </div>

        {{-- MAIN CONTENT: Two Column Layout --}}
        <div class="flex-1 flex gap-4 min-h-0">
            
            {{-- LEFT: DAILY AVAILABILITY --}}
            <div class="w-2/3 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col min-h-0">
                <h3 class="font-bold text-sm text-gray-800 mb-4 uppercase">Daily Car Availability</h3>
                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    <table class="w-full text-left">
                        <thead class="sticky top-0 bg-white z-10">
                            <tr class="text-gray-400 text-[10px] uppercase">
                                <th class="pb-3 font-semibold">Vehicle</th>
                                @foreach($weekDates as $day)
                                    <th class="pb-3 text-center">
                                        <span class="block {{ $day['is_today'] ? 'text-red-500' : 'text-gray-500' }} font-bold text-[10px]">{{ $day['name'] }}</span>
                                        <span class="block text-gray-800 text-xs">{{ $day['date'] }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($fleetAvailability as $car)
                            <tr>
                                <td class="py-3">
                                    <h4 class="font-bold text-gray-800 text-[11px]">{{ $car['modelName'] }}</h4>
                                    <p class="text-[9px] text-gray-400">{{ $car['plateNumber'] }}</p>
                                </td>
                                @foreach($car['schedule'] as $status)
                                <td class="py-3 text-center">
                                    <div class="inline-flex items-center justify-center w-7 h-7 rounded-lg {{ $status['is_today'] ? 'ring-1 ring-red-400' : '' }} {{ $status['available'] ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-400' }}">
                                        @if($status['available'])
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                                        @endif
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- RIGHT: SIDEBAR (Bookings & Distribution) --}}
            <div class="w-1/3 flex flex-col gap-4 min-h-0">
                
                {{-- RECENT BOOKINGS (Smaller Header) --}}
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-bold text-xs text-gray-800 uppercase">Recent Bookings</h3>
                        <a href="#" class="text-[9px] font-bold text-red-500 uppercase">View All</a>
                    </div>
                    <div class="space-y-2">
                        @forelse($recentBookings as $booking)
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-xl border border-gray-100">
                            <div>
                                <p class="text-[10px] font-bold text-gray-800 leading-tight">{{ $booking->modelName }}</p>
                                <p class="text-[9px] text-gray-400">{{ $booking->plateNumber }}</p>
                            </div>
                            <span class="text-[10px] font-bold text-gray-900">MYR {{ number_format($booking->totalPrice, 0) }}</span>
                        </div>
                        @empty
                        <p class="text-[10px] text-gray-400 italic">No recent bookings</p>
                        @endforelse
                    </div>
                </div>

                {{-- COLLEGE DISTRIBUTION (Fills the remaining height) --}}
                <div class="flex-1 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col min-h-0">
                    <h3 class="font-bold text-xs text-gray-800 mb-4 uppercase">College Distribution</h3>
                    <div class="flex-1 min-h-0">
                        <canvas id="collegeBarChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Chart.register(ChartDataLabels);
            const ctx = document.getElementById('collegeBarChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json(array_keys($collegeDistribution)),
                    datasets: [{
                        data: @json(array_values($collegeDistribution)),
                        backgroundColor: '#06b6d4',
                        borderRadius: 3,
                        barThickness: 'flex',
                        maxBarThickness: 12
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        datalabels: {
                            anchor: 'end', align: 'right', color: '#9ca3af',
                            font: { size: 9, weight: '600' },
                            formatter: (v) => v > 0 ? v + '%' : ''
                        }
                    },
                    scales: {
                        x: { display: false, max: 100 },
                        y: { grid: { display: false }, ticks: { font: { size: 9, weight: '600' } } }
                    }
                }
            });
        });
    </script>
</x-staff-layout>