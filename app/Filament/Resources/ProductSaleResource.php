<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductSaleResource\Pages;
use App\Filament\Resources\ProductSaleResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\ProductSale;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ProductSaleResource extends Resource
{
    protected static ?string $model = ProductSale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = "Inventory";


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(Auth::id()),

                Repeater::make('saleItems')
                    ->columnSpanFull()
                    ->columns(3)
                    ->relationship('saleItems')
                    ->schema([
                        Hidden::make('product_id'),
                        Select::make('product_inventory_id')
                            ->searchable()
                            ->preload()
                            ->options(
                                ProductInventory::with('product')
                                    ->where('quantity', '>', '0')
                                    ->get()
                                    ->mapWithKeys(function ($item) {
                                        return [
                                            $item->id => $item->product->name . " - RFID: " . $item->rfid
                                        ];
                                    })
                            )
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if (is_null($state)) {
                                    return;
                                }
                                $productId = ProductInventory::find($state)->product_id;
                                $rfid = ProductInventory::find($state)->rfid;
                                $set('product_id', $productId);
                            })
                            ->required(),
                        TextInput::make('quantity')
                            ->default(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $inventoryId = $get('product_inventory_id');
                                if (is_null($inventoryId)) {
                                    return;
                                }
                                $availableQuantity = ProductInventory::find($inventoryId)->quantity;

                                if ($state > $availableQuantity) {
                                    $set('quantity', $availableQuantity);
                                }
                            })
                            ->numeric(),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $items = $get('../../saleItems');
                                $price = 0;
                                foreach ($items as $item) {
                                    $price = $price + $item['price'] * $item['quantity'];
                                }
                                $set('../../price', $price);
                            })
                    ]),

                TextInput::make('price')
                    ->columnSpanFull()
                    ->required()
                    ->readOnly()
                    ->numeric()
                    ->prefix('$'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User'),
                TextColumn::make('price')->money(),
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
            'index' => Pages\ListProductSales::route('/'),
            'create' => Pages\CreateProductSale::route('/create'),
            'view' => Pages\ViewProductSale::route('/{record}'),
            'edit' => Pages\EditProductSale::route('/{record}/edit'),
        ];
    }
}
