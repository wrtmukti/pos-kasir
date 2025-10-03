<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    use HasFactory;
    protected $fillable = [
        'is_open',
        'is_close',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
