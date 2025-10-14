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

        Discount::create($validated);
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

        $diskon->update($validated);
        return redirect()->route('discount.index')->with('success', 'Diskon berhasil diperbarui!');
    }

    public function destroy(Discount $diskon)
    {
        $diskon->delete();
        return redirect()->route('discount.index')->with('success', 'Diskon berhasil dihapus!');
    }
}
