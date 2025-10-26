<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Session Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Tutoring Session Details</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Request Number</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $record->request_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Subject</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $record->subject }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Topic</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $record->specific_topic }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Duration</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $record->duration }} minutes</p>
                </div>
            </div>
            
            <!-- Pricing -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Session Rate</span>
                        <span class="text-gray-900 dark:text-white">${{ number_format($record->base_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Platform Fee (15%)</span>
                        <span class="text-gray-900 dark:text-white">${{ number_format($record->platform_fee, 2) }}</span>
                    </div>
                    <div class="border-t border-gray-300 dark:border-gray-600 pt-2 mt-2 flex justify-between">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Total Amount</span>
                        <span class="text-2xl font-bold text-green-600 dark:text-green-400">${{ number_format($record->total_price, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Select Payment Method</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- M-Pesa -->
                <button type="button" wire:click="selectPaymentMethod('mpesa')"
                        class="p-6 border-2 rounded-lg transition {{ $selectedMethod === 'mpesa' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-green-300' }}">
                    <div class="text-center">
                        <div class="text-3xl mb-2">📱</div>
                        <h4 class="font-bold text-gray-900 dark:text-white">M-Pesa</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pay with phone</p>
                    </div>
                </button>

                <!-- PayPal -->
                <button type="button" wire:click="selectPaymentMethod('paypal')"
                        class="p-6 border-2 rounded-lg transition {{ $selectedMethod === 'paypal' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-blue-300' }}">
                    <div class="text-center">
                        <div class="text-3xl mb-2">💳</div>
                        <h4 class="font-bold text-gray-900 dark:text-white">PayPal</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">International payment</p>
                    </div>
                </button>

                <!-- Pesapal -->
                <button type="button" wire:click="selectPaymentMethod('pesapal')"
                        class="p-6 border-2 rounded-lg transition {{ $selectedMethod === 'pesapal' ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-purple-300' }}">
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
                            💳 Pay ${{ number_format($record->total_price ?? 0, 2) }} via M-Pesa
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
                        🚀 Continue to PayPal (${{ number_format($record->total_price ?? 0, 2) }})
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
                        🚀 Continue to Pesapal (${{ number_format($record->total_price ?? 0, 2) }})
                    </span>
                    <span wire:loading wire:target="processPesapalPayment">
                        ⏳ Redirecting...
                    </span>
                </button>
            </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
