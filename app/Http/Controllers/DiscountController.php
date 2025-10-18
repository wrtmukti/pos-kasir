<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Tampilkan semua diskon.
     */
    public function index()
    {
        $discounts = Discount::with('product')->latest()->get();
        $products = Product::all();
        return view('admin.operator.discount.index', compact('discounts', 'products'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.operator.discount.form', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'keterangan' => 'required|string|max:255',
            'id_product' => 'required|exists:products,id',
            'type_diskon' => 'required|in:0,1',
            'value' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);
        $product = Product::findOrFail($request->id_product);

        // Jika type_diskon == 1 (potongan nominal), pastikan value ≤ harga produk
        if ($request->type_diskon == 1 && $request->value > $product->price) {
            return back()->withErrors(['value' => 'Potongan harga tidak boleh melebihi harga produk.'])->withInput();
        }
        // Jika type_diskon == 0 (persen), pastikan value tidak melebihi harga produk
        if ($request->type_diskon == 0 && $request->value > 100) {
            return back()->withErrors(['value' => 'Diskon persentase tidak boleh lebih besar dari 100%.'])->withInput();
        }

        Discount::create($validated);
        logActivity('menambahkan Diskon', "Pengguna menambahkan diskon: {$request->keterangan}");
        return redirect()->route('discount.index')->with('success', 'Diskon berhasil ditambahkan!');
    }

    public function edit(Discount $diskon)
    {
        $products = \App\Models\Product::all();
        return view('admin.operator.discount.form', compact('diskon', 'products'));
    }

    public function update(Request $request, Discount $diskon)
    {
        $validated = $request->validate([
            'keterangan' => 'required|string|max:255',
            'id_product' => 'required|exists:products,id',
            'type_diskon' => 'required|in:0,1',
            'value' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);
        $product = Product::findOrFail($request->id_product);

        // Jika type_diskon == 1 (potongan nominal), pastikan value ≤ harga produk
        if ($request->type_diskon == 1 && $request->value > $product->price) {
            return back()->withErrors(['value' => 'Potongan harga tidak boleh melebihi harga produk.'])->withInput();
        }
        // Jika type_diskon == 0 (persen), pastikan value tidak melebihi harga produk
        if ($request->type_diskon == 0 && $request->value > 100) {
            return back()->withErrors(['value' => 'Diskon persentase tidak boleh lebih besar dari 100%.'])->withInput();
        }

        $diskon->update($validated);
        logActivity('mengupdate Diskon', "Pengguna mengupdate diskon: {$request->keterangan}");
        return redirect()->route('discount.index')->with('success', 'Diskon berhasil diperbarui!');
    }

    public function destroy(Discount $diskon)
    {
        logActivity('menghapus Diskon', "Pengguna menghapus diskon: {$diskon->keterangan}");
        $diskon->delete();
        return redirect()->route('discount.index')->with('success', 'Diskon berhasil dihapus!');
    }
}
