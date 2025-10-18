<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        //
    }
    public function food()
    {
        $categories = Category::where('category_type', 0)->orderBy('category_name', 'asc')->get();
        $products = Product::with(['stocks' => function ($q) {
            $q->select('stocks.id', 'stocks.name', 'stocks.amount');
        }])
            ->where('type', 0)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($product) {
                $jumlahBisaDibuat = [];
                $sisaBahan = [];

                foreach ($product->stocks as $stock) {
                    $need = $stock->pivot->quantity;
                    $have = $stock->amount;

                    if ($need > 0) {
                        $jumlahBisaDibuat[] = floor($have / $need);
                        $sisaBahan[$stock->name] = $have % $need; // ← modulus untuk sisa bahan
                    }
                }

                $product->remaining_stock = count($jumlahBisaDibuat) ? min($jumlahBisaDibuat) : 0;
                $product->sisa_bahan = $sisaBahan;

                return $product;
            });
        $stocks = Stock::orderby('name', 'asc')->where('type', '0')->get();

        return view('admin.product.food', compact('categories', 'products', 'stocks'));
    }
    public function drink()
    {
        $categories = Category::where('category_type', 1)->orderBy('category_name', 'asc')->get();
        $products = Product::with(['stocks' => function ($q) {
            $q->select('stocks.id', 'stocks.name', 'stocks.amount');
        }])
            ->where('type', 1)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($product) {
                $jumlahBisaDibuat = [];
                $sisaBahan = [];

                foreach ($product->stocks as $stock) {
                    $need = $stock->pivot->quantity;
                    $have = $stock->amount;

                    if ($need > 0) {
                        $jumlahBisaDibuat[] = floor($have / $need);
                        $sisaBahan[$stock->name] = $have % $need; // ← modulus untuk sisa bahan
                    }
                }

                $product->remaining_stock = count($jumlahBisaDibuat) ? min($jumlahBisaDibuat) : 0;
                $product->sisa_bahan = $sisaBahan;

                return $product;
            });
        $stocks = Stock::orderby('name', 'asc')->where('type', '1')->get();

        return view('admin.product.drink', compact('categories', 'products', 'stocks'));
    }

    public function active(Request $request, $id)
    {
        $product = Product::where('id', $id)->update(['status' => $request->status]);
        return redirect()->back();
    }


    public function categoryCreate(Request $request)
    {
        $category_type = $request->category_type;
        return view('admin.product.categoryCreate', compact('category_type'));
    }
    public function categoryChoose(Request $request)
    {
        $category_type = $request->category_type;
        $categories = Category::where('category_type', $request->category_type)->get();
        return view('admin.product.categoryChoose', compact('categories', 'category_type'));
    }
    public function categoryStore(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'category_name' => 'required',
            'category_type' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $input = $request->all();
        if ($image = $request->file('image')) {
            $destinationPath = 'images/category/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }
        Category::create($input);
        logActivity('menambahkan Kategori', "Pengguna menambahkan kategori: {$input['category_name']}");


        $category_type = $request->category_type;
        $categories = Category::where('category_type', $request->category_type)->get();
        return view('admin.product.categoryChoose', compact('categories', 'category_type'));
    }

    public function create(Request $request)
    {
        // dd($request->all());
        $category_id = $request->category_id;
        $category_type = $request->category_type;
        $category = Category::find($category_id);
        // dd($category);
        $stocks = Stock::orderby('name', 'asc')->where('type', $category_type)->get();
        return view('admin.product.create', compact('stocks', 'category', 'category_type'));
    }


    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'required',
            'status' => 'required',
            'type' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $input = $request->all();
        if ($image = $request->file('image')) {
            $destinationPath = 'images/product/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }

        $product = Product::create($input);
        $stockData = [];
        foreach ($request->stocks as $stock) {
            $stockData[$stock['id']] = ['quantity' => $stock['quantity']];
        }
        $product->stocks()->attach($stockData);
        logActivity('menambahkan Produk', "Pengguna menambahkan produk: {$request->name}");


        if ($request->type == 0) {
            return redirect()->to('/admin/product/food')->with('success', 'Produk baru berhasil ditambahkan :)');
        } else {
            return redirect()->to('/admin/product/drink')->with('success', 'Produk baru berhasil ditambahkan :)');
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
        $product = Product::findOrFail($id);

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // validasi gambar
        ]);

        // Simpan data dasar produk
        $product->name = $request->name;
        $product->price = $request->price;
        $product->status = $request->status;
        $product->description = $request->description;

        // === Handle update gambar ===
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada dan file-nya ada di folder
            if ($product->image && file_exists(public_path('images/product/' . $product->image))) {
                unlink(public_path('images/product/' . $product->image));
            }

            // Simpan gambar baru
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/product'), $filename);

            // Simpan nama file baru ke database
            $product->image = $filename;
        }

        $product->save();

        // === Handle stok (pivot) ===
        $stocks = $request->input('stocks', []);
        $syncData = [];

        foreach ($stocks as $s) {
            if (!empty($s['id'])) {
                $syncData[$s['id']] = ['quantity' => $s['quantity']];
            }
        }

        $product->stocks()->sync($syncData);

        // Redirect sesuai tipe produk
        if ($request->type == 0) {
            return redirect()->to('/admin/product/food')->with('success', 'Produk berhasil diperbarui.');
        } else {
            return redirect()->to('/admin/product/drink')->with('success', 'Produk berhasil diperbarui.');
        }
    }


    public function destroy($id)
    {
        $link = Product::find($id);
        logActivity('menghapus Produk', "Pengguna menghapus produk: {$link->name}");

        Product::destroy($id);
        if ($link->type == 0) {
            return redirect()->to('/admin/product/food')->with('danger', 'item dihapus:(');
        } else {
            return redirect()->to('/admin/product/drink')->with('danger', 'item dihapus:(');
        }
    }
}
