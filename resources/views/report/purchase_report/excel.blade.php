<!-- Dalam file excel.blade.php dan pdf.blade.php -->
<thead>
    <tr>
        <!-- Kolom lama -->
        <th>No</th>
        <th>No Pembelian</th>
        <th>Tanggal Pembelian</th>
        <th>Supplier</th>
        <th>Pengguna</th>
        <th>Total</th>
        <th>Status</th>
        
        <!-- Kolom baru -->
        <th>Metode Pembayaran</th>
        <th>Kas/Bank</th>
    </tr>
</thead>
<tbody>
    @foreach ($data_purchases as $index => $p)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $p->no_purchase }}</td>
        <td>{{ \Carbon\Carbon::parse($p->purchase_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</td>
        <td>{{ $p->supplier->name ?? 'No Data' }}</td>
        <td>{{ $p->user->name }}</td>
        <td>Rp {{ number_format($p->total_cost, 0, ',', '.') }}</td>
        <td>{{ $p->status }}</td>
        
        <!-- Kolom baru -->
        <td>{{ $p->type_payment }}</td>
        <td>{{ $p->cash->name ?? 'No Data' }}</td>
    </tr>
    @endforeach
</tbody>