<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $microsite->title }}</title>
    @if($microsite->description)
        <meta name="description" content="{{ $microsite->description }}">
    @endif

    @if($microsite->og_image_path ?? $microsite->logo_path)
        <meta property="og:image" content="{{ asset('storage/' . ($microsite->og_image_path ?? $microsite->logo_path)) }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary:
                {{ $microsite->theme_color ?? '#10b981' }}
            ;
            --accent:
                {{ $microsite->accent_color ?? '#059669' }}
            ;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            color: #111827;
        }

        .theme-primary {
            background-color: var(--primary);
        }

        .theme-text-primary {
            color: var(--primary);
        }

        .theme-border-primary {
            border-color: var(--primary);
        }

        .theme-accent {
            background-color: var(--accent);
        }
    </style>
</head>

<body class="antialiased selection:bg-[var(--primary)] selection:text-white">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex flex-col">
        <!-- Top Navigation Bar -->
        <header class="w-full flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-2">
            </div>
            <div class="flex items-center gap-2 flex-col text-center">
                @if($microsite->logo_path)
                    <img src="{{ asset('storage/' . $microsite->logo_path) }}" alt="Logo" class="h-8 w-auto object-contain">
                @endif
            </div>
            <div>
                <button onclick="shareMicrosite()"
                    class="theme-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition">
                    Share
                </button>
            </div>
        </header>

        <main class="flex-grow pt-8 md:pt-16 pb-12 w-full">
            <!-- Hero Section -->
            <section class="max-w-5xl mx-auto px-4 text-center">
                @if($microsite->category)
                    <div class="inline-block px-4 py-2 rounded-full mb-6"
                        style="background-color: color-mix(in srgb, var(--primary) 10%, transparent);">
                        <span class="text-sm font-medium" style="color: var(--primary);">
                            {{ str($microsite->category)->title()->replace('_', ' ') }}
                        </span>
                    </div>
                @endif

                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $microsite->title }}
                </h1>

                @if($microsite->description)
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-12 leading-relaxed">
                        {{ $microsite->description }}
                    </p>
                @endif
            </section>

            <!-- Dynamic Child Sections (Usually just Links) -->
            <div class="max-w-5xl mx-auto px-4 w-full flex flex-col gap-12 md:gap-16">
                @foreach($microsite->sections as $section)
                    @if(view()->exists("components.sections.{$section->type}"))
                        @include("components.sections.{$section->type}", ['section' => $section])
                    @else
                        <div class="bg-yellow-50 p-4 rounded text-yellow-800 text-sm mt-8">
                            Section component '{{ $section->type }}' not found.
                        </div>
                    @endif
                @endforeach
            </div>
        </main>

        <footer class="px-4 py-8 mt-auto">
            <div class="max-w-5xl mx-auto text-center">
                <p class="text-sm text-gray-500 mb-2">Need help? Contact us at support@training.gov</p>
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Government Training & Development. All rights
                    reserved.</p>
            </div>
        </footer>
    </div>
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
                    alert('Link copied to clipboard!');
                });
            }
        }
    </script>
</body>

</html>