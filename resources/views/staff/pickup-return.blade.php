@extends('layouts.app')

@section('content')
{{-- AlpineJS for interactivity --}}
<script src="//unpkg.com/alpinejs" defer></script>

<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up {
        animation: fadeUp 0.6s ease-out forwards;
        opacity: 0;
    }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    
    /* Custom Radio styling */
    .radio-card:checked + div {
        border-color: #ef4444;
        background-color: #fef2f2;
        box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.1);
    }
    .radio-card:checked + div .radio-indicator {
        border-color: #ef4444;
        background-color: #ef4444;
    }
    .slider-btn.active {
        background-color: #ffffff;
        color: #111827;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
</style>

<div class="min-h-screen bg-gray-50 py-12" 
     x-data="paymentLogic({{ $full_amount }}, {{ $deposit_amount }})">
    
    <div class="max-w-3xl mx-auto px-6">
        
        <div class="text-center mb-10 animate-fade-up">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Complete Payment</h1>
            <p class="text-gray-500 mt-2">Secure your booking by providing your details below.</p>
        </div>

        <form action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @foreach($booking_data as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            {{-- Hidden Voucher Input for Submission --}}
            <input type="hidden" name="voucher_code" x-model="appliedVoucherCode">

            {{-- 1. PAYMENT SUMMARY (Breakdown Only) --}}
            <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-3xl p-8 text-white shadow-xl transform transition hover:scale-[1.01] duration-300 animate-fade-up relative overflow-hidden">
                {{-- Background Pattern --}}
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <h2 class="text-xl font-bold opacity-90 mb-6 border-b border-red-500 pb-2">Payment Summary</h2>
                    
                    {{-- Loop Pickups --}}
                    @foreach($todayPickups as $booking)
                    <tr class="hover:bg-gray-50 transition group">
                        <td class="px-6 py-4">
                            <span class="font-mono text-gray-700 font-medium">INS-{{ date('Y') }}-{{ $booking->bookingID }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase border border-green-200">
                                Pickup
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800">{{ $booking->fleet->modelName }}</span>
                                <span class="text-xs text-gray-500">{{ $booking->fleet->plateNumber }}</span>
                            </div>
                            <span class="font-mono">- RM <span x-text="formatMoney(discountAmount)"></span></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end border-t border-red-500 pt-4">
                        <p class="text-xs text-red-200 uppercase mb-1">Total Payable</p>
                        <span class="text-4xl font-extrabold tracking-tight">
                            RM <span x-text="formatMoney(totalPayable)"></span>
                        </span>
                    </div>
                    {{-- Hidden Calculation Fields for Form --}}
                    <input type="hidden" name="total_amount" :value="totalPayable">
                    <input type="hidden" name="deposit_amount" value="{{ $deposit_amount }}">
                    <input type="hidden" name="price_per_day" value="{{ $full_amount / max(1, ( (isset($booking_data['end_date']) && isset($booking_data['start_date'])) ? ( (new \DateTime($booking_data['end_date']))->diff(new \DateTime($booking_data['start_date']))->days ?: 1 ) : 1 )) }}">
                </div>
            </div>

            {{-- 2. PROMO CODE & REWARDS (Standalone Card) --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 animate-fade-up delay-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Promo Code & Rewards</h3>
                        <p class="text-xs text-gray-500">Enter a voucher code to redeem discounts.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" x-model="voucherInput" @keydown.enter.prevent="applyVoucher" 
                            placeholder="e.g. HASTA2024" 
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition uppercase placeholder-gray-400 font-medium">
                        
                        {{-- Status Icon --}}
                        <div class="absolute right-3 top-3.5" x-show="appliedVoucherCode && voucherSuccess">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <button type="button" @click="applyVoucher" :disabled="loading || !voucherInput"
                        class="bg-gray-900 text-white font-bold px-6 py-3 rounded-xl hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-gray-200">
                        <span x-show="!loading">Apply Code</span>
                        <span x-show="loading" class="animate-spin">↻</span>
                    </button>
                </div>

                {{-- Feedback Messages --}}
                <div class="mt-3 h-5">
                    <p x-text="voucherMessage" 
                       :class="voucherSuccess ? 'text-green-600' : 'text-red-500'"
                       class="text-sm font-semibold transition-all duration-300"></p>
                </div>
            </div>

            {{-- 3. VERIFY IDENTITY --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 animate-fade-up delay-200" 
                 x-data="{ 
                    idType: '{{ $customer->doc_ic_passport ? 'ic' : ($customer->doc_matric ? 'matric' : 'ic') }}', 
                    hasIdentity: {{ ($customer->doc_ic_passport || $customer->doc_matric) ? 'true' : 'false' }},
                    changeIdentity: false,
                    hasLicense: {{ $customer->doc_license ? 'true' : 'false' }},
                    changeLicense: false
                 }">
                
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Verify Identity</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Identity Doc --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Identity Document</label>
                        <template x-if="hasIdentity && !changeIdentity">
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex flex-col gap-2">
                                <div class="flex items-center gap-3">
                                    <div class="bg-green-100 p-2 rounded-full text-green-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-green-900 text-sm">Verified on Record</p>
                                        <p class="text-xs text-green-700">Type: <span x-text="idType === 'ic' ? 'IC / Passport' : 'Matric Card'" class="uppercase"></span></p>
                                    </div>
                                    @if($customer->doc_ic_passport)
                                    <a href="{{ asset('storage/' . $customer->doc_ic_passport) }}" target="_blank" class="bg-white p-2 rounded-lg border border-green-200 hover:bg-green-50 text-green-600 transition" title="View Document">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    @elseif($customer->doc_matric)
                                    <a href="{{ asset('storage/' . $customer->doc_matric) }}" target="_blank" class="bg-white p-2 rounded-lg border border-green-200 hover:bg-green-50 text-green-600 transition" title="View Document">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    @endif
                                </div>
                                <button type="button" @click="changeIdentity = true" class="text-xs text-green-800 hover:text-green-900 underline text-right">Update Document</button>
                            </div>
                        </template>
                        <template x-if="!hasIdentity || changeIdentity">
                            <div>
                                <div class="flex gap-2 mb-4">
                                    <label class="cursor-pointer flex-1">
                                        <input type="radio" name="id_type" value="ic" class="hidden peer" x-model="idType">
                                        <div class="text-center py-2 px-2 border border-gray-200 rounded-lg text-sm text-gray-600 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 transition-all">IC</div>
                                    </label>
                                    <label class="cursor-pointer flex-1">
                                        <input type="radio" name="id_type" value="matric" class="hidden peer" x-model="idType">
                                        <div class="text-center py-2 px-2 border border-gray-200 rounded-lg text-sm text-gray-600 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 transition-all">Matric</div>
                                    </label>
                                </div>
                                <input type="file" name="identity_document" accept="image/png, image/jpeg, image/jpg, application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition border border-gray-200 rounded-xl cursor-pointer bg-gray-50" :required="!hasIdentity">
                                <template x-if="changeIdentity"><button type="button" @click="changeIdentity = false" class="text-xs text-gray-400 hover:text-gray-600 mt-2 underline">Cancel Update</button></template>
                            </div>
                        </template>
                    </div>

                    {{-- Driving License --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Driver's License</label>
                        <template x-if="hasLicense && !changeLicense">
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex flex-col gap-2">
                                <div class="flex items-center gap-3">
                                    <div class="bg-green-100 p-2 rounded-full text-green-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-green-900 text-sm">License on Record</p>
                                        <p class="text-xs text-green-700">Ready for verification</p>
                                    </div>
                                    @if($customer->doc_license)
                                    <a href="{{ asset('storage/' . $customer->doc_license) }}" target="_blank" class="bg-white p-2 rounded-lg border border-green-200 hover:bg-green-50 text-green-600 transition" title="View License">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    @endif
                                </div>
                                <button type="button" @click="changeLicense = true" class="text-xs text-green-800 hover:text-green-900 underline text-right">Update License</button>
                            </div>
                        </template>
                        <template x-if="!hasLicense || changeLicense">
                            <div>
                                <div class="mt-1">
                                    <input type="file" name="driving_license" accept="image/png, image/jpeg, image/jpg, application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition border border-gray-200 rounded-xl cursor-pointer bg-gray-50" :required="!hasLicense">
                                </div>
                                <template x-if="changeLicense"><button type="button" @click="changeLicense = false" class="text-xs text-gray-400 hover:text-gray-600 mt-2 underline">Cancel Update</button></template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- 4. PAYER DETAILS (Refund Info) --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 animate-fade-up delay-200"
                 x-data="{ 
                    hasBank: {{ ($customer->bankName && $customer->accountNum) ? 'true' : 'false' }},
                    changeBank: false
                 }">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Refund Details</h3>
                        <p class="text-xs text-gray-500">Your bank details for deposit refund.</p>
                    </div>
                </div>

                {{-- STATE A: Bank Details Exist --}}
                <template x-if="hasBank && !changeBank">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-4">
                            <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-blue-500 font-bold uppercase tracking-wider">Verified Account</p>
                                <h4 class="text-lg font-bold text-gray-900">{{ $customer->bankName }}</h4>
                                <p class="text-sm font-mono text-gray-600">{{ $customer->accountNum }}</p>
                            </div>
                        </div>
                        <button type="button" @click="changeBank = true" class="px-4 py-2 bg-white text-blue-600 font-bold text-sm rounded-lg border border-blue-200 hover:bg-blue-50 transition shadow-sm">
                            Change Details
                        </button>
                    </div>
                </template>

                {{-- STATE B: Input New Details --}}
                <template x-if="!hasBank || changeBank">
                    <div class="space-y-5 animate-fade-up">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Bank Name</label>
                                <input type="text" name="payer_bank" placeholder="e.g. Maybank" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition" :required="!hasBank">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Account Number</label>
                                <input type="text" name="payer_account" placeholder="e.g. 1234567890" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition" :required="!hasBank">
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="w-full">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Full Name</label>
                                <input type="text" name="payer_name" value="{{ auth()->user()->name }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition" required>
                            </div>
                            <template x-if="changeBank">
                                <button type="button" @click="changeBank = false" class="ml-4 mt-6 text-sm text-gray-400 hover:text-gray-600 underline">Cancel</button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            {{-- 5. PAYMENT METHOD --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 animate-fade-up delay-300" x-data="{ method: 'bank_transfer' }">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Select Payment Method</h3>
                </div>

                <div class="relative bg-gray-100 p-1 rounded-xl flex mb-8">
                    <button type="button" @click="method = 'bank_transfer'" :class="method === 'bank_transfer' ? 'active' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-3 text-sm font-bold rounded-lg transition-all duration-300 text-center z-10 slider-btn">Manual Transfer</button>
                    <button type="button" @click="method = 'duitnow'" :class="method === 'duitnow' ? 'active' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-3 text-sm font-bold rounded-lg transition-all duration-300 text-center z-10 slider-btn">DuitNow QR</button>
                </div>
                <input type="hidden" name="payment_method" :value="method">

                <div x-show="method === 'bank_transfer'" class="border-2 border-gray-100 rounded-3xl p-8 bg-gray-50 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-center md:text-left space-y-1">
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Bank Details</p>
                        <h4 class="text-3xl font-extrabold text-gray-900">Maybank</h4>
                        <p class="text-lg text-gray-700">Hasta Travel & Tour</p>
                    </div>
                    <div class="w-full md:w-auto bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between gap-4">
                        <span class="font-mono text-xl font-bold tracking-wider text-gray-800" id="bankAccNo">139748362455166</span>
                        <button type="button" onclick="copyToClipboard()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2 rounded-lg transition active:scale-95"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg></button>
                    </div>
                </div>
                <div id="copyFeedback" class="text-green-600 text-xs font-bold text-right mt-2 opacity-0 transition-opacity duration-300 tracking-wide uppercase">Copied!</div>

                <div x-show="method === 'duitnow'" class="border-2 border-pink-100 rounded-3xl p-8 bg-pink-50/30 text-center" style="display: none;">
                    <h4 class="text-2xl font-bold text-gray-900 mb-2">Scan to Pay</h4>
                    <div class="inline-block bg-white p-4 rounded-2xl shadow-sm border border-gray-200"><img src="{{ asset('images/qrcode.png') }}" class="w-64 h-64 object-contain mx-auto"></div>
                </div>
            </div>

            {{-- 6. RECEIPT UPLOAD (Fixed PDF Support) --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 animate-fade-up delay-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Proof of Payment</h3>
                </div>

                <div class="relative border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-red-400 hover:bg-red-50 transition-colors cursor-pointer group" 
                     x-data="{ preview: null, fileName: null, isPdf: false }">
                    
                    {{-- Added .pdf to accept attribute explicitly --}}
                    <input type="file" name="receipt" accept=".pdf,image/png,image/jpeg,image/jpg" 
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" required 
                           @change="
                                const file = $event.target.files[0];
                                if(file) {
                                    preview = URL.createObjectURL(file);
                                    fileName = file.name;
                                    // Check MIME type OR file extension for robustness
                                    isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
                                }
                           ">
                    
                    {{-- Default View --}}
                    <div class="space-y-2" x-show="!preview">
                        <div class="text-gray-400 group-hover:text-red-500 transition-colors"><i class="fas fa-cloud-upload-alt text-4xl"></i></div>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <span class="relative bg-white rounded-md font-medium text-red-600 hover:text-red-500"><span>Upload a file</span></span><p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, PDF up to 5MB</p>
                    </div>

                    {{-- Preview View --}}
                    <div x-show="preview" class="relative z-10" style="display: none;">
                        
                        {{-- Image Preview --}}
                        <template x-if="!isPdf">
                            <img :src="preview" class="max-h-64 mx-auto rounded-lg shadow-md object-contain border border-gray-200">
                        </template>

                        {{-- PDF Preview Icon --}}
                        <template x-if="isPdf">
                            <div class="flex flex-col items-center justify-center p-6 bg-red-50 rounded-xl border border-red-100 w-full max-w-xs mx-auto">
                                <svg class="w-16 h-16 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <p x-text="fileName" class="text-sm font-bold text-gray-800 break-all"></p>
                                <p class="text-xs text-red-500 mt-1">PDF Document Selected</p>
                            </div>
                        </template>

                        <p class="text-sm text-gray-500 mt-3 font-medium">Click to change file</p>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="flex items-center gap-4 pt-4 animate-fade-up delay-300">
                <button type="button" onclick="window.history.back()" class="w-1/3 py-4 rounded-xl font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition shadow-sm">Back</button>
                <button type="submit" class="w-2/3 py-4 rounded-xl font-bold text-white bg-red-600 hover:bg-red-700 transition shadow-lg shadow-red-500/30 transform hover:-translate-y-0.5">Confirm & Pay</button>
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
            voucherInput: '',
            appliedVoucherCode: '',
            voucherMessage: '',
            voucherSuccess: false,
            loading: false,

            get discountAmount() {
                return (this.fullAmount * this.discountPercent) / 100;
            },

            get totalPayable() {
                return this.fullAmount + this.depositAmount - this.discountAmount;
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
                        this.discountPercent = data.discount;
                        this.appliedVoucherCode = this.voucherInput;
                        this.voucherSuccess = true;
                        this.voucherMessage = `Success! ${data.discount}% discount applied.`;
                    } else {
                        this.discountPercent = 0;
                        this.appliedVoucherCode = '';
                        this.voucherSuccess = false;
                        this.voucherMessage = 'Invalid or expired voucher code.';
                    }
                } catch (error) {
                    this.voucherSuccess = false;
                    this.voucherMessage = 'Error validating voucher.';
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endsection