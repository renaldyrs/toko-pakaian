
    @extends('layouts.app')

    @section('content')
    <div class="container">
        <h1>Daftar Pesanan</h1>
        <table>
            <thead>
                <tr>
                    <th>No. Invoice</th>
                    <th>Tanggal</th>
                    <th>Metode Pembayaran</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->invoice_number }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $transaction->paymentMethod->name }}</td>
                    <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('cashier.invoice', $transaction->id) }}" class="btn">
                            <i class="fas fa-eye"></i> Lihat Invoice
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endsection
    
    <style>
        
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        table tr:hover {
            background-color: #f9f9f9;
        }
        .btn {
            display: inline-block;
            padding: 5px 10px;
            font-size: 14px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>