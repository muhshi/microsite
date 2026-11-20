@php
    $linkCount = $section->links->count();
    
    // Tentukan container grid yang proporsional sesuai jumlah link
    $gridCols = match (true) {
        $linkCount === 1 => 'max-w-md mx-auto grid grid-cols-1 gap-5',
        $linkCount === 2 => 'max-w-3xl mx-auto grid grid-cols-1 sm:grid-cols-2 gap-5',
        default => 'max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5',
    };
@endphp

<div class="w-full" x-data="{ sectionOpen: true }">
    {{-- Section Header --}}
    @if(isset($section->config['title']))
        <div class="flex items-center justify-between pb-3 mb-6 border-b border-slate-200">
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

    {{-- Links Grid --}}
    <div x-show="sectionOpen" x-collapse.duration.300ms class="{{ $gridCols }} w-full">
        @foreach($section->links as $link)
            @if($link->children && $link->children->count() > 0)
                {{-- Parent Link (Collapsible Card) --}}
                @php
                    $childrenText = $link->children->pluck('title')->implode(' ') . ' ' . $link->children->pluck('badge_text')->implode(' ');
                    $parentSearchKeywords = strtolower($link->title . ' ' . ($link->badge_text ?? '') . ' ' . $childrenText);
                @endphp

                <div x-data="{ childOpen: false }"
                     x-show="!search || '{{ addslashes($parentSearchKeywords) }}'.includes(search.toLowerCase())"
                     x-cloak
                     class="bg-white border border-slate-200 shadow-xs hover:shadow-md hover:border-slate-300 transition-all duration-200 rounded-2xl overflow-hidden flex flex-col justify-between">
                    
                    <button @click="childOpen = !childOpen"
                        type="button"
                        class="w-full p-5 sm:p-6 flex flex-col items-center text-center gap-3 cursor-pointer group">

                        @if($link->icon)
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform"
                                style="background-color: color-mix(in srgb, var(--primary) 10%, white);">
                                @svg($link->icon, 'w-6 h-6', ['style' => 'color: var(--primary);'])
                            </div>
                        @endif

                        <div class="w-full">
                            <h3 class="font-bold text-slate-900 text-base group-hover:text-slate-700 transition-colors flex items-center gap-1.5 justify-center">
                                <span>{{ $link->title }}</span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                    :class="(childOpen || search.length > 0) ? 'rotate-180' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </h3>

                            <div class="flex items-center justify-center gap-1.5 mt-2 flex-wrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                    {{ $link->children->count() }} tautan
                                </span>
                                @if($link->badge_text)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                        style="background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);">
                                        {{ $link->badge_text }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </button>

                    {{-- Child Links --}}
                    <div x-show="childOpen || search.length > 0" x-collapse.duration.200ms class="px-4 pb-4">
                        <div class="border-t border-slate-100 pt-3 flex flex-col gap-1.5">
                            @foreach($link->children as $child)
                                @php
                                    $childKeyword = strtolower($child->title . ' ' . ($child->badge_text ?? ''));
                                @endphp
                                <a href="{{ $child->url }}"
                                    x-show="!search || '{{ addslashes($childKeyword) }}'.includes(search.toLowerCase()) || '{{ addslashes(strtolower($link->title)) }}'.includes(search.toLowerCase())"
                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-100 hover:border-slate-200 transition-colors group/child"
                                    target="_blank" rel="noopener">
                                    @if($child->icon)
                                        <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0"
                                            style="background-color: color-mix(in srgb, var(--primary) 10%, white);">
                                            @svg($child->icon, 'w-3.5 h-3.5', ['style' => 'color: var(--primary);'])
                                        </div>
                                    @endif
                                    <span class="text-xs font-medium text-slate-700 group-hover/child:text-slate-900 transition-colors truncate flex-grow text-left">{{ $child->title }}</span>
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
                {{-- Regular Link Bento Card --}}
                @php
                    $regularKeywords = strtolower($link->title . ' ' . ($link->badge_text ?? ''));
                @endphp
                <a href="{{ $link->url }}"
                    x-show="!search || '{{ addslashes($regularKeywords) }}'.includes(search.toLowerCase())"
                    x-cloak
                    class="relative bg-white border border-slate-200/90 shadow-xs hover:shadow-lg hover:border-slate-300 rounded-2xl p-5 sm:p-6 hover:-translate-y-1 transition-all duration-200 group flex flex-col items-center text-center justify-between"
                    target="_blank" rel="noopener">

                    <!-- Corner Arrow Indicator -->
                    <div class="absolute top-3.5 right-3.5 text-slate-300 group-hover:text-slate-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </div>

                    <div class="flex flex-col items-center text-center gap-3 w-full py-2">
                        @if($link->icon)
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform"
                                style="background-color: color-mix(in srgb, var(--primary) 10%, white);">
                                @svg($link->icon, 'w-6 h-6', ['style' => 'color: var(--primary);'])
                            </div>
                        @endif

                        <div class="w-full">
                            <h3 class="font-bold text-slate-900 text-base group-hover:text-slate-700 transition-colors leading-snug">
                                {{ $link->title }}
                            </h3>

                            @if($link->badge_text)
                                <span class="mt-2 inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold"
                                    style="background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary);">
                                    {{ $link->badge_text }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Action Link -->
                    <div class="mt-3 pt-3 border-t border-slate-100 w-full flex items-center justify-center gap-1 text-xs font-semibold text-slate-400 group-hover:text-slate-600 transition-colors">
                        <span>Buka Tautan</span>
                        <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</div>
