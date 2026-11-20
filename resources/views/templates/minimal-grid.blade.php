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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --color-bps-blue: {{ $microsite->theme_color ?? '#005bab' }};
            --color-bps-green: {{ $microsite->accent_color ?? '#4eb441' }};
            --color-bps-orange: {{ $microsite->accent_color ?? '#ff991d' }};
            
            /* Darken the primary color for hover states */
            --color-bps-dark: color-mix(in srgb, var(--color-bps-blue), black 20%);
            
            /* Add transparency variants for the dynamic colors */
            --primary: var(--color-bps-blue);
            --accent: var(--color-bps-green);
        }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="{ search: '', qrOpen: false }" class="bg-slate-50 text-slate-900 font-sans antialiased overflow-x-hidden min-h-screen flex flex-col relative selection:bg-slate-200">

    <!-- Ambient Dynamic Glow Background -->
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden bg-slate-50">
        <!-- Radial Dot Grid -->
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0); background-size: 32px 32px; opacity: 0.6;"></div>
        <!-- Ambient Top Glow from Theme Colors -->
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[720px] h-[450px] rounded-full blur-3xl opacity-15 pointer-events-none" style="background: radial-gradient(circle, var(--primary) 0%, var(--accent) 70%, transparent 100%);"></div>
        <div class="absolute top-1/2 -right-48 w-[500px] h-[500px] rounded-full blur-3xl opacity-10 pointer-events-none" style="background: radial-gradient(circle, var(--accent) 0%, transparent 70%);"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-slate-50/95"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative z-50 sticky top-0 px-4 sm:px-6 py-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between px-5 sm:px-8 py-3 rounded-2xl bg-white/90 backdrop-blur-md border border-slate-200/90 shadow-sm transition-all duration-300">
                <div class="flex items-center gap-3.5 group cursor-pointer" onclick="window.location.href='/'">
                    <div class="p-1 rounded-xl bg-slate-50/80 border border-slate-100 flex items-center justify-center">
                        <img src="{{ $microsite->logo_path ? asset('storage/' . $microsite->logo_path) : asset('images/logo.png') }}" alt="Logo {{ $microsite->title }}" class="h-8 w-auto object-contain">
                    </div>
                    <div class="border-l-2 border-slate-200 pl-3.5">
                        <span class="block text-xs sm:text-sm font-black text-slate-900 uppercase tracking-tight leading-none mb-0.5">PORTAL TERPADU</span>
                        <span class="block text-[8px] sm:text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em]">BPS Kabupaten Demak</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- QR Code Button -->
                    <button @click="qrOpen = true"
                        class="px-3 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 hover:text-slate-900 border border-slate-200 transition-all duration-200 flex items-center gap-1.5 active:scale-95 shadow-2xs"
                        title="Tampilkan QR Code Portal">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <span class="hidden md:inline">QR Code</span>
                    </button>

                    <!-- Share Button -->
                    <button onclick="shareMicrosite()"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 hover:text-slate-900 border border-slate-200 transition-all duration-200 flex items-center gap-1.5 active:scale-95 shadow-2xs">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        <span class="hidden sm:inline">Bagikan</span>
                    </button>

                    @auth
                        <a href="{{ url('/admin') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white transition-all duration-200 active:scale-95 shadow-sm hover:shadow-md" style="background-color: var(--primary);">Panel Admin</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative z-10 pt-12 pb-8 px-6 text-center reveal">
        <!-- Official Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/90 backdrop-blur border border-slate-200 shadow-2xs mb-6">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background-color: var(--accent);"></span>
                <span class="relative inline-flex rounded-full h-2 w-2" style="background-color: var(--accent);"></span>
            </span>
            <span class="text-[11px] font-semibold text-slate-700 tracking-wide">
                Portal Resmi BPS Kab. Demak
            </span>
            @if($microsite->category)
                <span class="text-slate-300">|</span>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    {{ $microsite->category->name }}
                </span>
            @endif
        </div>

        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-5 leading-tight text-slate-900 max-w-4xl mx-auto">
            {{ $microsite->hero_title ?? $microsite->title }}
        </h1>

        @if($microsite->hero_subtitle ?? $microsite->description)
            <p class="text-base sm:text-lg md:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed font-normal">
                {{ $microsite->hero_subtitle ?? $microsite->description }}
            </p>
        @endif
        
        <div class="w-16 h-1 mx-auto mt-8 rounded-full" style="background-color: var(--primary);"></div>
    </header>

    <!-- Live Search Bar -->
    <div class="relative z-20 max-w-xl mx-auto px-6 mb-8 w-full reveal">
        <div class="relative flex items-center">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" x-model="search"
                placeholder="Cari tautan, sistem, atau berkas layanan..."
                class="w-full pl-11 pr-10 py-3 sm:py-3.5 text-xs sm:text-sm bg-white/95 backdrop-blur rounded-2xl border border-slate-200 shadow-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all placeholder:text-slate-400 text-slate-800"
                style="--tw-ring-color: var(--primary);">
            <button x-show="search.length > 0" @click="search = ''" x-cloak
                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div x-show="search.length > 0" x-cloak class="mt-2 text-center text-xs text-slate-500 font-medium">
            Menyaring tautan dengan kata kunci: "<span class="font-bold text-slate-800" x-text="search"></span>"
        </div>
    </div>

    <!-- Sibling Year Tabs -->
    @if($microsite->series_id && isset($siblings) && $siblings->count() > 1)
        <div class="relative z-20 max-w-6xl mx-auto px-6 mb-10 -mt-2 flex justify-center reveal">
            <div class="inline-flex p-1.5 bg-white/90 backdrop-blur rounded-2xl border border-slate-200 shadow-sm">
                @foreach($siblings as $sibling)
                    @php
                        $siblingYear = $sibling->start_date 
                            ? date('Y', strtotime($sibling->start_date)) 
                            : $sibling->created_at->format('Y');
                        $isActive = $sibling->id === $microsite->id;
                    @endphp
                    
                    <a href="{{ route('redirect.handle', $sibling->slug) }}" 
                       class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 {{ $isActive 
                            ? 'bg-slate-50 text-slate-900 ring-1 ring-slate-200/60 shadow-2xs' 
                            : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                        
                        @if($isActive)
                            <span class="h-1.5 w-1.5 rounded-full" style="background-color: var(--primary);"></span>
                        @endif
                        
                        Tahun {{ $siblingYear }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Dynamic Sections -->
    <main class="relative z-10 flex-grow pb-24 px-4 sm:px-6 reveal">
        <div class="max-w-6xl mx-auto flex flex-col gap-14 sm:gap-16">
            @foreach($microsite->sections as $section)
                @if(view()->exists("components.sections.{$section->type}"))
                    <div class="reveal">
                        @include("components.sections.{$section->type}", ['section' => $section])
                    </div>
                @else
                    <div class="bg-amber-50 border border-amber-200 p-6 rounded-3xl text-amber-700 text-sm font-medium">
                        Section component '{{ $section->type }}' not found.
                    </div>
                @endif
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-10 flex flex-col md:flex-row items-center justify-between gap-6 sm:gap-8">
            <div class="flex items-center gap-4">
                <div class="p-2 bg-slate-50 rounded-xl border border-slate-100">
                    <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto" alt="Logo BPS">
                </div>
                <div>
                   <h4 class="text-sm font-black tracking-tight text-slate-900 leading-none mb-1">BADAN PUSAT STATISTIK</h4>
                   <h4 class="text-[10px] font-bold tracking-widest text-slate-500 leading-none uppercase">KABUPATEN DEMAK</h4>
                </div>
            </div>

            <p class="text-slate-500 font-medium text-xs text-center md:text-left">
                &copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Demak. <br class="md:hidden"> Seluruh hak cipta dilindungi.
            </p>

            <div class="flex items-center gap-3 px-4 py-2 rounded-lg bg-slate-50 border border-slate-100">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-600">Data Mencerdaskan Bangsa</span>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <button id="backToTop" class="fixed bottom-8 right-8 w-11 h-11 bg-white border border-slate-200 text-slate-600 rounded-xl shadow-sm hover:shadow-md flex items-center justify-center translate-y-20 opacity-0 transition-all duration-300 hover:bg-slate-50 hover:text-slate-900 z-[60]" title="Kembali ke atas">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
    </button>

    <!-- QR Code Modal -->
    <div x-show="qrOpen" x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-300"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="qrOpen = false">

        <div @click.away="qrOpen = false"
            class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full shadow-2xl border border-slate-100 text-center relative"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">

            <!-- Close Button -->
            <button @click="qrOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-2 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- QR Header -->
            <div class="mb-5">
                <div class="w-12 h-12 mx-auto rounded-2xl flex items-center justify-center mb-3 shadow-2xs" style="background-color: color-mix(in srgb, var(--primary) 10%, white);">
                    <svg class="w-6 h-6" style="color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">{{ $microsite->title }}</h3>
                <p class="text-xs text-slate-500 mt-1">Pindai QR code ini untuk membuka langsung di ponsel</p>
            </div>

            <!-- QR Image -->
            <div class="p-3 bg-slate-50 border border-slate-100 rounded-2xl inline-block shadow-inner mb-5">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=8&data={{ urlencode(request()->url()) }}"
                     alt="QR Code {{ $microsite->title }}"
                     class="w-48 h-48 mx-auto rounded-xl">
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-2">
                <button onclick="shareMicrosite()"
                    class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-white transition-all shadow-sm active:scale-98"
                    style="background-color: var(--primary);">
                    Salin & Bagikan Tautan
                </button>
                <button @click="qrOpen = false"
                    class="w-full py-2 px-4 rounded-xl text-xs font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 transition-colors">
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

        // Scroll Reveal & Back to Top Logic
        const observerOptions = { threshold: 0.1 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-on');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach((el) => {
            el.classList.add('reveal-off');
            observer.observe(el);
        });

        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) {
                backToTop.classList.remove('translate-y-20', 'opacity-0');
            } else {
                backToTop.classList.add('translate-y-20', 'opacity-0');
            }
        });
        backToTop.addEventListener('click', () => {
             window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>

    <style>
        .reveal-off {
            opacity: 0;
            transform: translateY(16px);
            transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal-on {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    </style>
</body>

</html>
