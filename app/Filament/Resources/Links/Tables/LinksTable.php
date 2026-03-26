<?php

namespace App\Filament\Resources\Links\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width('60px'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('url')
                    ->label('URL')
                    ->limit(40)
                    ->url(fn ($record): ?string => $record->url)
                    ->openUrlInNewTab()
                    ->color('primary'),
                TextColumn::make('microsite.title')
                    ->label('Microsite')
                    ->badge()
                    ->sortable(),
                TextColumn::make('section.type')
                    ->label('Section')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('parent.title')
                    ->label('Parent')
                    ->placeholder('—'),
                TextColumn::make('badge_text')
                    ->label('Badge')
                    ->badge()
                    ->color('success')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('microsite_id')
                    ->label('Microsite')
                    ->relationship('microsite', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('section_id')
                    ->label('Section')
                    ->relationship('section', 'type')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
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
