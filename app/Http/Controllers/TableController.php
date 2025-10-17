<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableController extends Controller
{
    public function index()
    {
        // dd("test 1");
        $mejas = Table::all();
        return view('admin.tables.index', compact('mejas'));
    }

    public function create()
    {
        return view('admin.tables.form');
    }

    public function store(Request $request)
    {
        $request->validate([
        'no_table' => 'required|integer|unique:tables,no_table',
        'status' => 'required|integer',
        ]);

        Table::create($request->all());

        logActivity('menambahkan Meja', "Pengguna menambahkan meja: {$request->no_table}");
        return redirect()->route('meja.index')->with('success', 'Meja berhasil ditambahkan!');
    }

    public function edit(Table $meja)
    {
        return view('admin.tables.form', compact('meja'));
    }

    public function update(Request $request, Table $meja)
    {
        $request->validate([
            'no_table' => 'required|integer|unique:tables,no_table,' . $meja->id,
            'status' => 'required|integer',
        ]);

        $meja->update($request->all());

        logActivity('mengupdate Meja', "Pengguna mengupdate meja: {$meja->no_table}");
        return redirect()->route('meja.index')->with('success', 'Table updated successfully!');
    }

    public function destroy(Table $meja)
    {
        logActivity('menghapus Meja', "Pengguna menghapus meja: {$meja->no_table}");
        $meja->delete();

        return redirect()->route('meja.index')->with('success', 'Table deleted successfully!');
    }

    
    public function generateQr($id)
    {
        $meja = Table::findOrFail($id);
        $serverLink = request()->getSchemeAndHttpHost();
        $link = $serverLink . '/' . $meja->id;
        $qr = QrCode::size(250)->generate($link);

        return view('admin.tables.qr', compact('qr', 'link', 'meja'));
    }
}
