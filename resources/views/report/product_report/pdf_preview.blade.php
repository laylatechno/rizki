<!-- resources/views/report/product_report/pdf_preview.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Preview Laporan Penjualan Produk</title>
    <style>
        /* Customize your PDF styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Laporan Penjualan Produk</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>No Order</th>
                <th>Nama Produk</th>
                <th>Quantity</th>
                <th>Order Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalOrderPrice = 0;
            $totalPrice = 0;
            $totalQuantity = 0;
            $no = 1;
            @endphp
            @foreach ($data_products as $order)
                @foreach ($order->products as $product)
                    @php
                    $totalQuantity += $product->pivot->quantity;
                    $totalOrderPrice += $product->pivot->order_price;
                    $totalPrice += $product->pivot->quantity * $product->pivot->order_price;
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $order->order_date }}</td>
                        <td>{{ $order->no_order }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>{{ number_format($product->pivot->order_price, 0, ',', '.') }}</td>
                        <td>{{ number_format($product->pivot->quantity * $product->pivot->order_price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-end">Jumlah Total:</th>
                <th>{{ number_format($totalOrderPrice, 0, ',', '.') }}</th>
                <th>{{ number_format($totalPrice, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
