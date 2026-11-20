<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $microsite->meta_title ?? $microsite->title }} | BPS Kab. Demak</title>
    
    @if($microsite->description)
        <meta name="description" content="{{ $microsite->meta_description ?? $microsite->description }}">
    @endif
    
    <meta property="og:title" content="{{ $microsite->meta_title ?? $microsite->title }}">
    @if($microsite->description)
        <meta property="og:description" content="{{ $microsite->meta_description ?? $microsite->description }}">
    @endif
    <meta property="og:type" content="website">
    @if($microsite->og_image_path ?? $microsite->logo_path)
        <meta property="og:image" content="{{ asset('storage/' . ($microsite->og_image_path ?? $microsite->logo_path)) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --color-bps-blue: {{ $microsite->theme_color ?? '#005bab' }};
            --color-bps-green: {{ $microsite->accent_color ?? '#4eb441' }};
            --color-bps-orange: {{ $microsite->accent_color ?? '#ff991d' }};
            
            --primary: var(--color-bps-blue);
            --accent: var(--color-bps-green);
        }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="{ search: '', qrOpen: false }" class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Super Clean Background (No dirty spots / no weird radial blobs) -->
    <div class="fixed inset-0 z-[-1] pointer-events-none bg-slate-50"></div>

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 px-4 sm:px-6 py-4">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between px-5 sm:px-7 py-3 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3 cursor-pointer group" onclick="window.location.href='/'">
                    <img src="{{ $microsite->logo_path ? asset('storage/' . $microsite->logo_path) : asset('images/logo.png') }}"
                         alt="Logo {{ $microsite->title }}"
                         class="h-8 w-auto object-contain">
                    <div class="border-l border-slate-200 pl-3">
                        <span class="block text-xs sm:text-sm font-black text-slate-900 uppercase tracking-tight leading-none mb-0.5">PORTAL TERPADU</span>
                        <span class="block text-[8px] sm:text-[9px] font-bold text-slate-500 uppercase tracking-widest">BPS KABUPATEN DEMAK</span>
                    </div>
                </div>

                <!-- Navigation Actions -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <button @click="qrOpen = true"
                        type="button"
                        class="px-3 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100/80 hover:bg-slate-200/80 border border-slate-200 transition-colors flex items-center gap-1.5 cursor-pointer"
                        title="Tampilkan QR Code Portal">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <span class="hidden sm:inline">QR Code</span>
                    </button>

                    <button onclick="shareMicrosite()"
                        type="button"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100/80 hover:bg-slate-200/80 border border-slate-200 transition-colors flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        <span class="hidden sm:inline">Bagikan</span>
                    </button>

                    @auth
                        <a href="{{ url('/admin') }}"
                           class="px-4 py-2 rounded-xl text-xs font-bold text-white transition-opacity hover:opacity-90 shadow-xs"
                           style="background-color: var(--primary);">
                            Panel Admin
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="pt-8 pb-6 px-4 sm:px-6 text-center">
        <div class="max-w-4xl mx-auto">
            <!-- Badge Resmi -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200/90 shadow-2xs mb-5">
                <span class="w-2 h-2 rounded-full" style="background-color: var(--accent);"></span>
                <span class="text-xs font-semibold text-slate-700">Portal Resmi BPS Kab. Demak</span>
                @if($microsite->category)
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600">{{ $microsite->category->name }}</span>
                @endif
            </div>

            <!-- Title -->
            <h1 class="text-2xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                {{ $microsite->hero_title ?? $microsite->title }}
            </h1>

            <!-- Subtitle / Deskripsi (hanya jika berbeda dari judul) -->
            @php
                $desc = $microsite->hero_subtitle ?? $microsite->description;
            @endphp
            @if($desc && trim(strtolower($desc)) !== trim(strtolower($microsite->title)))
                <p class="mt-4 text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                    {{ $desc }}
                </p>
            @endif
        </div>
    </header>

    <!-- Live Search Bar -->
    <div class="max-w-md mx-auto px-4 mb-8 w-full">
        <div class="relative flex items-center">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text"
                x-model="search"
                placeholder="Cari tautan atau materi..."
                style="padding-left: 2.5rem; padding-right: 2.25rem;"
                class="w-full py-2.5 text-xs sm:text-sm bg-white rounded-xl border border-slate-200 shadow-2xs focus:outline-none focus:ring-2 focus:border-transparent text-slate-800 placeholder:text-slate-400">
            <button x-show="search.length > 0" @click="search = ''" x-cloak
                type="button"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Sibling Year Tabs (jika seri tahunan) -->
    @if($microsite->series_id && isset($siblings) && $siblings->count() > 1)
        <div class="max-w-6xl mx-auto px-4 sm:px-6 mb-8 flex justify-center">
            <div class="inline-flex p-1 bg-white rounded-xl border border-slate-200 shadow-2xs">
                @foreach($siblings as $sibling)
                    @php
                        $siblingYear = $sibling->start_date 
                            ? date('Y', strtotime($sibling->start_date)) 
                            : $sibling->created_at->format('Y');
                        $isActive = $sibling->id === $microsite->id;
                    @endphp
                    
                    <a href="{{ route('redirect.handle', $sibling->slug) }}" 
                       class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $isActive 
                            ? 'bg-slate-100 text-slate-900 shadow-2xs' 
                            : 'text-slate-500 hover:text-slate-800' }}">
                        
                        @if($isActive)
                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: var(--primary);"></span>
                        @endif
                        
                        Tahun {{ $siblingYear }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Dynamic Sections -->
    <main class="flex-grow pb-20 px-4 sm:px-6">
        <div class="max-w-6xl mx-auto flex flex-col gap-10">
            @foreach($microsite->sections as $section)
                @if(view()->exists("components.sections.{$section->type}"))
                    @include("components.sections.{$section->type}", ['section' => $section])
                @else
                    <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl text-amber-700 text-sm">
                        Section component '{{ $section->type }}' not found.
                    </div>
                @endif
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-auto py-8 px-4 sm:px-6">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto" alt="Logo BPS">
                <div>
                   <h4 class="text-xs font-black tracking-tight text-slate-900 leading-none">BADAN PUSAT STATISTIK</h4>
                   <h4 class="text-[9px] font-bold tracking-widest text-slate-500 leading-none uppercase mt-0.5">KABUPATEN DEMAK</h4>
                </div>
            </div>

            <p class="text-slate-500 text-xs text-center">
                &copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Demak. Seluruh hak cipta dilindungi.
            </p>

            <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">
                Data Mencerdaskan Bangsa
            </span>
        </div>
    </footer>

    <!-- QR Code Modal -->
    <div x-show="qrOpen" x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
        @keydown.escape.window="qrOpen = false">

        <div @click.away="qrOpen = false"
            class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full shadow-2xl border border-slate-100 text-center relative">

            <button @click="qrOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-2 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="mb-4">
                <h3 class="text-base font-extrabold text-slate-900">{{ $microsite->title }}</h3>
                <p class="text-xs text-slate-500 mt-1">Pindai QR code ini untuk membuka langsung di ponsel</p>
            </div>

            <div class="p-3 bg-slate-50 border border-slate-100 rounded-2xl inline-block mb-5">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=8&data={{ urlencode(request()->url()) }}"
                     alt="QR Code {{ $microsite->title }}"
                     class="w-48 h-48 mx-auto rounded-xl">
            </div>

            <div class="flex flex-col gap-2">
                <button onclick="shareMicrosite()"
                    class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-white transition-opacity hover:opacity-90 cursor-pointer"
                    style="background-color: var(--primary);">
                    Salin & Bagikan Tautan
                </button>
                <button @click="qrOpen = false"
                    class="w-full py-2 px-4 rounded-xl text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function shareMicrosite() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ addslashes($microsite->title) }}',
                    text: '{{ addslashes($microsite->description ?? '') }}',
                    url: window.location.href,
                }).catch((error) => console.log('Error sharing', error));
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Tautan portal berhasil disalin ke clipboard!');
                });
            }
        }
    </script>
</body>

</html>
