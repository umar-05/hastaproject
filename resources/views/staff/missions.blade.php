<x-layouts.staff>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Flash Success Message --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-bold flex items-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex justify-between items-start mb-8">
                <div>
                    {{-- Changed font-black to font-bold --}}
                    <h1 class="text-4xl font-bold text-[#1A1C1E]">Missions</h1>
                    <p class="text-gray-500 font-medium">Manage and track work tasks with bonus commissions</p>
                </div>
                <button onclick="toggleModal('createTaskModal')" class="bg-[#C82333] hover:bg-red-700 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 shadow-lg transition-all transform hover:scale-105">
                    <i class="fas fa-plus"></i> Create Task
                </button>
            </div>

            {{-- Stats Overview --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-6">
                    <div class="bg-blue-50 text-blue-500 p-4 rounded-2xl"><i class="fas fa-bullseye text-2xl"></i></div>
                    <div>
                        {{-- Changed font-black to font-bold --}}
                        <span class="text-3xl font-bold text-gray-900">{{ $missions->where('status', 'Available')->count() }}</span>
                        <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">Available</p>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-6">
                    <div class="bg-yellow-50 text-yellow-500 p-4 rounded-2xl"><i class="fas fa-clock text-2xl"></i></div>
                    <div>
                        <span class="text-3xl font-bold text-gray-900">{{ $missions->where('status', 'Ongoing')->count() }}</span>
                        <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">Ongoing</p>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-6">
                    <div class="bg-green-50 text-green-500 p-4 rounded-2xl"><i class="fas fa-check-circle text-2xl"></i></div>
                    <div>
                        <span class="text-3xl font-bold text-gray-900">{{ $missions->where('status', 'Completed')->count() }}</span>
                        <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">Completed</p>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-6">
                    <div class="bg-purple-50 text-purple-500 p-4 rounded-2xl"><i class="fas fa-hand-holding-usd text-2xl"></i></div>
                    <div>
                        <span class="text-3xl font-bold text-gray-900">RM {{ number_format($missions->where('status', 'Completed')->where('assigned_to', auth()->user()->staffID)->sum('commission'), 2) }}</span>
                        <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">My Earnings</p>
                    </div>
                </div>
            </div>

            {{-- Tabs Filter --}}
            <div class="flex gap-4 mb-10">
                @php $tabClasses = "px-8 py-3 rounded-2xl font-bold text-sm transition-all"; @endphp
                <a href="{{ route('staff.missions.index') }}" 
                   class="{{ $tabClasses }} {{ !request('status') ? 'bg-[#C82333] text-white shadow-xl shadow-red-100' : 'bg-white text-gray-500' }}">
                    All Tasks
                </a>
                <a href="{{ route('staff.missions.index', ['status' => 'available']) }}" 
                   class="{{ $tabClasses }} {{ request('status') === 'available' ? 'bg-[#C82333] text-white shadow-xl shadow-red-100' : 'bg-white text-gray-500' }}">
                    Available
                </a>
                <a href="{{ route('staff.missions.index', ['status' => 'ongoing']) }}" 
                   class="{{ $tabClasses }} {{ request('status') === 'ongoing' ? 'bg-[#C82333] text-white shadow-xl shadow-red-100' : 'bg-white text-gray-500' }}">
                    Ongoing
                </a>
                <a href="{{ route('staff.missions.index', ['status' => 'completed']) }}" 
                   class="{{ $tabClasses }} {{ request('status') === 'completed' ? 'bg-[#C82333] text-white shadow-xl shadow-red-100' : 'bg-white text-gray-500' }}">
                    Completed
                </a>
            </div>

            {{-- Mission Cards --}}
            <div class="space-y-6">
                @forelse($missions as $mission)
                <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-50 transition-all hover:shadow-md">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-4">
                                {{-- Changed font-black to font-bold --}}
                                <h3 class="text-2xl font-bold text-[#1A1C1E]">{{ $mission->title }}</h3>
                                <span class="bg-blue-50 text-blue-600 px-5 py-1.5 rounded-full text-[11px] font-bold uppercase">{{ $mission->status }}</span>
                                <span class="bg-green-50 text-green-600 px-5 py-1.5 rounded-full text-[11px] font-bold uppercase">RM {{ number_format($mission->commission, 0) }}</span>
                            </div>
                            <p class="text-gray-600"><span class="font-bold text-[#1A1C1E]">Requirements:</span> {{ $mission->requirements }}</p>
                            <p class="text-gray-400 text-sm mt-4 font-medium">Created: {{ $mission->created_at->format('Y-m-d') }}</p>
                        </div>
                        <div class="flex gap-3">
                            <button onclick='showDetails(@json($mission))' class="px-8 py-3 bg-gray-50 text-gray-600 rounded-xl font-bold text-sm border border-gray-100 transition-all hover:bg-gray-100">View Details</button>
                            
                            @if($mission->status == 'Available')
                            <form action="{{ route('staff.missions.accept', $mission->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-8 py-3 bg-[#0D6EFD] text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Accept Task</button>
                            </form>
                            @endif

                            @if($mission->status == 'Ongoing' && $mission->assigned_to == auth()->user()->staffID)
                            <form action="{{ route('staff.missions.complete', $mission->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-green-100 hover:bg-green-700 transition-all">Mark as Completed</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                    <div class="p-20 text-center bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100 font-bold text-gray-400">No missions found.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL: VIEW DETAILS --}}
    <div id="detailsModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] max-w-2xl w-full shadow-2xl overflow-hidden">
            <div class="px-8 py-8 flex justify-between items-start">
                <div class="flex items-center gap-5">
                    <div class="bg-blue-50 text-blue-500 p-4 rounded-2xl border border-blue-100">
                        <i class="fas fa-bullseye text-2xl"></i>
                    </div>
                    <div>
                        {{-- Changed font-black to font-bold --}}
                        <h2 id="m-title" class="text-2xl font-bold text-gray-900"></h2>
                        <p id="m-id" class="text-gray-400 font-bold text-sm"></p>
                    </div>
                </div>
                <button onclick="toggleModal('detailsModal')" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
            </div>

            <div class="px-8 flex gap-3 mb-8">
                <span id="m-status-badge" class="bg-blue-50 text-blue-600 px-6 py-2 rounded-full text-xs font-bold uppercase"></span>
                <span class="bg-green-50 text-green-600 px-6 py-2 rounded-full text-xs font-bold flex items-center gap-2">
                    <i class="fas fa-dollar-sign"></i> Bonus: <span id="m-commission"></span>
                </span>
            </div>

            <div class="px-8 space-y-8 mb-10">
                <div>
                    {{-- Changed subheadings to font-bold --}}
                    <h4 class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-2">Requirements</h4>
                    <p id="m-req" class="text-[#1A1C1E] font-medium leading-relaxed"></p>
                </div>
                <div>
                    <h4 class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-2">Description</h4>
                    <p id="m-desc" class="text-gray-500 text-sm leading-relaxed"></p>
                </div>
                <div>
                    <h4 class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-2">Created Date</h4>
                    <p id="m-date" class="text-[#1A1C1E] font-bold"></p>
                </div>
            </div>

            <div class="px-8 py-8 border-t bg-gray-50/50 flex justify-end gap-4">
                <button onclick="toggleModal('detailsModal')" class="px-10 py-3 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-50">Close</button>
                <div id="modal-action-container"></div>
            </div>
        </div>
    </div>

    {{-- MODAL: CREATE TASK --}}
    <div id="createTaskModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] max-w-2xl w-full shadow-2xl overflow-hidden">
            <div class="px-10 py-8 flex justify-between items-center border-b">
                <div class="flex items-center gap-3">
                    <div class="bg-red-50 text-[#C82333] p-2 rounded-lg"><i class="fas fa-plus"></i></div>
                    <h3 class="text-2xl font-bold">Create New Task</h3>
                </div>
                <button onclick="toggleModal('createTaskModal')" class="text-gray-300 text-3xl">&times;</button>
            </div>
            <form action="{{ route('staff.missions.store') }}" method="POST" class="p-10 space-y-6">
                @csrf
                <input type="text" name="title" required class="w-full px-6 py-4 border-gray-100 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-red-100 outline-none transition-all" placeholder="Task Title *">
                <textarea name="req" required class="w-full px-6 py-4 border-gray-100 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-red-100 outline-none transition-all" placeholder="Requirements *"></textarea>
                <textarea name="desc" required class="w-full px-6 py-4 border-gray-100 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-red-100 outline-none transition-all" placeholder="Description *"></textarea>
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="commission" required class="w-full px-6 py-4 border-gray-100 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-red-100 outline-none" placeholder="Bonus Commission (RM) *">
                    <input type="text" name="remarks" class="w-full px-6 py-4 border-gray-100 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-red-100 outline-none" placeholder="Notes/Remarks">
                </div>
                <div class="flex justify-end gap-4 mt-8">
                    <button type="button" onclick="toggleModal('createTaskModal')" class="font-bold text-gray-400">Cancel</button>
                    <button type="submit" class="px-10 py-4 bg-[#C82333] text-white rounded-2xl font-bold shadow-lg shadow-red-100 hover:bg-red-700 transition-all">Publish Task</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
            document.body.style.overflow = modal.classList.contains('hidden') ? 'auto' : 'hidden';
        }

        function showDetails(mission) {
            document.getElementById('m-title').innerText = mission.title;
            document.getElementById('m-id').innerText = 'Task ID: T' + String(mission.id).padStart(3, '0');
            document.getElementById('m-status-badge').innerText = mission.status;
            document.getElementById('m-commission').innerText = 'RM ' + mission.commission;
            document.getElementById('m-req').innerText = mission.requirements;
            document.getElementById('m-desc').innerText = mission.description;
            
            const date = new Date(mission.created_at);
            document.getElementById('m-date').innerText = date.getFullYear() + '-' + 
                String(date.getMonth() + 1).padStart(2, '0') + '-' + 
                String(date.getDate()).padStart(2, '0');

            const actionContainer = document.getElementById('modal-action-container');
            actionContainer.innerHTML = ''; 

            if (mission.status === 'Available') {
                const form = document.createElement('form');
                form.action = `/staff/mission/${mission.id}/accept`;
                form.method = 'POST';
                form.innerHTML = `@csrf <button type="submit" class="px-10 py-3 bg-[#0D6EFD] text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Accept Task</button>`;
                actionContainer.appendChild(form);
            }

            toggleModal('detailsModal');
        }
    </script>
</x-layouts.staff>