<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductInventoryResource\Pages;
use App\Filament\Resources\ProductInventoryResource\RelationManagers;
use App\Models\ProductInventory;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductInventoryResource extends Resource
{
    protected static ?string $model = ProductInventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = "Inventory";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('quantity')->required()->numeric(),
                DatePicker::make('expires_at')->label('Expires At')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Product')->searchable(),
                TextColumn::make('rfid')->label('RFID')->searchable(),
                TextColumn::make('quantity')->sortable(),
                TextColumn::make('expires_at')->date()->label('Expires At')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductInventories::route('/'),
            'create' => Pages\CreateProductInventory::route('/create'),
            'view' => Pages\ViewProductInventory::route('/{record}'),
            'edit' => Pages\EditProductInventory::route('/{record}/edit'),
        ];
    }
}
