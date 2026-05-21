@extends('layouts.admin')

@section('header', 'Create New Discount')

@section('content')
<form action="{{ route('admin.discounts.store') }}" method="POST" x-data="{ type: 'percentage' }" class="max-w-5xl mx-auto">
    @csrf

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-8 bg-rose-50/80 backdrop-blur-sm border border-rose-200/60 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-2.5">
                <div class="w-8 h-8 rounded-lg bg-rose-500/10 flex items-center justify-center text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <p class="text-sm font-bold text-rose-900">Please correct the fields below:</p>
            </div>
            <ul class="list-disc list-inside text-xs text-rose-700/90 space-y-1 pl-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-8">

        {{-- ── Section 1: Basic Information ── --}}
        <div class="bg-white border border-slate-100 shadow-sm rounded-2xl overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                <span class="w-7 h-7 rounded-xl bg-indigo-600/10 text-indigo-600 text-xs font-extrabold flex items-center justify-center shrink-0">1</span>
                <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Basic Information</h3>
            </div>
            <div class="p-6 space-y-6">

                {{-- Name – full width --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Discount Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="e.g. Summer Sale 20%"
                           class="block w-full border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl px-4 py-3 text-slate-800 bg-white/50 backdrop-blur-sm transition-all text-sm outline-none placeholder:text-slate-400">
                    <p class="text-xs text-slate-400 mt-2 font-medium">Internal label — customers won't see this.</p>
                </div>

                {{-- Type | Value – two columns --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Discount Type</label>
                        <div class="relative">
                            <select name="type" x-model="type"
                                    class="block w-full border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl px-4 py-3 text-slate-800 bg-white/50 backdrop-blur-sm transition-all text-sm outline-none appearance-none">
                                <option value="percentage">Percentage off (%)</option>
                                <option value="fixed_amount">Fixed amount off (₹)</option>
                                <option value="bogo">Buy One Get One</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Value</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400 pointer-events-none"
                                  x-text="type === 'percentage' ? '%' : '₹'">%</span>
                            <input type="number" name="value" value="{{ old('value') }}"
                                   required min="1" step="0.01" placeholder="0"
                                   :max="type === 'percentage' ? 100 : ''"
                                   class="block w-full border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl pl-9 pr-4 py-3 text-slate-800 bg-white/50 backdrop-blur-sm transition-all text-sm outline-none">
                        </div>
                        <p class="text-xs text-slate-400 mt-2 font-medium">Enter a standard number (e.g. 20 for 20%).</p>
                    </div>
                </div>

                {{-- Priority | Min Order – two columns --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            Priority <span class="font-medium normal-case text-slate-400">(lower = applied first)</span>
                        </label>
                        <input type="number" name="priority" value="{{ old('priority', 0) }}"
                               class="block w-full border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl px-4 py-3 text-slate-800 bg-white/50 backdrop-blur-sm transition-all text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            Min. Order Value (₹) <span class="font-medium normal-case text-slate-400">(optional)</span>
                        </label>
                        <input type="number" name="min_order_value" step="0.01" value="{{ old('min_order_value', '0') }}"
                               class="block w-full border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl px-4 py-3 text-slate-800 bg-white/50 backdrop-blur-sm transition-all text-sm outline-none">
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Section 2: Schedule & Limits ── --}}
        <div class="bg-white border border-slate-100 shadow-sm rounded-2xl overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                <span class="w-7 h-7 rounded-xl bg-indigo-600/10 text-indigo-600 text-xs font-extrabold flex items-center justify-center shrink-0">2</span>
                <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Schedule & Limits</h3>
            </div>
            <div class="p-6 space-y-6">

                {{-- Starts At | Ends At --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Starts At</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" required
                               class="block w-full border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl px-4 py-3 text-slate-800 bg-white/50 backdrop-blur-sm transition-all text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            Ends At <span class="font-medium normal-case text-slate-400">(optional)</span>
                        </label>
                        <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}"
                               class="block w-full border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl px-4 py-3 text-slate-800 bg-white/50 backdrop-blur-sm transition-all text-sm outline-none">
                        <p class="text-xs text-slate-400 mt-2 font-medium">Leave blank for no expiry.</p>
                    </div>
                </div>

                {{-- Usage Limit | Stackable --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            Global Usage Limit <span class="font-medium normal-case text-slate-400">(optional)</span>
                        </label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit') }}"
                               placeholder="Leave blank for unlimited"
                               class="block w-full border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl px-4 py-3 text-slate-800 bg-white/50 backdrop-blur-sm transition-all text-sm outline-none placeholder:text-slate-400">
                    </div>
                    <div class="flex items-start gap-3.5 pt-4 md:pt-8">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_stackable" id="stackable" value="1"
                                   {{ old('is_stackable') ? 'checked' : '' }}
                                   class="h-5 w-5 text-indigo-600 border-slate-200 rounded-lg focus:ring-indigo-500 focus:ring-offset-0 transition-colors cursor-pointer">
                        </div>
                        <label for="stackable" class="text-sm text-slate-700 cursor-pointer select-none">
                            <span class="font-extrabold block text-slate-800 mb-0.5">Allow stacking</span>
                            <span class="text-xs text-slate-400 font-medium">Can combine with other discounts in the same cart.</span>
                        </label>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Section 3: Qualifiers ── --}}
        <div class="bg-white border border-slate-100 shadow-sm rounded-2xl overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-xl bg-indigo-600/10 text-indigo-600 text-xs font-extrabold flex items-center justify-center shrink-0">3</span>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Qualifiers</h3>
                </div>
                <span class="text-slate-400 font-bold text-xs bg-slate-100/60 px-3 py-1.5 rounded-lg">Leave blank to apply sitewide</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">Specific Products</label>
                        <select name="qualifiable_products[]" multiple
                                class="block w-full border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl p-3 text-slate-800 bg-white/50 backdrop-blur-sm transition-all text-sm outline-none h-48 focus:bg-white select-multiple-custom">
                            @foreach($products ?? [] as $product)
                                <option class="py-2 px-3 rounded-lg my-0.5 checked:bg-indigo-50 checked:text-indigo-900 text-slate-700"
                                        value="{{ $product->id }}" {{ collect(old('qualifiable_products', []))->contains($product->id) ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-400 mt-2 font-medium flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Hold Ctrl / Cmd to select multiple.
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">Specific Categories</label>
                        <select name="qualifiable_categories[]" multiple
                                class="block w-full border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl p-3 text-slate-800 bg-white/50 backdrop-blur-sm transition-all text-sm outline-none h-48 focus:bg-white select-multiple-custom">
                            @foreach($categories ?? [] as $category)
                                <option class="py-2 px-3 rounded-lg my-0.5 checked:bg-indigo-50 checked:text-indigo-900 text-slate-700"
                                        value="{{ $category->id }}" {{ collect(old('qualifiable_categories', []))->contains($category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-400 mt-2 font-medium flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Hold Ctrl / Cmd to select multiple.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Actions ── --}}
        <div class="flex items-center justify-end gap-4 pb-12">
            <a href="{{ route('admin.discounts.index') }}"
               class="px-6 py-3 text-sm font-extrabold text-slate-600 hover:text-slate-800 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl transition-all hover:shadow-sm">
                Cancel
            </a>
            <button type="submit"
                    class="px-7 py-3 text-sm font-extrabold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-all shadow-lg shadow-indigo-600/20 hover:shadow-xl hover:shadow-indigo-600/30 hover:-translate-y-0.5">
                Create Discount
            </button>
        </div>

    </div>
</form>
@endsection
