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
        $products = Product::with('category')->get();
        $food_categories = Category::where('category_type', '0')->get();
        $drink_categories = Category::where('category_type', '1')->get();
        return view('index', compact('products', 'food_categories', 'drink_categories'));
    }
    public function indexx()
    {
        $products = Product::with(['orders' => function ($q) {
            $q->orderBy('pivot_quantity', 'asc');
        }])->take(3)->get();
        $food_categories = Category::where('category_type', '0')->get();
        $drink_categories = Category::where('category_type', '1')->get();
        return view('index', compact('products', 'food_categories', 'drink_categories'));
    }

    public function checkout(Request $request)
    {

        // dd($request);
        $cart = json_decode($request->cart_data, true);
        $total_price = $request->total_price;

        $productIds = collect($cart)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->get();
        $tables = Table::where('status', '1')->get();

        return view('review', compact('cart', 'products', 'total_price', 'tables'));
    }

    public function review()
    {
        return redirect()->route('home');
    }

    public function submit(Request $request)
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

                DB::beginTransaction();

                try {
                    if (empty($request->products) || !is_array($request->products)) {
                        throw new \Exception('Tidak ada produk dalam pesanan.');
                    }

                    $order = Order::create([
                        'type' => 1,
                        'status' => 0,
                        'price' => $request->total_price,
                        'transaction_id' => null,
                        'customer_id' => $customer_id,
                    ]);

                    foreach ($request->products as $item) {
                        if (!isset($item['product_id']) || !isset($item['quantity'])) {
                            throw new \Exception('Format data produk tidak valid.');
                        }

                        $order->products()->attach($item['product_id'], [
                            'quantity' => $item['quantity'],
                            'note' => $item['note'] ?? null,
                        ]);
                    }

                    DB::commit();
                    // return redirect('/')->with('success', 'Pesanan berhasil dibuat!');
                    return redirect()->route('order.status')->with('success', 'Pesanan berhasil dibuat!');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect('/')->with('error', $e->getMessage());
                }
            } else {
                return redirect('/')->with('error', 'nomor meja');
            }
        } else {
            return redirect()->to('/order/status')->with('danger', 'Maaf Kamu belum mengisi no meja');
        }
    }

    public function orderStatus()
    {
        $orders = Order::with('products')->orderBy('created_at', 'desc')->where('status', '!=', 3)->whereHas('customer')->get();

        return view('status', compact('orders'));
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
