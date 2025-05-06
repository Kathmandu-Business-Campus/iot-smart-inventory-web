<?php

namespace App\Filament\Resources\ProductSaleResource\Pages;

use App\Filament\Resources\ProductSaleResource;
use App\Models\ProductInventory;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductSale extends CreateRecord
{
    protected static string $resource = ProductSaleResource::class;

    protected function afterCreate(){
        $record = $this->getRecord();
        $items = $record->saleItems()->get();

        $inventories = ProductInventory::whereIn('id', $items->pluck('product_inventory_id'))->get();

        foreach ($items as $item) {
            $inventory = $inventories->where('id', $item->product_inventory_id)->first();

            $inventory->update([
                'quantity' => $inventory->quantity - $item->quantity
            ]);
        }
    }

}
