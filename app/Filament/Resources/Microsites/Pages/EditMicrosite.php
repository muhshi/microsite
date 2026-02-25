<?php

namespace App\Filament\Resources\Microsites\Pages;

use App\Filament\Resources\Microsites\MicrositeResource;
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
            \Filament\Actions\Action::make('view_live')
                ->label('View Live')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn(\App\Models\Microsite $record): string => route('microsite.show', $record->slug))
                ->openUrlInNewTab()
                ->visible(fn(\App\Models\Microsite $record): bool => $record->is_published)
                ->color('success'),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
