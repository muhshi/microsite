<?php

namespace App\Filament\Resources\ShortLinks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShortLinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyableState(fn ($record): string => $record->getShortUrl())
                    ->copyMessage('Short URL disalin!')
                    ->weight('bold')
                    ->color('primary'),
                TextColumn::make('original_url')
                    ->label('URL Asli')
                    ->limit(50)
                    ->url(fn ($record): string => $record->original_url)
                    ->openUrlInNewTab()
                    ->searchable(),
                TextColumn::make('clicks')
                    ->label('Klik')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('expires_at')
                    ->label('Kadaluarsa')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Tidak ada'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
