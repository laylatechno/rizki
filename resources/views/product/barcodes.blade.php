<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Barcode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 14px;
        }

        /* Tata letak grid */
        .barcode-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            /* Jarak antar item */
            justify-content: start;
        }

        .barcode-item {
            width: calc(16.66% - 10px);
            /* 6 kolom dengan jarak antar item */
            box-sizing: border-box;
            text-align: center;
            padding: 10px;
            border: solid 0.5px #ddd;
        }

        /* Ukuran barcode lebih kecil */
        .barcode-item img {
            width: 100%;
            /* Sesuaikan dengan ukuran kontainer */
            max-height: 50px;
            /* Maksimal tinggi barcode */
            object-fit: contain;
        }

        .barcode-item h5 {
            text-align: start;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .barcode-item small {
            text-align: start;
            display: block;
        }

        /* Untuk cetak */
        @media print {
            .no-print {
                display: none;
            }

            .barcode-container {
                gap: 5px;
                /* Jarak antar item lebih kecil saat cetak */
            }
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <!-- Tombol Print -->
        <div class="text-end mb-4 no-print">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fa fa-print"></i> Print
            </button>
        </div>

        <!-- Grid Barcode -->
        <div class="barcode-container">
            @foreach($products as $product)
            <div class="barcode-item">
                <h5>{{ Str::limit($product->name, 20) }}</h5>
                {!! $barcodeGenerator->getBarcode($product->code_product, $barcodeGenerator::TYPE_CODE_128) !!}
                <small>Kode: {{ $product->code_product }}</small>
            </div>
            @endforeach
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>