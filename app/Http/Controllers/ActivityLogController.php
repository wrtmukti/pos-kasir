<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\User; // Asumsikan Anda memiliki model User

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data pengguna untuk dropdown filter
        // Asumsi Anda hanya perlu pengguna yang ada di log
        $users = User::orderBy('name')->get();

        // 2. Mulai query dengan eager loading (seperti sebelumnya)
        $logs = ActivityLog::with('user')->latest();

        // 3. Logika Filtering

        // Filter berdasarkan Pengguna
        if ($request->filled('user_id')) {
            $logs->where('user_id', $request->user_id);
        }

        // Filter berdasarkan Aksi
        if ($request->filled('action')) {
            $logs->where('action', $request->action);
        }

        // Filter berdasarkan Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date . ' 00:00:00';
            $end = $request->end_date . ' 23:59:59';
            $logs->whereBetween('created_at', [$start, $end]);
        }

        // 4. Eksekusi query dan paginasi
        $logs = $logs->paginate(20)->withQueryString(); // withQueryString agar filter tetap ada saat pindah halaman

        // 5. Kirim data ke view, termasuk data filter yang aktif
        return view('admin.logs.index', compact('logs', 'users'));
    }
}
