<div class="max-w-4xl mx-auto w-full" x-data="{ sectionOpen: true }">
    {{-- Section Header --}}
    @if(isset($section->config['title']))
        <div class="flex items-center justify-between pb-3 mb-5 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <span class="w-1.5 h-6 rounded-full" style="background-color: var(--primary);"></span>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                        {{ $section->config['title'] }}
                    </h2>
                    @if(isset($section->config['description']))
                        <p class="text-xs sm:text-sm text-slate-500 font-normal mt-0.5">{{ $section->config['description'] }}</p>
                    @endif
                </div>
            </div>

            <button @click="sectionOpen = !sectionOpen"
                type="button"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                title="Buka / Tutup Seksi">
                <svg class="w-5 h-5 transition-transform duration-200"
                    :class="sectionOpen ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Links List --}}
    <div x-show="sectionOpen" x-collapse.duration.300ms class="flex flex-col gap-2.5">
        @foreach($section->links as $link)
            @if($link->children && $link->children->count() > 0)
                {{-- Parent Link (Collapsible Row) --}}
                @php
                    $childrenText = $link->children->pluck('title')->implode(' ') . ' ' . $link->children->pluck('badge_text')->implode(' ');
                    $parentSearchKeywords = strtolower($link->title . ' ' . ($link->badge_text ?? '') . ' ' . $childrenText);
                @endphp

                <div x-data="{ childOpen: false }"
                     x-show="!search || '{{ addslashes($parentSearchKeywords) }}'.includes(search.toLowerCase())"
                     x-cloak
                     class="bg-white border border-slate-200 shadow-2xs hover:shadow-sm hover:border-slate-300 transition-all rounded-xl overflow-hidden">
                    
                    <button @click="childOpen = !childOpen"
                        type="button"
                        class="w-full p-4 flex items-center gap-3.5 cursor-pointer group text-left">

                        @if($link->icon)
                            <div class="w-10 h-10 rounded-lg shrink-0 flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform"
                                style="background-color: color-mix(in srgb, var(--primary) 10%, white);">
                                @svg($link->icon, 'w-5 h-5', ['style' => 'color: var(--primary);'])
                            </div>
                        @endif

                        <div class="flex-grow min-w-0">
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-slate-700 transition-colors truncate">
                                {{ $link->title }}
                            </h3>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[10px] font-semibold text-slate-500">
                                    {{ $link->children->count() }} tautan
                                </span>
                                @if($link->badge_text)
                                    <span class="text-slate-300">•</span>
                                    <span class="text-[10px] font-semibold" style="color: var(--primary);">
                                        {{ $link->badge_text }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="shrink-0 text-slate-400 group-hover:text-slate-600 transition-colors ml-1">
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="(childOpen || search.length > 0) ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    {{-- Child Links --}}
                    <div x-show="childOpen || search.length > 0" x-collapse.duration.200ms class="px-4 pb-3">
                        <div class="border-t border-slate-100 pt-2.5 flex flex-col gap-1.5">
                            @foreach($link->children as $child)
                                @php
                                    $childKeyword = strtolower($child->title . ' ' . ($child->badge_text ?? ''));
                                @endphp
                                <a href="{{ $child->url }}"
                                    x-show="!search || '{{ addslashes($childKeyword) }}'.includes(search.toLowerCase()) || '{{ addslashes(strtolower($link->title)) }}'.includes(search.toLowerCase())"
                                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 transition-colors group/child"
                                    target="_blank" rel="noopener">
                                    @if($child->icon)
                                        <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0"
                                            style="background-color: color-mix(in srgb, var(--primary) 10%, white);">
                                            @svg($child->icon, 'w-3.5 h-3.5', ['style' => 'color: var(--primary);'])
                                        </div>
                                    @endif
                                    <span class="text-xs font-medium text-slate-700 group-hover/child:text-slate-900 transition-colors flex-grow truncate text-left">{{ $child->title }}</span>
                                    @if($child->badge_text)
                                        <span class="shrink-0 px-2 py-0.5 rounded-full text-[9px] font-semibold"
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
                    class="bg-white border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-slate-300 p-3.5 sm:p-4 rounded-xl flex items-center gap-3.5 transition-all group hover:translate-x-0.5"
                    target="_blank" rel="noopener">

                    @if($link->icon)
                        <div class="w-10 h-10 rounded-lg shrink-0 flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform"
                            style="background-color: color-mix(in srgb, var(--primary) 10%, white);">
                            @svg($link->icon, 'w-5 h-5', ['style' => 'color: var(--primary);'])
                        </div>
                    @endif

                    <div class="flex-grow min-w-0 text-left">
                        <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-slate-700 transition-colors truncate">
                            {{ $link->title }}
                        </h3>
                    </div>

                    @if($link->badge_text)
                        <span class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-semibold"
                            style="background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);">
                            {{ $link->badge_text }}
                        </span>
                    @endif

                    <div class="shrink-0 text-slate-300 group-hover:text-slate-500 transition-colors group-hover:translate-x-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</div>
