<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\DiscountService;

class HomeController extends Controller
{
    /**
     * Display the Home Page
     * GET /
     */
    public function index(DiscountService $discountService)
    {
        // Get up to 3 active promotions for the Featured block
        $offers = $discountService->getActiveDiscounts()->take(3);
        
        // Get 6 featured products (latest by default)
        $products = Product::with('category')->latest()->take(6)->get();
        
        return view('home', compact('offers', 'products'));
    }
}
