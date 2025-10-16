<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesSummary extends Model
{
    use HasFactory;

    protected $table = 'sales_summary';

    protected $fillable = [
        'transaction_date', 'transaction_id', 'order_id', 'product_id',
        'category_id', 'quantity_sold', 'unit_price', 'total_revenue',
        'discount_amount', 'voucher_code', 'payment_method'
    ];

    // Relasi opsional
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
