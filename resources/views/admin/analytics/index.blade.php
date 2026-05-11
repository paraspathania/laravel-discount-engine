<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Usage Analytics') }}
            </h2>
            <button onclick="alert('Export Logic goes here in Step 8/9')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                ⬇ Export CSV
            </button>
        </div>
    </x-slot>

    @php
        $query = \App\Models\DiscountUsage::with(['user', 'discount', 'order'])->latest();
        
        // Basic filtering logic built inline for demonstration purposes
        if(request('discount_id')) {
            $query->where('discount_id', request('discount_id'));
        }
        if(request('start_date')) {
            $query->whereDate('created_at', '>=', request('start_date'));
        }
        if(request('end_date')) {
            $query->whereDate('created_at', '<=', request('end_date'));
        }

        $usages = $query->paginate(15)->withQueryString();
        $discounts = \App\Models\Discount::orderBy('name')->get();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Filter by Discount</label>
                        <select name="discount_id" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">All Discounts</option>
                            @foreach($discounts as $discount)
                                <option value="{{ $discount->id }}" {{ request('discount_id') == $discount->id ? 'selected' : '' }}>
                                    {{ $discount->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <button type="submit" class="bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700">
                            Apply Filters
                        </button>
                        <a href="{{ url()->current() }}" class="ml-2 text-sm text-indigo-600 hover:text-indigo-900">Clear</a>
                    </div>
                </form>
            </div>

            <!-- Analytics Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount Applied</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount Saved</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($usages as $usage)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $usage->created_at->format('M j, Y g:i A') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $usage->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600"><a href="{{ route('admin.discounts.edit', $usage->discount_id) }}">{{ $usage->discount->name }}</a></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $usage->order_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-green-600">${{ $usage->saved_amount_formatted }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No usage records match your filters.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $usages->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
