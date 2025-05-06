<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductPurchaseResource\Pages;
use App\Filament\Resources\ProductPurchaseResource\RelationManagers;
use App\Models\ProductPurchase;
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

class ProductPurchaseResource extends Resource
{
    protected static ?string $model = ProductPurchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = "Inventory";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(Auth::id()),

                Repeater::make('purchaseItems')
                    ->columnSpanFull()
                    ->columns(2)
                    ->relationship('purchaseItems')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required(),
                        TextInput::make('rfid'),
                        TextInput::make('quantity')
                            ->default(1)
                            ->required()
                            ->numeric(),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $items = $get('../../purchaseItems');
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
            'index' => Pages\ListProductPurchases::route('/'),
            'create' => Pages\CreateProductPurchase::route('/create'),
            'view' => Pages\ViewProductPurchase::route('/{record}'),
            'edit' => Pages\EditProductPurchase::route('/{record}/edit'),
        ];
    }
}
