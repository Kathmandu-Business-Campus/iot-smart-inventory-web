<?php

namespace App\Filament\Resources\ProductPurchaseResource\Pages;

use App\Filament\Resources\ProductPurchaseResource;
use App\Models\ProductInventory;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductPurchase extends CreateRecord
{
    protected static string $resource = ProductPurchaseResource::class;

    protected function afterCreate(){
        $record = $this->getRecord();
        $items = $record->purchaseItems()->get();

        foreach ($items as $item) {
            $inventory = ProductInventory::create([
                'product_id' => $item->product_id,
                'rfid' => $item->rfid,
                'quantity' => $item->quantity,
            ]);

            $item->update([
                'product_inventory_id' => $inventory->id
            ]);
        }
    }
}
