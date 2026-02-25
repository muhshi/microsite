<div class="w-full">
    @if(isset($section->config['title']))
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">{{ $section->config['title'] }}</h2>
            @if(isset($section->config['description']))
                <p class="mt-2 text-lg text-gray-500">{{ $section->config['description'] }}</p>
            @endif
        </div>
    @endif

    <div class="flex flex-col gap-4">
        @foreach($section->links as $link)
            <a href="{{ $link->url }}"
                class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-slate-100 hover:border-[var(--primary)] flex items-center gap-6">

                @if($link->icon)
                    <div class="w-14 h-14 rounded-xl flex-shrink-0 flex items-center justify-center group-hover:scale-110 transition-transform duration-300"
                        style="background: linear-gradient(to bottom right, color-mix(in srgb, var(--primary) 10%, transparent), #f3e8ff);">
                        @svg($link->icon, 'w-7 h-7', ['style' => 'color: var(--primary);'])
                    </div>
                @endif

                <div class="flex-grow">
                    <h3 class="text-lg font-bold text-slate-800 group-hover:text-[var(--primary)] transition-colors">
                        {{ $link->title }}
                    </h3>

                    @if(isset($link->description))
                        <p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ $link->description }}</p>
                    @endif
                </div>

                @if($link->badge_text)
                    <div class="flex-shrink-0">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium"
                            style="background-color: color-mix(in srgb, var(--primary) 10%, transparent); color: var(--primary);">
                            {{ $link->badge_text }}
                        </span>
                    </div>
                @endif

                <div class="flex-shrink-0 text-gray-400 group-hover:text-[var(--primary)] transition-colors ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </a>
        @endforeach
    </div>
</div>