@extends('layouts.admin')

@section('header', isset($product) ? 'Edit Product' : 'Add Product')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-950 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.products.index') }}" class="text-slate-400 hover:text-white mr-4 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h3 class="text-base font-extrabold text-white uppercase tracking-wider">
                    {{ isset($product) ? 'Edit: ' . $product->name : 'Create New Product' }}
                </h3>
            </div>
            <span class="text-[10px] font-extrabold text-slate-400 bg-slate-900 px-3 py-1.5 rounded-lg uppercase tracking-wider">Product Inventory</span>
        </div>

        {{-- Form --}}
        <div class="p-6">
            <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST">
                @csrf
                @if(isset($product)) @method('PUT') @endif

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="mb-6 bg-rose-50/80 backdrop-blur-sm border border-rose-200/60 rounded-xl p-5 shadow-sm">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Name --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Product Name <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
                               class="w-full rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-800 placeholder:text-slate-400 text-sm px-4 py-3 bg-white outline-none transition-all @error('name') border-rose-400 focus:border-rose-400 focus:ring-rose-500/10 @enderror">
                        @error('name') <p class="text-rose-600 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- SKU --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">SKU <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required
                               placeholder="e.g. LAP-MBP-14"
                               class="w-full rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-mono font-extrabold text-slate-700 placeholder:text-slate-400 text-sm px-4 py-3 bg-white outline-none transition-all @error('sku') border-rose-400 focus:border-rose-400 focus:ring-rose-500/10 @enderror">
                        @error('sku') <p class="text-rose-600 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Category</label>
                        <div class="relative">
                            <select name="category_id" class="w-full rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700 text-sm px-4 py-3 bg-white outline-none transition-all appearance-none pr-10">
                                <option value="">— No Category —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Price (₹) <span class="text-rose-500 font-bold">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">₹</span>
                            <input type="number" name="price" step="0.01" min="0"
                                   value="{{ old('price', isset($product) ? number_format($product->price / 100, 2) : '') }}"
                                   required class="w-full pl-8 pr-4 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-extrabold text-slate-800 placeholder:text-slate-400 text-sm py-3 bg-white outline-none transition-all @error('price') border-rose-400 focus:border-rose-400 focus:ring-rose-500/10 @enderror">
                        </div>
                        @error('price') <p class="text-rose-600 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Stock --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Stock Quantity <span class="text-rose-500 font-bold">*</span></label>
                        <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}"
                               required class="w-full rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-extrabold text-slate-800 placeholder:text-slate-400 text-sm px-4 py-3 bg-white outline-none transition-all @error('stock') border-rose-400 focus:border-rose-400 focus:ring-rose-500/10 @enderror">
                        @error('stock') <p class="text-rose-600 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Submit --}}
                <div class="mt-8 pt-4 border-t border-slate-100 flex items-center gap-4">
                    <button type="submit"
                            class="flex-1 flex justify-center items-center bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3 px-6 rounded-xl transition-all shadow-md shadow-indigo-600/20 hover:shadow-lg hover:shadow-indigo-600/30 hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        {{ isset($product) ? 'Save Changes' : 'Create Product' }}
                    </button>
                    <a href="{{ route('admin.products.index') }}"
                       class="font-extrabold text-slate-500 hover:text-slate-700 hover:bg-slate-100 px-6 py-3 rounded-xl transition-all text-sm">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
