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
    </style>
</head>

<body class="bg-slate-50 text-slate-900 font-sans antialiased overflow-x-hidden min-h-screen flex flex-col">

    <!-- Clean Grid Background -->
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden bg-slate-50">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0); background-size: 32px 32px; opacity: 0.6;"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-slate-50/90"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative z-50 sticky top-0 px-6 py-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between px-8 py-3.5 rounded-2xl bg-white border border-slate-200 shadow-sm transition-all duration-300">
                <div class="flex items-center gap-4 group cursor-pointer" onclick="window.location.href='/'">
                    <div class="p-1 rounded-xl">
                        <img src="{{ $microsite->logo_path ? asset('storage/' . $microsite->logo_path) : asset('images/logo.png') }}" alt="Logo {{ $microsite->title }}" class="h-8 w-auto">
                    </div>
                    <div class="border-l-2 border-slate-200 pl-4">
                        <span class="block text-sm font-black text-slate-900 uppercase tracking-tight leading-none mb-0.5">PORTAL TERPADU</span>
                        <span class="block text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em]">BPS Kabupaten Demak</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button onclick="shareMicrosite()"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 hover:text-slate-900 border border-slate-200 transition-all duration-300 flex items-center gap-2 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        <span class="hidden sm:inline">Share</span>
                    </button>
                    @auth
                        <a href="{{ url('/admin') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white transition-all duration-300 active:scale-95 shadow-sm hover:shadow-md" style="background-color: var(--primary);">Manage</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <header class="relative z-10 pt-16 pb-12 px-6 text-center reveal">
        @if($microsite->category)
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-slate-200 mb-8 shadow-sm">
                <span class="flex h-2 w-2 rounded-full" style="background-color: var(--accent);"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600">
                    {{ $microsite->category->name }}
                </span>
            </div>
        @endif

        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-6 leading-tight text-slate-900 reveal">
            {{ $microsite->hero_title ?? $microsite->title }}
        </h1>

        @if($microsite->hero_subtitle ?? $microsite->description)
            <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed font-medium reveal">
                {{ $microsite->hero_subtitle ?? $microsite->description }}
            </p>
        @endif
        
        <div class="w-16 h-1 mx-auto mt-10 rounded-full" style="background-color: var(--primary);"></div>
    </header>

    @if($microsite->series_id && isset($siblings) && $siblings->count() > 1)
            <div class="relative z-20 max-w-6xl mx-auto px-6 mb-10 -mt-2 flex justify-center reveal">
                <div class="inline-flex p-1.5 bg-white rounded-2xl border border-slate-200 shadow-sm">
                    @foreach($siblings as $sibling)
                        @php
                            $siblingYear = $sibling->start_date 
                                ? date('Y', strtotime($sibling->start_date)) 
                                : $sibling->created_at->format('Y');
                            $isActive = $sibling->id === $microsite->id;
                        @endphp
                        
                        <a href="{{ route('redirect.handle', $sibling->slug) }}" 
                           class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 {{ $isActive 
                                ? 'bg-slate-50 text-slate-900 ring-1 ring-slate-200/50' 
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
    <main class="relative z-10 flex-grow pb-24 px-6 reveal">
        <div class="max-w-6xl mx-auto flex flex-col gap-16">
            @foreach($microsite->sections as $section)
                @if(view()->exists("components.sections.{$section->type}"))
                    <div class="reveal">
                        @include("components.sections.{$section->type}", ['section' => $section])
                    </div>
                @else
                    <div class="glass border-amber-200 p-6 rounded-3xl text-amber-700 text-sm font-medium">
                        Section component '{{ $section->type }}' not found.
                    </div>
                @endif
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12">
        <div class="max-w-7xl mx-auto px-6 py-10 flex flex-col md:flex-row items-center justify-between gap-8">
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
    <button id="backToTop" class="fixed bottom-8 right-8 w-12 h-12 bg-white border border-slate-200 text-slate-600 rounded-xl shadow-sm hover:shadow-md flex items-center justify-center translate-y-20 opacity-0 transition-all duration-300 hover:bg-slate-50 hover:text-slate-900 z-[60]">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
    </button>

    <script>
        function shareMicrosite() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $microsite->title }}',
                    text: '{{ $microsite->description }}',
                    url: window.location.href,
                }).catch((error) => console.log('Error sharing', error));
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const btn = document.querySelector('button[onclick="shareMicrosite()"]');
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<svg class="w-4 h-4 text-bps-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg><span>Copied!</span>';
                    setTimeout(() => { btn.innerHTML = originalHTML; }, 2000);
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
            transform: translateY(20px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal-on {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    </style>
</body>

</html>