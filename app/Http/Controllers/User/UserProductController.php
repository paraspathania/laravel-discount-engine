<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class UserProductController extends Controller
{
    /**
     * Product Listing with Filters
     * GET /products
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Preserve query string for pagination links
        $products = $query->paginate(12)->withQueryString();
        
        $categories = Category::orderBy('name')->get();
        
        return view('user.products.index', compact('products', 'categories'));
    }

    /**
     * Single Product Detail View
     * GET /products/{product}
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('user.products.show', compact('product'));
    }
}
