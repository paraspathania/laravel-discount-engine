@extends('layouts.admin')

@section('header', 'Products')

@section('content')

{{-- Top Bar: Filters + Add Button --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <form method="GET" class="flex flex-wrap items-center gap-3 flex-1">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or SKU…"
                   class="rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700 placeholder:text-slate-400 text-sm px-4 py-2.5 w-64 bg-white shadow-sm outline-none transition-all">
        </div>
        <div class="relative">
            <select name="category_id" class="rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700 text-sm px-4 py-2.5 bg-white shadow-sm outline-none transition-all appearance-none pr-10">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-5 py-2.5 rounded-xl text-sm transition-all shadow-md shadow-indigo-600/10 hover:shadow-indigo-600/20">
            Filter
        </button>
        @if(request()->hasAny(['search','category_id']))
            <a href="{{ route('admin.products.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold px-5 py-2.5 rounded-xl text-sm transition-colors">
                Clear
            </a>
        @endif
    </form>
    <a href="{{ route('admin.products.create') }}"
       class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-6 py-2.5 rounded-xl text-sm transition-all shadow-md shadow-emerald-600/15 hover:shadow-emerald-600/25 shrink-0 hover:-translate-y-0.5">
        <svg class="w-4.5 h-4.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        Add Product
    </a>
</div>

{{-- Products Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4.5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4.5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-4.5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-4.5 text-right text-xs font-extrabold text-slate-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4.5 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-4.5 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Discounts</th>
                    <th class="px-6 py-4.5 text-right text-xs font-extrabold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                    <tr class="hover:bg-slate-50/40 transition-colors group">
                        <td class="px-6 py-4.5 whitespace-nowrap text-xs font-mono font-extrabold text-slate-400 bg-slate-50/30 group-hover:bg-transparent transition-colors">{{ $product->sku }}</td>
                        <td class="px-6 py-4.5 whitespace-nowrap">
                            <span class="text-sm font-extrabold text-slate-800">{{ $product->name }}</span>
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-sm text-slate-500 font-bold">
                            @if($product->category)
                                <span class="bg-slate-100/80 text-slate-700 px-2.5 py-1 rounded-lg text-xs">
                                    {{ $product->category->name }}
                                </span>
                            @else
                                <span class="text-slate-400 font-normal">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-sm font-black text-slate-900 text-right">
                            ₹{{ number_format($product->price / 100, 2) }}
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-center">
                            @if($product->stock > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-extrabold bg-emerald-500/10 text-emerald-600 border border-emerald-500/10">
                                    {{ $product->stock }} In Stock
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-extrabold bg-rose-500/10 text-rose-600 border border-rose-500/10">
                                    Out of Stock
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-extrabold bg-indigo-50 text-indigo-600 border border-indigo-100/50">
                                {{ $product->discounts_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="inline-flex items-center text-xs font-extrabold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-2 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ addslashes($product->name) }}? This cannot be undone.')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center text-xs font-extrabold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-3 py-2 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-24 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <p class="text-slate-500 font-extrabold text-lg">No products found.</p>
                            <p class="text-slate-400 text-xs mt-1.5 font-medium">Add products to your catalog to apply discounts.</p>
                            <a href="{{ route('admin.products.create') }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-5 py-2.5 rounded-xl text-sm transition-all shadow-md shadow-indigo-600/10">+ Add your first product</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50">
            {{ $products->links() }}
        </div>
    @endif
</div>

@endsection
