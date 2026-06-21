<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                ComponentsGrid::make(3)
                    ->schema([

                        // LEFT SIDE
                        ComponentsSection::make()
                            ->schema([

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

                                RichEditor::make('description')
                                    ->label('Description')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'strike',
                                        'bulletList',
                                        'orderedList',
                                        'h2',
                                        'h3',
                                        'blockquote',
                                        'link',
                                    ])
                                    ->extraAttributes([
                                        'style' => 'min-height:250px;',
                                    ]),

                            ])
                            ->columnSpan(2),

                        // RIGHT SIDE
                        ComponentsSection::make()
                            ->schema([

                                Toggle::make('is_new_arrival')
                                    ->label('New Arrival')
                                    ->default(false),

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

                                Select::make('brand_id')
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Repeater::make('colors')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('hex_code')
                                            ->label('Hex Code')
                                            ->required()
                                            ->regex('/^#[0-9A-Fa-f]{6}$/')
                                            ->placeholder('Choose color')
                                            ->default('#FF5733')
                                            ->prefixIcon('heroicon-o-swatch')
                                            ->extraInputAttributes([
                                                'type' => 'color',
                                                'style' => 'width:50px; padding:2px; cursor:pointer;',
                                            ]),
                                    ])
                                    ->defaultItems(1),

                                TextInput::make('price_usd')
                                    ->label('Price (USD)')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0)
                                    ->minValue(0)
                                    ->required(),

                                TextInput::make('stock_qty')
                                    ->label('Stock Quantity')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),

                                // SEO Settings
                                ComponentsSection::make('SEO Settings')
                                    ->schema([

                                        Textarea::make('meta_description')
                                            ->rows(4)
                                            ->maxLength(160)
                                            ->helperText(
                                                'Recommended: 150–160 characters'
                                            ),

                                        TextInput::make('keywords')
                                            ->placeholder(
                                                'phone, smartphone, apple, ios'
                                            )
                                            ->helperText(
                                                'Separate keywords with commas'
                                            ),

                                    ])
                                    ->collapsible(),

                                ComponentsSection::make('Product Images')
                                    ->schema([
                                        Repeater::make('productImages')
                                            ->relationship()
                                            ->schema([
                                                FileUpload::make('image_url')
                                                    ->label('Image')
                                                    ->image()
                                                    ->directory('products')
                                                    ->visibility('public')
                                                    ->required(),

                                                TextInput::make('sort_order')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->required(),
                                            ])
                                            ->defaultItems(1),
                                    ])->columnSpan(1),
                            ])
                            ->columnSpan(1),
                    ])->columnSpan(3),
            ]);
    }
}
