<!DOCTYPE html>
<html>
<head>
    <title>Cetak Barcode Produk</title>
    <style>
        @page {
            size: 33mm 25mm; /* Ukuran kertas thermal */
            margin: 2mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            
        }
        .barcode-sheet {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 kolom */
            width: 33mm;
            gap: 2mm;
            width: 100%;
        }
        .barcode-label {
            border: 1px dashed #ccc;
            padding: 1mm;
            text-align: center;
            page-break-inside: avoid;
        }
        .product-name {
            font-weight: bold;
            margin-bottom: 1mm;
            word-break: break-word;
        }
        .barcode {
            margin: 1mm 0;
            height: 15mm;
        }
        .product-price {
            font-weight: bold;
            color: #d00;
        }
        .no-print {
            display: none;
        }
        @media screen {
            body {
                padding: 10px;
                background: #f5f5f5;
            }
            .no-print {
                display: block;
                margin-bottom: 10px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding:5px 10px;background:#4CAF50;color:white;border:none;cursor:pointer;">
            🖨️ Cetak Barcode
        </button>
        <a href="{{ route('products.index') }}" style="padding:5px 10px;background:#f44336;color:white;text-decoration:none;">
            Kembali
        </a>
        <p style="margin-top:5px;">Total produk: {{ $products->count() }} (akan mencetak {{ $products->sum('stock') }} label)</p>
    </div>

    <div class="barcode-sheet">
        @foreach($products as $product)
            @for($i = 0; $i < $product->stock; $i++)
                <div class="barcode-label">
                    <div class="product-name">{{ Str::limit($product->name, 20) }}</div>
                    <svg class="barcode"
                        jsbarcode-value="{{ $product->code }}"
                        jsbarcode-height="30"
                        jsbarcode-displayValue="false"
                        jsbarcode-margin="0">
                    </svg>
                    <div class="product-price">Rp {{ number_format($product->price,0) }}</div>
                    <div class="product-code">{{ $product->code }}</div>
                </div>
            @endfor
        @endforeach
    </div>

    <script>
        // Generate semua barcode
        window.onload = function() {
            JsBarcode(".barcode").init();
            
            @if(request()->has('auto_print'))
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    window.location.href = "{{ route('products.index') }}?printed=true";
                }, 1000);
            }, 500);
            @endif
        };
    </script>
</body>
</html>