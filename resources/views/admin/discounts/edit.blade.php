@extends('layouts.admin')

@section('header', 'Edit Discount: ' . ($discount->name ?? 'Update'))

@section('content')
    <form action="{{ route('admin.discounts.update', $discount ?? 0) }}" method="POST" x-data="{ type: '{{ $discount->type ?? 'percentage' }}' }" class="max-w-4xl">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-8 py-5 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-black flex items-center justify-center mr-3">1</span>
                    <h3 class="text-lg font-extrabold text-gray-900">Basic Information</h3>
                </div>
                <div class="flex items-center">
                    <span class="mr-3 text-sm font-bold text-gray-700">Status:</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ ($discount->is_active ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Discount Name</label>
                    <input type="text" name="name" value="{{ $discount->name ?? '' }}" required class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm py-3">
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
                        <input type="number" name="value" value="{{ ($discount->value ?? 0) / 100 }}" required min="1" step="0.01" class="w-full pl-10 rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-bold shadow-sm py-3" :max="type === 'percentage' ? 100 : ''">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 pb-12">
            <a href="{{ route('admin.discounts.index') }}" class="px-8 py-4 text-gray-700 font-bold hover:bg-gray-100 rounded-xl transition-colors">Cancel</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-10 py-4 rounded-xl shadow-lg transition-all hover:-translate-y-0.5">
                Update Discount
            </button>
        </div>
    </form>
@endsection
