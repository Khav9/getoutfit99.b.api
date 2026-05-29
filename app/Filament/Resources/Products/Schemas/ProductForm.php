<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn($state, callable $set) =>
                        $set(
                            'slug',
                            Str::slug($state) . '-' . time()
                        )
                    ),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('price_usd')
                    ->label('Price (USD)')
                    ->numeric()
                    ->prefix('$')
                    ->required(),

                TextInput::make('sale_price_usd')
                    ->label('Sale Price (USD)')
                    ->numeric()
                    ->prefix('$'),

                TextInput::make('stock_qty')
                    ->label('Stock Quantity')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Toggle::make('is_new_arrival')
                    ->label('New Arrival')
                    ->default(false),

                Repeater::make('productImages')
                    ->relationship()
                    ->label('Product Images')
                    ->schema([
                        FileUpload::make('image_url')
                            ->label('Image')
                            ->image()
                            ->directory('products')
                            ->required(),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->collapsible()
                    ->cloneable()
                    ->defaultItems(1)
                    ->columnSpanFull(),
            ]);
    }
}
