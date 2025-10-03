<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_status',
        'total_price',
        'cash',
        'debit',
        'kembalian',
        'note',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function summary()
    {
        return $this->belongsTo(Summary::class);
    }
}
