<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Table;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GuestController extends Controller
{

    public function index()
    {
        $now = now();

        // ambil produk + kategori + diskon aktif
        $products = Product::with([
            'category',
            'diskons' => function ($q) use ($now) {
                $q->where('status', 'active')
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
            }
        ])->get();

        // hitung harga final berdasarkan diskon
        $products = $products->map(function ($p) {
            $original = $p->price;
            $final = $original;

            // jika ada diskon aktif
            if ($p->diskons && $p->diskons->count() > 0) {
                $diskon = $p->diskons->first();

                if ($diskon->type_diskon == 0) {
                    // 0 = persen
                    $final = $original - ($original * ($diskon->value / 100));
                } elseif ($diskon->type_diskon == 1) {
                    // 1 = potongan harga
                    $final = max(0, $original - $diskon->value);
                }
            }

            $p->price_original = $original;
            $p->price_final = round($final);
            return $p;
        });

        $food_categories = Category::where('category_type', '0')->get();
        $drink_categories = Category::where('category_type', '1')->get();

        return view('index', compact('products', 'food_categories', 'drink_categories'));
    }

    public function indexxx()
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
        $cart = json_decode($request->cart_data, true);
        $total_price = $request->total_price;

        $productIds = collect($cart)->pluck('product_id');

        // Ambil produk + diskon aktif
        $products = Product::whereIn('id', $productIds)
            ->with(['diskons' => function ($q) {
                $q->where('status', 'active')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now());
            }])
            ->get();

        $tables = Table::where('status', '1')->get();

        return view('review', compact('cart', 'products', 'total_price', 'tables'));
    }

    public function review()
    {
        return redirect()->route('home');
    }

    public function submit(Request $request)
    {

        // dd($request);
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

                if ($request->voucher_id) {
                    $voucher_id = $request->voucher_id;
                } else {
                    $voucher_id = null;
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
                        'total_payment' => $request->total_payment,
                        'transaction_id' => null,
                        'customer_id' => $customer_id,
                        'voucher_id' => $voucher_id,
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
        $orders = Order::with('products')
            ->whereHas('customer')
            ->where(function ($query) {
                $query->where('status', '!=', 3)
                    ->where(function ($q) {
                        $q->where('status', '!=', 4)
                            ->orWhere(function ($sub) {
                                $sub->where('status', 4)
                                    ->where('created_at', '>=', Carbon::now()->subHours(2));
                            });
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view('status', compact('orders'));
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
