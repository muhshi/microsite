<div class="w-full" x-data="{ sectionOpen: true }">
    {{-- Section Header (Collapsible) --}}
    @if(isset($section->config['title']))
        <button @click="sectionOpen = !sectionOpen" class="w-full flex items-center justify-between mb-6 group cursor-pointer text-left">
            <div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 group-hover:text-slate-700 transition-colors flex items-center gap-2">
                    {{ $section->config['title'] }}
                </h2>
                @if(isset($section->config['description']))
                    <p class="mt-1.5 text-sm sm:text-base text-slate-500 font-normal leading-relaxed">{{ $section->config['description'] }}</p>
                @endif
            </div>
            <div class="shrink-0 w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-100 border border-slate-200/80 flex items-center justify-center ml-4 group-hover:bg-slate-200/80 transition-all shadow-2xs">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-500 transition-transform duration-300"
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
                @php
                    $childrenText = $link->children->pluck('title')->implode(' ') . ' ' . $link->children->pluck('badge_text')->implode(' ');
                    $parentSearchKeywords = strtolower($link->title . ' ' . ($link->badge_text ?? '') . ' ' . $childrenText);
                @endphp

                <div x-data="{ childOpen: false }"
                     x-show="!search || '{{ addslashes($parentSearchKeywords) }}'.includes(search.toLowerCase())"
                     x-cloak
                     class="bg-white/95 backdrop-blur-xs border border-slate-200 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-300 rounded-2xl overflow-hidden">
                    
                    <button @click="childOpen = !childOpen"
                        class="w-full p-4 sm:p-5 flex items-center gap-4 cursor-pointer group text-left">

                        @if($link->icon)
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl shrink-0 flex items-center justify-center group-hover:scale-105 transition-transform duration-300 shadow-2xs"
                                style="background-color: color-mix(in srgb, var(--primary) 8%, white);">
                                @svg($link->icon, 'w-5 h-5 sm:w-6 sm:h-6', ['style' => 'color: var(--primary);'])
                            </div>
                        @endif

                        <div class="flex-grow min-w-0">
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-slate-700 transition-colors truncate">
                                {{ $link->title }}
                            </h3>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[10px] sm:text-[11px] font-semibold text-slate-500">
                                    {{ $link->children->count() }} tautan
                                </span>
                                @if($link->badge_text)
                                    <span class="text-slate-300">&bull;</span>
                                    <span class="text-[10px] sm:text-[11px] font-semibold" style="color: var(--primary);">
                                        {{ $link->badge_text }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="shrink-0 text-slate-400 group-hover:text-slate-600 transition-colors ml-1">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform duration-300"
                                :class="(childOpen || search.length > 0) ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    {{-- Child Links --}}
                    <div x-show="childOpen || search.length > 0" x-collapse.duration.300ms class="px-4 sm:px-5 pb-4 sm:pb-5">
                        <div class="border-t border-slate-100 pt-3 flex flex-col gap-2">
                            @foreach($link->children as $child)
                                @php
                                    $childKeyword = strtolower($child->title . ' ' . ($child->badge_text ?? ''));
                                @endphp
                                <a href="{{ $child->url }}"
                                    x-show="!search || '{{ addslashes($childKeyword) }}'.includes(search.toLowerCase()) || '{{ addslashes(strtolower($link->title)) }}'.includes(search.toLowerCase())"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-slate-50/90 hover:bg-slate-100/90 border border-slate-100 hover:border-slate-200 transition-all duration-200 group/child"
                                    target="_blank" rel="noopener">
                                    @if($child->icon)
                                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0"
                                            style="background-color: color-mix(in srgb, var(--primary) 10%, white);">
                                            @svg($child->icon, 'w-3.5 h-3.5', ['style' => 'color: var(--primary);'])
                                        </div>
                                    @endif
                                    <span class="text-xs sm:text-sm font-medium text-slate-700 group-hover/child:text-slate-900 transition-colors flex-grow truncate text-left">{{ $child->title }}</span>
                                    @if($child->badge_text)
                                        <span class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                            style="background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);">
                                            {{ $child->badge_text }}
                                        </span>
                                    @endif
                                    <svg class="w-3.5 h-3.5 text-slate-300 group-hover/child:text-slate-500 transition-colors ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                {{-- Regular Link Row --}}
                @php
                    $regularKeywords = strtolower($link->title . ' ' . ($link->badge_text ?? ''));
                @endphp
                <a href="{{ $link->url }}"
                    x-show="!search || '{{ addslashes($regularKeywords) }}'.includes(search.toLowerCase())"
                    x-cloak
                    class="bg-white/95 backdrop-blur-xs border border-slate-200 shadow-2xs hover:shadow-md hover:border-slate-300 p-4 sm:p-5 rounded-2xl flex items-center gap-4 transition-all duration-300 group hover:-translate-x-0.5"
                    target="_blank" rel="noopener">

                    @if($link->icon)
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl shrink-0 flex items-center justify-center group-hover:scale-105 transition-transform duration-300 shadow-2xs"
                            style="background-color: color-mix(in srgb, var(--primary) 8%, white);">
                            @svg($link->icon, 'w-5 h-5 sm:w-6 sm:h-6', ['style' => 'color: var(--primary);'])
                        </div>
                    @endif

                    <div class="flex-grow min-w-0 text-left">
                        <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-slate-700 transition-colors truncate">
                            {{ $link->title }}
                        </h3>
                    </div>

                    @if($link->badge_text)
                        <span class="shrink-0 px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold"
                            style="background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);">
                            {{ $link->badge_text }}
                        </span>
                    @endif

                    <div class="shrink-0 text-slate-300 group-hover:text-slate-500 transition-colors group-hover:translate-x-0.5">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</div>
