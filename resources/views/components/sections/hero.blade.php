<div class="text-center space-y-4 py-12 md:py-20">
    @if(isset($section->config['label']))
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium theme-text-primary bg-opacity-10"
            style="background-color: color-mix(in srgb, var(--primary) 10%, transparent);">
            {{ $section->config['label'] }}
        </span>
    @endif

    <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">
        {{ $section->config['title'] ?? $microsite->hero_title ?? $microsite->title }}
    </h2>

    <p class="text-xl text-gray-500 max-w-2xl mx-auto">
        {{ $section->config['subtitle'] ?? $microsite->hero_subtitle }}
    </p>

    @if($section->links->count() > 0)
        <div class="mt-8 flex justify-center gap-4 flex-wrap">
            @foreach($section->links as $link)
                <a href="{{ $link->url }}"
                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white theme-primary hover:opacity-90 transition shadow-sm">
                    @if($link->icon)
                        <span class="mr-2">{{-- Wait we need x-icon or something but since we use blade-heroicons we can do this
                            --}}
                            @svg($link->icon, 'w-5 h-5')
                        </span>
                    @endif
                    {{ $link->title }}
                    @if($link->badge_text)
                        <span
                            class="ml-2 px-2 py-0.5 rounded-full text-xs bg-white text-gray-900 shadow-sm">{{ $link->badge_text }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</div>