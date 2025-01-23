<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No Penjualan</th>
            <th>Tanggal Penjualan</th>
            <th>Pelanggan</th>
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
            <td>{{ $p->total_cost }}</td>
            <td>{{ $p->status }}</td>
            <td>{{ $p->type_payment }}</td>
            <td>{{ $p->cash->name ?? 'No Data' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>