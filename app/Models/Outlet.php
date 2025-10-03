<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'email',
        'image',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function customer()
    {
        return $this->hasMany(Customer::class);
    }
    public function order()
    {
        return $this->hasMany(Order::class);
    }
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
