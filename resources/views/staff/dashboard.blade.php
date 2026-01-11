<x-staff-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <div class="py-6 bg-[#f8f9fc] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- TOP METRICS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 rounded-xl bg-blue-50 text-blue-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div>
                            <div>
                                <p class="text-gray-400 font-semibold text-[10px] uppercase">Action Required</p>
                                <h3 class="text-gray-800 font-bold text-base uppercase">Pickup today</h3>
                            </div>
                        </div>
                        <span class="text-3xl font-bold text-gray-900">{{ $pickupsToday }}</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 rounded-xl bg-green-50 text-green-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div>
                            <div>
                                <p class="text-gray-400 font-semibold text-[10px] uppercase">Active Status</p>
                                <h3 class="text-gray-800 font-bold text-base uppercase">Return today</h3>
                            </div>
                        </div>
                        <span class="text-3xl font-bold text-gray-900">{{ $returnsToday }}</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 rounded-xl bg-purple-50 text-purple-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                            <div>
                                <p class="text-gray-400 font-semibold text-[10px] uppercase">Fleet Status</p>
                                <h3 class="text-gray-800 font-bold text-base uppercase">Available</h3>
                            </div>
                        </div>
                        <span class="text-3xl font-bold text-gray-900">{{ $availableCarsCount }}</span>
                    </div>
                </div>
            </div>

            {{-- AVAILABILITY TABLE --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
                <h3 class="font-bold text-lg text-gray-800 mb-6">Daily Car Availability</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-gray-400 text-[11px] uppercase">
                                <th class="pb-4 font-semibold">Vehicle</th>
                                @foreach($weekDates as $day)
                                    <th class="pb-4 text-center">
                                        <span class="block {{ $day['is_today'] ? 'text-red-500' : 'text-gray-500' }} font-bold text-xs">{{ $day['name'] }}</span>
                                        <span class="block text-gray-800 text-sm">{{ $day['date'] }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($fleetAvailability as $car)
                            <tr>
                                <td class="py-4">
                                    <h4 class="font-semibold text-gray-800 text-xs">{{ $car['modelName'] }}</h4>
                                    <p class="text-[10px] text-gray-400">{{ $car['plateNumber'] }}</p>
                                </td>
                                @foreach($car['schedule'] as $status)
                                <td class="py-4 text-center">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $status['is_today'] ? 'ring-1 ring-red-400' : '' }} {{ $status['available'] ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-400' }}">
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

            {{-- RECENT BOOKINGS & CHART --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg text-gray-800 mb-6 uppercase">Recent Bookings</h3>
                    <div class="space-y-4">
                        @foreach($recentBookings as $booking)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div>
                                <p class="text-xs font-bold text-gray-800">{{ $booking->modelName }}</p>
                                <p class="text-[10px] text-gray-400">{{ $booking->plateNumber }}</p>
                            </div>
                            <span class="text-xs font-bold text-gray-900">MYR {{ number_format($booking->totalPrice, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg text-gray-800 mb-6 uppercase">Customer Distribution</h3>
                    <div class="relative w-full" style="height: 250px;">
                        <canvas id="collegeBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        borderRadius: 4,
                        barThickness: 10
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
                            formatter: (v) => v + '%'
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