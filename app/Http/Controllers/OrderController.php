<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
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
        $orders = Order::where('customer_id', $id)->where('status', '!=', 4)->get();
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
        $products = Product::with('stocks')->orderBy('name', 'desc')->get();
        return view('admin.order.create', compact('products', 'categories'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $products = $request->input('product_id', []);
        $amounts = $request->input('amount', []);
        if ($request->status == 1) {
            $order = Order::create($request->all());
            $sync_data = [];
            for ($i = 0; $i < count($products); $i++) {
                $sync_data[$products[$i]] = ['quantity' => $amounts[$i]];
                $order->products()->sync($sync_data);
            };
            for ($i = 0; $i < count($products); $i++) {
                if ($products[$i] != '') {
                    $product_id = $products[$i];
                    $amount = $amounts[$i];
                    $stocks = Stock::wherehas('products', function ($query) use ($product_id) {
                        $query->where('id', $product_id);
                    })->update(['amount' => DB::raw('amount - ' . $amount)]);
                }
            }
        }

        return redirect()->to('admin/order/manual/');
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
                    if ($products[$i] != '') {
                        $product_id = $products[$i];
                        $amount = $amounts[$i];
                        $stocks = Stock::wherehas('products', function ($query) use ($product_id) {
                            $query->where('id', $product_id);
                        })->update(['amount' => DB::raw('amount - ' . $amount)]);
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
