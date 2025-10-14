<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'voucher_type',
        'value',
        'status',
        'balance',
        'starttime',
        'endtime',
        'note',
        'order_id'
    ];

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
