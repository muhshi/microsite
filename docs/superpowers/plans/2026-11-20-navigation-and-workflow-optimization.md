# Navigation and Workflow Optimization Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Merampingkan menu sidebar Filament dengan menyembunyikan menu redundan (Manage Link) dan mengelompokkan master data, mengaktifkan alur preview portal draft untuk pengguna yang login, menambahkan 1-klik copy URL portal dan filter status di tabel, serta mendeploy pembaruan ke server.

**Architecture:**
1. Sidebar Resources: Set `$shouldRegisterNavigation = false` pada `LinkResource` dan atur `navigationGroup` & `navigationSort` yang terstruktur.
2. Preview Flow: Perbarui `RedirectController` agar query `is_published` diabaikan jika `auth()->check()`, dan perbarui tombol aksi di tabel menjadi "Buka Live" (hijau) atau "Preview Draft" (amber).
3. Tabel Peningkatan: Tambahkan kolom `slug` yang `copyable` dan `TernaryFilter` untuk status tayang.
4. Server Deployment: Jalankan `deploy.sh` via SSH ke server Ubuntu BPS Demak (`10.133.21.24`).

**Tech Stack:** Laravel 13, Filament v5, Pest v4, Docker FrankenPHP.

## Global Constraints

- Do NOT drop, truncate, or delete anything about the database.
- Keep all existing tests passing.
- Run `vendor/bin/pint --format agent` on all modified PHP files.
- Finish workflow: `git add .`, `git commit -m "..."`, `git push`, update `README.md` Changelog, and deploy to server.

## Review Focus

- Guests accessing an unpublished microsite still receive a 404 response.
- Authenticated employees accessing an unpublished microsite can view the page in draft preview mode.
- The `Manage Link` menu is hidden from the sidebar without breaking relations in `MicrositeForm`.
- Clicking the slug column in the table copies the full URL to the clipboard.

---

### Task 1: Perampingan & Penataan Menu Sidebar Filament

**Files:**
- Modify: `app/Filament/Resources/Links/LinkResource.php`
- Modify: `app/Filament/Resources/Microsites/MicrositeResource.php`
- Modify: `app/Filament/Resources/ShortLinks/ShortLinkResource.php`
- Modify: `app/Filament/Resources/Categories/CategoryResource.php`
- Modify: `app/Filament/Resources/Series/SeriesResource.php`
- Modify: `app/Filament/Resources/Users/UserResource.php`

- [ ] **Step 1: Hide redundant `LinkResource` from navigation**
In `app/Filament/Resources/Links/LinkResource.php`:
Add `protected static bool $shouldRegisterNavigation = false;`.

- [ ] **Step 2: Restructure navigation groups and sorting**
Group `Portal & Layanan`:
- `MicrositeResource`: Sort 1, Label 'Portal Microsite'
- `ShortLinkResource`: Sort 2, Label 'Short Links'
Group `Data Master`:
- `CategoryResource`: Sort 1, Label 'Kategori Portal'
- `SeriesResource`: Sort 2, Label 'Seri Kegiatan'
Group `Pengaturan`:
- `UserResource`: Sort 1, Label 'Pengguna'

- [ ] **Step 3: Commit**
```bash
git add app/Filament/Resources/
git commit -m "feat: streamline sidebar navigation and organize master data groups"
```

---

### Task 2: Alur Preview Portal Draft untuk Pengguna Terautentikasi

**Files:**
- Modify: `app/Http/Controllers/RedirectController.php`
- Modify: `app/Filament/Resources/Microsites/Tables/MicrositesTable.php`
- Modify: `tests/Feature/MicrositeFrontendTest.php`

- [ ] **Step 1: Write test in `tests/Feature/MicrositeFrontendTest.php`**
Test that guests receive 404 for unpublished microsites, while authenticated users can view/preview them.

- [ ] **Step 2: Run test to see failure**
Run: `php artisan test --compact --filter=MicrositeFrontendTest`

- [ ] **Step 3: Update `RedirectController.php`**
Modify the query so `where('is_published', true)` only applies when `!auth()->check()`.

- [ ] **Step 4: Update `MicrositesTable.php` view action**
Allow `view_live` action to always be visible, labeling it "Buka Live" (green) or "Preview Draft" (amber).

- [ ] **Step 5: Run test to verify it passes**
Run: `php artisan test --compact --filter=MicrositeFrontendTest`
Expected: PASS.

- [ ] **Step 6: Commit**
```bash
git add app/Http/Controllers/RedirectController.php app/Filament/Resources/Microsites/Tables/MicrositesTable.php tests/Feature/MicrositeFrontendTest.php
git commit -m "feat: enable draft preview mode for authenticated users"
```

---

### Task 3: Peningkatan Kolom Tabel Microsite (1-Klik Copy URL & Filter Status)

**Files:**
- Modify: `app/Filament/Resources/Microsites/Tables/MicrositesTable.php`

- [ ] **Step 1: Add copyable slug column and TernaryFilter in `MicrositesTable.php`**
Add `slug` column with `copyableState` generating full route URL. Add `TernaryFilter` for `is_published`.

- [ ] **Step 2: Run tests to verify table configuration**
Run: `php artisan test --compact`
Expected: PASS.

- [ ] **Step 3: Commit**
```bash
git add app/Filament/Resources/Microsites/Tables/MicrositesTable.php
git commit -m "feat: add copyable URL column and publication filter in microsite table"
```

---

### Task 4: Linting, Testing, Changelog, Git Push, and Deployment

**Files:**
- Modify: `README.md`

- [ ] **Step 1: Run Pint code formatter**
Run: `vendor/bin/pint --format agent`

- [ ] **Step 2: Run full test suite**
Run: `php artisan test --compact`
Expected: 100% PASS.

- [ ] **Step 3: Update Changelog in `README.md`**

- [ ] **Step 4: Commit and push**
```bash
git add .
git commit -m "chore: update changelog and apply pint formatting"
git push
```

- [ ] **Step 5: Deploy to server via SSH**
Run `bash deploy.sh` on server `bpsdemak@10.133.21.24`.
Verify HTTP 200 OK.
