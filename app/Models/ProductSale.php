<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    protected $fillable = [
        'price',
        'user_id',
    ];

    // Relationships

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
