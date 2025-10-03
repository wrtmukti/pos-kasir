<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'amount',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    // public function outlet()
    // {
    //     return $this->belongsTo(Outlet::class);
    // }
}
