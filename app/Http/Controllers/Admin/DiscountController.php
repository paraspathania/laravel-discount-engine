<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::withSum('usages', 'saved_amount')
            ->latest()
            ->paginate(15);
            
        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        // We reuse the single form.blade.php view for both create and edit
        return view('admin.discounts.form', compact('products', 'categories'));
    }

    public function store(StoreDiscountRequest $request)
    {
        $data = $request->validated();
        
        $discount = Discount::create($data);
        
        if (!empty($data['product_ids'])) {
            $discount->qualifiableProducts()->sync($data['product_ids']);
        }
        if (!empty($data['category_ids'])) {
            $discount->qualifiableCategories()->sync($data['category_ids']);
        }
        
        return redirect()->route('admin.discounts.index')->with('success', 'Discount created successfully.');
    }

    public function show(Discount $discount)
    {
        // View usage metrics for a specific discount
        $discount->loadSum('usages', 'saved_amount');
        return view('admin.discounts.show', compact('discount'));
    }

    public function edit(Discount $discount)
    {
        $products = Product::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        return view('admin.discounts.form', compact('discount', 'products', 'categories'));
    }

    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        $data = $request->validated();
        
        $discount->update($data);
        
        // Sync qualifiers. Empty array gracefully detaches all if none selected.
        $discount->qualifiableProducts()->sync($data['product_ids'] ?? []);
        $discount->qualifiableCategories()->sync($data['category_ids'] ?? []);
        
        return redirect()->route('admin.discounts.index')->with('success', 'Discount updated successfully.');
    }

    public function destroy(Discount $discount)
    {
        // In a real app we might soft-delete to preserve foreign key integrity on usages.
        // For this demo, we can just delete it (assuming DB cascades or nullable FKs).
        $discount->delete();
        
        return redirect()->route('admin.discounts.index')->with('success', 'Discount deleted successfully.');
    }
}
