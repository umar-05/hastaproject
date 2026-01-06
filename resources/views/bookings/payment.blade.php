@extends('layouts.app')

@section('content')
<script src="//unpkg.com/alpinejs" defer></script>

<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up {
        animation: fadeUp 0.6s ease-out forwards;
        opacity: 0;
    }
    
    /* File Input Styling */
    input[type="file"]::file-selector-button {
        padding: 0.5rem 1rem;
        margin-right: 1rem;
        border-radius: 0.5rem;
        border-width: 0;
        background-color: #f3f4f6; 
        color: #1f2937;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.2s;
    }
    input[type="file"]::file-selector-button:hover {
        background-color: #e5e7eb;
    }
</style>

<div class="min-h-screen bg-gray-50 py-10" 
     x-data="paymentLogic({{ $full_amount }}, {{ $deposit_amount }})">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 animate-fade-up">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Checkout</h1>
            <p class="text-lg text-gray-500 mt-1">Complete your booking for <span class="font-bold text-gray-800">{{ $car->modelName }}</span>.</p>
        </div>

        <form action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @foreach($booking_data as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <input type="hidden" name="voucher_code" x-model="appliedVoucherCode">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- LEFT COLUMN: DETAILS (Span 7) --}}
                <div class="lg:col-span-7 space-y-8 animate-fade-up">
                    
                    {{-- 1. VEHICLE SUMMARY --}}
                    @php
                        $vehicleImage = 'default-car.png';
                        if (isset($car)) {
                            $model = strtolower($car->modelName);
                            $year = $car->year;
                            if (str_contains($model, 'axia')) $vehicleImage = ($year >= 2023) ? 'axia-2024.png' : 'axia-2018.png';
                            elseif (str_contains($model, 'bezza')) $vehicleImage = 'bezza-2018.png';
                            elseif (str_contains($model, 'myvi')) $vehicleImage = ($year >= 2020) ? 'myvi-2020.png' : 'myvi-2015.png';
                            elseif (str_contains($model, 'alza')) $vehicleImage = 'alza-2019.png';
                            elseif (str_contains($model, 'vellfire')) $vehicleImage = 'vellfire-2020.png';
                            elseif (str_contains($model, 'aruz')) $vehicleImage = 'aruz-2020.png';
                            elseif (str_contains($model, 'saga')) $vehicleImage = 'saga-2017.png';
                            elseif (str_contains($model, 'x50')) $vehicleImage = 'x50-2024.png';
                            elseif (str_contains($model, 'y15')) $vehicleImage = 'y15zr-2023.png';
                        }
                    @endphp
                    <div class="bg-white rounded-3xl p-6 shadow-md border border-gray-100 flex flex-col sm:flex-row items-center gap-8">
                        <div class="w-full sm:w-40 h-32 bg-gray-50 rounded-2xl flex items-center justify-center flex-shrink-0 p-2">
                            <img src="{{ asset('images/' . $vehicleImage) }}" class="w-full h-full object-contain" 
                                 onerror="this.src='https://cdn-icons-png.flaticon.com/512/3202/3202926.png';">
                        </div>
                        <div class="flex-1 w-full">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $car->modelName }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ $car->plateNumber }}
                                    </span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                    <p class="text-xs font-bold text-gray-400 uppercase">Pick Up</p>
                                    <p class="font-bold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($booking_data['start_date'])->format('d M, Y') }}</p>
                                    <p class="text-xs text-gray-600 truncate">{{ $booking_data['pickup_location'] }}</p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                    <p class="text-xs font-bold text-gray-400 uppercase">Return</p>
                                    <p class="font-bold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($booking_data['end_date'])->format('d M, Y') }}</p>
                                    <p class="text-xs text-gray-600 truncate">{{ $booking_data['return_location'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. VERIFY IDENTITY --}}
                    <div class="bg-white rounded-3xl p-8 shadow-md border border-gray-100"
                         x-data="{ 
                            idType: '{{ $customer->doc_ic_passport ? 'ic' : ($customer->doc_matric ? 'matric' : 'ic') }}', 
                            hasIdentity: {{ ($customer->doc_ic_passport || $customer->doc_matric) ? 'true' : 'false' }},
                            changeIdentity: false,
                            hasLicense: {{ $customer->doc_license ? 'true' : 'false' }},
                            changeLicense: false
                         }">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Identity Verification</h3>
                        </div>
                        
                        <div class="space-y-6">
                            {{-- ID Card --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Identity Document (IC / Matric)</label>
                                
                                {{-- View Mode --}}
                                <template x-if="hasIdentity && !changeIdentity">
                                    <div class="flex justify-between items-center bg-green-50 p-4 rounded-xl border border-green-200">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-green-200 p-1.5 rounded-full text-green-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                                            <span class="text-sm font-bold text-green-800">Document Uploaded</span>
                                        </div>
                                        <button type="button" @click="changeIdentity = true" class="text-sm font-semibold text-green-700 hover:text-green-900 hover:underline">Change</button>
                                    </div>
                                </template>

                                {{-- Edit/Upload Mode --}}
                                <template x-if="!hasIdentity || changeIdentity">
                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 transition-all duration-300">
                                        <div class="flex gap-4 mb-4">
                                            <label class="cursor-pointer flex-1 text-center py-2 px-4 border rounded-lg text-sm font-bold transition-all peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 bg-white text-gray-600 hover:bg-gray-100">
                                                <input type="radio" name="id_type" value="ic" class="hidden peer" x-model="idType"> IC / Passport
                                            </label>
                                            <label class="cursor-pointer flex-1 text-center py-2 px-4 border rounded-lg text-sm font-bold transition-all peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 bg-white text-gray-600 hover:bg-gray-100">
                                                <input type="radio" name="id_type" value="matric" class="hidden peer" x-model="idType"> Matric Card
                                            </label>
                                        </div>
                                        
                                        <input type="file" name="identity_document" accept="image/*,application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-gray-800 file:text-white hover:file:bg-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-white" :required="!hasIdentity">
                                        
                                        {{-- CANCEL BUTTON FOR ID --}}
                                        <template x-if="changeIdentity">
                                            <div class="mt-3 text-right">
                                                <button type="button" @click="changeIdentity = false" class="text-xs font-bold text-gray-500 hover:text-gray-800 underline">Cancel Update</button>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            {{-- License --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Driver's License</label>
                                
                                {{-- View Mode --}}
                                <template x-if="hasLicense && !changeLicense">
                                    <div class="flex justify-between items-center bg-green-50 p-4 rounded-xl border border-green-200">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-green-200 p-1.5 rounded-full text-green-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                                            <span class="text-sm font-bold text-green-800">License Uploaded</span>
                                        </div>
                                        <button type="button" @click="changeLicense = true" class="text-sm font-semibold text-green-700 hover:text-green-900 hover:underline">Change</button>
                                    </div>
                                </template>

                                {{-- Edit/Upload Mode --}}
                                <template x-if="!hasLicense || changeLicense">
                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 transition-all duration-300">
                                        <input type="file" name="driving_license" accept="image/*,application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-gray-800 file:text-white hover:file:bg-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-white" :required="!hasLicense">
                                        
                                        {{-- CANCEL BUTTON FOR LICENSE --}}
                                        <template x-if="changeLicense">
                                            <div class="mt-3 text-right">
                                                <button type="button" @click="changeLicense = false" class="text-xs font-bold text-gray-500 hover:text-gray-800 underline">Cancel Update</button>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- 3. REFUND BANK DETAILS --}}
                    <div class="bg-white rounded-3xl p-8 shadow-md border border-gray-100"
                         x-data="{ hasBank: {{ ($customer->bankName && $customer->accountNum) ? 'true' : 'false' }}, changeBank: false }">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Refund Details</h3>
                        </div>

                        <template x-if="hasBank && !changeBank">
                            <div class="flex justify-between items-center bg-blue-50 p-5 rounded-xl border border-blue-100">
                                <div>
                                    <p class="text-xs text-blue-600 font-bold uppercase tracking-wide">Refund To:</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $customer->bankName }}</p>
                                    <p class="text-sm font-mono text-gray-600">{{ $customer->accountNum }}</p>
                                </div>
                                <button type="button" @click="changeBank = true" class="text-sm font-bold text-blue-600 bg-white border border-blue-200 px-4 py-2 rounded-lg hover:bg-blue-50 shadow-sm">Edit</button>
                            </div>
                        </template>

                        <template x-if="!hasBank || changeBank">
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Bank Name</label>
                                        <input type="text" name="payer_bank" placeholder="e.g. Maybank" class="w-full text-base border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 py-3" :required="!hasBank">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Account Number</label>
                                        <input type="text" name="payer_account" placeholder="e.g. 1234567890" class="w-full text-base border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 py-3" :required="!hasBank">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Full Name</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="payer_name" value="{{ auth()->user()->name }}" class="w-full text-base border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 py-3" placeholder="Account Holder Name" required>
                                        <template x-if="changeBank">
                                            <button type="button" @click="changeBank = false" class="px-4 py-2 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200">Cancel</button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- RIGHT COLUMN: SUMMARY & PAYMENT (Span 5) --}}
                <div class="lg:col-span-5 space-y-8 animate-fade-up delay-100">
                    
                    {{-- 4. ORDER SUMMARY --}}
                    <div class="bg-gradient-to-br from-red-700 to-red-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden transform transition hover:scale-[1.01] duration-300">
                        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-48 h-48 bg-white opacity-10 rounded-full blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-black opacity-10 rounded-full blur-2xl"></div>
                        
                        <div class="relative z-10">
                            <h2 class="text-xl font-bold mb-6 border-b border-red-500/50 pb-4">Order Summary</h2>
                            
                            <div class="space-y-3 text-base text-red-50 mb-8">
                                <div class="flex justify-between">
                                    <span>Rental Charges</span> 
                                    <span class="font-mono text-white tracking-wide">RM <span x-text="formatMoney(fullAmount)"></span></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Security Deposit</span> 
                                    <span class="font-mono text-white tracking-wide">RM <span x-text="formatMoney(depositAmount)"></span></span>
                                </div>
                                
                                <div x-show="discountAmount > 0" 
                                     x-transition 
                                     class="flex justify-between items-center text-yellow-300 font-bold bg-black/20 p-2 rounded-lg mt-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                        <span>Discount (<span x-text="appliedVoucherCode"></span>)</span>
                                    </div>
                                    <span>- RM <span x-text="formatMoney(discountAmount)"></span></span>
                                </div>
                            </div>

                            {{-- VOUCHER --}}
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/20 mb-8">
                                <label class="block text-xs font-bold text-red-100 uppercase tracking-widest mb-2">Have a Promo Code?</label>
                                <div class="flex gap-2">
                                    <input type="text" x-model="voucherInput" @keydown.enter.prevent="applyVoucher" 
                                           class="flex-1 bg-white border-none rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-yellow-400 font-bold uppercase text-sm py-3 px-4 shadow-sm" 
                                           placeholder="CODE">
                                    <button type="button" @click="applyVoucher" :disabled="loading || !voucherInput" 
                                            class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-black transition shadow-lg disabled:opacity-50">
                                        <span x-show="!loading">APPLY</span>
                                        <span x-show="loading" class="animate-spin">↻</span>
                                    </button>
                                </div>
                                <p x-text="voucherMessage" 
                                   :class="voucherSuccess ? 'text-green-300' : 'text-yellow-300'" 
                                   class="text-xs font-bold mt-2 h-4"></p>
                            </div>

                            <div class="flex justify-between items-end pt-4 border-t border-red-500/50">
                                <span class="text-sm font-medium text-red-200 uppercase tracking-wide">Total Payable</span>
                                <span class="text-4xl font-extrabold tracking-tight text-white drop-shadow-md">
                                    RM <span x-text="formatMoney(totalPayable)"></span>
                                </span>
                            </div>
                            
                            <input type="hidden" name="total_amount" :value="totalPayable">
                            <input type="hidden" name="deposit_amount" value="{{ $deposit_amount }}">
                            <input type="hidden" name="price_per_day" value="{{ $full_amount / max(1, ( (isset($booking_data['end_date']) && isset($booking_data['start_date'])) ? ( (new \DateTime($booking_data['end_date']))->diff(new \DateTime($booking_data['start_date']))->days ?: 1 ) : 1 )) }}">
                        </div>
                    </div>

                    {{-- 5. PAYMENT METHOD --}}
                    <div class="bg-white rounded-3xl p-8 shadow-md border border-gray-100" x-data="{ method: 'bank_transfer' }">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Payment Method</h3>
                            
                            <div class="flex bg-gray-100 p-1 rounded-lg">
                                <button type="button" @click="method = 'bank_transfer'" :class="{'bg-white shadow-sm text-gray-900': method === 'bank_transfer', 'text-gray-500': method !== 'bank_transfer'}" class="px-4 py-2 text-xs font-bold rounded-md transition-all">Manual</button>
                                <button type="button" @click="method = 'duitnow'" :class="{'bg-white shadow-sm text-gray-900': method === 'duitnow', 'text-gray-500': method !== 'duitnow'}" class="px-4 py-2 text-xs font-bold rounded-md transition-all">QR Scan</button>
                            </div>
                        </div>
                        <input type="hidden" name="payment_method" :value="method">

                        <div x-show="method === 'bank_transfer'" class="mb-6 p-5 bg-gray-50 rounded-2xl border border-gray-200">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase">Bank To</p>
                                    <p class="text-lg font-bold text-gray-900">Maybank</p>
                                    <p class="text-sm text-gray-600">Hasta Travel & Tour</p>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-gray-200 shadow-sm">
                                    <img src="{{ asset('images/maybank.png') }}" 
                                    alt="Maybank" 
                                    class="h-8 w-8 object-contain"
                                    onerror="this.style.display='none'">
                                </div>
                            </div>
                            <div class="flex items-center gap-3 mt-2 bg-white px-4 py-3 rounded-xl border border-gray-300 shadow-inner">
                                <span class="font-mono text-xl font-bold text-gray-800 tracking-widest flex-1" id="bankAccNo">139748362455166</span>
                                <button type="button" onclick="copyToClipboard()" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-3 py-1.5 rounded-lg transition uppercase tracking-wide">Copy</button>
                            </div>
                            <div id="copyFeedback" class="text-green-600 text-xs font-bold text-right mt-1 opacity-0 transition-opacity">Copied!</div>
                        </div>

                        <div x-show="method === 'duitnow'" class="mb-6 text-center p-6 bg-pink-50 rounded-2xl border border-pink-100" style="display: none;">
                            <div class="bg-white p-2 inline-block rounded-xl shadow-sm border border-pink-100">
                                <img src="{{ asset('images/qrcode.png') }}" class="w-48 h-48 object-contain mx-auto mix-blend-multiply">
                            </div>
                            <p class="text-sm text-pink-800 font-bold mt-3">Scan with any banking app</p>
                        </div>

                        {{-- RECEIPT UPLOAD --}}
                        <div x-data="{ preview: null, fileName: null, isPdf: false }" class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:bg-gray-50 hover:border-red-400 transition relative group cursor-pointer">
                            <input type="file" name="receipt" accept=".pdf,image/png,image/jpeg,image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required 
                                   @change="
                                        const file = $event.target.files[0];
                                        if(file) {
                                            preview = URL.createObjectURL(file);
                                            fileName = file.name;
                                            isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
                                        }
                                   ">
                            
                            <div x-show="!preview" class="space-y-2 py-2">
                                <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto group-hover:bg-blue-100 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Upload Payment Receipt</p>
                                    <p class="text-xs text-gray-500 mt-1">Supports JPG, PNG, PDF</p>
                                </div>
                            </div>

                            <div x-show="preview" style="display: none;" class="relative z-0">
                                <template x-if="!isPdf">
                                    <img :src="preview" class="h-32 mx-auto object-contain rounded-lg shadow-md border border-gray-200">
                                </template>
                                <template x-if="isPdf">
                                    <div class="flex flex-col items-center justify-center gap-2 text-red-600 bg-red-50 p-4 rounded-xl border border-red-100">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                        <span class="text-sm font-bold truncate max-w-[200px]" x-text="fileName"></span>
                                    </div>
                                </template>
                                <p class="text-xs text-blue-600 font-bold mt-2 hover:underline">Click to change file</p>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-4 pt-2">
                        <button type="button" onclick="window.history.back()" class="w-1/3 py-4 rounded-2xl font-bold text-gray-700 bg-white border-2 border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition text-base">Back</button>
                        <button type="submit" class="w-2/3 py-4 rounded-2xl font-bold text-white bg-gray-900 hover:bg-black shadow-xl shadow-gray-400/20 transform hover:-translate-y-0.5 transition text-base flex items-center justify-center gap-2">
                            <span>Confirm Payment</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function copyToClipboard() {
        const accNo = document.getElementById('bankAccNo').innerText;
        navigator.clipboard.writeText(accNo).then(() => {
            const feedback = document.getElementById('copyFeedback');
            feedback.style.opacity = '1';
            setTimeout(() => { feedback.style.opacity = '0'; }, 2000);
        });
    }

    function paymentLogic(fullAmount, depositAmount) {
        return {
            fullAmount: fullAmount,
            depositAmount: depositAmount,
            discountPercent: 0,
            discountType: 'percentage', // percentage or fixed
            voucherInput: '',
            appliedVoucherCode: '',
            voucherMessage: '',
            voucherSuccess: false,
            loading: false,

            get discountAmount() {
                if (this.discountType === 'fixed') {
                    return this.discountPercent; // In this case, discountPercent stores the fixed amount
                }
                return (this.fullAmount * this.discountPercent) / 100;
            },

            get totalPayable() {
                return Math.max(0, this.fullAmount + this.depositAmount - this.discountAmount);
            },

            formatMoney(value) {
                return parseFloat(value).toFixed(2);
            },

            async applyVoucher() {
                if (!this.voucherInput) return;
                this.loading = true;
                this.voucherMessage = '';

                try {
                    const response = await fetch("{{ route('bookings.validateVoucher') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ voucher_code: this.voucherInput })
                    });

                    const data = await response.json();

                    if (data.valid) {
                        this.discountPercent = parseFloat(data.discount);
                        this.discountType = data.type || 'percentage'; // Support fixed/percent
                        this.appliedVoucherCode = this.voucherInput;
                        this.voucherSuccess = true;
                        this.voucherMessage = `Success! ${this.discountType === 'fixed' ? 'RM' + this.discountPercent : this.discountPercent + '%'} discount applied.`;
                    } else {
                        this.discountPercent = 0;
                        this.appliedVoucherCode = '';
                        this.voucherSuccess = false;
                        this.voucherMessage = 'Invalid code.';
                    }
                } catch (error) {
                    this.voucherSuccess = false;
                    this.voucherMessage = 'Error validating.';
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endsection