<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'product_purchase_id',
        'rfid',
        'product_id',
        'product_inventory_id',
        'quantity',
        'price',
        'expires_at',
    ];

    // Relationships
    public function productPurchase()
    {
        return $this->belongsTo(ProductPurchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productInventory()
    {
        return $this->belongsTo(ProductInventory::class);
    }
}
