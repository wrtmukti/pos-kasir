<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'customer_id',
        'transaction_id',
        'price',
        'note',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class)->withPivot('quantity');
    }
}
