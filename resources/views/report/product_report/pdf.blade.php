<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <link rel="shortcut icon" type="image/png" href="/upload/profil/{{ $profil->favicon }}" />

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Laporan Penjualan</h2>
    <p style="text-align: center;">
        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Order</th>
                <th>Tanggal Order</th>
                <th>Customer Name</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Order Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($data_orders as $order)
            @foreach ($order->orderItems as $orderItem)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $order->no_order }}</td>
                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}</td>
                <td>{{ $order->customer->name }}</td>
                <td>{{ $orderItem->product->name }}</td>
                <td class="text-right">{{ $orderItem->quantity }}</td>
                <td class="text-right">Rp {{ number_format($orderItem->order_price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($orderItem->quantity * $orderItem->order_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-center fw-bold">Total</td>
                <td class="text-right fw-bold">{{ $totalQuantity }}</td>
                <td class="text-right fw-bold">Rp {{ number_format($totalOrderPrice, 0, ',', '.') }}</td>
                <td class="text-right fw-bold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>