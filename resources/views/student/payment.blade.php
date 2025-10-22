<x-layouts.app>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Project Details Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900">Complete Your Payment</h2>
                    <p class="mt-1 text-sm text-gray-600">Project #{{ $project->project_number }}</p>
                </div>
                
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Project Title</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $project->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Subject</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $project->subject?->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pages</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $project->page_count }} pages</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Deadline</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $project->deadline->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                    
                    <!-- Cost Breakdown -->
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <div class="flex justify-between text-lg font-semibold text-gray-900">
                            <span>Total Amount:</span>
                            <span class="text-primary-600">${{ number_format($project->cost, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Select Payment Method</h3>
                    
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <!-- M-Pesa -->
                        @if($mpesa)
                        <form action="{{ route('student.project.payment.mpesa', $project) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex flex-col items-center justify-center p-6 border-2 border-gray-300 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition">
                                <svg class="w-16 h-16 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                                </svg>
                                <span class="mt-2 text-lg font-semibold text-gray-900">M-Pesa</span>
                                <span class="text-sm text-gray-500">Pay via M-Pesa</span>
                            </button>
                        </form>
                        @endif

                        <!-- PayPal -->
                        @if($paypal)
                        <form action="{{ route('student.project.payment.paypal', $project) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex flex-col items-center justify-center p-6 border-2 border-gray-300 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition">
                                <svg class="w-16 h-16 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.067 8.478c.492.88.556 2.014.3 3.327-.74 3.806-3.276 5.12-6.514 5.12h-.5a.805.805 0 00-.794.68l-.04.22-.63 3.993-.028.154a.804.804 0 01-.794.68H7.72c-.3 0-.55-.22-.59-.54L5.01 13.75c-.043-.28.164-.53.446-.53h1.722c.348 0 .655.28.738.626l.323 2.05c.083.346.39.626.738.626h.51c2.39 0 4.27-1.004 4.82-3.898.41-2.13-.1-3.628-1.52-4.47-.543-.32-1.183-.53-1.894-.61l-.31-.036a9.996 9.996 0 01-.147-.023c-.31-.05-.62-.097-.93-.135C9.195 7.316 9 7.147 9 6.847c0-.42.385-.76.86-.76h5.542c.678 0 1.265.514 1.37 1.186l.33 2.094.003.01c.05.296.28.5.565.5.385 0 .703-.313.663-.696l-.36-2.286c-.16-1.004-.976-1.715-1.93-1.715H9.86c-.913 0-1.66.747-1.66 1.67 0 .77.49 1.427 1.175 1.655l.01.003c.236.063.476.113.716.158.05.01.1.02.15.028l.31.05c.847.13 1.62.38 2.294.76 1.875 1.053 2.76 2.98 2.294 5.37-.582 2.99-3.014 4.898-6.2 4.898h-.51a1.725 1.725 0 01-1.705-1.454l-.323-2.05a.805.805 0 00-.794-.68H3.74a.805.805 0 01-.795-.955l2.12-8.359A1.715 1.715 0 016.76 5h10.542c2.012 0 3.698 1.528 3.898 3.52.06.616.034 1.21-.132 1.76z"/>
                                </svg>
                                <span class="mt-2 text-lg font-semibold text-gray-900">PayPal</span>
                                <span class="text-sm text-gray-500">Pay with PayPal</span>
                            </button>
                        </form>
                        @endif

                        <!-- PesaPal -->
                        @if($pesapal)
                        <form action="{{ route('student.project.payment.pesapal', $project) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex flex-col items-center justify-center p-6 border-2 border-gray-300 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition">
                                <svg class="w-16 h-16 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm2 0v14h14V5H5zm2 2h10a1 1 0 011 1v8a1 1 0 01-1 1H7a1 1 0 01-1-1V8a1 1 0 011-1zm1 2v6h8V9H8z"/>
                                </svg>
                                <span class="mt-2 text-lg font-semibold text-gray-900">PesaPal</span>
                                <span class="text-sm text-gray-500">Pay with Card</span>
                            </button>
                        </form>
                        @endif
                    </div>

                    @if(!$mpesa && !$paypal && !$pesapal)
                    <div class="text-center py-8">
                        <p class="text-gray-500">No payment methods are currently available.</p>
                        <p class="text-sm text-gray-400 mt-2">Please contact support for assistance.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6 text-center">
                <a href="{{ route('student.projects.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Back to Projects
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
