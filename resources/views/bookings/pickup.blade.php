<x-app-layout>
    <div class="min-h-screen bg-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            {{-- 1. ERROR BLOCK --}}
            @if ($errors->any())
                <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm leading-5 font-bold text-red-800">
                                Please fix the following errors:
                            </h3>
                            <ul class="list-disc list-inside text-sm text-red-700 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Header --}}
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-black text-hasta-red tracking-tight">Car Pickup Form</h1>
                <a href="{{ route('bookings.show', $booking->bookingID) }}" class="text-gray-400 hover:text-gray-900 transition p-2 hover:bg-gray-100 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            </div>

            {{-- 2. FORM ID ADDED --}}
            <form id="inspectionForm" action="{{ route('bookings.store-inspection', $booking->bookingID) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="pickup">

                {{-- Booking Details --}}
                <div class="bg-gray-50 rounded-2xl p-8 mb-8 border border-gray-100">
                    <h2 class="text-xl font-bold mb-6 text-gray-900">Booking Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <div>
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Booking ID</p>
                            <p class="font-bold text-lg text-gray-900">#{{ $booking->bookingID }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Plate Number</p>
                            <p class="font-bold text-lg text-gray-900">{{ $booking->plateNumber ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Vehicle</p>
                            <p class="font-bold text-lg text-gray-900">{{ $booking->fleet->modelName ?? 'Vehicle' }} {{ $booking->fleet->year ?? '' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Date & Time --}}
                <div class="flex flex-col md:flex-row gap-4 mb-10">
                    <div class="flex-1 bg-gray-100 rounded-xl px-6 py-4 flex items-center gap-4 border-l-4 border-hasta-red">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="font-bold text-gray-800 text-lg">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('D, d M Y') }}</span>
                    </div>
                    <div class="flex-1 bg-gray-100 rounded-xl px-6 py-4 flex items-center gap-4 border-l-4 border-gray-300">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-bold text-gray-800 text-lg">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('h : i A') }}</span>
                    </div>
                </div>

                {{-- Car Photos (With Pre-fill Logic) --}}
                <div class="mb-12">
                    <h3 class="text-lg font-bold mb-2 text-gray-900">Car Photos</h3>
                    <p class="text-gray-500 mb-6 text-sm">( Upload photos of the car from multiple angles )</p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        @php
                            $photoMap = ['Front' => 'frontViewImage', 'Back' => 'backViewImage', 'Left' => 'leftViewImage', 'Right' => 'rightViewImage'];
                        @endphp

                        @foreach(['Front', 'Back', 'Left', 'Right'] as $angle)
                            @php
                                $dbColumn = $photoMap[$angle];
                                $existingPhoto = $inspection->$dbColumn ?? null;
                            @endphp

                            <div class="relative group image-upload-container h-32 w-full">
                                {{-- Placeholder: HIDE if photo exists --}}
                                <div class="border-2 border-dashed border-gray-200 rounded-2xl h-full w-full flex flex-col items-center justify-center text-gray-400 group-hover:border-hasta-red group-hover:text-hasta-red transition bg-gray-50 cursor-pointer overflow-hidden placeholder-content {{ $existingPhoto ? 'hidden' : '' }}">
                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <span class="text-xs font-bold uppercase tracking-wider">{{ $angle }} Image</span>
                                </div>
                                <input type="file" name="photo_{{ strtolower($angle) }}" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10 file-input">
                                
                                {{-- Preview: SHOW if photo exists --}}
                                <div class="absolute inset-0 z-0 image-preview {{ $existingPhoto ? '' : 'hidden' }} h-full w-full rounded-2xl overflow-hidden bg-white border border-gray-200">
                                    @if($existingPhoto)
                                        <img src="{{ asset('storage/' . $existingPhoto) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Fuel & Mileage (With Pre-fill Logic) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-gray-900">Fuel Image</h3>
                        <div class="relative group image-upload-container h-40">
                            @php $fuelPhoto = $inspection->fuelImage ?? null; @endphp
                            
                            <div class="border-2 border-dashed border-gray-200 rounded-2xl h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50 transition group-hover:border-hasta-red placeholder-content {{ $fuelPhoto ? 'hidden' : '' }}">
                                <svg class="w-10 h-10 mb-2 group-hover:text-hasta-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span class="text-sm font-medium">Click to upload fuel image</span>
                            </div>
                            <input type="file" name="fuel_image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10 file-input" {{ $fuelPhoto ? '' : 'required' }}>
                            <div class="absolute inset-0 z-0 image-preview {{ $fuelPhoto ? '' : 'hidden' }} h-full w-full rounded-2xl overflow-hidden bg-white border border-gray-200">
                                @if($fuelPhoto)
                                    <img src="{{ asset('storage/' . $fuelPhoto) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-3">
                            <span class="text-gray-600 font-medium">Level:</span>
                            <select name="fuel_level" class="border-gray-200 bg-gray-50 rounded-lg text-sm focus:ring-hasta-red focus:border-hasta-red">
                                @foreach(['1', '2', '3', '5' => 'Half', '10' => 'Full'] as $val => $label)
                                    @php 
                                        $value = is_string($val) ? $val : $label;
                                        $displayText = is_string($val) ? $label : $label . ' Bar' . ($label > 1 ? 's' : '');
                                        $selected = (isset($inspection->fuelBar) && $inspection->fuelBar == $value) ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $value }}" {{ $selected }}>{{ is_string($val) ? $label . ' Tank' : $displayText }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-gray-900">Mileage</h3>
                        <div class="flex items-center gap-3">
                            <input type="number" name="mileage" step="0.1" 
                                   value="{{ $inspection->mileage ?? '' }}" 
                                   class="border-gray-200 bg-gray-50 rounded-xl px-4 py-3 w-full text-lg font-bold focus:ring-hasta-red focus:border-hasta-red" placeholder="e.g. 54000">
                            <span class="text-lg text-gray-500 font-bold">km</span>
                        </div>
                    </div>
                </div>

                {{-- Text Inputs --}}
                <div class="space-y-6 mb-12">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Notes (optional)</label>
                        <textarea name="notes" class="w-full border-gray-200 bg-gray-50 rounded-xl p-3 h-24 focus:ring-hasta-red focus:border-hasta-red resize-none" placeholder="Remarks...">{{ $inspection->remark ?? '' }}</textarea>
                    </div>
                </div>

                {{-- Signature Section (Read-Only if signed) --}}
                <div class="mb-10">
                    <h3 class="text-lg font-bold mb-2 text-gray-900">Signature</h3>
                    <div class="relative w-full">
                        @if(isset($inspection->signature))
                            {{-- VIEW MODE --}}
                            <div class="border border-gray-200 rounded-xl w-full h-48 bg-white shadow-inner flex items-center justify-center relative">
                                <img src="{{ asset('storage/' . $inspection->signature) }}" class="h-full object-contain">
                                <div class="absolute top-4 right-4 bg-green-100 text-green-800 text-xs font-bold px-3 py-1.5 rounded-lg">Signed</div>
                            </div>
                        @else
                            {{-- EDIT MODE --}}
                            <canvas id="signature-pad" class="border border-gray-200 rounded-xl w-full h-48 bg-white shadow-inner touch-none cursor-crosshair"></canvas>
                            <button type="button" id="clear-signature" class="absolute top-4 right-4 bg-white/90 border border-gray-200 shadow-sm px-3 py-1.5 rounded-lg text-xs font-bold text-gray-600 hover:text-red-600 hover:border-red-200 transition z-10">Clear</button>
                            <input type="hidden" name="signature" id="signature">
                        @endif
                    </div>

                    <div class="mt-6 flex items-start gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <input type="checkbox" name="confirm" required 
                               {{ isset($inspection) ? 'checked' : '' }} 
                               class="mt-1 w-5 h-5 rounded border-gray-300 text-hasta-red focus:ring-hasta-red cursor-pointer">
                        <label class="text-gray-600 text-sm leading-relaxed cursor-pointer font-medium">
                            I acknowledge that I have inspected the vehicle and confirm its condition as documented above.
                        </label>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('bookings.show', $booking->bookingID) }}" class="flex-1 border border-gray-300 bg-white text-gray-700 font-bold py-4 rounded-xl text-center hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    @if(!isset($inspection))
                        <button type="button" onclick="submitForm()" class="flex-1 bg-hasta-red text-white font-bold py-4 rounded-xl hover:bg-red-700 transition flex items-center justify-center gap-2 shadow-lg shadow-red-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Submit Pickup Form
                        </button>
                    @else
                        <button type="button" disabled class="flex-1 bg-gray-300 text-gray-500 font-bold py-4 rounded-xl cursor-not-allowed">
                            Already Submitted
                        </button>
                    @endif
                </div>

            </form>
        </div>
    </div>

    {{-- Javascript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image Preview
            const fileInputs = document.querySelectorAll('.file-input');
            fileInputs.forEach(input => {
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const container = e.target.closest('.image-upload-container');
                    const placeholder = container.querySelector('.placeholder-content');
                    const preview = container.querySelector('.image-preview');

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            placeholder.classList.add('hidden');
                            preview.classList.remove('hidden');
                            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        }
                        reader.readAsDataURL(file);
                    } else {
                        placeholder.classList.remove('hidden');
                        preview.classList.add('hidden');
                        preview.innerHTML = '';
                    }
                });
            });

            // Signature Logic
            const canvas = document.getElementById('signature-pad');
            if (canvas) { // Only run if canvas exists (Edit mode)
                const signatureInput = document.getElementById('signature');
                const clearButton = document.getElementById('clear-signature');
                const ctx = canvas.getContext('2d');
                let isDrawing = false;

                function resizeCanvas() {
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    ctx.scale(ratio, ratio);
                }
                window.addEventListener('resize', resizeCanvas);
                resizeCanvas();

                function startDrawing(e) { isDrawing = true; ctx.beginPath(); const { x, y } = getPointerPos(e); ctx.moveTo(x, y); }
                function draw(e) { if (!isDrawing) return; const { x, y } = getPointerPos(e); ctx.lineTo(x, y); ctx.strokeStyle = '#000'; ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.stroke(); }
                function stopDrawing() { isDrawing = false; }
                function getPointerPos(event) {
                    const rect = canvas.getBoundingClientRect();
                    const clientX = event.touches ? event.touches[0].clientX : event.clientX;
                    const clientY = event.touches ? event.touches[0].clientY : event.clientY;
                    return { x: clientX - rect.left, y: clientY - rect.top };
                }

                canvas.addEventListener('mousedown', startDrawing); canvas.addEventListener('mousemove', draw); canvas.addEventListener('mouseup', stopDrawing); canvas.addEventListener('mouseout', stopDrawing);
                canvas.addEventListener('touchstart', (e) => { e.preventDefault(); startDrawing(e); }); canvas.addEventListener('touchmove', (e) => { e.preventDefault(); draw(e); }); canvas.addEventListener('touchend', stopDrawing);

                clearButton.addEventListener('click', () => { ctx.clearRect(0, 0, canvas.width, canvas.height); signatureInput.value = ''; });
            }
        });

        // Submit Logic
        function submitForm() {
            const canvas = document.getElementById('signature-pad');
            const signatureInput = document.getElementById('signature');
            const form = document.getElementById('inspectionForm');
            if (canvas) {
                signatureInput.value = canvas.toDataURL('image/png');
            }
            form.submit();
        }
    </script>
</x-app-layout>