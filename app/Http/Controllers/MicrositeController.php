<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
                $query->where('is_active', true)->orderBy('order');
            }
        ])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $template = $microsite->template_key ?? 'minimal-grid';

        if (!view()->exists("templates.{$template}")) {
            $template = 'minimal-grid'; // Fallback
        }

        return view("templates.{$template}", compact('microsite'));
    }
}
