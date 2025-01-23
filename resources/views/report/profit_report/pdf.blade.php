<!-- resources/views/report/profit_report/pdf.blade.php -->
<html>

<head>
    <!-- Favicon icon-->
    <title>{{ $title }}</title>
    <link rel="shortcut icon" type="image/png" href="/upload/profil/{{ $profil->favicon }}" />
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

        .total {
            text-align: right;
        }

        .text-success {
            color: green;
        }

        .text-danger {
            color: red;
        }

        .text-primary {
            color: blue;
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
                <th>Tanggal Transaksi</th>
                <th>Kas</th>
                <th>Transaksi</th>
                <th>Jumlah Tambah</th>
                <th>Jumlah Kurang</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalTambah = 0;
            $totalKurang = 0;
            @endphp
            @foreach($profitLoss as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($entry->date)->format('d/m/Y') }}</td>
                <td>{{ $entry->cash->name ?? 'N/A' }}</td>
                <td>
                    @if($entry->transaction_id)
                    {{ $entry->transaction->name ?? 'N/A' }}
                    @elseif($entry->order_id)
                    Penjualan - {{ $entry->order->no_order ?? 'N/A' }}
                    @elseif($entry->purchase_id)
                    Pembelian - {{ $entry->purchase->no_purchase ?? 'N/A' }}
                    @else
                    'N/A'
                    @endif
                </td>
                <td>
                    @if($entry->category === 'tambah')
                    @php
                    $totalTambah += $entry->amount;
                    @endphp
                    {{ number_format($entry->amount, 0, ',', '.') }}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if($entry->category === 'kurang')
                    @php
                    $totalKurang += $entry->amount;
                    @endphp
                    {{ number_format($entry->amount, 0, ',', '.') }}
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Total:</th>
                <th class="text-success">{{ number_format($totalTambah, 0, ',', '.') }}</th>
                <th class="text-danger">{{ number_format($totalKurang, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="4">Selisih:</th>
                <th colspan="2" class="text-primary">
                    {{ number_format($totalTambah - $totalKurang, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>
</body>

</html>