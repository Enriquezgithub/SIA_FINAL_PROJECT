<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // $prod = Product::get();
        $cat = Category::get();

        return inertia('Landing', [
            'category' => $cat,
            'product' => Product::with('category')->get()->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'category_id' => $product->category_id,
                    'category_name' => $product->category->name, // Accessing category name
                    'price' => $product->price,
                    'status' => $product->status,
                    'quantity' => $product->quantity,
                    'image' => asset('storage/' . $product->image)
                ];
            })
        ]);
    }
}
