<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    @php
        // Fetching data directly since no DashboardController was requested
        $activeDiscounts = \App\Models\Discount::active()->count();
        $totalSavings = \App\Models\DiscountUsage::sum('saved_amount') / 100;
        $recentRedemptions = \App\Models\DiscountUsage::with(['user', 'discount', 'order'])
            ->latest()
            ->take(5)
            ->get();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <h3 class="text-gray-500 text-sm uppercase font-semibold">Active Discounts</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activeDiscounts }}</p>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <h3 class="text-gray-500 text-sm uppercase font-semibold">Total Savings Given</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($totalSavings, 2) }}</p>
                </div>
            </div>

            <!-- Recent Redemptions Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Redemptions</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saved</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentRedemptions as $usage)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $usage->created_at->format('M j, Y g:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $usage->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $usage->discount->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 hover:text-indigo-900">
                                            #{{ $usage->order_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                            ${{ $usage->saved_amount_formatted }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No redemptions yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
