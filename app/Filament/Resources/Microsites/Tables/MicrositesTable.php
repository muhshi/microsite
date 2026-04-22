<?php

namespace App\Filament\Resources\Microsites\Tables;

use App\Models\Microsite;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MicrositesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                ColorColumn::make('theme_color')
                    ->action(
                        Action::make('edit_theme_color')
                            ->form([
                                \Filament\Forms\Components\ColorPicker::make('theme_color')
                                    ->required(),
                            ])
                            ->fillForm(fn (Microsite $record): array => [
                                'theme_color' => $record->theme_color,
                            ])
                            ->action(function (Microsite $record, array $data): void {
                                $record->update($data);
                            })
                    ),
                ColorColumn::make('accent_color')
                    ->action(
                        Action::make('edit_accent_color')
                            ->form([
                                \Filament\Forms\Components\ColorPicker::make('accent_color')
                                    ->required(),
                            ])
                            ->fillForm(fn (Microsite $record): array => [
                                'accent_color' => $record->accent_color,
                            ])
                            ->action(function (Microsite $record, array $data): void {
                                $record->update($data);
                            })
                    ),
                IconColumn::make('is_published')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('view_live')
                    ->label('View Live')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Microsite $record): string => route('redirect.handle', $record->slug))
                    ->openUrlInNewTab()
                    ->visible(fn (Microsite $record): bool => $record->is_published),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
