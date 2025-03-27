<!-- resources/views/returns/receipt.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Struk Retur #{{ $return->return_number }}</title>
    <style>
        @page { size: 58mm auto; margin: 0; }
        body { font-family: Arial; width: 58mm; padding: 5px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 5px; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        .items td { padding: 3px 0; }
        .text-right { text-align: right; }
        .footer { text-align: center; margin-top: 10px; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div style="font-weight:bold;">RETUR BARANG</div>
        <div>{{ config('app.name') }}</div>
    </div>
    
    <div class="divider"></div>
    
    <div>
        <div>No Retur: {{ $return->return_number }}</div>
        <div>Tanggal: {{ $return->created_at->format('d/m/Y H:i') }}</div>
        <div>No Transaksi: {{ $return->transaction->invoice_number }}</div>
        <div>Kasir: {{ $return->user->name }}</div>
    </div>
    
    <div class="divider"></div>
    
    <table class="items">
        <tbody>
            @foreach($return->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td class="text-right">{{ $item->quantity }}x</td>
                <td class="text-right">{{ number_format($item->price, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="divider"></div>
    
    <div style="text-align:right; font-weight:bold;">
        TOTAL RETUR: Rp {{ number_format($return->total_refund, 0) }}
    </div>
    
    <div class="divider"></div>
    
    <div>Alasan: {{ $return->reason }}</div>
    
    <div class="footer">
        <div>Terima kasih</div>
        <div>{{ date('d/m/Y H:i:s') }}</div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    window.location.href = "{{ route('returns.index') }}";
                }, 1000);
            }, 500);
        };
    </script>
</body>
</html>