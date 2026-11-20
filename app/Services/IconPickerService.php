<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class IconPickerService
{
    /**
     * Get all icon groups with their icons and pre-rendered SVGs cached.
     *
     * @return array<string, array<int, array{name: string, label: string, svg: string}>>
     */
    public static function getGroupData(): array
    {
        return Cache::rememberForever('icon_picker_groups_v1', function (): array {
            $rawGroups = self::getRawIconDefinitions();
            $result = [];

            foreach ($rawGroups as $groupName => $icons) {
                $groupIcons = [];
                foreach ($icons as $name) {
                    $label = str_replace(
                        ['heroicon-o-', 'heroicon-m-', 'fas-', 'phosphor-'],
                        '',
                        $name
                    );
                    $label = str_replace('-', ' ', $label);

                    try {
                        $svgContent = svg($name, 'text-gray-600 dark:text-gray-400')->toHtml();
                    } catch (\Throwable) {
                        $svgContent = '<svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>';
                    }

                    $groupIcons[] = [
                        'name' => $name,
                        'label' => ucwords($label),
                        'svg' => $svgContent,
                    ];
                }
                $result[$groupName] = $groupIcons;
            }

            return $result;
        });
    }

    /**
     * Raw icon definitions grouped by set.
     *
     * @return array<string, array<int, string>>
     */
    public static function getRawIconDefinitions(): array
    {
        return [
            'Populer' => [
                'heroicon-o-document-text',
                'heroicon-o-globe-alt',
                'heroicon-o-link',
                'heroicon-o-chart-bar',
                'heroicon-o-presentation-chart-line',
                'heroicon-o-table-cells',
                'heroicon-o-clipboard-document',
                'heroicon-o-folder',
                'heroicon-o-archive-box',
                'heroicon-o-inbox',
                'heroicon-o-bell',
                'heroicon-o-megaphone',
                'heroicon-o-user',
                'heroicon-o-users',
                'heroicon-o-user-group',
                'heroicon-o-calendar',
                'heroicon-o-clock',
                'heroicon-o-phone',
                'heroicon-o-envelope',
                'heroicon-o-map-pin',
                'heroicon-o-building-office',
                'heroicon-o-academic-cap',
                'heroicon-o-book-open',
                'heroicon-o-photo',
                'heroicon-o-video-camera',
                'heroicon-o-arrow-top-right-on-square',
                'heroicon-o-arrow-down-tray',
                'heroicon-o-check-circle',
                'heroicon-o-information-circle',
                'heroicon-o-question-mark-circle',
                'heroicon-o-shield-check',
                'heroicon-o-star',
            ],
            'Heroicons' => [
                'heroicon-o-home',
                'heroicon-o-user',
                'heroicon-o-users',
                'heroicon-o-cog',
                'heroicon-o-envelope',
                'heroicon-o-phone',
                'heroicon-o-map-pin',
                'heroicon-o-link',
                'heroicon-o-globe-alt',
                'heroicon-o-document-text',
                'heroicon-o-chat-bubble-left',
                'heroicon-o-bell',
                'heroicon-o-star',
                'heroicon-o-heart',
                'heroicon-o-check-circle',
                'heroicon-o-exclamation-circle',
                'heroicon-o-information-circle',
                'heroicon-o-question-mark-circle',
                'heroicon-o-arrow-right',
                'heroicon-o-arrow-left',
                'heroicon-o-arrow-up',
                'heroicon-o-arrow-down',
                'heroicon-o-chevron-right',
                'heroicon-o-chevron-left',
                'heroicon-o-plus',
                'heroicon-o-minus',
                'heroicon-o-magnifying-glass',
                'heroicon-o-briefcase',
                'heroicon-o-building-office',
                'heroicon-o-academic-cap',
                'heroicon-o-book-open',
                'heroicon-o-camera',
                'heroicon-o-video-camera',
                'heroicon-o-calendar',
                'heroicon-o-clock',
                'heroicon-o-currency-dollar',
                'heroicon-o-shopping-cart',
                'heroicon-o-paper-clip',
                'heroicon-o-tag',
                'heroicon-o-key',
                'heroicon-o-lock-closed',
                'heroicon-o-shield-check',
                'heroicon-o-fire',
                'heroicon-o-light-bulb',
                'heroicon-o-computer-desktop',
                'heroicon-o-device-phone-mobile',
                'heroicon-o-wifi',
                'heroicon-o-chart-bar',
                'heroicon-o-presentation-chart-line',
                'heroicon-o-folder',
                'heroicon-o-inbox',
                'heroicon-o-sparkles',
                'heroicon-o-trophy',
                'heroicon-o-photo',
                'heroicon-o-trash',
                'heroicon-o-pencil',
                'heroicon-o-eye',
                'heroicon-o-clipboard-document',
                'heroicon-o-wrench',
                'heroicon-o-squares-2x2',
                'heroicon-o-rectangle-stack',
                'heroicon-o-archive-box',
                'heroicon-o-arrow-path',
                'heroicon-o-bolt',
                'heroicon-o-flag',
                'heroicon-o-gift',
                'heroicon-o-megaphone',
                'heroicon-o-table-cells',
                'heroicon-o-user-group',
                'heroicon-o-calculator',
                'heroicon-o-chart-pie',
                'heroicon-o-document-arrow-down',
                'heroicon-o-document-arrow-up',
                'heroicon-o-document-check',
                'heroicon-o-qr-code',
            ],
            'Font Awesome' => [
                'fas-home',
                'fas-user',
                'fas-users',
                'fas-cog',
                'fas-envelope',
                'fas-phone',
                'fas-map-marker-alt',
                'fas-link',
                'fas-globe',
                'fas-file-alt',
                'fas-comment',
                'fas-bell',
                'fas-star',
                'fas-heart',
                'fas-check-circle',
                'fas-exclamation-circle',
                'fas-info-circle',
                'fas-question-circle',
                'fas-search',
                'fas-briefcase',
                'fas-building',
                'fas-graduation-cap',
                'fas-book',
                'fas-camera',
                'fas-video',
                'fas-calendar',
                'fas-clock',
                'fas-dollar-sign',
                'fas-shopping-cart',
                'fas-paperclip',
                'fas-tag',
                'fas-key',
                'fas-lock',
                'fas-shield-alt',
                'fas-fire',
                'fas-lightbulb',
                'fas-desktop',
                'fas-mobile-alt',
                'fas-wifi',
                'fas-chart-bar',
                'fas-chart-line',
                'fas-folder',
                'fas-inbox',
                'fas-trophy',
                'fas-image',
                'fas-trash',
                'fas-pencil-alt',
                'fas-eye',
                'fas-clipboard',
                'fas-wrench',
                'fas-bars',
                'fas-archive',
                'fas-sync-alt',
                'fas-bolt',
                'fas-thumbs-up',
                'fas-flag',
                'fas-gift',
                'fas-bullhorn',
                'fas-table',
                'fas-user-plus',
                'fas-calculator',
                'fas-chart-pie',
                'fas-file-download',
                'fas-file-upload',
            ],
            'Phosphor' => [
                'phosphor-house',
                'phosphor-user',
                'phosphor-users',
                'phosphor-gear',
                'phosphor-envelope-simple',
                'phosphor-phone',
                'phosphor-map-pin',
                'phosphor-link',
                'phosphor-globe',
                'phosphor-file-text',
                'phosphor-chat-circle',
                'phosphor-bell',
                'phosphor-star',
                'phosphor-heart',
                'phosphor-check-circle',
                'phosphor-warning-circle',
                'phosphor-info',
                'phosphor-question',
                'phosphor-magnifying-glass',
                'phosphor-briefcase',
                'phosphor-buildings',
                'phosphor-graduation-cap',
                'phosphor-book-open',
                'phosphor-camera',
                'phosphor-video-camera',
                'phosphor-calendar',
                'phosphor-clock',
                'phosphor-currency-dollar',
                'phosphor-shopping-cart',
                'phosphor-paperclip',
                'phosphor-tag',
                'phosphor-key',
                'phosphor-lock',
                'phosphor-shield-check',
                'phosphor-fire',
                'phosphor-lightbulb',
                'phosphor-desktop',
                'phosphor-device-mobile',
                'phosphor-wifi-high',
                'phosphor-chart-bar',
                'phosphor-chart-line',
                'phosphor-folder',
                'phosphor-tray',
                'phosphor-trophy',
                'phosphor-image',
                'phosphor-trash',
                'phosphor-pencil-simple',
                'phosphor-eye',
                'phosphor-clipboard-text',
                'phosphor-wrench',
                'phosphor-list',
                'phosphor-squares-four',
                'phosphor-archive',
                'phosphor-arrows-clockwise',
                'phosphor-lightning',
                'phosphor-flag',
                'phosphor-gift',
                'phosphor-megaphone-simple',
                'phosphor-table',
                'phosphor-user-plus',
                'phosphor-calculator',
                'phosphor-chart-pie-slice',
                'phosphor-download-simple',
                'phosphor-upload-simple',
            ],
        ];
    }
}
