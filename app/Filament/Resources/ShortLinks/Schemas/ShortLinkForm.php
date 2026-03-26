<?php

namespace App\Filament\Resources\ShortLinks\Schemas;

use App\Models\Microsite;
use App\Models\ShortLink;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class ShortLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Link')
                    ->schema([
                        TextInput::make('original_url')
                            ->label('URL Asli (Panjang)')
                            ->required()
                            ->url()
                            ->columnSpanFull()
                            ->placeholder('https://example.com/very/long/url/here'),
                        TextInput::make('code')
                            ->label('Kode Pendek')
                            ->helperText('Kosongkan untuk generate otomatis. Harus unik.')
                            ->placeholder('Contoh: sensus2026')
                            ->maxLength(50)
                            ->rules([
                                'alpha_dash',
                                fn ($record) => Rule::unique('short_links', 'code')->ignore($record),
                                function () {
                                    return function (string $attribute, $value, $fail) {
                                        if ($value && Microsite::where('slug', $value)->exists()) {
                                            $fail('Kode ini sudah dipakai sebagai slug microsite.');
                                        }
                                    };
                                },
                            ]),
                    ])->columns(2),

                Section::make('Pengaturan')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                        DateTimePicker::make('expires_at')
                            ->label('Kadaluarsa (Opsional)')
                            ->placeholder('Tidak ada batas waktu')
                            ->native(false),
                        Placeholder::make('clicks')
                            ->label('Total Klik')
                            ->content(fn (?ShortLink $record): string => $record ? number_format($record->clicks) : '0')
                            ->visibleOn('edit'),
                        Placeholder::make('short_url')
                            ->label('Short URL')
                            ->content(fn (?ShortLink $record): string => $record ? $record->getShortUrl() : '-')
                            ->visibleOn('edit'),
                    ])->columns(2),
            ]);
    }
}
