<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    use HasFactory;

    protected $table = 'discounts';

    protected $fillable = [
        'keterangan',
        'id_product',
        'type_diskon',
        'value',
        'start_date',
        'end_date',
        'status',
    ];

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    // Scope: hanya diskon aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope: diskon yang sedang berlaku sekarang
    public function scopeValidNow($query)
    {
        return $query->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }
}
