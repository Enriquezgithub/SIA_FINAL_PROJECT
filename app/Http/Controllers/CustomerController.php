<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $cus = Customer::all();

        return inertia('Customer', [
            'customer' => $cus
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        Customer::create($request->all());

        return redirect('/customers')->with('create', 'Customer created successfully');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        $file = $request->file('file');
        $fileData = file_get_contents($file);
        $rows = array_map('str_getcsv', explode("\n", $fileData));

        foreach ($rows as $row) {
            Customer::create([
                'name' => $row[0],
                'address' => $row[1],
                'phone' => $row[2],
                'email' => $row[3]
            ]);
        }

        return redirect('/customers')->with('create', 'Customers imported successfully!');
    }

    public function export()
    {
        $products = Customer::orderBy('id')->get();

        $filename = storage_path('app/public/customer.csv');

        $file = fopen($filename, 'w+');

        fputcsv($file, ['ID', 'Name', 'Address', 'Phone', 'Email']);

        foreach ($products as $prod) {
            fputcsv($file, [
                $prod->id,
                $prod->name,
                $prod->address,
                $prod->phone,
                $prod->email,
            ]);
        }

        fclose($file);

        return response()->download($filename);
    }
}
