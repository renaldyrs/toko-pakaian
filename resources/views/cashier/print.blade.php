<!DOCTYPE html>
<html>
<head>
    <title>Struk #{{ $transaction->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }
            body {
                font-family: 'Courier New', monospace;
                font-size: 12px;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
        .receipt {
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            padding: 10px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <div class="text-center mb-2">
            <h1 class="font-bold text-lg">{{ DB::table('store_profiles')->first()->name }}</h1>
            <img src="{{ DB::table('store_profiles')->first()->logo ? asset('storage/' . DB::table('store_profiles')->first()->logo) : asset('images/default-logo.png') }}"
                alt="User Avatar" class="w-20 h-20 rounded-full mx-auto">
            <p>{{ DB::table('store_profiles')->first()->address }}</p>
            <p>Telp: {{ DB::table('store_profiles')->first()->phone }}</p>
            
        </div>

        <div class="border-t border-b border-black py-2 my-2">
            <div class="flex justify-between">
                <span>No. Struk:</span>
                <span>{{ $transaction->invoice_number }}</span>
            </div>
            <div class="flex justify-between">
                <span>Tanggal:</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kasir:</span>
                <span>{{ Auth::user()->name }}</span>
            </div>
        </div>

        <div class="mb-4">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-black">
                        <th class="text-left">Item</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->details as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td class="text-right">{{ $detail->quantity }} x {{ number_format($detail->price) }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t border-black pt-2">
            <div class="flex justify-between font-bold">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($transaction->total_amount) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Pembayaran:</span>
                <span>{{ $transaction->paymentMethod->name }}</span>
            </div>
            <div class="flex justify-between font-bold">
                <span>Pembayaran:</span>
                <span>Rp {{ number_format($transaction->payment_amount) }}</span>
            </div>
            <div class="flex justify-between font-bold">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($transaction->change_amount) }}</span>
            </div>
        </div>

        <div class="text-center mt-4">
            <p>Terima kasih telah berbelanja</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
        </div>

        <div class="no-print text-center mt-6">
            <a href="{{ route('cashier.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mr-2">Tutup Jendela
            </a>
        </div>
    </div>

    <script>
        // Auto print jika dari halaman transaksi
        @if(request()->has('print'))
        window.onload = function() {
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    window.location.href = "{{ route('cashier.index') }}";
                }, 1000); // Redirect setelah 1 detik
            }, 500); // Delay print 0.5 detik
        };
        @endif
    </script>
</body>
</html>