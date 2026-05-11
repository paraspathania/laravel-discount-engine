<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Coupon Manager') }}
        </h2>
    </x-slot>

    @php
        $coupons = \App\Models\Coupon::with('discount')->latest()->paginate(15);
        $discounts = \App\Models\Discount::active()->get();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Bulk Generate Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Generate Unique Coupons</h3>
                <form action="{{ route('admin.coupons.store') }}" method="POST" class="flex flex-wrap items-end gap-4">
                    @csrf
                    <div class="w-full md:w-1/3">
                        <label class="block text-sm font-medium text-gray-700">Select Parent Discount</label>
                        <select name="discount_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Select Active Discount --</option>
                            @foreach($discounts as $discount)
                                <option value="{{ $discount->id }}">{{ $discount->name }} ({{ $discount->type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Quantity to Generate</label>
                        <input type="number" name="quantity" min="1" max="500" value="10" required class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Max Uses Per User</label>
                        <input type="number" name="max_uses_per_user" min="1" value="1" required class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700">
                            Generate Codes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Coupons Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Linked Discount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usage Count</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Max Per User</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Created</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($coupons as $coupon)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-gray-900">{{ $coupon->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $coupon->discount->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $coupon->usage_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $coupon->max_uses_per_user }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">{{ $coupon->created_at->format('M j, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No coupons generated yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $coupons->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
