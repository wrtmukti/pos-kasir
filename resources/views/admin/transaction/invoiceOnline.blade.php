<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        .container {
            width: 300px;
        }
        .header {
            margin: 0;
            text-align: center;
        }
        h2, p {
            margin: 0;
        }
        .flex-container-1 {
            display: flex;
            margin-top: 10px;
        }
        .flex-container-1 > div {
            text-align : left;
        }
        .flex-container-1 .right {
            text-align : right;
            width: 200px;
        }
        .flex-container-1 .left {
            width: 100px;
        }
        .flex-container {
            width: 300px;
            display: flex;
        }
        .flex-container > div {
            -ms-flex: 1;  /* IE 10 */
            flex: 1;
        }
        ul {
            display: contents;
        }
        ul li {
            display: block;
        }
        hr {
            border-style: dashed;
        }
        a {
            text-decoration: none;
            text-align: center;
            padding: 10px;
            background: #00e676;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }
        s {
            color: #888;
            /* font-size: 13px; */
        }
        strong {
            /* color: #e53935; */
            /* font-weight: bold; */
        }
    </style>
</head>
<body>
  
    <div class="container">
      <div class="header" style="margin-bottom: 30px;">
        <img src="{{ asset('images/logo_bw.png') }}" alt="" width="100px">
            <h2>BAGASKARA</h2>
            {{-- <small>PAPAJI PURI PERMAI</small> --}}
        </div>
        <hr>
        <div class="flex-container-1">
            <div class="left">
                <ul>
                    <li>Struk</li>
                    <li>Kasir</li>
                    <li>Tanggal</li>
                </ul>
            </div>
            <div class="right">
                <ul>
                    <li>{{ $transaction->id }}-{{ $transaction->created_at->format("Ymd") }}</li>
                    <li>{{ Auth::user()->name }}</li>
                    <li>{{ $transaction->created_at->format("d/m/Y  H:i") }}</li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="flex-container" style="margin-bottom: 10px; text-align:right;">
            <div style="text-align: left;">Nama Product</div>
            <div>Harga</div>
            <div>Total</div>
        </div>

        @foreach ($orders as $order)
        <div style="text-align: left; margin-top: 5px;">Pesanan ke-{{ $loop->iteration }}</div>
          @foreach ($order->products as $item)
            @php
                // Ambil diskon aktif terbaru (jika ada)
                $activeDiscount = $item->diskons->first(); // karena query sudah limit(1)
                $discountedPrice = $item->price;

                if ($activeDiscount) {
                    if ($activeDiscount->type_diskon == '0') {
                        $discountedPrice = $item->price - ($item->price * $activeDiscount->value / 100);
                    } elseif ($activeDiscount->type_diskon == '1') {
                        $discountedPrice = $item->price - $activeDiscount->value;
                    }
                }
            @endphp

            <div class="flex-container" style="text-align: right; margin-bottom: 5px;">
                <div style="text-align: left;">
                    {{ $item->pivot->quantity }}x {{ $item->name }}
                </div>
                <div>
                    @if ($activeDiscount)
                        <div>
                            <s>{{ number_format($item->price) }}</s><br>
                            <p>{{ number_format($discountedPrice) }}</p>
                        </div>
                    @else
                        {{ number_format($item->price) }}
                    @endif
                </div>
                <div>
                    @if ($activeDiscount)
                        <div>
                            {{-- <s>{{ number_format($item->pivot->quantity * $item->price) }}</s><br> --}}
                            <p>{{ number_format($item->pivot->quantity * $discountedPrice) }}</p>
                        </div>
                    @else
                        {{ number_format($item->pivot->quantity * $item->price) }}
                    @endif
                </div>
            </div>
          @endforeach 
            @if($order->voucher_id !== null)
             <div class="flex-container" style="text-align: right; margin-bottom: 5px;">
                <div style="text-align: left;">
                    {{ $order->voucher->name }}
                </div>
                <div>
                    @if ($order->voucher->type == 0)
                        <div>
                            <p>- {{ $order->voucher->value }}%</p>
                        </div>
                    @else
                        <div>
                            <p>- {{ $order->voucher->value }}</p>
                        </div>
                    @endif
                </div>
                <div>
                    @if ($order->voucher->type == 0)
                        <div>
                            <p>- {{ number_format($order->price * $order->voucher->value/100) }}</p>
                        </div>
                    @else
                        <div>
                            <p>- {{ number_format($order->voucher->value) }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        @endforeach
        
        <hr>
        <div class="flex-container" style="text-align: right; margin-top: 10px;">
            <div></div>
            <div>
                <ul>
                    <li>Total</li>
                    <li>Bayar Tunai</li>
                    <li>Bayar Debit</li>
                    <li>Kembalian</li>
                </ul>
            </div>
            <div style="text-align: right;">
                <ul>
                    <li>{{ number_format($transaction->total_price) }}</li>
                    <li>{{ number_format($transaction->cash + $transaction->kembalian) }}</li>
                    <li>{{ number_format($transaction->debit) }}</li>
                    <li>{{ number_format($transaction->kembalian) }}</li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="header" style="margin-top: 50px;">
            <h3>Terimakasih</h3>
            <p>Silahkan berkunjung kembali</p>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
