<?php

namespace App\Http\Controllers;

use App\Models\Microsite;
use App\Models\ShortLink;

class RedirectController extends Controller
{
    public function handle(string $code): mixed
    {
        // 1. Check short links first (fast redirect)
        $shortLink = ShortLink::where('code', $code)->first();

        if ($shortLink) {
            if (! $shortLink->isAccessible()) {
                abort(404);
            }

            $shortLink->incrementClicks();

            return redirect()->away($shortLink->original_url);
        }

        // 2. Fall back to microsite
        $microsite = Microsite::with([
            'category',
            'sections' => function ($query) {
                $query->where('is_active', true)->orderBy('order');
            },
            'sections.links' => function ($query) {
                $query->where('is_active', true)->whereNull('parent_id')->orderBy('order');
            },
            'sections.links.children' => function ($query) {
                $query->where('is_active', true)->orderBy('order');
            },
        ])
            ->where('slug', $code)
            ->where('is_published', true)
            ->first();

        if ($microsite) {
            if (! $microsite->is_public && ! auth()->check()) {
                return redirect()->guest(route('login'));
            }

            $template = $microsite->template_key ?? 'minimal-grid';

            if (! view()->exists("templates.{$template}")) {
                $template = 'minimal-grid';
            }

            $siblings = collect();
            if ($microsite->series_id) {
                $siblings = Microsite::where('series_id', $microsite->series_id)
                    ->where('is_published', true)
                    ->orderBy('start_date', 'asc')
                    ->orderBy('created_at', 'asc')
                    ->get();
            }

            return view("templates.{$template}", compact('microsite', 'siblings'));
        }

        abort(404);
    }
}
