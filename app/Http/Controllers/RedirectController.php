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
            $template = $microsite->template_key ?? 'minimal-grid';

            if (! view()->exists("templates.{$template}")) {
                $template = 'minimal-grid';
            }

            return view("templates.{$template}", compact('microsite'));
        }

        abort(404);
    }
}
