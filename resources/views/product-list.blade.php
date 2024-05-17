<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Download Pdf</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
        }
        th {
            font-size: 1.3em;
        }
        .list-name {
            text-align: center;
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .total {
            font-weight: bold;
        }
        .category {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 1.2em;
        }
        .date{
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="list-name">
        <h1>Product List</h1>
    </div>
    <div class="date">
        Date {{ $date }}
    </div>
    <table>
        <thead>
            <tr>
                <th>Id #</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPrice = 0;   
            @endphp
            @foreach ($prods as $product)
                <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->description}}</td>
                    <td>{{$product->category->name}}</td>
                    <td>{{$product->price}}</td>
                </tr>
                @php
                    $totalPrice += $product->price;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="4">Total Price:</td>
                <td>{{$totalPrice}}</td>
            </tr>
        </tfoot>
    </table>
    
</body>
</html>