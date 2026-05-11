<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Order #') }}{{ $order->id }}
            </h2>
            <a href="{{ route('user.orders.index') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">&larr; Back to Orders</a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 bg-green-600 text-white p-6 rounded-xl shadow-lg flex items-center justify-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-xl font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden border border-gray-100 mb-8">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Order Summary</h3>
                    <span class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M j, Y \a\t g:i A') }}</span>
                </div>
                <div class="px-6 py-6 sm:px-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 uppercase font-bold text-green-600">{{ $order->status }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Total Items</dt>
                            <dd class="mt-1 text-sm text-gray-900">Items purchased</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Receipt Breakdown -->
            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Payment Breakdown</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <dt>Original Subtotal</dt>
                            <dd class="font-medium text-gray-900">${{ number_format($order->subtotal / 100, 2) }}</dd>
                        </div>
                        
                        @if($order->discount_total > 0)
                            <div class="flex justify-between text-green-600 font-bold border-t border-gray-100 pt-4">
                                <dt>Discounts Saved</dt>
                                <dd>-${{ number_format($order->discount_total / 100, 2) }}</dd>
                            </div>
                        @endif

                        <div class="flex justify-between border-t border-gray-100 pt-4">
                            <dt>Tax Paid</dt>
                            <dd class="font-medium text-gray-900">${{ number_format($order->tax_total / 100, 2) }}</dd>
                        </div>

                        <div class="flex justify-between items-center border-t border-gray-200 pt-6 pb-2">
                            <dt class="text-lg font-extrabold text-gray-900">Grand Total Paid</dt>
                            <dd class="text-2xl font-extrabold text-gray-900">${{ $order->grand_total_formatted }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
