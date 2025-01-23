<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Quantity</th>
            <th>Order Price</th>
            <th>Total Price</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalOrderPrice = 0;
            $totalPrice = 0;
            $totalQuantity = 0;
            $no = 1;
        @endphp
        @foreach ($data_products as $order)
            @foreach ($order->products as $product)
                @php
                    $totalQuantity += $product->pivot->quantity;
                    $totalOrderPrice += $product->pivot->order_price;
                    $totalPrice += $product->pivot->quantity * $product->pivot->order_price;
                @endphp
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>{{ number_format($product->pivot->order_price, 0, ',', '.') }}</td>
                    <td>{{ number_format($product->pivot->quantity * $product->pivot->order_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" class="text-end">Jumlah Total:</th>
            <th>{{ $totalQuantity }}</th>
            <th>{{ number_format($totalOrderPrice, 0, ',', '.') }}</th>
            <th>{{ number_format($totalPrice, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>
