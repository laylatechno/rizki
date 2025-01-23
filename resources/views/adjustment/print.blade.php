<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Adjustment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h3, h4 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section p {
            margin: 5px 0;
        }

        .info-section span {
            font-weight: bold;
        }

        .image-section img {
            max-width: 200px;
            height: auto;
            display: block;
            margin-top: 10px;
        }

        @media print {
            /* Menghilangkan margin untuk cetakan */
            body {
                margin: 0;
                padding: 0;
            }

            /* Tidak mencetak tombol */
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <h3>Adjustment Detail</h3>
    <h4>Nomor: {{ $data_adjustment->adjustment_number }}</h4>

    <div class="info-section">
        <p><span>Tanggal Adjustment:</span> {{ \Carbon\Carbon::parse($data_adjustment->adjustment_date)->format('d M Y') }}</p>
        <p><span>Deskripsi:</span> {{ $data_adjustment->description ?? '-' }}</p>
        <p><span>Total Adjustment:</span> {{ number_format($data_adjustment->total, 0, ',', '.') }}</p>
    </div>

    @if ($data_adjustment->image)
    <div class="image-section">
        <p><span>Gambar Bukti:</span></p>
        <img src="{{ asset('upload/adjustments/' . $data_adjustment->image) }}" alt="Adjustment Image">
    </div>
    @endif

    <h4>Detail Produk</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Alasan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_adjustment->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ $detail->reason }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button class="no-print" onclick="window.print()">Cetak</button>
</body>

</html>
