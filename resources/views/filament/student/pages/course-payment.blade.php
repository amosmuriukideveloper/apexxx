<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Course Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Course Enrollment Payment</h2>
            
            <div class="flex gap-6">
                @if($record->thumbnail)
                <img src="{{ Storage::url($record->thumbnail) }}" 
                     alt="{{ $record->title }}" 
                     class="w-32 h-32 object-cover rounded-lg">
                @endif
                
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $record->title }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $record->description }}</p>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Instructor</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $record->instructor->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Duration</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $record->duration ?? 'Self-paced' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Level</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($record->level ?? 'All Levels') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Lessons</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $record->lessons_count ?? 0 }} lessons</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pricing -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-900 dark:text-white">Course Price</span>
                    <span class="text-2xl font-bold text-green-600 dark:text-green-400">${{ number_format($record->price, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Select Payment Method</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- M-Pesa -->
                <button type="button" wire:click="selectPaymentMethod('mpesa')"
                        class="p-6 border-2 rounded-lg transition 
                        {{ $selectedMethod === 'mpesa' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-green-300' }}">
                    <div class="text-center">
                        <div class="text-3xl mb-2">📱</div>
                        <h4 class="font-bold text-gray-900 dark:text-white">M-Pesa</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pay with phone</p>
                    </div>
                </button>

                <!-- PayPal -->
                <button type="button" wire:click="selectPaymentMethod('paypal')"
                        class="p-6 border-2 rounded-lg transition
                        {{ $selectedMethod === 'paypal' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-blue-300' }}">
                    <div class="text-center">
                        <div class="text-3xl mb-2">💳</div>
                        <h4 class="font-bold text-gray-900 dark:text-white">PayPal</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">International payment</p>
                    </div>
                </button>

                <!-- Pesapal -->
                <button type="button" wire:click="selectPaymentMethod('pesapal')"
                        class="p-6 border-2 rounded-lg transition
                        {{ $selectedMethod === 'pesapal' ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-purple-300' }}">
                    <div class="text-center">
                        <div class="text-3xl mb-2">💰</div>
                        <h4 class="font-bold text-gray-900 dark:text-white">Pesapal</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Card payment</p>
                    </div>
                </button>
            </div>

            <!-- M-Pesa Form -->
            @if($selectedMethod === 'mpesa')
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 bg-green-50 dark:bg-green-900/10 p-6 rounded-lg">
                <h4 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="text-2xl mr-2">📱</span> M-Pesa Payment
                </h4>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           wire:model.defer="phoneNumber" 
                           class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-900 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200"
                           placeholder="254712345678"
                           maxlength="12">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: 254XXXXXXXXX (12 digits)</p>
                    @error('phoneNumber')
                        <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                    
                    <button type="button" 
                            wire:click="processMpesaPayment" 
                            wire:loading.attr="disabled"
                            class="mt-4 w-full px-6 py-4 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="processMpesaPayment">
                            💳 Pay ${{ number_format($record->price, 2) }} via M-Pesa
                        </span>
                        <span wire:loading wire:target="processMpesaPayment">
                            ⏳ Processing Payment...
                        </span>
                    </button>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-3 text-center">
                        ✅ You will receive an STK push notification on your phone
                    </p>
                </div>
            </div>
            @endif

            @if($selectedMethod === 'paypal')
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 bg-blue-50 dark:bg-blue-900/10 p-6 rounded-lg text-center">
                <h4 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-center">
                    <span class="text-2xl mr-2">💳</span> PayPal Payment
                </h4>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    You will be redirected to PayPal to complete your payment securely.
                </p>
                <button type="button" 
                        wire:click="processPayPalPayment"
                        wire:loading.attr="disabled" 
                        class="px-8 py-4 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="processPayPalPayment">
                        🚀 Continue to PayPal (${{ number_format($record->price, 2) }})
                    </span>
                    <span wire:loading wire:target="processPayPalPayment">
                        ⏳ Redirecting...
                    </span>
                </button>
            </div>
            @endif

            @if($selectedMethod === 'pesapal')
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 bg-purple-50 dark:bg-purple-900/10 p-6 rounded-lg text-center">
                <h4 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-center">
                    <span class="text-2xl mr-2">💰</span> Pesapal Payment
                </h4>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    You will be redirected to Pesapal to complete your payment securely via card or mobile money.
                </p>
                <button type="button" 
                        wire:click="processPesapalPayment"
                        wire:loading.attr="disabled" 
                        class="px-8 py-4 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="processPesapalPayment">
                        🚀 Continue to Pesapal (${{ number_format($record->price, 2) }})
                    </span>
                    <span wire:loading wire:target="processPesapalPayment">
                        ⏳ Redirecting...
                    </span>
                </button>
            </div>
            @endif
        </div>

        <!-- Security Notice -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-900 dark:text-blue-100">Secure Payment</h4>
                    <p class="text-sm text-blue-800 dark:text-blue-200 mt-1">
                        All payments are processed securely. Your payment information is encrypted and never stored on our servers.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
