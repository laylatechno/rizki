<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="{{ asset('/upload/profil/' . ($profil->favicon ?: 'https://static1.squarespace.com/static/524883b7e4b03fcb7c64e24c/524bba63e4b0bf732ffc8bce/646fb10bc178c30b7c6a31f2/1712669811602/Squarespace+Favicon.jpg?format=1500w')) }}" />

    <title>Invoice Pembelian</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .invoice-details {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .invoice-details th,
        .invoice-details td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .invoice-details th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .total-cost {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
        }

        .status-lunas {
            background-color: #28a745;
        }

        .status-pending {
            background-color: #fd7e14;
        }

        .status-pesanan-pembelian {
            background-color: #5D87FF;
        }

        .status-belum-lunas {
            background-color: #dc3545;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
        }

        .print-btn {
            margin: 20px 0;
            text-align: center;
        }

        /* Media query untuk menyembunyikan tombol cetak saat di print */
        @media print {
            .print-btn {
                display: none;
            }

            body {
                margin: 0;
                padding: 10px;
            }

            .header h1 {
                font-size: 26px;
            }

            .invoice-details th,
            .invoice-details td {
                padding: 8px;
            }

            .total-cost {
                font-size: 16px;
            }
        }

        .header img {
            display: block;
            margin: 0 auto 15px;
            max-height: 100px;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <div class="header">
        @if($profil->logo)
        <img src="{{ asset('/upload/profil/' . $profil->logo) }}"
            alt="{{ $profil->nama_profil }}"
            style="max-width: 150px; margin-bottom: 15px;">
        @endif

        @if($profil->nama_profil)
        <h1>{{ $profil->nama_profil }}</h1>
        @else
        <h1>Invoice Pembelian</h1>
        @endif
        <h4>{{ $profil->alamat }}</h4>

        <p>No. Pembelian: {{ $purchase->no_purchase }}</p>
        <p>Tanggal Pembelian: {{ \Carbon\Carbon::parse($purchase->purchase_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
        <p>Supplier: {{ $purchase->supplier->name ?? 'No Data' }}</p>
        <p>Pengguna: {{ $purchase->user->name }}</p>


    </div>

    <table class="invoice-details">
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Harga Beli</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->purchaseItems as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->product->name }}</td>
                <td>Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="additional-info">
        <div class="payment-details">

            <p>
                <strong>Status Pembelian:</strong>

                @if ($purchase->status == 'Lunas')
                <span class="status-badge status-lunas">Lunas</span>
                @elseif ($purchase->status == 'Pending')
                <span class="status-badge status-pending">Pending</span>
                @elseif ($purchase->status == 'Pesanan Pembelian')
                <span class="status-badge status-pesanan-pembelian">Pesanan Pembelian</span>
                @else
                <span class="status-badge status-belum-lunas">Belum Lunas</span>
                @endif
            </p>
            <p>
                <strong>Jenis Pembayaran:</strong>
                @if ($purchase->type_payment == 'CASH')
                <span class="status-badge status-lunas">CASH</span>
                @else
                <span class="status-badge status-pending">TRANSFER</span>
                @endif
            </p>

            @if ($purchase->description)
            <p>
                Deskripsi {{ $purchase->description }}
            </p>
            @endif
        </div>
    </div>
    <div class="total-cost">
        <p>Total Pembelian: Rp {{ number_format($purchase->total_cost, 0, ',', '.') }}</p>
    </div>


    <div class="footer">
        <p>Terima kasih atas transaksi Anda!</p>
    </div>

    <div class="print-btn">
        <button onclick="window.print()">Cetak Invoice</button>
    </div>
</body>

</html>