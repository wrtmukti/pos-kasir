@extends('admin.layouts.layout')

@section('content')
<div class="content-wrapper log-page-wrapper" style="padding: 20px;">
    
    {{-- Header --}}
    <div class="header-section" style="margin-bottom: 20px;">
        <h2 style="color: #333; margin-bottom: 5px;">Log Aktivitas Pengguna 📋</h2>
        <p style="color: #666; font-size: 1.1em;">Daftar lengkap riwayat aksi yang dilakukan pengguna.</p>
    </div>

    {{-- Filter Form (Peningkatan UI/UX) --}}
    <div class="filter-card" style="margin-bottom: 25px; padding: 15px; border: 1px solid #e0e0e0; border-radius: 6px; background-color: #f9f9f9;">
        <form method="GET" action="{{ route('logs.index') }}" class="filter-form" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">

            {{-- Filter Pengguna --}}
            <div class="filter-group" style="min-width: 150px; flex-grow: 1;">
                <label for="user_id" style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9em;">Filter User:</label>
                <select name="user_id" id="user_id" onchange="this.form.submit()" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">Semua Pengguna</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Aksi --}}
            <div class="filter-group" style="min-width: 120px; flex-grow: 1;">
                <label for="action" style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9em;">Filter Aksi:</label>
                <select name="action" id="action" onchange="this.form.submit()" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">Semua Aksi</option>
                    @foreach (['CREATE', 'UPDATE', 'DELETE', 'LOGIN'] as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ Str::title($action) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tanggal Mulai --}}
            <div class="filter-group" style="min-width: 150px; flex-grow: 1;">
                <label for="start_date" style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9em;">Tanggal Mulai:</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            {{-- Filter Tanggal Akhir --}}
            <div class="filter-group" style="min-width: 150px; flex-grow: 1;">
                <label for="end_date" style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9em;">Tanggal Akhir:</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            
            {{-- Tombol Terapkan & Reset --}}
            <div class="filter-group" style="display: flex; gap: 10px;">
                <button type="submit" style="padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Terapkan</button>
                @if(request()->anyFilled(['user_id', 'action', 'start_date', 'end_date']))
                    <a href="{{ route('logs.index') }}" style="padding: 8px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Reset</a>
                @endif
            </div>

        </form>
    </div>

    {{-- Tampilkan pesan jika filter aktif --}}
    @if(request()->anyFilled(['user_id', 'action', 'start_date', 'end_date']))
        <p style="margin-bottom: 15px; padding: 10px; border-left: 3px solid #007bff; background-color: #e6f0ff;">
            Filter aktif. <a href="{{ route('logs.index') }}" style="color: #007bff;">Reset Filter</a>
        </p>
    @endif

    {{-- ... (Sisa Kode Tabel Log Aktivitas) ... --}}
    <div class="table-container" style="overflow-x: auto; border: 1px solid #ddd; border-radius: 4px; background-color: #fff;">
        {{-- ... (Thead dan Tbody seperti sebelumnya) ... --}}
        
        <table class="data-table log-table-data" style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #f8f8f8;">
                <tr>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: left; white-space: nowrap;">Tanggal & Waktu</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: left; white-space: nowrap;">Pengguna</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: left; white-space: nowrap;">Aksi</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: left;">Deskripsi</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: left; white-space: nowrap;">Alamat IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td data-label="Tanggal & Waktu" class="log-value" style="padding: 12px; white-space: nowrap;">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td data-label="Pengguna" class="log-value" style="padding: 12px; white-space: nowrap;">
                            <span class="badge-style" style="background-color: #e0e0e0; padding: 4px 8px; border-radius: 3px; font-size: 0.9em;">
                                {{ $log->user?->name ?? 'Sistem/Anonim' }}
                            </span>
                        </td>
                        <td data-label="Aksi" class="log-value" style="padding: 12px;">
                            @php
                                $actionColor = ['CREATE' => '#4CAF50', 'UPDATE' => '#FFC107', 'DELETE' => '#F44336', ][$log->action] ?? '#2196F3';
                            @endphp
                            <span class="badge-style" style="background-color: {{ $actionColor }}; color: #fff; padding: 4px 8px; border-radius: 3px; font-size: 0.9em; display: inline-block;">
                                {{ Str::upper($log->action) }}
                            </span>
                        </td>
                        <td data-label="Deskripsi" class="log-value" style="padding: 12px;">{{ Str::limit($log->description, 70, '...') }}</td>
                        <td data-label="Alamat IP" class="log-value" style="padding: 12px; white-space: nowrap;">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                            <i class="fas fa-exclamation-triangle"></i> Tidak ada log aktivitas yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginasi --}}
    <div class="pagination-section" style="margin-top: 20px; text-align: center;">
        {{ $logs->links() }}
    </div>
</div>
@endsection

{{-- PUSH STYLES UNTUK RESPONSIVITAS MOBILE & FILTER --}}
@push('styles')
<style>
    /* ------------------------------------------------ */
    /* STYLING UMUM UNTUK ELEMEN FILTER */
    /* ------------------------------------------------ */
    .filter-form .filter-group {
        display: flex;
        flex-direction: column;
    }
    .filter-form select, 
    .filter-form input[type="date"] {
        box-sizing: border-box; /* Penting untuk responsif */
    }
    
    /* ------------------------------------------------ */
    /* MEDIA QUERY UNTUK MOBILE & TABLET (RESPONSIVITAS) */
    /* ------------------------------------------------ */
    @media screen and (max-width: 768px) {
        
        /* Filter Form di Mobile */
        .filter-form {
            flex-direction: column; /* Tumpuk semua elemen filter */
            gap: 10px !important;
        }
        .filter-form .filter-group {
            width: 100% !important; /* Ambil lebar penuh */
            min-width: 100% !important;
        }
        .filter-form .filter-group:last-child {
            flex-direction: row; /* Biarkan tombol Terapkan/Reset berdampingan */
            justify-content: space-between;
        }

        /* --- Gaya Tabel di Mobile (Card View) --- */
        .log-table-data thead {
            display: none;
        }
        .log-table-data tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 10px;
        }
        .log-table-data td {
            display: block;
            text-align: right !important;
            padding: 8px 0 !important;
            border: none !important;
            position: relative;
        }
        .log-table-data td::before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
            color: #555;
            font-size: 0.9em;
        }
        .log-table-data td[data-label="Deskripsi"] {
            text-align: left !important;
        }
        .log-table-data td[data-label="Deskripsi"]::before {
            float: none;
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }
        .log-table-data tr:has(td[colspan="5"]) {
            display: table-row;
            text-align: center;
            border: none;
            box-shadow: none;
            margin-bottom: 0;
        }
        .log-table-data .badge-style {
            white-space: normal; 
        }
    }
</style>
@endpush