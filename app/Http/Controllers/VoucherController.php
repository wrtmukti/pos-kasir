<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VoucherController extends Controller
{

    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->get();
        return view('admin.operator.voucher.index', compact('vouchers'));
    }


    public function create()
    {
        return view('admin.operator.voucher.form');
    }


    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'nullable|string|max:100|unique:vouchers,code',
            'voucher_type' => 'required|in:0,1', // 0 = percent, 1 = fixed
            'value'        => 'required|numeric|min:0',
            'status'       => 'nullable|in:0,1',
            'balance'      => 'nullable|integer|min:0',
            'starttime'    => 'nullable|date', // accepts many formats; we'll parse with Carbon
            'endtime'      => 'nullable|date',
            'note'         => 'nullable|string',
        ], [
            'name.required' => 'Nama voucher wajib diisi.',
            'value.required' => 'Nilai voucher wajib diisi.',
            'voucher_type.required' => 'Tipe voucher wajib dipilih.',
            'code.unique' => 'Kode voucher sudah digunakan, silakan gunakan kode lain.'
        ]);

        DB::beginTransaction();

        try {
            // jika tidak ada code dari form, generate otomatis (unik)
            $code = $validated['code'] ?? strtoupper(Str::random(8));
            // pastikan unik (loop kecil jika kebetulan clash)
            while (Voucher::where('code', $code)->exists()) {
                $code = strtoupper(Str::random(8));
            }

            // parse datetime jika ada (meng-handle format dari input type="datetime-local")
            $starttime = null;
            $endtime = null;

            if (!empty($validated['starttime'])) {
                // Carbon::parse bisa mem-parse format 'YYYY-MM-DDTHH:MM' or other formats
                $starttime = Carbon::parse($validated['starttime'])->toDateTimeString();
            }

            if (!empty($validated['endtime'])) {
                $endtime = Carbon::parse($validated['endtime'])->toDateTimeString();
            }

            // simpan voucher
            $voucher = Voucher::create([
                'name'         => $validated['name'],
                'code'         => $code,
                'voucher_type' => (int)$validated['voucher_type'],
                'value'        => $validated['value'],
                'status'       => isset($validated['status']) ? (int)$validated['status'] : 0,
                'balance'      => $validated['balance'] ?? null,
                'starttime'    => $starttime,
                'endtime'      => $endtime,
                'note'         => $validated['note'] ?? null,
            ]);

            DB::commit();
            logActivity('membuat Voucher', "Pengguna membuat voucher: {$voucher->name}");

            return redirect()->route('voucher.index')->with('success', 'Voucher berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            // untuk development: gunakan ->with('error', $e->getMessage());
            return redirect()->route('voucher.index')->with('error', 'Gagal menyimpan voucher: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.operator.voucher.form', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:vouchers,code,' . $voucher->id,
            'voucher_type' => 'required|in:0,1',
            'value' => 'required|integer|min:1',
            'status' => 'required|in:0,1',
            'balance' => 'nullable|integer|min:0',
            'starttime' => 'nullable|date',
            'endtime' => 'nullable|date|after_or_equal:starttime',
        ]);

        $voucher->update($request->all());
        logActivity('mengupdate Voucher', "Pengguna mengupdate voucher: {$voucher->name}");

        return redirect()->route('voucher.index')->with('success', 'Voucher berhasil diperbarui!');
    }

    // HAPUS DATA
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        logActivity('menghapus Voucher', "Pengguna menghapus voucher: {$voucher->name}");

        $voucher->delete();

        return redirect()->route('voucher.index')->with('danger', 'Voucher telah dihapus!');
    }



    public function show($id)
    {
        //
    }
}
