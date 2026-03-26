@php
    $columns = $section->config['columns'] ?? $microsite->layout_type === 'list' ? 1 : 3;
    $gridClass = match ((int) $columns) {
        1 => 'grid-cols-1 max-w-3xl mx-auto',
        2 => 'grid-cols-1 sm:grid-cols-2 max-w-4xl mx-auto',
        4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

<div class="w-full" x-data="{ sectionOpen: true }">
    {{-- Section Header (Collapsible) --}}
    @if(isset($section->config['title']))
        <button @click="sectionOpen = !sectionOpen" class="w-full flex items-center justify-between mb-8 group cursor-pointer">
            <div class="text-left">
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 group-hover:text-gray-700 transition-colors">
                    {{ $section->config['title'] }}
                </h2>
                @if(isset($section->config['description']))
                    <p class="mt-2 text-base sm:text-lg text-gray-500 font-light">{{ $section->config['description'] }}</p>
                @endif
            </div>
            <div class="shrink-0 w-10 h-10 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center ml-4 group-hover:bg-gray-200 transition-all">
                <svg class="w-5 h-5 text-gray-500 transition-transform duration-300"
                    :class="sectionOpen ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </button>
    @endif

    {{-- Links Grid --}}
    <div x-show="sectionOpen" x-collapse.duration.400ms class="grid gap-4 sm:gap-6 {{ $gridClass }}">
        @foreach($section->links as $link)
            @if($link->children && $link->children->count() > 0)
                {{-- Parent Link — Collapsible Card --}}
                <div x-data="{ childOpen: false }" class="glass-card rounded-2xl overflow-hidden">
                    <button @click="childOpen = !childOpen"
                        class="w-full p-6 sm:p-8 flex flex-col items-center text-center gap-4 cursor-pointer group">

                        @if($link->icon)
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 icon-glow"
                                style="background: linear-gradient(135deg, color-mix(in srgb, var(--primary) 12%, white), color-mix(in srgb, var(--accent) 8%, white));">
                                @svg($link->icon, 'w-7 h-7 sm:w-8 sm:h-8', ['style' => 'color: var(--primary);'])
                            </div>
                        @endif

                        <div>
                            <h3 class="font-semibold text-gray-900 text-base sm:text-lg group-hover:text-gray-700 transition-colors flex items-center gap-2 justify-center">
                                {{ $link->title }}
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-300"
                                    :class="childOpen ? 'rotate-180' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </h3>

                            @if($link->badge_text)
                                <span class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-medium"
                                    style="background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);">
                                    {{ $link->badge_text }}
                                </span>
                            @endif
                        </div>
                    </button>

                    {{-- Child Links --}}
                    <div x-show="childOpen" x-collapse.duration.300ms class="px-4 sm:px-6 pb-4 sm:pb-6">
                        <div class="border-t border-gray-100 pt-4 flex flex-col gap-2">
                            @foreach($link->children as $child)
                                <a href="{{ $child->url }}"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 border border-gray-100 hover:border-gray-200 transition-all duration-200 group/child"
                                    target="_blank" rel="noopener">
                                    @if($child->icon)
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                                            style="background-color: color-mix(in srgb, var(--primary) 10%, white);">
                                            @svg($child->icon, 'w-4 h-4', ['style' => 'color: var(--primary);'])
                                        </div>
                                    @endif
                                    <span class="text-sm text-gray-600 group-hover/child:text-gray-900 transition-colors truncate">{{ $child->title }}</span>
                                    @if($child->badge_text)
                                        <span class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-medium"
                                            style="background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);">
                                            {{ $child->badge_text }}
                                        </span>
                                    @endif
                                    <svg class="w-4 h-4 text-gray-300 group-hover/child:text-gray-500 transition-colors ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                {{-- Regular Link Card --}}
                <a href="{{ $link->url }}"
                    class="block glass-card rounded-2xl p-6 sm:p-8 hover:-translate-y-1 transition-all duration-300 group"
                    target="_blank" rel="noopener">

                    <div class="flex flex-col items-center text-center gap-4">
                        @if($link->icon)
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 icon-glow"
                                style="background: linear-gradient(135deg, color-mix(in srgb, var(--primary) 12%, white), color-mix(in srgb, var(--accent) 8%, white));">
                                @svg($link->icon, 'w-7 h-7 sm:w-8 sm:h-8', ['style' => 'color: var(--primary);'])
                            </div>
                        @endif

                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1 text-base sm:text-lg group-hover:text-gray-700 transition-colors">
                                {{ $link->title }}
                            </h3>

                            @if($link->badge_text)
                                <span class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-medium"
                                    style="background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);">
                                    {{ $link->badge_text }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</div>