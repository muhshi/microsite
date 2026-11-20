# Microsite Enhancements Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mengizinkan semua pegawai mengedit microsite, mengoptimalkan dan mengompres upload gambar logo, serta merombak desain frontend microsite agar lebih modern, interaktif (live search, ambient glow, QR code modal), dan optimal tanpa query N+1.

**Architecture:** 
1. Authorization: Buka permission `update` & `replicate` di `MicrositePolicy` dan `MicrositesTable` untuk semua pegawai yang memiliki permission.
2. Form upload: Terapkan client-side auto-resizing (500x500 max contain) dan validasi ketat `maxSize(2048)` di Filament `FileUpload`.
3. Backend: Eager-load `category` dan `siblings` di `RedirectController` untuk mengeliminasi query langsung di template Blade.
4. Frontend: Rombak template Blade (`minimal-grid`, `grid`, `list`) dengan Alpine.js untuk live-filtering, ambient dynamic glow, modern bento card elevation, dan QR Code modal.

**Tech Stack:** Laravel 13, Filament v5, Livewire v4, Tailwind CSS v4, Alpine.js v3, Pest v4.

**Spec:** Diskusi dan arahan perbaikan microsite (fitur edit bersama, optimasi kompresi upload, desain UI modern).

## Global Constraints

- Do NOT drop, truncate, or delete anything about the database.
- Follow existing code conventions and directory structure.
- Run `vendor/bin/pint --format agent` on all modified PHP files.
- Finish workflow: `git add .`, `git commit -m "..."`, `git push`, and update `README.md` Changelog.

## Review Focus

- Non-owner pegawai can edit any microsite in admin panel.
- Non-owner pegawai cannot delete another user's microsite (deletion stays protected).
- Large images uploaded via logo field are resized client-side and validated <= 2MB.
- Microsite page renders without N+1 query and without database query execution inside Blade.
- Live search bar instantly filters visible link cards without breaking parent-child links.

---

### Task 1: Hak Akses Edit Microsite untuk Semua Pegawai

**Files:**
- Modify: `app/Policies/MicrositePolicy.php`
- Modify: `app/Filament/Resources/Microsites/Tables/MicrositesTable.php`
- Modify: `tests/Feature/OwnershipTest.php`

**Interfaces:**
- Consumes: `App\Models\Microsite`, `App\Models\User`
- Produces: `MicrositePolicy::update()`, `MicrositePolicy::replicate()` allowing all users with permission to edit/replicate.

- [ ] **Step 1: Write failing test in `tests/Feature/OwnershipTest.php`**
Update tests in `tests/Feature/OwnershipTest.php` so that another user with `Update:Microsite` permission IS allowed to update any microsite, while deletion remains restricted.

- [ ] **Step 2: Run test to verify failure**
Run: `php artisan test --compact --filter=OwnershipTest`
Expected: FAIL due to existing policy requiring `$microsite->created_by === $user->id`.

- [ ] **Step 3: Update `MicrositePolicy.php` and `MicrositesTable.php`**
In `app/Policies/MicrositePolicy.php`:
Allow `update` and `replicate` for super admin OR users with `Update:Microsite` / `Replicate:Microsite` permission. Keep `delete`, `forceDelete`, and `restore` protected.
In `app/Filament/Resources/Microsites/Tables/MicrositesTable.php`:
Remove `created_by === auth()->id()` restriction on inline color editing and duplicate action.

- [ ] **Step 4: Run test to verify it passes**
Run: `php artisan test --compact --filter=OwnershipTest`
Expected: PASS.

- [ ] **Step 5: Commit**
```bash
git add app/Policies/MicrositePolicy.php app/Filament/Resources/Microsites/Tables/MicrositesTable.php tests/Feature/OwnershipTest.php
git commit -m "feat: allow all employees with permission to edit and duplicate microsites"
```

---

### Task 2: Optimasi Upload Logo di Builder (Compress, Resize, Max Size)

**Files:**
- Modify: `app/Filament/Resources/Microsites/Schemas/MicrositeForm.php`

**Interfaces:**
- Consumes: Filament `FileUpload`
- Produces: Optimized `logo_path` field with client-side resize and max size limit.

