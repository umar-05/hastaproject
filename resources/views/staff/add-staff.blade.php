<x-staff-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Staff Record</h2>
            <p class="text-gray-500 text-sm mt-1">Manage staff information and performance</p>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-5 flex items-center gap-4">
                <div class="p-3 rounded-lg bg-blue-50 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 3v4h14V3"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ isset($staffs) ? $staffs->count() : 0 }}</div>
                    <div class="text-sm text-gray-500">Total Staff</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-5 flex items-center gap-4">
                <div class="p-3 rounded-lg bg-green-50 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ isset($staffs) ? $staffs->where('position','Driver')->count() : 0 }}</div>
                    <div class="text-sm text-gray-500">Drivers</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-5 flex items-center gap-4">
                <div class="p-3 rounded-lg bg-purple-50 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ isset($staffs) ? $staffs->where('position','Admin Staff')->count() : 0 }}</div>
                    <div class="text-sm text-gray-500">Admin Staff</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-5 flex items-center gap-4">
                <div class="p-3 rounded-lg bg-yellow-50 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ isset($staffs) ? $staffs->where('position','Manager')->count() : 0 }}</div>
                    <div class="text-sm text-gray-500">Managers</div>
                </div>
            </div>
        </div>

        {{-- Controls: Search, Filter, Export, Add Staff --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3 w-full md:w-2/3">
                    <div class="flex-1 relative">
                        <input id="search" type="text" placeholder="Search staff..." class="w-full border border-gray-200 rounded-full px-4 py-3" />
                    </div>

                    <div class="w-48">
                        <select id="filterPosition" class="w-full border border-gray-200 rounded-full px-4 py-3">
                            <option value="">All Positions</option>
                            <option value="Driver">Driver</option>
                            <option value="Admin Staff">Admin Staff</option>
                            <option value="Manager">Manager</option>
                            <option value="Technician">Technician</option>
                        </select>
                    </div>

                    <button id="filterBtn" class="px-4 py-2 bg-white border rounded-full">Filter</button>
                    <a href="{{ route('staff.export') ?? '#' }}" class="px-4 py-2 bg-white border rounded-full">Export</a>
                </div>

                <div class="flex items-center gap-3 justify-end">
                    <a href="{{ route('staff.create') ?? route('staff.add') }}" class="bg-[#bb1419] text-white px-5 py-3 rounded-full font-semibold">Add Staff</a>
                </div>
            </div>
        </div>

        {{-- Staff Table --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-sm font-medium text-gray-600">STAFF ID</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-600">NAME</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-600">CONTACT</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-600">MISSIONS</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-600">STATUS</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-600">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @if(isset($staffs) && $staffs->count())
                            @foreach($staffs as $s)
                                <tr>
                                    <td class="px-6 py-4 align-top font-medium">{{ $s->staff_id ?? $s->id }}</td>
                                    <td class="px-6 py-4 align-top">
                                        <div class="font-semibold">{{ $s->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $s->ic_number ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <div>{{ $s->phone ?? '' }}</div>
                                        <div class="text-xs text-gray-400">{{ $s->email ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 align-top">{{ $s->missions_count ?? '-' }}</td>
                                    <td class="px-6 py-4 align-top">
                                        <span class="inline-block px-3 py-1 rounded-full text-sm bg-green-50 text-green-600">{{ $s->status ?? 'Active' }}</span>
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <a href="{{ route('staff.show', $s->id) }}" class="text-gray-500 hover:text-gray-800 mr-3">View</a>
                                        <a href="{{ route('staff.edit', $s->id) }}" class="text-gray-500 hover:text-gray-800">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            {{-- Placeholder rows if no data provided --}}
                            <tr>
                                <td class="px-6 py-6" colspan="6">
                                    <div class="text-center text-gray-500">No staff records found. Click "Add Staff" to create one.</div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Basic client-side filtering (no server round-trip) to improve UX
        document.addEventListener('DOMContentLoaded', function() {
            const search = document.getElementById('search');
            const filterPosition = document.getElementById('filterPosition');
            const filterBtn = document.getElementById('filterBtn');

            function applyFilter() {
                const q = search.value.toLowerCase();
                const pos = filterPosition.value;
                document.querySelectorAll('table tbody tr').forEach(row => {
                    // skip placeholder row
                    if (!row.querySelector('td')) return;
                    const name = (row.querySelector('td:nth-child(2)')||{innerText:''}).innerText.toLowerCase();
                    const position = (row.dataset.position||'').toLowerCase();
                    let visible = true;
                    if (q && name.indexOf(q) === -1) visible = false;
                    if (pos && position !== pos.toLowerCase()) visible = false;
                    row.style.display = visible ? '' : 'none';
                });
            }

            filterBtn.addEventListener('click', applyFilter);
            search.addEventListener('input', applyFilter);
        });
    </script>
</x-staff-layout>