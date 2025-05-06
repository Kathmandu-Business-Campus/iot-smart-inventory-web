<?php

namespace App\Filament\Resources\ProductSaleResource\Pages;

use App\Filament\Resources\ProductSaleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductSale extends EditRecord
{
    protected static string $resource = ProductSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
