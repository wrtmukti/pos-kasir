<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'image',
        'price',
        'status',
        'type',
        'description',
        'category_id',
    ];

    public function stocks()
    {
        return $this->belongsToMany(Stock::class)->withPivot('quantity');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'note');
    }
    public function diskons()
    {
        return $this->hasMany(Discount::class, 'id_product');
    }

    // public function outlet()
    // {
    //     return $this->belongsTo(Outlet::class);
    // }
}
