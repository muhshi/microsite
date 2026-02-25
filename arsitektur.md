🏗 BPS Event / Activity Hub Architecture

Stack: Laravel 12 + Filament 5
Konsep: Multi-Microsite Builder (Template + Config Driven)

1️⃣ High Level Architecture
┌──────────────────────────────┐
│        Admin Panel           │
│        (Filament 5)          │
│------------------------------│
│ - Manage Activities          │
│ - Manage Microsites          │
│ - Manage Templates           │
│ - Manage Sections & Links    │
│ - Theme Customization        │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│        Public Frontend       │
│------------------------------│
│ /{slug}                      │
│ Render dynamic template      │
│ based on config + theme      │
└──────────────────────────────┘
2️⃣ Core Concept Perubahan (Dari Survey → Activity)

Gunakan istilah umum:

Entity Utama: Activity

Activity bisa berupa:

Pelatihan

Zona Integritas

Reformasi Birokrasi

Sensus

Forum Internal

Event Nasional

Portal Administrasi

Microsite adalah representasi publik dari Activity.

3️⃣ Database Structure (Clean & Future Proof)
activities
id
name
category (training, zi, rb, event, portal, dll)
description
start_date
end_date
is_active
microsites
id
activity_id (nullable)
title
slug (unique)
template_key
theme_color
accent_color
logo_path
hero_title
hero_subtitle
layout_type (grid / list / mixed)
is_published
published_at

Satu activity bisa punya lebih dari satu microsite (misalnya versi 2025 & 2026)

microsite_sections

Untuk fleksibilitas layout.

id
microsite_id
type (hero, card_grid, info_block, timeline, stats, custom_html)
order
config (JSON)
is_active

Contoh config JSON untuk card_grid:

{
  "columns": 3,
  "style": "rounded",
  "show_icon": true
}
microsite_links
id
microsite_id
section_id (nullable)
title
description
url
icon
badge_text
order
is_active
4️⃣ Rendering Flow
Route
Route::get('/{slug}', [MicrositeController::class, 'show']);
Controller Logic
public function show($slug)
{
    $microsite = Microsite::with([
        'sections',
        'links'
    ])
    ->whereSlug($slug)
    ->whereIsPublished(true)
    ->firstOrFail();

    return view("templates.{$microsite->template_key}", compact('microsite'));
}
5️⃣ Template Strategy

Folder structure:

resources/views/templates/
    minimal-grid.blade.php
    soft-gradient.blade.php
    dark-mode.blade.php
    professional-card.blade.php

Setiap template:

Pakai CSS variables untuk theme

Render section loop

Tidak hardcoded

6️⃣ Theme System (Dynamic Color)

Di layout utama:

<style>
:root {
    --primary: {{ $microsite->theme_color }};
    --accent: {{ $microsite->accent_color }};
}
</style>

Semua:

Button

Icon

Badge

Hover state

pakai variable itu.

7️⃣ Struktur Section Rendering (Flexible)

Di template:

@foreach($microsite->sections as $section)
    @include('components.sections.' . $section->type, [
        'section' => $section
    ])
@endforeach

Folder:

resources/views/components/sections/
    hero.blade.php
    card-grid.blade.php
    info-block.blade.php
    timeline.blade.php
8️⃣ Layout Design (Mengikuti Gambar)

Struktur visual:

[ Hero Section ]
  - Label kecil
  - Title besar
  - Subtitle
  - Optional logo

[ Card Grid ]
  - 2–4 kolom
  - Icon + Title
  - Deskripsi kecil
  - Hover effect

[ Optional Footer ]
  - Contact
  - Tahun
  - Copyright
9️⃣ Filament Resource Structure
ActivityResource

CRUD activity

Relasi microsite

MicrositeResource

Form:

Title

Slug

Template select

Theme color picker

Hero title

Layout type

Publish toggle

SectionRelationManager

Reorderable

Pilih type

JSON config builder

LinkRelationManager

Drag & drop order

Icon select

Badge optional

🔟 URL Structure
event.bpsdemak.go.id/
    /sakernas-2026
    /zona-integritas-2026
    /rb-2026
    /pelatihan-petugas-2026