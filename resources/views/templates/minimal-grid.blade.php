<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $microsite->meta_title ?? $microsite->title }}</title>
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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        :root {
            --primary: {{ $microsite->theme_color ?? '#6366f1' }};
            --accent: {{ $microsite->accent_color ?? '#818cf8' }};
        }

        .glass-card {
            background: white;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08), 0 4px 10px -3px rgba(0,0,0,0.04);
            border-color: #d1d5db;
        }

        .animate-gradient-x {
            background-size: 200% 200%;
            animation: gradient-x 6s ease infinite;
        }

        @keyframes gradient-x {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .icon-glow {
            box-shadow: 0 4px 14px color-mix(in srgb, var(--primary) 20%, transparent);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 font-sans antialiased overflow-x-hidden selection:bg-indigo-100 min-h-screen flex flex-col">

    {{-- Background Decoration --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-[600px] h-[600px] rounded-full opacity-[0.06]"
            style="background: radial-gradient(circle, var(--primary), transparent 70%);"></div>
        <div class="absolute top-1/3 -right-40 w-[500px] h-[500px] rounded-full opacity-[0.05]"
            style="background: radial-gradient(circle, var(--accent), transparent 70%);"></div>
        <div class="absolute -bottom-40 left-1/4 w-[700px] h-[700px] rounded-full opacity-[0.04]"
            style="background: radial-gradient(circle, #3b82f6, transparent 70%);"></div>
    </div>

    {{-- Navigation --}}
    <nav class="relative z-50 sticky top-0 border-b border-gray-200/80 bg-white/80 backdrop-blur-xl">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 sm:h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($microsite->logo_path)
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center overflow-hidden bg-gray-50 border border-gray-200 p-1 shadow-sm">
                        <img src="{{ asset('storage/' . $microsite->logo_path) }}" alt="Logo"
                            class="w-full h-full object-contain">
                    </div>
                @endif
                <span class="text-lg sm:text-xl font-bold tracking-tight text-gray-900">
                    {{ $microsite->title }}
                </span>
            </div>

            <button onclick="shareMicrosite()"
                class="px-4 py-2 rounded-full text-sm font-medium bg-gray-100 hover:bg-gray-200 border border-gray-200 text-gray-700 transition-all duration-300 hover:shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                </svg>
                <span class="hidden sm:inline">Share</span>
            </button>
        </div>
    </nav>

    {{-- Hero --}}
    <header class="relative z-10 pt-12 sm:pt-20 pb-8 sm:pb-12 px-4 sm:px-6 text-center">
        @if($microsite->category)
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-6 border"
                style="background-color: color-mix(in srgb, var(--primary) 8%, white); border-color: color-mix(in srgb, var(--primary) 15%, transparent);">
                <span class="w-2 h-2 rounded-full animate-pulse"
                    style="background-color: var(--primary); box-shadow: 0 0 6px var(--primary);"></span>
                <span class="text-sm font-medium tracking-wide" style="color: var(--primary);">
                    {{ str($microsite->category)->title()->replace('_', ' ') }}
                </span>
            </div>
        @endif

        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight mb-6 leading-[1.1] max-w-4xl mx-auto">
            <span class="bg-clip-text text-transparent bg-gradient-to-r animate-gradient-x"
                style="background-image: linear-gradient(to right, var(--primary), var(--accent), var(--primary));">
                {{ $microsite->hero_title ?? $microsite->title }}
            </span>
        </h1>

        @if($microsite->hero_subtitle ?? $microsite->description)
            <p class="text-base sm:text-lg md:text-xl text-gray-500 max-w-2xl mx-auto leading-relaxed font-light">
                {{ $microsite->hero_subtitle ?? $microsite->description }}
            </p>
        @endif
    </header>

    {{-- Dynamic Sections --}}
    <main class="relative z-10 flex-grow pb-16 sm:pb-24 px-4 sm:px-6">
        <div class="max-w-6xl mx-auto flex flex-col gap-10 sm:gap-14">
            @foreach($microsite->sections as $section)
                @if(view()->exists("components.sections.{$section->type}"))
                    @include("components.sections.{$section->type}", ['section' => $section])
                @else
                    <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl text-amber-700 text-sm">
                        Section component '{{ $section->type }}' not found.
                    </div>
                @endif
            @endforeach
        </div>
    </main>

    {{-- Footer --}}
    <footer class="relative z-10 border-t border-gray-200 mt-auto bg-white/60 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-gray-400 text-xs sm:text-sm font-light text-center sm:text-left">
                &copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Demak.
            </p>
            <div class="flex items-center gap-2 text-gray-400 text-xs sm:text-sm font-light">
                <span>Made with <span class="text-rose-500 mx-1">♥</span> for data lovers.</span>
            </div>
        </div>
    </footer>

    <script>
        function shareMicrosite() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $microsite->title }}',
                    text: '{{ $microsite->description }}',
                    url: window.location.href,
                })
                    .then(() => console.log('Successful share'))
                    .catch((error) => console.log('Error sharing', error));
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const btn = document.querySelector('button[onclick="shareMicrosite()"]');
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg><span>Copied!</span>';
                    setTimeout(() => { btn.innerHTML = originalHTML; }, 2000);
                });
            }
        }
    </script>
</body>

</html>