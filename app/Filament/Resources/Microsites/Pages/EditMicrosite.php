<?php

namespace App\Filament\Resources\Microsites\Pages;

use App\Filament\Resources\Microsites\MicrositeResource;
use App\Models\Microsite;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditMicrosite extends EditRecord
{
    protected static string $resource = MicrositeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_live')
                ->label(fn (Microsite $record): string => $record->is_published ? 'Buka Live' : 'Preview Draft')
                ->icon(fn (Microsite $record): string => $record->is_published ? 'heroicon-o-arrow-top-right-on-square' : 'heroicon-o-eye')
                ->color(fn (Microsite $record): string => $record->is_published ? 'success' : 'warning')
                ->url(fn (Microsite $record): string => route('redirect.handle', $record->slug))
                ->openUrlInNewTab(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
