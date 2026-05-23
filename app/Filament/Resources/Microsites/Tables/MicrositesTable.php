<?php

namespace App\Filament\Resources\Microsites\Tables;

use App\Models\Microsite;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

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
                                ColorPicker::make('theme_color')
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
                                ColorPicker::make('accent_color')
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
                IconColumn::make('is_public')
                    ->label('Public')
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
                Action::make('duplicate')
                    ->label('Duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('info')
                    ->requiresConfirmation()
                    ->tooltip('Duplikasi microsite ini beserta seluruh seksi & link di dalamnya')
                    ->action(function (Microsite $record): void {
                        // 1. Duplikasi data utama microsite
                        $newMicrosite = $record->replicate();
                        $newMicrosite->title = $record->title.' (Copy)';
                        $newMicrosite->slug = $record->slug.'-'.Str::lower(Str::random(4));
                        $newMicrosite->is_published = false; // default draft
                        $newMicrosite->save();

                        // 2. Duplikasi seksi-seksinya
                        foreach ($record->sections as $section) {
                            $newSection = $section->replicate();
                            $newSection->microsite_id = $newMicrosite->id;
                            $newSection->save();

                            // Ambil semua parent link di seksi ini (parent_id = null)
                            $parentLinks = $section->links()->whereNull('parent_id')->get();

                            foreach ($parentLinks as $link) {
                                // Duplikasi parent link
                                $newLink = $link->replicate();
                                $newLink->microsite_id = $newMicrosite->id;
                                $newLink->section_id = $newSection->id;
                                $newLink->save();

                                // Duplikasi child link di bawah parent ini
                                foreach ($link->children as $child) {
                                    $newChild = $child->replicate();
                                    $newChild->microsite_id = $newMicrosite->id;
                                    $newChild->section_id = $newSection->id;
                                    $newChild->parent_id = $newLink->id; // pasang ke parent baru
                                    $newChild->save();
                                }
                            }
                        }

                        Notification::make()
                            ->title('Microsite berhasil diduplikasi!')
                            ->success()
                            ->send();
                    }),
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
