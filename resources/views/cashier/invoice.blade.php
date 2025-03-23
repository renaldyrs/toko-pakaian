<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .invoice-print, .invoice-print * {
                visibility: visible;
            }
            .invoice-print {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="invoice-print bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-4">Invoice #{{ $transaction->invoice_number }}</h1>
            <div class="mb-4">
                <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
                <p><strong>Metode Pembayaran:</strong> {{ $transaction->paymentMethod->name }}</p>
            </div>
            <table class="min-w-full mb-4">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($transaction->details as $detail)
                    <tr>
                        <td class="px-6 py-4">{{ $detail->product->name }}</td>
                        <td class="px-6 py-4">{{ $detail->quantity }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
            <div class="text-center">
                <p>Terima kasih telah berbelanja!</p>
            </div>
        </div>
        <div class="mt-4">
            <button onclick="printInvoice()" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                <i class="fas fa-print"></i> Cetak Invoice
            </button>
            <a href="{{ route('cashier.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md">
                <i class="fas fa-arrow-left"></i> Kembali ke Kasir
            </a>
        </div>
    </div>

    <script>
        // Fungsi untuk mencetak invoice
        function printInvoice() {
            window.print();
        }

        // Otomatis cetak invoice setelah halaman dimuat
        window.onload = function() {
            printInvoice();
        };
    </script>
</body>
</html>