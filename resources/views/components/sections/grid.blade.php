@php
    $columns = $section->config['columns'] ?? ($microsite->layout_type === 'list' ? 1 : 3);
    
    // Container-level max-width limits
    $containerClass = match ((int) $columns) {
        1 => 'max-w-3xl mx-auto',
        2 => 'max-w-4xl mx-auto',
        default => 'max-w-6xl mx-auto',
    };

    // Item-level width styling to make sure cards remain consistent in size and center leftovers
    $itemClass = match ((int) $columns) {
        1 => 'w-full',
        2 => 'w-full sm:w-[calc(50%-12px)]',
        4 => 'w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)]',
        default => 'w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]', // 3 columns
    };
@endphp

<div class="w-full" x-data="{ sectionOpen: true }">
    {{-- Section Header (Collapsible) --}}
    @if(isset($section->config['title']))
        <button @click="sectionOpen = !sectionOpen" class="w-full flex items-center justify-between mb-6 sm:mb-8 group cursor-pointer text-left">
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

    {{-- Links Grid --}}
    <div x-show="sectionOpen" x-collapse.duration.400ms class="flex flex-wrap justify-center gap-4 sm:gap-6 w-full {{ $containerClass }}">
        @foreach($section->links as $link)
            @if($link->children && $link->children->count() > 0)
                {{-- Parent Link — Collapsible Card --}}
                @php
                    $childrenText = $link->children->pluck('title')->implode(' ') . ' ' . $link->children->pluck('badge_text')->implode(' ');
                    $parentSearchKeywords = strtolower($link->title . ' ' . ($link->badge_text ?? '') . ' ' . $childrenText);
                @endphp

                <div x-data="{ childOpen: false }"
                     x-show="!search || '{{ addslashes($parentSearchKeywords) }}'.includes(search.toLowerCase())"
                     x-cloak
                     class="bg-white/95 backdrop-blur-xs border border-slate-200 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-300 rounded-3xl overflow-hidden flex flex-col justify-between {{ $itemClass }}">
                    
                    <button @click="childOpen = !childOpen"
                        class="w-full p-6 sm:p-7 flex flex-col items-center text-center gap-3.5 cursor-pointer group">

                        @if($link->icon)
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center group-hover:scale-105 transition-transform duration-300 shadow-2xs"
                                style="background-color: color-mix(in srgb, var(--primary) 8%, white);">
                                @svg($link->icon, 'w-7 h-7 sm:w-8 sm:h-8', ['style' => 'color: var(--primary);'])
                            </div>
                        @endif

                        <div class="w-full">
                            <h3 class="font-bold text-slate-900 text-base sm:text-lg group-hover:text-slate-700 transition-colors flex items-center gap-2 justify-center">
                                <span>{{ $link->title }}</span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-300"
                                    :class="(childOpen || search.length > 0) ? 'rotate-180' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </h3>

                            <div class="flex items-center justify-center gap-1.5 mt-2 flex-wrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200/60">
                                    {{ $link->children->count() }} tautan
                                </span>
                                @if($link->badge_text)
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold"
                                        style="background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);">
                                        {{ $link->badge_text }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </button>

                    {{-- Child Links --}}
                    <div x-show="childOpen || search.length > 0" x-collapse.duration.300ms class="px-4 sm:px-6 pb-4 sm:pb-6">
                        <div class="border-t border-slate-100 pt-3.5 flex flex-col gap-2">
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
                                    <span class="text-xs sm:text-sm font-medium text-slate-700 group-hover/child:text-slate-900 transition-colors truncate flex-grow text-left">{{ $child->title }}</span>
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
                {{-- Regular Link Card --}}
                @php
                    $regularKeywords = strtolower($link->title . ' ' . ($link->badge_text ?? ''));
                @endphp
                <a href="{{ $link->url }}"
                    x-show="!search || '{{ addslashes($regularKeywords) }}'.includes(search.toLowerCase())"
                    x-cloak
                    class="relative bg-white/95 backdrop-blur-xs border border-slate-200 shadow-2xs hover:shadow-md hover:border-slate-300 rounded-3xl p-6 sm:p-7 hover:-translate-y-1 transition-all duration-300 group flex flex-col items-center text-center justify-between {{ $itemClass }}"
                    target="_blank" rel="noopener">

                    <!-- Corner Arrow Indicator -->
                    <div class="absolute top-4 right-4 text-slate-300 group-hover:text-slate-500 transition-all duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </div>

                    <div class="flex flex-col items-center text-center gap-3.5 w-full">
                        @if($link->icon)
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center group-hover:scale-105 transition-transform duration-300 shadow-2xs"
                                style="background-color: color-mix(in srgb, var(--primary) 8%, white);">
                                @svg($link->icon, 'w-7 h-7 sm:w-8 sm:h-8', ['style' => 'color: var(--primary);'])
                            </div>
                        @endif

                        <div class="w-full">
                            <h3 class="font-bold text-slate-900 text-base sm:text-lg group-hover:text-slate-700 transition-colors leading-snug">
                                {{ $link->title }}
                            </h3>

                            @if($link->badge_text)
                                <span class="mt-2 inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold"
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
