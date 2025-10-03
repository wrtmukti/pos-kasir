<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::orderBy('created_at', 'desc')->get();
        return view('admin.operator.stock.index', compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.operator.stock.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required',
            'amount' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        Stock::create([
            'name' => $request->name,
            'type' => $request->type,
            'amount' => $request->amount,

        ]);

        return redirect()->to('/admin/stock')->with('success', 'Item baru berhasil ditambahkan :)');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function operatoredit($id)
    {
        // dd($request->amount);
        $stock = Stock::findOrFail($id);
        return view('admin.operator.stock.edit', compact('stock'));
    }
    public function operatorUpdate(Request $request, $id)
    {
        // dd($request->amount);
        $stock = Stock::findOrFail($id);
        $stock->update(['amount' => DB::raw('amount + ' . $request->amount)]);
        return redirect()->to('/admin/stock')->with('success', 'Stok berhasil diupdate :)');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function operatorDestroy($id)
    {
        Stock::destroy($id);
        return redirect()->to('/admin/stock')->with('danger', 'item dihapus:(');
    }
}
