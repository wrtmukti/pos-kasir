<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{

    public function index()
    {
        $stocks = Stock::orderBy('created_at', 'desc')->get();
        return view('admin.operator.stock.index', compact('stocks'));
    }


    public function create()
    {
        return view('admin.operator.stock.create');
    }


    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required',
            'amount' => 'required',
            'unit' => 'required',
            'counted' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        Stock::create([
            'name' => $request->name,
            'type' => $request->type,
            'amount' => $request->amount,
            'unit' => $request->unit,
            'counted' => $request->counted,

        ]);
        logActivity('Membuat Stok', "Pengguna membuat stok: {$request->name}");


        return redirect()->to('/admin/stock')->with('success', 'Stok baru berhasil ditambahkan :)');
    }

    public function show($id)
    {
        //
    }

    public function operatoredit($id)
    {
        // dd($request->amount);
        $stock = Stock::findOrFail($id);
        return view('admin.operator.stock.edit', compact('stock'));
    }
    public function operatorUpdate(Request $request, $id)
    {
        // dd($request->amount);
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:0,1',
            'unit' => 'required|in:0,1,2,3',
            'counted' => 'required|boolean',
            'amount' => 'required|numeric|min:0',
        ]);

        // Cari stok berdasarkan ID
        $stock = Stock::findOrFail($id);

        // Update data
        $stock->update([
            'name' => $request->name,
            'type' => $request->type,
            'unit' => $request->unit,
            'counted' => $request->counted,
            'amount' => $request->amount,
        ]);
        logActivity('mengupdate Stok', "Pengguna mengupdate stok: {$stock->name}");

        return redirect()->to('/admin/stock')->with('success', 'Stok berhasil diupdate :)');
    }

    public function operatorDestroy($id)
    {
        $stock = Stock::findOrFail($id);
        logActivity('menghapus Stok', "Pengguna menghapus stok: {$stock->name}");
        return redirect()->to('/admin/stock')->with('danger', 'Stok dihapus:(');
    }
}
