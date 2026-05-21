@extends('layouts.admin')

@section('header', 'Create New Discount')

@section('content')
<form action="{{ route('admin.discounts.store') }}" method="POST" x-data="{ type: 'percentage' }">
    @csrf

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-5 py-4">
            <p class="text-sm font-semibold text-red-700 mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside text-sm text-red-600 space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-6">

        {{-- ── Section 1: Basic Information ── --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                <h3 class="text-sm font-semibold text-gray-900">Basic Information</h3>
            </div>
            <div class="p-6 space-y-5">

                {{-- Name – full width --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Discount Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="e.g. Summer Sale 20%"
                           class="block w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-gray-400">
                    <p class="text-xs text-gray-400 mt-1">Internal label — customers won't see this.</p>
                </div>

                {{-- Type | Value – two columns --}}
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Discount Type</label>
                        <select name="type" x-model="type"
                                class="block w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="percentage">Percentage off (%)</option>
                            <option value="fixed_amount">Fixed amount off (₹)</option>
                            <option value="bogo">Buy One Get One</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Value</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 pointer-events-none"
                                  x-text="type === 'percentage' ? '%' : '₹'">%</span>
                            <input type="number" name="value" value="{{ old('value') }}"
                                   required min="1" step="0.01" placeholder="0"
                                   :max="type === 'percentage' ? 100 : ''"
                                   class="block w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Enter a standard number (e.g. 20 for 20%).</p>
                    </div>
                </div>

                {{-- Priority | Min Order – two columns --}}
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Priority
                            <span class="font-normal normal-case text-gray-400">(lower = applied first)</span>
                        </label>
                        <input type="number" name="priority" value="{{ old('priority', 0) }}"
                               class="block w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Min. Order Value (₹)
                            <span class="font-normal normal-case text-gray-400">(optional)</span>
                        </label>
                        <input type="number" name="min_order_value" step="0.01" value="{{ old('min_order_value', '0') }}"
                               class="block w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Section 2: Schedule & Limits ── --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0">2</span>
                <h3 class="text-sm font-semibold text-gray-900">Schedule & Limits</h3>
            </div>
            <div class="p-6 space-y-5">

                {{-- Starts At | Ends At --}}
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Starts At</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" required
                               class="block w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Ends At
                            <span class="font-normal normal-case text-gray-400">(optional)</span>
                        </label>
                        <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}"
                               class="block w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <p class="text-xs text-gray-400 mt-1">Leave blank for no expiry.</p>
                    </div>
                </div>

                {{-- Usage Limit | Stackable --}}
                <div class="grid grid-cols-2 gap-5 items-start">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Global Usage Limit
                            <span class="font-normal normal-case text-gray-400">(optional)</span>
                        </label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit') }}"
                               placeholder="Leave blank for unlimited"
                               class="block w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div class="flex items-start gap-3 pt-7">
                        <input type="checkbox" name="is_stackable" id="stackable" value="1"
                               {{ old('is_stackable') ? 'checked' : '' }}
                               class="mt-0.5 h-4 w-4 shrink-0 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="stackable" class="text-sm text-gray-700 cursor-pointer">
                            <span class="font-semibold block">Allow stacking</span>
                            <span class="text-xs text-gray-400">Can combine with other discounts in the same cart.</span>
                        </label>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Section 3: Qualifiers ── --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0">3</span>
                <h3 class="text-sm font-semibold text-gray-900">Qualifiers
                    <span class="text-gray-400 font-normal text-xs ml-1">— leave blank to apply sitewide</span>
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Specific Products</label>
                        <select name="qualifiable_products[]" multiple
                                class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent h-44">
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}" {{ collect(old('qualifiable_products', []))->contains($product->id) ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Hold Ctrl / Cmd to select multiple.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Specific Categories</label>
                        <select name="qualifiable_categories[]" multiple
                                class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent h-44">
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ collect(old('qualifiable_categories', []))->contains($category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Hold Ctrl / Cmd to select multiple.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Actions ── --}}
        <div class="flex items-center justify-end gap-3 pb-8">
            <a href="{{ route('admin.discounts.index') }}"
               class="px-5 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900 border border-gray-300 hover:border-gray-400 rounded-lg transition-colors bg-white">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors shadow-sm">
                Create Discount
            </button>
        </div>

    </div>
</form>
@endsection
