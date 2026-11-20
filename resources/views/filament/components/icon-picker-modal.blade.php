@php
    $groupData = \App\Services\IconPickerService::getGroupData();
@endphp

<style>
    .icon-picker-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(75px, 1fr)) !important;
        gap: 8px !important;
        max-height: 380px !important;
        overflow-y: auto !important;
        padding: 12px 16px !important;
    }

    .icon-picker-card {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 10px 4px !important;
        border-radius: 12px !important;
        border: 1px solid #e5e7eb !important;
        background: #ffffff;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        min-height: 68px !important;
        gap: 6px !important;
        box-sizing: border-box !important;
    }

    .dark .icon-picker-card {
        background: #1f2937;
        border-color: #374151 !important;
    }

    .icon-picker-card:hover {
        border-color: #f59e0b !important;
        background: #fffbeb !important;
        transform: translateY(-1px);
    }

    .dark .icon-picker-card:hover {
        background: #374151 !important;
        border-color: #f59e0b !important;
    }

    .icon-picker-card svg {
        width: 24px !important;
        height: 24px !important;
        flex-shrink: 0;
    }

    .icon-picker-label {
        font-size: 10px;
        line-height: 1.2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 100%;
        text-align: center;
        color: #4b5563;
        min-width: 0;
        font-weight: 500;
    }

    .dark .icon-picker-label {
        color: #9ca3af;
    }

    .icon-picker-tabs {
        display: flex !important;
        border-bottom: 2px solid #e5e7eb;
        padding: 0 16px;
        gap: 4px;
        overflow-x: auto;
    }

    .dark .icon-picker-tabs {
        border-color: #374151;
    }

    .icon-picker-tab {
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        background: transparent;
        cursor: pointer;
        color: #6b7280;
        border-radius: 8px 8px 0 0;
        transition: all 0.15s ease;
        white-space: nowrap;
    }

    .icon-picker-tab.active {
        color: #d97706;
        border-bottom: 2px solid #d97706;
        margin-bottom: -2px;
    }

    .dark .icon-picker-tab.active {
        color: #fbbf24;
        border-color: #fbbf24;
    }

    .ip-hidden {
        display: none !important;
    }
</style>

<div x-data="{
    search: '',
    activeTab: 'Populer',
    switchTab(group) {
        this.activeTab = group;
        this.search = '';
    },
    matchSearch(label) {
        return this.search === '' || label.toLowerCase().includes(this.search.toLowerCase());
    },
    selectIcon(name) {
        $wire.set('{{ $statePath }}', name);
        $dispatch('close-modal', { id: $el.closest('[x-ref]')?.getAttribute('x-ref') || Object.keys($wire.__instance.canonical.snapshots || {}).pop() });
        document.querySelectorAll('.fi-modal-close-btn').forEach(b => b.click());
    }
}" x-init="$nextTick(() => $el.querySelector('#icon-picker-search')?.focus())">
    {{-- Tabs --}}
    <div class="icon-picker-tabs">
        @foreach (array_keys($groupData) as $groupName)
            <button type="button" class="icon-picker-tab" :class="activeTab === '{{ $groupName }}' ? 'active' : ''"
                x-on:click="switchTab('{{ $groupName }}')">
                {{ $groupName }}
                @if($groupName === 'Populer')
                    <span class="ml-1 text-[10px] px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">★</span>
                @endif
            </button>
        @endforeach
    </div>

    {{-- Search --}}
    <div style="padding: 12px 16px 8px;">
        <input type="search" id="icon-picker-search" placeholder="Cari ikon (misal: dokumen, link, chart, user)..." x-model="search"
            style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 13px; outline: none; background: transparent;"
            class="text-gray-900 dark:text-gray-100 dark:border-gray-600 focus:ring-2 focus:ring-amber-500">
    </div>

    {{-- Icon Grids --}}
    @foreach ($groupData as $groupName => $icons)
        <div class="icon-picker-grid" :class="activeTab !== '{{ $groupName }}' && 'ip-hidden'">
            @foreach ($icons as $icon)
                <button type="button"
                    :class="!matchSearch('{{ $icon['label'] }}') && 'ip-hidden'"
                    x-on:click="selectIcon('{{ $icon['name'] }}')"
                    class="icon-picker-card"
                    title="{{ $icon['name'] }}">
                    {!! $icon['svg'] !!}
                    <span class="icon-picker-label">{{ $icon['label'] }}</span>
                </button>
            @endforeach
        </div>
    @endforeach
</div>
