<?php

namespace App\Filament\Resources\Microsites\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\KeyValue;
use Guava\IconPicker\Forms\Components\IconPicker;
use Illuminate\Support\Str;

class MicrositeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->schema([
                        Select::make('category')
                            ->options([
                                'training' => 'Training & Development',
                                'sensus' => 'Sensus / Survey',
                                'zi' => 'Zona Integritas',
                                'event' => 'General Event',
                            ])
                            ->required(),
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->columnSpanFull(),
                        DatePicker::make('start_date'),
                        DatePicker::make('end_date'),
                        Toggle::make('is_published')
                            ->default(true)
                            ->required(),
                    ])->columns(2),

                Section::make('Design & Branding')
                    ->schema([
                        Select::make('template_key')
                            ->options([
                                'minimal-grid' => 'Minimal Grid',
                                'soft-gradient' => 'Soft Gradient',
                            ])
                            ->required()
                            ->default('minimal-grid'),
                        Select::make('layout_type')
                            ->options([
                                'grid' => 'Grid',
                                'list' => 'List',
                            ])
                            ->required()
                            ->default('grid'),
                        ColorPicker::make('theme_color')->default('#10b981'),
                        ColorPicker::make('accent_color')->default('#059669'),
                        FileUpload::make('logo_path')
                            ->disk('public')
                            ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/jpeg', 'image/webp'])
                            ->directory('logos')
                            ->imagePreviewHeight('100')
                            ->helperText('SVG, PNG, JPG, or WebP. Maksimal 2MB.'),
                        FileUpload::make('og_image_path')
                            ->disk('public')
                            ->image()
                            ->directory('seo')
                            ->label('Social Share Image (Optional)'),
                    ])->columns(2),

                Section::make('Content (Sections & Links)')
                    ->schema([
                        Repeater::make('sections')
                            ->relationship('sections')
                            ->schema([
                                Select::make('type')
                                    ->label('Section Layout')
                                    ->options([
                                        'grid' => 'Grid',
                                        'list' => 'List',
                                    ])
                                    ->required(),
                                Toggle::make('is_active')
                                    ->default(true),
                                Group::make([
                                    TextInput::make('config.title')->label('Section Title')->columnSpan(1),
                                    Textarea::make('config.description')->label('Section Description')->rows(2)->columnSpanFull(),
                                ])->columns(2),
                                Repeater::make('links')
                                    ->relationship('links')
                                    ->schema([
                                        TextInput::make('title')->required(),
                                        TextInput::make('url')->label('URL'),
                                        IconPicker::make('icon')
                                            ->label('Icon')
                                            ->iconsSearchResults()
                                            ->columns(6),
                                        TextInput::make('badge_text'),
                                        Toggle::make('is_active')->default(true),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull()
                                    ->orderColumn('order')
                                    ->collapsible()
                                    ->itemLabel(fn(array $state): ?string => $state['title'] ?? null),
                            ])
                            ->orderColumn('order')
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['type'] ?? null),
                    ])->columnSpanFull(),
            ]);
    }
}
