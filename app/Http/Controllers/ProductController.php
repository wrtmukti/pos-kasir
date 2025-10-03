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
        $products = Product::with('stocks')->orderBy('created_at', 'desc')->where('type', 0)->get();

        return view('admin.product.food', compact('categories', 'products'));
    }
    public function drink()
    {
        $categories = Category::where('category_type', 1)->orderBy('category_name', 'asc')->get();
        $products = Product::with('stocks')->orderBy('name', 'asc')->where('type', 1)->get();

        return view('admin.product.drink', compact('categories', 'products'));
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
            'sku' => 'required|string|max:255',
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
        $product->stocks()->attach($request->stock_id);

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
        //
    }


    public function destroy($id)
    {
        $link = Product::find($id);
        Product::destroy($id);
        if ($link->type == 0) {
            return redirect()->to('/admin/product/food')->with('danger', 'item dihapus:(');
        } else {
            return redirect()->to('/admin/product/drink')->with('danger', 'item dihapus:(');
        }
    }
}
