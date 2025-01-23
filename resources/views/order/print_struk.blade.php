<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="{{ asset('/upload/profil/' . ($profil->favicon ?: 'https://static1.squarespace.com/static/524883b7e4b03fcb7c64e24c/524bba63e4b0bf732ffc8bce/646fb10bc178c30b7c6a31f2/1712669811602/Squarespace+Favicon.jpg?format=1500w')) }}" />

    <title>{{ $title }}</title>
 
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            width: 80mm;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 5px;
        }

        .header h1 {
            font-size: 14px;
            margin: 5px 0;
        }

        .header p,
        .footer p {
            font-size: 10px;
            margin: 2px 0;
        }

        .invoice-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .invoice-details th,
        .invoice-details td {
            text-align: left;
            padding: 5px;
        }

        .invoice-details th {
            border-bottom: 1px dashed #000;
            font-size: 10px;
        }

        .invoice-details td {
            font-size: 10px;
        }

        .dashed-line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .additional-info {
            font-size: 9px;
            text-align: right;
            margin-top: 5px;
        }

        .total-cost {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }

        .print-btn {
            text-align: center;
            margin-top: 10px;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        @if($profil->nama_profil)
        <h1>{{ $profil->nama_profil }}</h1>
        @else
        <h1>Struk Penjualan</h1>
        @endif
        <p>{{ $profil->alamat }}</p>
        <p>No. Penjualan: {{ $order->no_order }}</p>
        <p>Tanggal: {{ \Carbon\Carbon::parse($order->order_date)->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</p>
        <p>Pelanggan: {{ $order->customer->name ?? 'Tidak Ada' }}</p>
        <p>Petugas: {{ $order->user->name }}</p>
    </div>

    <table class="invoice-details">
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->order_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="dashed-line"></div>

    <div class="additional-info">
        <p>Sub Total: Rp {{ number_format($order->total_cost_before, 0, ',', '.') }}</p>
        <p>Diskon: {{ $order->percent_discount }}%</p>
        <p>Diskon: Rp {{ number_format($order->amount_discount, 0, ',', '.') }}</p>
        <p>Bayar: Rp {{ number_format($order->input_payment, 0, ',', '.') }}</p>
        <p>Kembalian: Rp {{ number_format($order->return_payment, 0, ',', '.') }}</p>
    </div>

    <div class="total-cost">
        Total: Rp {{ number_format($order->total_cost, 0, ',', '.') }}
    </div>
    <br>
    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Kami tunggu kembali kehadirannya</p>
    </div>

    <div class="print-btn">
        <button onclick="window.print()">Cetak Struk</button>
    </div>
</body>

</html>