<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $cat = Category::get();

        return inertia('Category', [
            'category' => $cat
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        Category::create($request->all());

        return redirect('/categories')->with('create', 'Category created successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect('/categories')->with('delete', 'Category deleted successfully');
    }
}
