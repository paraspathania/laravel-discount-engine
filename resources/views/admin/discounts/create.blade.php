@extends('layouts.admin')

@section('header', 'Create New Discount')

@section('content')
    <form action="{{ route('admin.discounts.store') }}" method="POST" x-data="{ type: 'percentage' }" class="max-w-4xl">
        @csrf
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-8 py-5 border-b border-gray-200 bg-gray-50 flex items-center">
                <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-black flex items-center justify-center mr-3">1</span>
                <h3 class="text-lg font-extrabold text-gray-900">Basic Information</h3>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Discount Name (Internal)</label>
                    <input type="text" name="name" required placeholder="e.g. Black Friday Sale" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm py-3">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Discount Type</label>
                    <select name="type" x-model="type" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-bold shadow-sm py-3">
                        <option value="percentage">Percentage Off (%)</option>
                        <option value="fixed_amount">Fixed Amount Off ($)</option>
                        <option value="bogo">Buy One Get One</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Discount Value</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-bold" x-text="type === 'percentage' ? '%' : '$'"></span>
                        </div>
                        <input type="number" name="value" required min="1" step="0.01" placeholder="0.00" class="w-full pl-10 rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-bold shadow-sm py-3" :max="type === 'percentage' ? 100 : ''">
                    </div>
                    <p class="text-xs text-gray-500 font-medium mt-2">Enter standard numbers (we handle the cent-conversion automatically in the backend).</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Application Priority</label>
                    <input type="number" name="priority" value="0" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-bold shadow-sm py-3">
                    <p class="text-xs text-gray-500 font-medium mt-2">Higher numbers process first in the checkout pipeline.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-8 py-5 border-b border-gray-200 bg-gray-50 flex items-center">
                <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-black flex items-center justify-center mr-3">2</span>
                <h3 class="text-lg font-extrabold text-gray-900">Schedule & Limits</h3>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Starts At</label>
                    <input type="datetime-local" name="starts_at" required class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm py-3">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ends At (Optional)</label>
                    <input type="datetime-local" name="ends_at" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm py-3">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Global Usage Limit (Optional)</label>
                    <input type="number" name="usage_limit" placeholder="Leave empty for unlimited" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm py-3">
                </div>

                <div class="flex items-center pt-8">
                    <input type="checkbox" name="is_stackable" id="stackable" value="1" class="h-6 w-6 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="stackable" class="ml-3 block text-sm font-bold text-gray-900">
                        Is Stackable?
                        <span class="block text-xs font-medium text-gray-500 mt-1">Allow this to be combined with other discounts in the cart.</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-8 py-5 border-b border-gray-200 bg-gray-50 flex items-center">
                <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-black flex items-center justify-center mr-3">3</span>
                <h3 class="text-lg font-extrabold text-gray-900">Restrictions</h3>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Minimum Order Value ($)</label>
                    <input type="number" name="min_order_value" step="0.01" value="0.00" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-bold shadow-sm py-3">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Apply To (Qualifiers)</label>
                    <select class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-bold shadow-sm py-3 mb-2">
                        <option>Entire Store (Sitewide)</option>
                        <option>Specific Products</option>
                        <option>Specific Categories</option>
                    </select>
                    <p class="text-xs text-gray-500 font-medium">To assign specific products/categories, create the discount first then attach them from the edit screen.</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 border-t border-gray-200 pt-8 pb-12">
            <a href="{{ route('admin.discounts.index') }}" class="px-8 py-4 text-gray-700 font-bold hover:bg-gray-100 rounded-xl transition-colors">Cancel</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-10 py-4 rounded-xl shadow-lg transition-all hover:-translate-y-0.5">
                Save Discount Rule
            </button>
        </div>
    </form>
@endsection
