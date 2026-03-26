<div class="w-full" x-data="{ sectionOpen: true }">
    {{-- Section Header (Collapsible) --}}
    @if(isset($section->config['title']))
        <button @click="sectionOpen = !sectionOpen" class="w-full flex items-center justify-between mb-6 group cursor-pointer">
            <div class="text-left">
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 group-hover:text-gray-700 transition-colors">
                    {{ $section->config['title'] }}
                </h2>
                @if(isset($section->config['description']))
                    <p class="mt-2 text-base text-gray-500 font-light">{{ $section->config['description'] }}</p>
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

    {{-- Links List --}}
    <div x-show="sectionOpen" x-collapse.duration.400ms class="flex flex-col gap-3">
        @foreach($section->links as $link)
            @if($link->children && $link->children->count() > 0)
                {{-- Parent Link — Collapsible Row --}}
                <div x-data="{ childOpen: false }" class="glass-card rounded-2xl overflow-hidden">
                    <button @click="childOpen = !childOpen"
                        class="w-full p-5 sm:p-6 flex items-center gap-5 cursor-pointer group">

                        @if($link->icon)
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl shrink-0 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 icon-glow"
                                style="background: linear-gradient(135deg, color-mix(in srgb, var(--primary) 12%, white), color-mix(in srgb, var(--accent) 8%, white));">
                                @svg($link->icon, 'w-6 h-6 sm:w-7 sm:h-7', ['style' => 'color: var(--primary);'])
                            </div>
                        @endif

                        <div class="flex-grow text-left">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-gray-700 transition-colors">
                                {{ $link->title }}
                            </h3>
                        </div>

                        @if($link->badge_text)
                            <span class="shrink-0 px-3 py-1 rounded-full text-xs font-medium"
                                style="background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);">
                                {{ $link->badge_text }}
                            </span>
                        @endif

                        <div class="shrink-0 text-gray-400 group-hover:text-gray-600 transition-colors ml-1">
                            <svg class="w-5 h-5 transition-transform duration-300"
                                :class="childOpen ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    {{-- Child Links --}}
                    <div x-show="childOpen" x-collapse.duration.300ms class="px-5 sm:px-6 pb-4 sm:pb-5">
                        <div class="border-t border-gray-100 pt-3 flex flex-col gap-2">
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
                                    <span class="text-sm text-gray-600 group-hover/child:text-gray-900 transition-colors flex-grow truncate">{{ $child->title }}</span>
                                    @if($child->badge_text)
                                        <span class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-medium"
                                            style="background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);">
                                            {{ $child->badge_text }}
                                        </span>
                                    @endif
                                    <svg class="w-4 h-4 text-gray-300 group-hover/child:text-gray-500 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                {{-- Regular Link Row --}}
                <a href="{{ $link->url }}"
                    class="glass-card rounded-2xl p-5 sm:p-6 flex items-center gap-5 group transition-all duration-300"
                    target="_blank" rel="noopener">

                    @if($link->icon)
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl shrink-0 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 icon-glow"
                            style="background: linear-gradient(135deg, color-mix(in srgb, var(--primary) 12%, white), color-mix(in srgb, var(--accent) 8%, white));">
                            @svg($link->icon, 'w-6 h-6 sm:w-7 sm:h-7', ['style' => 'color: var(--primary);'])
                        </div>
                    @endif

                    <div class="flex-grow">
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-gray-700 transition-colors">
                            {{ $link->title }}
                        </h3>
                    </div>

                    @if($link->badge_text)
                        <span class="shrink-0 px-3 py-1 rounded-full text-xs font-medium"
                            style="background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);">
                            {{ $link->badge_text }}
                        </span>
                    @endif

                    <div class="shrink-0 text-gray-300 group-hover:text-gray-500 transition-colors ml-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</div>