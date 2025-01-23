<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Quantity</th>
            <th>Total Rupiah</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($topProduct as $index => $product)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $product->product->name ?? 'N/A' }}</td>
            <td>{{ $product->product->category->name ?? 'N/A' }}</td>
            <td>{{ $product->total_quantity }}</td>
            <td>{{ $product->total_price }}</td> 
        </tr>
        @endforeach
    </tbody>
</table>
