<?php

namespace App\Filament\Resources\Links\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Link Details')
                    ->schema([
                        TextInput::make('title')
                            ->required(),
                        TextInput::make('url')
                            ->label('URL')
                            ->url(),
                        TextInput::make('icon')
                            ->label('Icon')
                            ->readOnly()
                            ->extraInputAttributes([
                                'x-on:click' => '$el.closest(\'[data-field-wrapper]\').querySelector(\'button\').click()',
                                'style' => 'cursor: pointer;',
                            ])
                            ->suffixAction(
                                Action::make('selectIcon')
                                    ->icon('heroicon-m-squares-2x2')
                                    ->modalHeading('Select Icon')
                                    ->modalIcon(false)
                                    ->modalWidth('4xl')
                                    ->modalContent(fn ($component): \Illuminate\Contracts\View\View => view('filament.components.icon-picker-modal', ['statePath' => $component->getStatePath()]))
                                    ->modalSubmitAction(false)
                                    ->modalCancelAction(false)
                            ),
                        TextInput::make('badge_text')
                            ->label('Badge Text'),
                    ])->columns(2),

                Section::make('Relasi & Pengaturan')
                    ->schema([
                        Select::make('microsite_id')
                            ->label('Microsite')
                            ->relationship('microsite', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih Microsite (opsional)'),
                        Select::make('section_id')
                            ->label('Section')
                            ->relationship('section', 'type')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih Section (opsional)'),
                        Select::make('parent_id')
                            ->label('Parent Link (opsional)')
                            ->relationship(
                                'parent',
                                'title',
                                modifyQueryUsing: fn (\Illuminate\Database\Eloquent\Builder $query, ?\Illuminate\Database\Eloquent\Model $record) => $query
                                    ->when($record, fn ($q) => $q->where('id', '!=', $record->getKey()))
                                    ->whereNull('parent_id'),
                            )
                            ->searchable()
                            ->preload()
                            ->placeholder('Tidak ada parent'),
                        TextInput::make('order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
