<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Earnings Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Earnings</h3>
                    <x-heroicon-o-currency-dollar class="w-5 h-5 text-green-500" />
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($totalEarnings, 2) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gross revenue</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Net Earnings</h3>
                    <x-heroicon-o-banknotes class="w-5 h-5 text-blue-500" />
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($netEarnings, 2) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">After 30% platform fee</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">This Month</h3>
                    <x-heroicon-o-calendar class="w-5 h-5 text-purple-500" />
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($thisMonthEarnings, 2) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Net earnings</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Balance</h3>
                    <x-heroicon-o-clock class="w-5 h-5 text-yellow-500" />
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($pendingBalance, 2) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Available for withdrawal</p>
            </div>
        </div>

        <!-- Request Payout Button -->
        <div class="bg-gradient-to-r from-purple-500 to-blue-500 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-2">Ready to withdraw?</h3>
                    <p class="text-purple-100">Request a payout of your pending balance</p>
                </div>
                <button class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-purple-50 transition">
                    Request Payout
                </button>
            </div>
        </div>

        <!-- Course-wise Earnings -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Course-wise Earnings</h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Course</th>
                                <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Enrollments</th>
                                <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Gross Revenue</th>
                                <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Net Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courseEarnings as $course)
                            <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="py-3 px-4">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $course->title }}</p>
                                </td>
                                <td class="text-right py-3 px-4 text-gray-600 dark:text-gray-400">
                                    {{ $course->enrollments }}
                                </td>
                                <td class="text-right py-3 px-4 text-gray-900 dark:text-white font-medium">
                                    ${{ number_format($course->revenue, 2) }}
                                </td>
                                <td class="text-right py-3 px-4 text-green-600 dark:text-green-400 font-semibold">
                                    ${{ number_format($course->revenue * 0.70, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Monthly Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Monthly Breakdown</h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($monthlyBreakdown as $month)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $month['month'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Gross: ${{ number_format($month['gross'], 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">${{ number_format($month['net'], 2) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Net earnings</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Payout History -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Payout History</h2>
            </div>
            <div class="p-6">
                @if($payoutHistory->count() > 0)
                <div class="space-y-3">
                    @foreach($payoutHistory as $payout)
                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">${{ number_format($payout->amount, 2) }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $payout->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            @if($payout->status === 'completed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Completed
                            </span>
                            @elseif($payout->status === 'processing')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                Processing
                            </span>
                            @elseif($payout->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Pending
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                Failed
                            </span>
                            @endif
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">via {{ $payout->payment_method }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <x-heroicon-o-banknotes class="w-16 h-16 mx-auto mb-4 opacity-50" />
                    <p>No payout history yet</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
