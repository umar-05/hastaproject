@extends('layouts.staff')

@section('content')
<div class="p-6">
    <h3 class="text-lg font-semibold mb-4">Add Vehicle</h3>

    @if ($errors->any())
        <div class="mb-4 text-red-600 bg-red-100 p-3 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('staff.fleet.store') }}" method="POST" class="space-y-4 max-w-lg">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Plate Number</label>
            <input type="text" name="plateNumber" value="{{ old('plateNumber') }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Model Name</label>
            <input type="text" name="modelName" value="{{ old('modelName') }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Year</label>
            <input type="number" name="year" value="{{ old('year') }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="available">Available</option>
                <option value="booked">Booked</option>
                <option value="rented">Rented</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>

        <div class="flex space-x-3 pt-4">
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Save</button>
            <a href="{{ route('staff.fleet.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</a>
        </div>
    </form>
</div>
@endsection