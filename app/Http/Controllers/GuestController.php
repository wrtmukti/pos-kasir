<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{

    public function index()
    {
        $products = Product::with(['orders' => function ($q) {
            $q->orderBy('pivot_quantity', 'asc');
        }])->take(3)->get();
        $food_categories = Category::where('category_type', '0')->get();
        $drink_categories = Category::where('category_type', '1')->get();
        return view('index', compact('products', 'food_categories', 'drink_categories'));
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $products = Product::where(DB::raw('lower(name)'), 'like', '%' . strtolower($search) . '%')->get();
        return view('search', compact('products', 'search'));
    }


    public function status()
    {
        $orders = Order::with('products')->orderBy('created_at', 'desc')->where('status', '!=', 3)->whereHas('customer')->get();
        return view('order', compact('orders'));
    }

    public function category($id)
    {
        $category = Category::find($id);
        $products = Product::with('stocks')->where('category_id', $id)->orderBy('price', 'asc')->get();
        return view('category', compact('products', 'category'));
    }

    public function store(Request $request)
    {
        if ($request->no_table !== null) {
            $table = Table::where('no_table', $request->no_table)->first();
            if ($table->status == 1) {

                $customers = Customer::where('no_table', $request->no_table)->whereHas('orders', function ($query) {
                    $query->where('status', '<', '3');
                })->first();

                if ($customers !== null) {
                    $customer_id = $customers->id;
                } else {
                    $customer = new Customer();
                    $customer->no_table = $request->no_table;
                    if ($request->has('customer_name')) {
                        $customer->name = $request->customer_name;
                    }
                    if ($request->has('customer_whatsapp')) {
                        $customer->whatsapp = $request->customer_whatsapp;
                    }
                    $customer->save();
                    $customer_id = $customer->id;
                }

                $products = $request->input('product_id', []);
                $amounts = $request->input('amount', []);

                $order = Order::create([
                    'status' =>  $request->status,
                    'price' =>  $request->price,
                    'type' => $request->type,
                    'customer_id' => $customer_id,
                    'note' => $request->note,
                ]);
                $sync_data = [];
                for ($i = 0; $i < count($products); $i++) {
                    $sync_data[$products[$i]] = ['quantity' => $amounts[$i]];
                    $order->products()->sync($sync_data);
                };
                return redirect()->to('/order/status')->with('success', 'Pesanan Kamu Berhasil Dikirim :)');
            } else {
                return redirect()->to('/order/status')->with('danger', 'Meja kamu belum aktif, silahkan hubungi kasir!');
            }
        } else {
            return redirect()->to('/order/status')->with('danger', 'Maaf Kamu belum mengisi no meja');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
