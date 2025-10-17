<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function online()
    {
        $orders = Order::where('customer_id', '>', 0)->orderBy('created_at', 'desc')->where('status', '!=', 4)->get()->groupBy('customer_id');
        return view('admin.order.online', compact('orders'));
    }
    public function manual()
    {
        $orders = Order::where('customer_id', null)->orderBy('created_at', 'desc')->get();
        return view('admin.order.manual', compact('orders'));
    }

    public function onlineShow($id)
    {
        $orders = Order::with('voucher')->where('customer_id', $id)->where('status', '!=', 4)->get();
        $customer = Customer::where('id', $id)->first();
        if ($orders->where('status', 3)->count() < 1) {
            return view('admin.order.onlineShow', compact('orders', 'customer'));
        } else {
            $transaction = Order::where('customer_id', $id)->where('status', 3)->first();
            $transaction_id = $transaction->transaction_id;
            return view('admin.order.onlineShow', compact('orders', 'customer', 'transaction_id'));
        }
    }

    public function manualShow($id)
    {
        $order = Order::where('id', $id)->first();

        return view('admin.order.manualShow', compact('order'));
    }

    public function create()
    {
        $categories = Category::orderBy('category_name', 'asc')->get();
        $products = Product::with(['diskons' => function ($q) {
            $q->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now());
        }])->get();
        return view('admin.order.create', compact('products', 'categories'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $products = $request->input('product_id', []);
        $amounts = $request->input('amount', []);
        if ($request->status == 1) {
            if ($request->voucher_id) {
                $voucher_id = $request->voucher_id;
            } else {
                $voucher_id = null;
            }
            $order = Order::create([
                'status' =>  $request->status,
                'price' =>  $request->price,
                'type' => $request->type,
                'note' => $request->note,
                'total_payment'  => $request->total_payment,
                'voucher_id' => $voucher_id,
            ]);
            $sync_data = [];
            for ($i = 0; $i < count($products); $i++) {
                $sync_data[$products[$i]] = ['quantity' => $amounts[$i]];
                $order->products()->sync($sync_data);
            };
            for ($i = 0; $i < count($products); $i++) {
                if (!empty($products[$i])) {
                    $product_id = $products[$i];
                    $amount = $amounts[$i];

                    // Ambil relasi product_stock dan stock terkait
                    $productStocks = DB::table('product_stock')
                        ->where('product_id', $product_id)
                        ->get();

                    foreach ($productStocks as $productStock) {
                        $stock = Stock::find($productStock->stock_id);

                        // Validasi: hanya potong kalau counted == 1
                        if ($stock && $stock->counted == 1) {
                            $quantity = $productStock->quantity;

                            // Hitung pengurangan: amount_stock - (quantity * amount_pesanan)
                            $deduction = $quantity * $amount;

                            // Update stok dengan pengurangan
                            $stock->decrement('amount', $deduction);
                        }
                    }
                }
            }
        }

        return redirect()->to('admin/order/manual/');
    }

    public function check(Request $request)
    {
        $code = $request->code;
        $total = floatval($request->total_price ?? 0);

        if ($total <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Total harga tidak valid.'
            ]);
        }

        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan.'
            ]);
        }

        // === Validasi status aktif ===
        if ($voucher->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak aktif.'
            ]);
        }

        // === Validasi masa berlaku ===
        $now = now();
        if ($voucher->starttime && $now->lt($voucher->starttime)) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher belum berlaku.'
            ]);
        }

        if ($voucher->endtime && $now->gt($voucher->endtime)) {
            return response()->json([
                'success' => false,
                'message' => 'Masa berlaku voucher sudah habis.'
            ]);
        }

        // === Validasi balance (sisa pemakaian) ===
        if ($voucher->balance <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah habis kuotanya.'
            ]);
        }

        // === Hitung diskon ===
        $discount = 0;
        if ($voucher->voucher_type == 0) {
            // Diskon dalam persen
            $discount = $total * ($voucher->value / 100);
        } else {
            // Diskon nominal
            $discount = $voucher->value;
        }

        $newTotal = max(0, $total - $discount);

        // === Kurangi balance (opsional, hanya jika kamu mau voucher langsung dianggap terpakai) ===
        // $voucher->decrement('balance');


        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diterapkan!',
            'voucher_name' => $voucher->name,
            'voucher_id' => $voucher->id,
            'discount' => $discount,
            'new_total' => $newTotal,
            'voucher_type' => $voucher->voucher_type, // <— tambahan ini
            'value' => $voucher->value,             // <— tambahan ini
        ]);
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        switch ($request->status) {
            case '1':
                # pending -> inprogress
                $products = $request->input('product_id', []);
                $amounts = $request->input('amount', []);
                for ($i = 0; $i < count($products); $i++) {
                    if (!empty($products[$i])) {
                        $product_id = $products[$i];
                        $amount = $amounts[$i];

                        // Ambil relasi product_stock dan stock terkait
                        $productStocks = DB::table('product_stock')
                            ->where('product_id', $product_id)
                            ->get();

                        foreach ($productStocks as $productStock) {
                            $stock = Stock::find($productStock->stock_id);

                            // Validasi: hanya potong kalau counted == 1
                            if ($stock && $stock->counted == 1) {
                                $quantity = $productStock->quantity;

                                // Hitung pengurangan: amount_stock - (quantity * amount_pesanan)
                                $deduction = $quantity * $amount;

                                // Update stok dengan pengurangan
                                $stock->decrement('amount', $deduction);
                            }
                        }
                    }
                }
                $order = Order::findOrFail($id);
                $order->update([
                    'status' => $request->status,
                ]);
                break;
            case '2':
                # in progress -> waiting payment
                $order = Order::findOrFail($id);
                $order->update([
                    'status' => $request->status,
                ]);
                break;

            case '4':
                # pesanan ditolak
                $order = Order::findOrFail($id);
                $order->update([
                    'status' => $request->status,
                    'note' => $request->note,
                ]);
                break;

            default:
                # code...
                break;
        }

        if ($request->status == 4) {
            return redirect()->to('/admin');
        }
        $order = Order::findOrFail($id);
        if ($order->customer_id === null) {
            return redirect()->to('admin/order/manual/' . $id);
        } else {
            return redirect()->to('admin/order/online/' . $order->customer_id);
        }
    }


    public function operatorDestroy($id)
    {
        Order::destroy($id);
        return redirect()->to('/admin/order/online')->with('danger', 'order dihapus:(');
    }
}
