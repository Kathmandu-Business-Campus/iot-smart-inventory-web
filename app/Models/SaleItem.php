<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'product_sale_id',
        'product_id',
        'product_inventory_id',
        'quantity',
        'price',
        'expires_at',
    ];

    // Relationships
    public function productSale()
    {
        return $this->belongsTo(ProductSale::class);
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
