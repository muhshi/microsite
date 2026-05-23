<?php

namespace App\Filament\Widgets;

use App\Models\Microsite;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ActiveMicrositesTable extends TableWidget
{
    protected static ?string $heading = 'Daftar Portal Aktif (Live)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => Microsite::query()
                    ->where('is_published', true)
                    ->latest('published_at')
            )
            ->columns([
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Nama Portal')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_public')
                    ->label('Tipe Akses')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-open')
                    ->falseIcon('heroicon-o-lock-closed')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn (bool $state): string => $state ? 'Terbuka untuk Publik' : 'Terbatas (BPS SSO)'),

                TextColumn::make('start_date')
                    ->label('Periode Aktif')
                    ->formatStateUsing(
                        fn ($record): string => ($record->start_date ? date('d M Y', strtotime($record->start_date)) : '-').
                        ' s.d '.
                        ($record->end_date ? date('d M Y', strtotime($record->end_date)) : '-')
                    ),
            ])
            ->recordActions([
                Action::make('view_live')
                    ->label('View Live')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Microsite $record): string => route('redirect.handle', $record->slug))
                    ->openUrlInNewTab()
                    ->color('success'),
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Microsite $record): string => route('filament.admin.resources.microsites.edit', $record))
                    ->color('primary'),
            ]);
    }
}
