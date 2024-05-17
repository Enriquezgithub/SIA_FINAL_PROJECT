<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProductController extends Controller
{
    public function index()
    {
        // $prod = Product::orderBy('name')->get();
        //'product' => $prod, 
        $cat = Category::get();

        return inertia('Product', [
            'category' => $cat,
            'product' => Product::with('category')->get()->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'category_id' => $product->category_id,
                    'category_name' => $product->category->name,
                    'price' => $product->price,
                    'status' => $product->status,
                    'quantity' => $product->quantity,
                    'image' => asset('storage/' . $product->image)
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category' => 'required',
            'price' => 'required',
            'status' => 'required',
            'quantity' => 'required',
            'image' => 'required'
        ]);

        $category = Category::where('name', $request->category)->first();
        $image = $request->file('image')->store('pictures', 'public');

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $category->id,
            'price' => $request->price,
            'status' => $request->status,
            'quantity' => $request->quantity,
            'image' => $image
        ]);

        $qrCode = QrCode::size(200)->generate($product->id);
        $product->update(['qrcode' => $qrCode]);

        return redirect('/products')->with('create', 'Product created successfully');
    }

    public function generateCSV()
    {
        $products = Product::orderBy('id')->with('category')->get();

        $filename = storage_path('app/public/product-list.csv');

        $file = fopen($filename, 'w+');

        fputcsv($file, ['ID', 'Name', 'Description', 'Category', 'Price', 'Status', 'Quantity', 'Image']);

        foreach ($products as $prod) {
            fputcsv($file, [
                $prod->id,
                $prod->name,
                $prod->description,
                $prod->category->name ?? 'N/A', // Handle case where category might be null
                $prod->price,
                $prod->status,
                $prod->quantity,
                asset('storage/' . $prod->image),
            ]);
        }

        fclose($file);

        return response()->download($filename);
    }

    public function show()
    {
        $prods = Product::orderBy('id')->get();
        $date = Carbon::now()->format('Y-m-d');

        $pdf = Pdf::loadView('product-list', compact('prods', 'date'));
        return $pdf->download('product-list.pdf');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category_name' => 'required',
            'price' => 'required',
            'status' => 'required',
            'quantity' => 'required',
            // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|required'
        ]);

        $category = Category::where('name', $request->category_name)->first();

        $fields = [
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $category->id,
            'price' => $request->price,
            'status' => $request->status,
            'quantity' => $request->quantity,
        ];

        if ($request->hasFile('image')) {
            Storage::delete('public/' . $product->image);
            $image = $request->file('image')->store('pictures', 'public');
            $fields['image'] = $image;
        }

        $product->update($fields);

        return redirect('/products')->with('update', 'Product updated successfully');
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return redirect('/products')->with('delete', 'Product deleted successfully');
    }

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required',
    //     ]);

    //     $file = $request->file('file');
    //     $fileData = file_get_contents($file);
    //     $rows = array_map('str_getcsv', explode("\n", $fileData));

    //     foreach ($rows as $row) {
    //         Product::create([
    //             'name' => $row[0],
    //             'description' => $row[1],
    //             'category_id' => $row[2],
    //             'price' => $row[3],
    //             'status' => $row[4],
    //             'quantity' => $row[5],
    //             'image' => $row[6]
    //         ]);
    //     }

    //     return redirect('/customers')->with('create', 'Customers imported successfully!');
    // }
}
