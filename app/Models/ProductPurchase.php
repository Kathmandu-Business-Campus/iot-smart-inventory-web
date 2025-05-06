<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPurchase extends Model
{
    protected $fillable = [
        'price',
        'user_id',
    ];

    // Relationships

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
