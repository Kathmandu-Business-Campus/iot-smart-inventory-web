<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    protected $fillable = [
        'product_id',
        'rfid',
        'quantity',
        'expires_at',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
