<?php

namespace App\Filament\Resources\Links\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Tautan')
                    ->schema([
                        TextInput::make('title')
                            ->label('Nama / Judul Tautan')
                            ->required(),
                        TextInput::make('url')
                            ->label('Alamat Web (URL)')
                            ->url(),
                        TextInput::make('icon')
                            ->label('Ikon Tautan')
                            ->placeholder('Klik untuk memilih ikon...')
                            ->readOnly()
                            ->prefixIcon(fn ($state) => filled($state) ? $state : null)
                            ->extraInputAttributes([
                                'x-on:click' => '$el.closest(\'[data-field-wrapper]\').querySelector(\'button[title*="Pilih"], button\').click()',
                                'style' => 'cursor: pointer;',
                            ])
                            ->suffixActions([
                                Action::make('selectIcon')
                                    ->label('Pilih')
                                    ->icon('heroicon-m-squares-2x2')
                                    ->tooltip('Buka katalog ikon')
                                    ->modalHeading('Pilih Ikon Tautan')
                                    ->modalIcon(false)
                                    ->modalWidth('4xl')
                                    ->modalContent(fn ($component): View => view('filament.components.icon-picker-modal', ['statePath' => $component->getStatePath()]))
                                    ->modalSubmitAction(false)
                                    ->modalCancelAction(false),
                                Action::make('clearIcon')
                                    ->label('Hapus')
                                    ->icon('heroicon-m-x-mark')
                                    ->color('danger')
                                    ->tooltip('Kosongkan ikon')
                                    ->visible(fn ($state) => filled($state))
                                    ->action(fn ($component) => $component->state(null)),
                            ]),
                        TextInput::make('badge_text')
                            ->label('Label Badge (opsional)'),
                    ])->columns(2),

                Section::make('Relasi & Penempatan')
                    ->schema([
                        Select::make('microsite_id')
                            ->label('Microsite Induk')
                            ->relationship('microsite', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih Microsite (opsional)')
                            ->live(),
                        Select::make('section_id')
                            ->label('Seksi Konten')
                            ->relationship(
                                'section',
                                'type',
                                modifyQueryUsing: fn (Builder $query, Get $get): Builder => $query
                                    ->when(
                                        $get('microsite_id'),
                                        fn ($q, $micrositeId) => $q->where('microsite_id', $micrositeId),
                                        fn ($q) => $q->whereNull('id')
                                    ),
                            )
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih Seksi (opsional)'),
                        Select::make('parent_id')
                            ->label('Jadikan Sub-Tautan dari (Parent Link)')
                            ->relationship(
                                'parent',
                                'title',
                                modifyQueryUsing: fn (Builder $query, ?Model $record, Get $get): Builder => $query
                                    ->when($record, fn ($q) => $q->where('id', '!=', $record->getKey()))
                                    ->whereNull('parent_id')
                                    ->when(
                                        $get('microsite_id'),
                                        fn ($q, $micrositeId) => $q->where('microsite_id', $micrositeId),
                                        fn ($q) => $q->whereNull('id')
                                    ),
                            )
                            ->searchable()
                            ->preload()
                            ->placeholder('Tidak ada parent (tingkat utama)'),
                        TextInput::make('order')
                            ->label('Nomor Urut')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
