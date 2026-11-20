<?php

namespace App\Filament\Resources\Microsites\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MicrositeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('MicrositeTabs')
                    ->tabs([
                        Tab::make('Informasi Utama')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Group::make([
                                    Select::make('category_id')
                                        ->label('Kategori Portal')
                                        ->relationship('category', 'name')
                                        ->required()
                                        ->searchable()
                                        ->preload()
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->label('Nama Kategori')
                                                ->required(),
                                        ]),

                                    Select::make('series_id')
                                        ->label('Seri Kegiatan (opsional)')
                                        ->relationship('series', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('Pilih Seri / Induk Kegiatan')
                                        ->helperText('Kelompokkan microsite dalam satu seri lintas tahun (misal: Sakernas).')
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->label('Nama Seri')
                                                ->required(),
                                        ]),
                                ])->columns(2),

                                TextInput::make('title')
                                    ->label('Judul Portal')
                                    ->placeholder('Contoh: Portal Sensus Pertanian 2026')
                                    ->required()
                                    ->columnSpanFull(),

                                Textarea::make('description')
                                    ->label('Deskripsi Singkat')
                                    ->placeholder('Tulis deskripsi tujuan portal atau instruksi bagi pengunjung...')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Group::make([
                                    DatePicker::make('start_date')
                                        ->label('Tanggal Mulai')
                                        ->native(false),
                                    DatePicker::make('end_date')
                                        ->label('Tanggal Berakhir')
                                        ->native(false),
                                ])->columns(2),

                                Section::make('Pengaturan Visibilitas')
                                    ->description('Atur status tayang dan batasan hak akses pengunjung portal.')
                                    ->schema([
                                        Toggle::make('is_published')
                                            ->label('Publikasikan Portal')
                                            ->helperText('Jika aktif, portal dapat diakses sesuai URL slug.')
                                            ->default(true)
                                            ->required(),

                                        Toggle::make('is_public')
                                            ->label('Akses Terbuka untuk Umum')
                                            ->helperText('Jika nonaktif, hanya pegawai yang login via SIPETRA SSO yang dapat mengakses portal.')
                                            ->default(true)
                                            ->required(),
                                    ])->columns(2),
                            ]),

                        Tab::make('Desain & Branding')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Group::make([
                                    Select::make('template_key')
                                        ->label('Gaya Tampilan (Template)')
                                        ->options([
                                            'minimal-grid' => 'Minimal Bento Grid (Rekomendasi)',
                                            'soft-gradient' => 'Soft Gradient',
                                        ])
                                        ->required()
                                        ->default('minimal-grid'),

                                    Select::make('layout_type')
                                        ->label('Tata Letak Default')
                                        ->options([
                                            'grid' => 'Grid (Kartu Berjajar)',
                                            'list' => 'List (Daftar Baris Horizontal)',
                                        ])
                                        ->required()
                                        ->default('grid'),
                                ])->columns(2),

                                Group::make([
                                    ColorPicker::make('theme_color')
                                        ->label('Warna Utama (Theme Color)')
                                        ->helperText('Warna header, tombol aksi, dan sorotan utama.')
                                        ->default('#005bab'),

                                    ColorPicker::make('accent_color')
                                        ->label('Warna Aksen (Accent Color)')
                                        ->helperText('Warna badge, ambient glow, dan indikator aktif.')
                                        ->default('#4eb441'),
                                ])->columns(2),

                                FileUpload::make('logo_path')
                                    ->label('Logo Khusus Portal (Opsional)')
                                    ->disk('public')
                                    ->image()
                                    ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/jpeg', 'image/webp'])
                                    ->directory('logos')
                                    ->maxSize(2048)
                                    ->imageResizeMode('contain')
                                    ->imageResizeTargetWidth('500')
                                    ->imageResizeTargetHeight('500')
                                    ->imageEditor()
                                    ->imagePreviewHeight('120')
                                    ->helperText('Format SVG, PNG, JPG, atau WebP. Otomatis dikompresi & dipotong (maks. 2MB). Jika dikosongkan, logo default BPS akan digunakan.')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Konten & Tautan')
                            ->icon('heroicon-o-queue-list')
                            ->schema([
                                Repeater::make('sections')
                                    ->label('Daftar Seksi Konten')
                                    ->relationship('sections')
                                    ->addActionLabel('Tambah Seksi Konten')
                                    ->orderColumn('order')
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => filled($state['config']['title'] ?? null)
                                        ? ($state['config']['title'].' ('.ucfirst($state['type'] ?? 'grid').')')
                                        : 'Seksi Baru ('.ucfirst($state['type'] ?? 'grid').')')
                                    ->schema([
                                        Group::make([
                                            Select::make('type')
                                                ->label('Tata Letak Seksi Ini')
                                                ->options([
                                                    'grid' => 'Grid Kartu',
                                                    'list' => 'List Baris',
                                                ])
                                                ->required()
                                                ->default('grid'),

                                            Toggle::make('is_active')
                                                ->label('Seksi Aktif')
                                                ->default(true),
                                        ])->columns(2),

                                        Group::make([
                                            TextInput::make('config.title')
                                                ->label('Judul Seksi')
                                                ->placeholder('Misal: Layanan Utama, Dokumen Panduan, atau Aplikasi')
                                                ->columnSpanFull(),

                                            Textarea::make('config.description')
                                                ->label('Deskripsi Seksi (opsional)')
                                                ->placeholder('Penjelasan singkat tentang isi tautan dalam seksi ini...')
                                                ->rows(2)
                                                ->columnSpanFull(),
                                        ]),

                                        Repeater::make('links')
                                            ->label('Tautan dalam Seksi')
                                            ->relationship('links')
                                            ->addActionLabel('Tambah Tautan Baru')
                                            ->orderColumn('order')
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => filled($state['title'] ?? null)
                                                ? ($state['title'].(filled($state['badge_text'] ?? null) ? ' ['.$state['badge_text'].']' : ''))
                                                : 'Tautan Baru')
                                            ->schema([
                                                Group::make([
                                                    TextInput::make('title')
                                                        ->label('Nama / Judul Tautan')
                                                        ->placeholder('Misal: Panduan Sensus Tani 2026')
                                                        ->required(),

                                                    TextInput::make('url')
                                                        ->label('Alamat Web (URL)')
                                                        ->placeholder('https://...'),
                                                ])->columns(2),

                                                Group::make([
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
                                                        ->label('Label Badge (opsional)')
                                                        ->placeholder('Misal: Baru, PDF, Penting, Excel'),
                                                ])->columns(2),

                                                Group::make([
                                                    Select::make('parent_id')
                                                        ->label('Jadikan Sub-Tautan dari (Parent Link)')
                                                        ->relationship(
                                                            'parent',
                                                            'title',
                                                            modifyQueryUsing: fn (Builder $query, ?Model $record, $livewire) => $query
                                                                ->when($record, fn ($q) => $q->where('id', '!=', $record->getKey()))
                                                                ->whereNull('parent_id')
                                                                ->when(
                                                                    isset($livewire->record) && $livewire->record->exists,
                                                                    fn ($q) => $q->where('microsite_id', $livewire->record->id),
                                                                    fn ($q) => $q->whereNull('id')
                                                                ),
                                                        )
                                                        ->searchable()
                                                        ->preload()
                                                        ->placeholder('Tautan Tingkat Utama (Top-level)')
                                                        ->helperText('Jika dipilih, tautan ini akan tersimpan sebagai anak di dalam kartu parent.'),

                                                    Toggle::make('is_active')
                                                        ->label('Tautan Aktif')
                                                        ->default(true),
                                                ])->columns(2),
                                            ])
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
