<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 80mm; /* Ukuran struk untuk printer thermal */
            margin: 0 auto;
            padding: 10px;
        }
        .header, .footer {
            text-align: center;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
        }
        .header p {
            font-size: 12px;
            margin: 5px 0;
        }
        .items {
            margin: 10px 0;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
        }
        .items th, .items td {
            border-bottom: 1px dashed #000;
            padding: 5px 0;
            text-align: left;
        }
        .total {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
    <script>
        // Print struk otomatis saat halaman dimuat
        window.onload = function() {
            window.print();
        };
    </script>
</head>
<body>
    <div class="header">
        <h1>Toko Pakaian XYZ</h1>
        <p>Jl. Contoh No. 123, Kota Contoh</p>
        <p>Telp: 0812-3456-7890</p>
    </div>
    <div class="items">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="total">
        <p>Total: Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
        <p>Pembayaran: Rp {{ number_format($paymentAmount, 0, ',', '.') }}</p>
        <p>Kembalian: Rp {{ number_format($change, 0, ',', '.') }}</p>
    </div>
    <div class="footer">
        <p>Terima kasih telah berbelanja!</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>