@php
    $columns = $section->config['columns'] ?? $microsite->layout_type === 'list' ? 1 : 3;
    $gridClass = match ((int) $columns) {
        1 => 'grid-cols-1 max-w-3xl mx-auto',
        2 => 'grid-cols-1 sm:grid-cols-2 max-w-4xl mx-auto',
        4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

<div class="w-full">
    @if(isset($section->config['title']))
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">{{ $section->config['title'] }}</h2>
            @if(isset($section->config['description']))
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">{{ $section->config['description'] }}</p>
            @endif
        </div>
    @endif

    <div class="grid gap-6 md:gap-8 {{ $gridClass }}">
        @foreach($section->links as $link)
            <a href="{{ $link->url }}"
                class="block bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group hover:-translate-y-1">

                <div class="flex flex-col items-center text-center gap-4">
                    @if($link->icon)
                        <div class="w-16 h-16 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300"
                            style="background: linear-gradient(to bottom right, color-mix(in srgb, var(--primary) 10%, transparent), #f3e8ff);">
                            @svg($link->icon, 'w-8 h-8', ['style' => 'color: var(--primary);'])
                        </div>
                    @endif

                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2 text-lg">
                            {{ $link->title }}
                        </h3>

                        @if(isset($link->description))
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $link->description }}</p>
                        @endif

                        @if($link->badge_text)
                            <span class="mt-4 inline-block px-3 py-1 rounded-full text-xs font-medium"
                                style="background-color: color-mix(in srgb, var(--primary) 10%, transparent); color: var(--primary);">
                                {{ $link->badge_text }}
                            </span>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>