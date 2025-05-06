<?php

namespace App\Filament\Resources\ProductSaleResource\Pages;

use App\Filament\Resources\ProductSaleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProductSale extends ViewRecord
{
    protected static string $resource = ProductSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
