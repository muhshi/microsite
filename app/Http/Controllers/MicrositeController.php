<?php

namespace App\Http\Controllers;

use App\Models\Microsite;

class MicrositeController extends Controller
{
    public function show($slug)
    {
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
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        if (! $microsite->is_public && ! auth()->check()) {
            return redirect()->guest(route('login'));
        }

        $template = $microsite->template_key ?? 'minimal-grid';

        if (! view()->exists("templates.{$template}")) {
            $template = 'minimal-grid'; // Fallback
        }

        return view("templates.{$template}", compact('microsite'));
    }
}
