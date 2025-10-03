<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'image',
        'price',
        'status',
        'type',
        'category_id',
    ];

    public function stocks()
    {
        return $this->belongsToMany(Stock::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }

    // public function outlet()
    // {
    //     return $this->belongsTo(Outlet::class);
    // }
}
