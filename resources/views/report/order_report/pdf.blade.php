<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="shortcut icon" type="image/png" href="/upload/profil/{{ $profil->favicon }}" />

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h3 class="title">{{ $title }}</h3>
    <p>Periode: {{ $startDate }} - {{ $endDate }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Penjualan</th>
                <th>Tanggal Penjualan</th>
                <th>Supplier</th>
                <th>Pengguna</th>
                <th>Total</th>
                <th>Status</th>
                <th>Metode Pembayaran</th>
                <th>Kas/Bank</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_orders as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->no_order }}</td>
                <td>{{ \Carbon\Carbon::parse($p->order_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</td>
                <td>{{ $p->customer->name ?? 'No Data' }}</td>
                <td>{{ $p->user->name }}</td>
                <td>Rp {{ number_format($p->total_cost, 0, ',', '.') }}</td>
                <td>{{ $p->status }}</td>
                <td>{{ $p->type_payment }}</td>
                <td>{{ $p->cash->name ?? 'No Data' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>