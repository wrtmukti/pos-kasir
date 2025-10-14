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
        'price',
        'note',
        'customer_id',
        'transaction_id',
        'voucher_id',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'note');
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
