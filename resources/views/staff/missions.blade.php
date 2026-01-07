<x-layouts.staff>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Flash Message --}}
            @if(session('success'))
                <div class="mb-6 p-3 bg-green-100 border border-green-200 text-green-700 rounded-xl font-semibold flex items-center gap-3 text-sm">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex justify-between items-start mb-8">
                <div>
                    {{-- Updated to text-3xl per your request --}}
                    <h1 class="text-3xl font-bold text-[#1A1C1E]">Missions</h1>
                    <p class="text-sm text-gray-500 font-normal">Manage tasks and track commissions</p>
                </div>
                <button onclick="toggleModal('createTaskModal')" class="bg-[#C82333] hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow-md transition-all">
                    <i class="fas fa-plus text-xs"></i> Create Task
                </button>
            </div>

            {{-- Stats Overview --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                @php
                    $stats = [
                        ['Available', $missions->where('status', 'Available')->count(), 'blue', 'bullseye'],
                        ['Ongoing', $missions->where('status', 'Ongoing')->count(), 'yellow', 'clock'],
                        ['Completed', $missions->where('status', 'Completed')->count(), 'green', 'check-circle'],
                        ['My Earnings', 'RM ' . number_format($missions->where('status', 'Completed')->where('assigned_to', auth()->user()->staffID)->sum('commission'), 2), 'purple', 'hand-holding-usd']
                    ];
                @endphp

                @foreach($stats as [$label, $value, $color, $icon])
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="bg-{{$color}}-50 text-{{$color}}-500 p-3 rounded-xl"><i class="fas fa-{{$icon}} text-lg"></i></div>
                    <div>
                        <span class="text-xl font-bold text-gray-900">{{ $value }}</span>
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">{{ $label }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Tabs Filter --}}
            <div class="flex gap-3 mb-8">
                @php $tabClasses = "px-6 py-2 rounded-xl text-xs transition-all"; @endphp
                <a href="{{ route('staff.missions.index') }}" 
                   class="{{ $tabClasses }} {{ !request('status') ? 'bg-[#C82333] text-white font-bold' : 'bg-white text-gray-500 font-medium border border-gray-100' }}">
                    All Tasks
                </a>
                <a href="{{ route('staff.missions.index', ['status' => 'available']) }}" 
                   class="{{ $tabClasses }} {{ request('status') === 'available' ? 'bg-[#C82333] text-white font-bold' : 'bg-white text-gray-500 font-medium border border-gray-100' }}">
                    Available
                </a>
                <a href="{{ route('staff.missions.index', ['status' => 'ongoing']) }}" 
                   class="{{ $tabClasses }} {{ request('status') === 'ongoing' ? 'bg-[#C82333] text-white font-bold' : 'bg-white text-gray-500 font-medium border border-gray-100' }}">
                    Ongoing
                </a>
                <a href="{{ route('staff.missions.index', ['status' => 'completed']) }}" 
                   class="{{ $tabClasses }} {{ request('status') === 'completed' ? 'bg-[#C82333] text-white font-bold' : 'bg-white text-gray-500 font-medium border border-gray-100' }}">
                    Completed
                </a>
            </div>

            {{-- Mission Cards --}}
            <div class="space-y-4">
                @forelse($missions as $mission)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 transition-all hover:border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-[#1A1C1E]">{{ $mission->title }}</h3>
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase">{{ $mission->status }}</span>
                                <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase">RM {{ number_format($mission->commission, 0) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 font-normal"><span class="font-bold text-[#1A1C1E]">Requirements:</span> {{ $mission->requirements }}</p>
                            <p class="text-gray-400 text-[11px] mt-2">Created: {{ $mission->created_at->format('Y-m-d') }}</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick='showDetails(@json($mission))' class="px-5 py-2 bg-gray-50 text-gray-600 rounded-lg font-bold text-xs border border-gray-100 hover:bg-gray-100">Details</button>
                            
                            @if($mission->status == 'Available')
                            <form action="{{ route('staff.missions.accept', $mission->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-5 py-2 bg-[#0D6EFD] text-white rounded-lg font-bold text-xs shadow-sm hover:bg-blue-700">Accept</button>
                            </form>
                            @endif

                            @if($mission->status == 'Ongoing' && $mission->assigned_to == auth()->user()->staffID)
                            <form action="{{ route('staff.missions.complete', $mission->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg font-bold text-xs shadow-sm hover:bg-green-700">Complete</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                    <div class="p-12 text-center bg-white rounded-2xl border border-dashed border-gray-200 font-medium text-sm text-gray-400">No missions found.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.staff>