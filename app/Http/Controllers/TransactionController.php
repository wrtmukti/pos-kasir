<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\SalesSummary;
use App\Models\Transaction;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\type;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
        return view('admin.transaction.index', compact('transactions'));
    }
    public function payment(Request  $request)
    {
        // dd($request->all());
        if ($request->payment_method == 'cash') {
            $cash = $request->value;
            $debit = 0;
            $kembalian = $request->kembalian;
        } else {
            $cash = 0;
            $debit = $request->value;
            $kembalian = 0;
        }

        $transaction = Transaction::create([
            'payment_status' => 0,
            'total_price' => $request->total_price,
            'cash' => $cash - $request->kembalian,
            'debit' => $debit,
            'kembalian' => $kembalian,
            'note' => "Produk Terjual",
        ]);
        $totalRevenue = 0;

        if ($request->order_type == 1) {
            $order = Order::findOrFail($request->order_id);
            // dd($request->all(),$order);
            $order->update([
                'status' => 3,
                'transaction_id' => $transaction->id,
            ]);
            //deva
            $voucherDiscount = 0;

            // $totalRevenue = 0;

            // $order->update(['status' => 3, 'transaction_id' => $transaction->id]);
            // Jika ada voucher_id, ambil voucher persentase global
            if ($order->voucher_id) {
                $voucher = Voucher::find($order->voucher_id);
                if ($voucher && $voucher->voucher_type == '0') {
                    // dd($voucher->value);
                    $voucherDiscount = $voucher->value; // nilai dalam persen
                }
                // dd($voucher,$voucher->voucher_type,"if tidak bekerja");
            }
            foreach ($order->products as $product) {
                // dd($order->products);
                $activeDiscount = $product->diskons->first();
                $originalPrice = $product->price;
                $discountedPrice = $originalPrice;

                if ($activeDiscount) {
                    if ($activeDiscount->type_diskon == 0) {
                        $discountedPrice -= ($originalPrice * $activeDiscount->value / 100);
                    } elseif ($activeDiscount->type_diskon == 1) {
                        $discountedPrice -= $activeDiscount->value;
                    }
                }

                if ($voucherDiscount > 0) {
                    $discountedPrice -= ($discountedPrice * $voucherDiscount / 100);
                }

                $discountedPrice = max($discountedPrice, 0);

                $subtotal = $discountedPrice * $product->pivot->quantity;
                $totalRevenue += $subtotal;

                $sales = SalesSummary::create([
                    'transaction_date'     => now()->toDateString(),
                    'transaction_id'       => $transaction->id,
                    'order_id'             => $order->id,
                    'product_id'           => $product->id,
                    'category_id'          => $product->category_id,
                    'quantity_sold'        => $product->pivot->quantity,
                    'unit_price'           => $originalPrice,
                    'subtotal'             => $originalPrice * $product->pivot->quantity,
                    'discount_id' => $activeDiscount ? $activeDiscount->id : null,
                    'discount_amount'      => $activeDiscount
                        ? ($activeDiscount->type_diskon == 0
                            ? $originalPrice * $activeDiscount->value / 100
                            : $activeDiscount->value)
                        : 0,
                    'price_after_discount' => $discountedPrice,
                    'voucher_id'           => $order->voucher_id,
                    'voucher_percent'      => $voucherDiscount,
                    'voucher_applied'      => $order->voucher_id ? 1 : 0,
                    'total_revenue'        => $subtotal,
                    'payment_method'       => $this->getPaymentMethod($transaction),
                ]);
                $sales->refresh();
                // dd($sales, "dd asat sales sumaary", $subtotal, $discountedPrice, "akhir", $originalPrice, "total revenue : ". $totalRevenue);
            }

            //deva end

            return redirect()->to('admin/order/manual/' . $request->order_id);
        } else {
            $orders = Order::where('customer_id', $request->customer_id)
                ->where('status', 2)
                ->with(['products.diskons' => function ($query) {
                    $query->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->where('status', 'active')
                        ->orderBy('created_at', 'desc')
                        ->limit(1);
                }])
                ->get();



            foreach ($orders as $order) {
                $voucherDiscount = 0;

                $order->update(['status' => 3, 'transaction_id' => $transaction->id]);
                // Jika ada voucher_id, ambil voucher persentase global
                if ($order->voucher_id) {
                    $voucher = Voucher::find($order->voucher_id);
                    if ($voucher && $voucher->voucher_type == '0') {
                        // dd($voucher->value);
                        $voucherDiscount = $voucher->value; // nilai dalam persen
                    }
                    // dd($voucher,$voucher->voucher_type,"if tidak bekerja");
                }
                foreach ($order->products as $product) {
                    $activeDiscount = $product->diskons->first();
                    $originalPrice = $product->price;
                    $discountedPrice = $originalPrice;

                    if ($activeDiscount) {
                        if ($activeDiscount->type_diskon == 0) {
                            $discountedPrice -= ($originalPrice * $activeDiscount->value / 100);
                        } elseif ($activeDiscount->type_diskon == 1) {
                            $discountedPrice -= $activeDiscount->value;
                        }
                    }

                    if ($voucherDiscount > 0) {
                        $discountedPrice -= ($discountedPrice * $voucherDiscount / 100);
                    }

                    $discountedPrice = max($discountedPrice, 0);

                    $subtotal = $discountedPrice * $product->pivot->quantity;
                    $totalRevenue += $subtotal;

                    $sales = SalesSummary::create([
                        'transaction_date'     => now()->toDateString(),
                        'transaction_id'       => $transaction->id,
                        'order_id'             => $order->id,
                        'product_id'           => $product->id,
                        'category_id'          => $product->category_id,
                        'quantity_sold'        => $product->pivot->quantity,
                        'unit_price'           => $originalPrice,
                        'subtotal'             => $originalPrice * $product->pivot->quantity,
                        'discount_id' => $activeDiscount ? $activeDiscount->id : null,
                        'discount_amount'      => $activeDiscount
                            ? ($activeDiscount->type_diskon == 0
                                ? $originalPrice * $activeDiscount->value / 100
                                : $activeDiscount->value)
                            : 0,
                        'price_after_discount' => $discountedPrice,
                        'voucher_id'           => $order->voucher_id,
                        'voucher_percent'      => $voucherDiscount,
                        'voucher_applied'      => $order->voucher_id ? 1 : 0,
                        'total_revenue'        => $subtotal,
                        'payment_method'       => $this->getPaymentMethod($transaction),
                    ]);
                }
                $sales->refresh();
                // dd($sales, "dd asat sales sumaary", $subtotal, $discountedPrice, "akhir", $originalPrice, "total revenue : ". $totalRevenue);


            }
            //deva end
            return redirect()->to('admin/order/online/' . $request->customer_id);
        }
    }

    //deva new function

    private function getPaymentMethod($transaction)
    {
        if ($transaction->cash > 0) return 'cash';
        if ($transaction->debit > 0) return 'card';
        return 'qris';
    }

    //end deva

    public function invoiceOnline($id)
    {
        $transaction = Transaction::find($id);

        $orders = Order::with([
            'products' => function ($productQuery) {
                $productQuery->where('status', '1')
                    ->with(['diskons' => function ($discountQuery) {
                        $discountQuery->where('start_date', '<=', now())
                            ->where('end_date', '>=', now())
                            ->where('status', 'active')
                            ->orderBy('created_at', 'desc')
                            ->limit(1);
                    }]);
            }
        ])->where('transaction_id', $id)->get();
        return view('admin.transaction.invoiceOnline', compact('transaction', 'orders'));
    }
    public function invoiceManual($id)
    {
        $transaction = Transaction::find($id);
        $order = Order::with('products')->where('transaction_id', $id)->first();
        return view('admin.transaction.invoiceManual', compact('transaction', 'order'));
    }
    public function summary($id)
    {

        if ($id == 1) {
            $transactions = Transaction::orderBy('created_at',  'desc')->get()->groupBy(function ($item) {
                return $item->created_at->format('d-m-Y');
            });
        } else {
            $transactions = Transaction::orderBy('created_at',  'desc')->get()->groupBy(function ($item) {
                return $item->created_at->format('m-Y');
            });
        }

        return view('admin.transaction.summary', compact('transactions', 'id'));
    }
    public function summaryShow($date)
    {
        if (strlen($date) > 7) {
            $transactions = Transaction::where(DB::raw("(DATE_FORMAT(created_at,'%d-%m-%Y'))"), '=', $date)->orderBy('created_at',  'asc')->get();
        } else {
            $transactions = Transaction::where(DB::raw("(DATE_FORMAT(created_at,'%m-%Y'))"), '=', $date)->orderBy('created_at',  'asc')->get();
        }

        return view('admin.transaction.summaryShow', compact('transactions', 'date'));
    }

    public function create()
    {
        return view('admin.transaction.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'payment_status' => 'required',
        ]);

        if ($request->payment_method == 'cash') {
            $cash = $request->value;
            $debit = 0;
        } else {
            $cash = 0;
            $debit = $request->value;
        }


        Transaction::create([
            'payment_status' => $request->payment_status,
            'cash' => $cash,
            'debit' => $debit,
            'total_price' => $cash + $debit,
            'kembalian' => '0',
            'note' => $request->note,
        ]);

        return redirect()->to('admin/transaction');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('admin.transaction.show', compact('transaction'));
    }

    public function sale($id)
    {
        if ($id == 0) {
            $products = Product::whereHas('orders')->get()->groupBy(function ($item) {
                return $item->created_at->format('d-m-Y');
            });;
        } else {
            $products = Product::whereHas('orders')->get()->groupBy(function ($item) {
                return $item->created_at->format('m-Y');
            });;
        }

        // dd($products);
        return view('admin.transaction.sale', compact('products'));
    }

    public function saleShow($date)
    {
        if (strlen($date) > 7) {
            $products = Product::whereHas('orders')->where(DB::raw("(DATE_FORMAT(created_at,'%d-%m-%Y'))"), '=', $date)->orderBy('created_at',  'desc')->get();
        } else {
            $products = Product::whereHas('orders')->where(DB::raw("(DATE_FORMAT(created_at,'%m-%Y'))"), '=', $date)->orderBy('created_at',  'desc')->get();
        }
        return view('admin.transaction.saleShow', compact('products', 'date'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function operatorDestroy($id)
    {
        Transaction::destroy($id);
        return redirect()->to('/admin/transaction')->with('danger', 'transaksi dihapus:(');
    }
}
