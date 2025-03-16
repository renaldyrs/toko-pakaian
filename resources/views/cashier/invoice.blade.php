@extends('layouts.app')

@section('content')
<div class="struk">
        <!-- Header -->
        <div class="struk-header">
            <h1>Toko Pakaian Offline</h1>
            <p>Jl. Contoh No. 123, Kota Contoh</p>
            <p>Telp: 0812-3456-7890</p>
            <p>Invoice: #{{ $transaction->invoice_number }}</p>
            <p>Tanggal: {{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
        </div>

        <!-- Table -->
        <table class="struk-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->details as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total</td>
                    <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="struk-footer">
            <p>Terima kasih telah berbelanja!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.</p>
        </div>
    </div>

    <!-- Tombol Cetak -->
    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer;">
            <i class="fas fa-print"></i> Cetak Struk
        </button>
    </div>
@endsection

<style>
/* General Styles */
body {
    font-family: 'Courier New', Courier, monospace; /* Font mirip struk */
    font-size: 12px;
    margin: 0;
    padding: 10px;
    background-color: #fff;
}

.struk {
    width: 100%;
    max-width: 300px; /* Lebar struk */
    margin: 0 auto;
    padding: 10px;
    border: 1px solid #000;
    background-color: #fff;
}

/* Header Styles */
.struk-header {
    text-align: center;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px dashed #000;
}

.struk-header h1 {
    font-size: 16px;
    margin: 0;
    font-weight: bold;
}

.struk-header p {
    font-size: 12px;
    margin: 3px 0;
}

/* Table Styles */
.struk-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
}

.struk-table th,
.struk-table td {
    padding: 5px 0;
    text-align: left;
}

.struk-table th {
    font-weight: bold;
}

.struk-table td {
    border-bottom: 1px dashed #ccc;
}

.struk-table tfoot td {
    font-weight: bold;
    border-bottom: none;
}

/* Footer Styles */
.struk-footer {
    text-align: center;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed #000;
}

.struk-footer p {
    font-size: 12px;
    margin: 3px 0;
}

/* Print Styles */
@media print {
    body {
        margin: 0;
        padding: 0;
    }

    .struk {
        border: none;
        box-shadow: none;
        margin: 0;
        padding: 0;
    }
}

@media print {
    body {
        margin: 0;
        padding: 0;
    }

    .struk {
        border: none;
        box-shadow: none;
        margin: 0;
        padding: 0;
    }

    button {
        display: none; /* Sembunyikan tombol cetak saat dicetak */
    }
}
</style>

<script>
    window.onload = function() {
        window.print(); // Cetak otomatis saat halaman selesai dimuat
    };
</script>
