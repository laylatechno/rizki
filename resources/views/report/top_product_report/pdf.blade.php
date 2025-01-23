<!-- resources/views/report/top_product_report/pdf.blade.php -->
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        h1,
        h3 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>{{ $title }}</h1>
    <h3>{{ $subtitle }}</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Quantity</th>
                <th>Total Rupiah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProduct as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->product->name ?? 'N/A' }}</td>
                <td>{{ $product->product->category->name ?? 'N/A' }}</td>
                <td>{{ $product->total_quantity }}</td>
                <td>{{ number_format($product->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
