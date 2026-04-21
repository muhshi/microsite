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
                ->label('View Live')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (Microsite $record): string => route('redirect.handle', $record->slug))
                ->openUrlInNewTab()
                ->visible(fn (Microsite $record): bool => $record->is_published)
                ->color('success'),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
