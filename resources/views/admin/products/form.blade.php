@extends('layouts.admin')

@section('header', isset($product) ? 'Edit Product' : 'Add Product')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-100 bg-slate-900 flex items-center">
            <a href="{{ route('admin.products.index') }}" class="text-slate-400 hover:text-white mr-4 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h3 class="text-lg font-extrabold text-white">
                {{ isset($product) ? 'Edit: ' . $product->name : 'Create New Product' }}
            </h3>
        </div>

        {{-- Form --}}
        <div class="p-6">
            <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST">
                @csrf
                @if(isset($product)) @method('PUT') @endif

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                        <p class="font-bold text-red-700 mb-2 text-sm">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Name --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
                               class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm py-3 @error('name') border-red-400 @enderror">
                        @error('name') <p class="text-red-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- SKU --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">SKU <span class="text-red-500">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required
                               class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-mono font-bold shadow-sm py-3 @error('sku') border-red-400 @enderror">
                        @error('sku') <p class="text-red-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Category</label>
                        <select name="category_id" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm py-3">
                            <option value="">— No Category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Price (₹) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-bold">₹</span>
                            <input type="number" name="price" step="0.01" min="0"
                                   value="{{ old('price', isset($product) ? number_format($product->price / 100, 2) : '') }}"
                                   required class="w-full pl-8 rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-bold shadow-sm py-3 @error('price') border-red-400 @enderror">
                        </div>
                        @error('price') <p class="text-red-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Stock --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Stock Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}"
                               required class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-bold shadow-sm py-3 @error('stock') border-red-400 @enderror">
                        @error('stock') <p class="text-red-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Submit --}}
                <div class="mt-8 flex items-center gap-4">
                    <button type="submit"
                            class="flex-1 flex justify-center items-center bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3 px-6 rounded-xl transition-all shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ isset($product) ? 'Save Changes' : 'Create Product' }}
                    </button>
                    <a href="{{ route('admin.products.index') }}"
                       class="flex-none font-bold text-gray-500 hover:text-gray-800 px-5 py-3 rounded-xl hover:bg-gray-100 transition-colors text-sm">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