- [ ] **Step 1: Update `MicrositeForm.php` with image compression and validation**
Add `->image()`, `->maxSize(2048)`, `->imageResizeMode('contain')`, `->imageResizeTargetWidth(500)`, `->imageResizeTargetHeight(500)`, and `->imageEditor()`.

- [ ] **Step 2: Run feature tests to ensure no regressions**
Run: `php artisan test --compact --filter=LinkSelectionTest`
Expected: PASS.

- [ ] **Step 3: Commit**
```bash
git add app/Filament/Resources/Microsites/Schemas/MicrositeForm.php
git commit -m "perf: optimize logo upload with client-side resizing, editor, and 2MB limit"
```

---

### Task 3: Backend Controller & Data Optimization (Eager Loading & Separation of Concerns)

**Files:**
- Modify: `app/Http/Controllers/RedirectController.php`
- Modify: `resources/views/templates/minimal-grid.blade.php`

**Interfaces:**
- Consumes: `Microsite` model relations
- Produces: `RedirectController::handle` eager loading `category` and calculating/passing `$siblings`.

- [ ] **Step 1: Update `RedirectController.php`**
Add `'category'` to eager loading array. If `$microsite->series_id`, eager load/query siblings in controller and pass to view.

- [ ] **Step 2: Clean up database query inside `minimal-grid.blade.php`**
Use `$siblings` passed from controller instead of executing `\App\Models\Microsite::where(...)` inside Blade template.

- [ ] **Step 3: Run frontend tests**
Run: `php artisan test --compact --filter=MicrositeFrontendTest`
Expected: PASS.

- [ ] **Step 4: Commit**
```bash
git add app/Http/Controllers/RedirectController.php resources/views/templates/minimal-grid.blade.php
git commit -m "perf: eager-load category and move sibling series query to controller"
```

---

### Task 4: Desain Frontend Microsite yang Lebih Modern & Interaktif

**Files:**
- Modify: `resources/views/templates/minimal-grid.blade.php`
- Modify: `resources/views/components/sections/grid.blade.php`
- Modify: `resources/views/components/sections/list.blade.php`

**Interfaces:**
- Consumes: `$microsite`, `$siblings`, `$sections`
- Produces: Modern bento layout with ambient dynamic glow, live search filter (Alpine.js), QR Code modal, and polished card components.

- [ ] **Step 1: Implement ambient glow and QR code modal in `minimal-grid.blade.php`**
Add dynamic color glow in background, QR Code modal trigger in header, and Alpine.js state for search query `search: ''` and QR modal `qrOpen: false`.

- [ ] **Step 2: Implement live instant search bar in `minimal-grid.blade.php`**
Add responsive search input under hero. Bind with Alpine `x-model="search"` so cards automatically filter.

- [ ] **Step 3: Upgrade card design in `grid.blade.php` and `list.blade.php`**
Enhance styling:
- Soft squircle icon container with dynamic accent tint.
- Visual sub-link counter badge for parent links.
- Quick direct link / copy button.
- Alpine `x-show` filter matching card title and badge text with `search`.
- Fallback empty search state ("Tautan tidak ditemukan").

- [ ] **Step 4: Run frontend tests to ensure no regressions**
Run: `php artisan test --compact`
Expected: ALL PASS.

- [ ] **Step 5: Commit**
```bash
git add resources/views/templates/minimal-grid.blade.php resources/views/components/sections/grid.blade.php resources/views/components/sections/list.blade.php
git commit -m "feat: redesign microsite with ambient glow, live search, and QR code modal"
```

---

### Task 5: Final Pint Formatting, Testing, Changelog, and Git Push

**Files:**
- Modify: `README.md`

- [ ] **Step 1: Run Pint code formatter**
Run: `vendor/bin/pint --format agent`

- [ ] **Step 2: Run all Pest tests**
Run: `php artisan test --compact`
Expected: 100% PASS.

- [ ] **Step 3: Update Changelog in `README.md`**
Add entry under Changelog with today's date (2026-11-20) detailing all updates made.

- [ ] **Step 4: Final commit and push**
```bash
git add .
git commit -m "chore: update changelog and apply formatting"
git push
```
