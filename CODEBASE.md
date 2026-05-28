# Microsite Builder — Codebase Reference

> **Untuk AI:** Dokumen ini adalah referensi utama untuk memahami arsitektur, model, alur data, dan konvensi kode proyek ini. Baca seluruh bagian yang relevan sebelum membuat perubahan.

## Stack & Versi

| Komponen | Versi |
|---------|-------|
| PHP | 8.4 |
| Laravel | 13 |
| Filament | 5 |
| Livewire | 4 |
| Tailwind CSS | 4 |
| Alpine.js | 3 (via CDN di templates) |
| Database | SQLite (dev) / MySQL (prod) |
| Container | FrankenPHP (PHP 8.4 + Caddy) |

---

## Struktur Direktori Utama

```
app/
  Filament/
    Resources/
      Categories/          # CRUD Category
      Links/               # CRUD Link (standalone)
      Microsites/          # CRUD Microsite (utama)
      Series/              # CRUD Series
      ShortLinks/          # CRUD ShortLink
    Widgets/
      MicrositeStatsOverview.php   # Stat cards di dashboard
      ActiveMicrositesTable.php    # Tabel portal aktif di dashboard
  Http/Controllers/
    Auth/SsoController.php         # OAuth2 SIPETRA SSO
    MicrositeController.php        # (legacy, sebagian besar diganti RedirectController)
    RedirectController.php         # Universal handler: short link + microsite
  Models/
    Concerns/HasSlug.php           # Trait: auto-generate slug
    Category.php
    Microsite.php
    MicrositeLink.php
    MicrositeSection.php
    Series.php
    ShortLink.php
    User.php
  Providers/
    Filament/AdminPanelProvider.php
    SipetraSocialiteProvider.php   # Custom Socialite provider SIPETRA
routes/
  web.php                          # Semua route publik
database/migrations/               # Semua migrasi
resources/views/
  templates/                       # Template frontend microsite
  filament/components/             # Blade components khusus Filament
  auth/sso-button.blade.php        # Tombol SSO di login Filament
tests/Feature/                     # Semua feature tests (Pest)
```

---

## Alur URL / Routing

Semua URL publik dihandle oleh **satu wildcard route**:

```
GET /{code}  →  RedirectController@handle
```

Logika di `handle()`:
1. Cek `ShortLink` dengan `code = {code}` → jika ditemukan dan aktif, redirect ke `original_url`, increment `clicks`
2. Cek `Microsite` dengan `slug = {code}` dan `is_published = true` → render template
3. Jika microsite tidak `is_public` dan user belum login → redirect ke `route('login')`
4. Jika tidak ditemukan → `abort(404)`

**Route lain:**
```
GET /                    → welcome view
GET /login               → SsoController@redirect (alias route('login'))
GET /auth/sipetra/redirect → SsoController@redirect
GET /auth/sipetra/callback → SsoController@callback
```

> ⚠️ **Penting:** Urutan pengecekan `ShortLink` dulu, baru `Microsite`. Short link code dan microsite slug **tidak boleh sama**.

---

## Model-Model

### `Microsite` — Model Utama

**File:** `app/Models/Microsite.php`  
**Traits:** `HasFactory`, `HasSlug`, `SoftDeletes`

**Field `$fillable`:**
| Field | Tipe | Keterangan |
|-------|------|------------|
| `category_id` | FK | Wajib, relasi ke `Category` |
| `series_id` | FK nullable | Opsional, relasi ke `Series` |
| `title` | string | Sumber auto-slug (`$slugSource = 'title'`) |
| `slug` | string | Auto-generated, dipakai sebagai URL |
| `description` | text nullable | Deskripsi portal |
| `start_date` / `end_date` | date nullable | Periode aktif |
| `template_key` | string | Key template frontend (`minimal-grid`, `soft-gradient`) |
| `theme_color` / `accent_color` | string | Hex color untuk branding |
| `logo_path` | string nullable | Path di disk `public`, dir `logos/` |
| `hero_title` / `hero_subtitle` | string nullable | Teks hero section |
| `layout_type` | string | `grid` atau `list` |
| `is_published` | boolean | Portal live jika `true` |
| `published_at` | datetime nullable | Waktu publish |
| `meta_title` / `meta_description` | string nullable | SEO |
| `og_image_path` | string nullable | OpenGraph image |
| `is_public` | boolean | `false` = hanya user BPS SSO |

**Relasi:**
- `sections()` → `HasMany(MicrositeSection)` order by `order`
- `links()` → `HasMany(MicrositeLink)` order by `order`
- `category()` → `BelongsTo(Category)`
- `series()` → `BelongsTo(Series)`

---

### `MicrositeSection` — Seksi/Blok Konten

**File:** `app/Models/MicrositeSection.php`

**Field:**
| Field | Tipe | Keterangan |
|-------|------|------------|
| `microsite_id` | FK | Induk microsite |
| `type` | string | `grid` atau `list` (layout seksi) |
| `order` | integer | Urutan tampil |
| `config` | array (JSON) | Konfigurasi bebas: `title`, `description` |
| `is_active` | boolean | Tampil di frontend jika `true` |

**Relasi:**
- `microsite()` → `BelongsTo(Microsite)`
- `links()` → `HasMany(MicrositeLink, 'section_id')` order by `order`

---

### `MicrositeLink` — Link/Kartu dalam Seksi

**File:** `app/Models/MicrositeLink.php`

**Field:**
| Field | Tipe | Keterangan |
|-------|------|------------|
| `microsite_id` | FK | Relasi ke microsite (auto-set dari section saat create) |
| `section_id` | FK nullable | Seksi induk |
| `parent_id` | FK nullable | Self-referential: link child dari parent |
| `title` | string | Label tampil |
| `url` | string nullable | URL tujuan |
| `icon` | string nullable | Nama icon (Heroicons/FA/Phosphor) |
| `badge_text` | string nullable | Badge label di pojok kartu |
| `order` | integer | Urutan tampil |
| `is_active` | boolean | Tampil di frontend jika `true` |

**Relasi:**
- `microsite()` → `BelongsTo(Microsite)`
- `section()` → `BelongsTo(MicrositeSection, 'section_id')`
- `parent()` → `BelongsTo(MicrositeLink, 'parent_id')`
- `children()` → `HasMany(MicrositeLink, 'parent_id')` order by `order`

**Behavior Otomatis (booted):**
> Saat link dibuat melalui nested repeater (section_id ada tapi microsite_id kosong), `microsite_id` otomatis diisi dari `section.microsite_id`.

---

### `Category` — Kategori Portal

**File:** `app/Models/Category.php`  
**Traits:** `HasFactory`, `HasSlug`, `SoftDeletes`

**Field:** `name`, `slug` (auto), `description`  
**Relasi:** `microsites()` → `HasMany(Microsite)`  
**Slug source:** `name` (default `HasSlug`)

---

### `Series` — Seri / Pengelompokan Lintas Tahun

**File:** `app/Models/Series.php`  
**Traits:** `HasFactory`, `HasSlug`, `SoftDeletes`

**Field:** `name`, `slug` (auto), `description`  
**Relasi:** `microsites()` → `HasMany(Microsite)`  
**Slug source:** `name` (default `HasSlug`)

**Kegunaan:** Mengelompokkan microsite yang merupakan versi tahun berbeda dari survei/kegiatan yang sama (misal: Sakernas 2024, Sakernas 2025). Di frontend, microsite dalam seri yang sama akan menampilkan tab navigasi tahun.

---

### `ShortLink` — URL Shortener

**File:** `app/Models/ShortLink.php`

**Field:** `code` (auto 6 karakter), `original_url`, `clicks`, `is_active`, `expires_at`

**Methods penting:**
- `isExpired(): bool` — cek kadaluarsa
- `isAccessible(): bool` — `is_active && !isExpired()`
- `incrementClicks(): void` — increment counter
- `static generateUniqueCode(int $length = 6): string` — buat kode unik yang belum ada di ShortLink **dan** Microsite
- `getShortUrl(): string` — kembalikan URL pendek

---

### `User` — Pengguna

**File:** `app/Models/User.php`

**Field tambahan SSO:**
- `sipetra_id` — ID dari SIPETRA
- `nip` — Nomor Induk Pegawai
- `jabatan` — Jabatan pegawai
- `sipetra_token` / `sipetra_refresh_token`
- `password` — **nullable** (SSO-only accounts tidak punya password)

**Implements:** `FilamentUser` → method `canAccessPanel()` mengizinkan semua authenticated user untuk dev/local, atau user tertentu di production.

**Role & Authorization (Filament Shield & Spatie Permission):**
- Menggunakan `HasRoles` trait dari Spatie Permission.
- **Roles:** `super_admin` (akses tidak terbatas via `define_via_gate => true`) dan `pegawai` (default role).
- Method helper `isSuperAdmin(): bool` memeriksa apakah pengguna adalah super admin.
- Hubungan `microsites()` dan `shortLinks()` menghubungkan pengguna ke record yang mereka buat.

---

## Trait: `HasSlug`

**File:** `app/Models/Concerns/HasSlug.php`

```php
trait HasSlug {
    // Hook ke Eloquent 'saving' event
    // Jika slug kosong & source field tidak kosong → auto-generate slug
    protected static function bootHasSlug(): void { ... }

    // Override di model untuk custom source field
    protected function getSlugSourceField(): string {
        return $this->slugSource ?? 'name';
    }
}
```

**Cara override source field di model:**
```php
class Microsite extends Model {
    use HasSlug;
    protected string $slugSource = 'title'; // default: 'name'
}
```

> **Penting:** Slug hanya digenerate saat **belum ada** (kosong). Untuk update slug, harus set ke null/kosong dulu.

---

## Trait: `HasOwner`

**File:** `app/Models/Concerns/HasOwner.php`

```php
trait HasOwner {
    // Hook ke Eloquent 'creating' event untuk mengisi created_by dengan id user yang login
    protected static function bootHasOwner(): void { ... }

    // Relasi BelongsTo ke User yang membuat record
    public function createdBy(): BelongsTo { ... }

    // Scope query untuk menyaring data milik user saat ini saja
    public function scopeOwnedByCurrentUser(Builder $query): Builder { ... }
}
```

**Penggunaan di Model:**
Tambahkan `use HasOwner;` di kelas model dan tambahkan `'created_by'` ke dalam properti `$fillable`. Digunakan pada model `Microsite` dan `ShortLink`.

---

## Filament Resources

### Struktur Resource Pattern

Setiap resource menggunakan **class-based separation**:

```
Resources/
  ModelName/
    ModelNameResource.php     ← Resource utama (icon, group, pages)
    Schemas/
      ModelNameForm.php       ← Form configuration (Schema-based)
    Tables/
      ModelNamesTable.php     ← Table configuration
    Pages/
      ListModelNames.php
      CreateModelName.php
      EditModelName.php
```

> Untuk resource sederhana (Category, Series, ShortLinks), form dan table langsung di file `*Resource.php`.

---

### `MicrositeResource`

**Nav group:** `Microsite`  
**Icon:** `Heroicon::OutlinedGlobeAlt`  
**Record title attribute:** `title`  
**Soft delete:** Ya (dengan `getRecordRouteBindingEloquentQuery()` yang bypass `SoftDeletingScope`)

**Pages:**
- `index` → `ListMicrosites` (tabel dengan filter trashed)
- `create` → `CreateMicrosite`
- `edit` → `EditMicrosite` (dengan tombol "View Live", Delete, Force Delete, Restore)

---

### `MicrositeForm` — Form Schema

**File:** `app/Filament/Resources/Microsites/Schemas/MicrositeForm.php`

Terdiri dari 3 Section:

#### 1. General Information (2 columns)
- `category_id` — Select dengan `createOptionForm` (bisa buat category baru inline)
- `series_id` — Select opsional dengan `createOptionForm`
- `title` — TextInput required
- `description` — Textarea `columnSpanFull`
- `start_date` / `end_date` — DatePicker
- `is_published` — Toggle (default: true)
- `is_public` — Toggle (default: true)

#### 2. Design & Branding (2 columns)
- `template_key` — Select: `minimal-grid` | `soft-gradient`
- `layout_type` — Select: `grid` | `list`
- `theme_color` / `accent_color` — ColorPicker (default hijau emerald)
- `logo_path` — FileUpload (disk: public, dir: logos/, accepts SVG/PNG/JPG/WebP)

#### 3. Content (Sections & Links) — full width
- **Nested Repeater `sections`** (collapsible, orderable):
  - `type` — Select: grid/list
  - `is_active` — Toggle
  - `config.title` / `config.description` — dalam Group 2 columns
  - **Nested Repeater `links`** (collapsible, orderable, 2 columns):
    - `title` — TextInput required
    - `url` — TextInput
    - `icon` — TextInput readonly + Action suffix untuk **custom icon picker modal**
    - `badge_text` — TextInput
    - `parent_id` — Select, filtered by microsite (hanya link top-level dari microsite yang sedang diedit)
    - `is_active` — Toggle

**Icon Picker Modal:**
- Dipanggil via `suffixAction` pada field `icon`
- View: `filament.components.icon-picker-modal`
- Mendukung 3 icon set: Heroicons, Font Awesome, Phosphor Icons
- Click pada text field juga membuka modal (via `x-on:click`)

---

### `MicrositesTable` — Table Configuration

**File:** `app/Filament/Resources/Microsites/Tables/MicrositesTable.php`

**Columns:** `category.name` (badge), `title`, `theme_color` (ColorColumn+inline edit), `accent_color` (ColorColumn+inline edit), `is_published` (icon bool), `is_public` (icon bool), timestamps (togglable hidden)

**Filter:** `TrashedFilter` (tampilkan/sembunyikan soft-deleted)

**Record Actions:**
1. `view_live` — buka tab baru ke microsite (hanya tampil jika `is_published`)
2. `duplicate` — **duplikasi lengkap** dengan konfirmasi:
   - Clone microsite (judul + "(Copy)", slug unik, `is_published = false`)
   - Clone semua sections
   - Clone semua parent links per section
   - Clone semua children links dengan `parent_id` yang benar
3. `EditAction` — buka halaman edit

**Toolbar Actions:** BulkAction (Delete, Force Delete, Restore)

---

### `SeriesResource`

**Nav group:** `Microsite`  
**Icon:** `Heroicon::OutlinedSquare2Stack`  
**Page:** Single `ManageSeries` (CRUD dalam satu halaman)

**Table columns:** `name`, `slug`, `description` (limit 50), `microsites_count` (count relation)

---

### `CategoryResource`

**Nav group:** (default/root)  
**Page:** Single `ManageCategories`

---

### `LinkResource` (Standalone Link Manager)

**File:** `app/Filament/Resources/Links/LinkResource.php`

Form (`LinkForm`) terdiri dari 2 Section:
1. **Link Details**: `title`, `url`, `icon` (dengan icon picker), `badge_text`
2. **Relasi & Pengaturan**: `microsite_id` (live=true), `section_id` (filtered by microsite_id), `parent_id` (filtered by microsite_id), `order`, `is_active`

**Fitur reactive:** Saat `microsite_id` berubah, `section_id` dan `parent_id` difilter hanya ke options yang relevan. Jika tidak ada microsite dipilih, dropdown kosong (`whereNull('id')`).

---

## Filament Widgets (Dashboard)

### `MicrositeStatsOverview`
- Sort: 1 (tampil pertama)
- Stats: Total Microsite, Microsite Aktif (`is_published=true`), Total Klik Short Link

### `ActiveMicrositesTable`
- Sort: 2 (di bawah stats)
- Column span: full
- Query: `Microsite` where `is_published=true`, order by `published_at` desc
- Columns: kategori (badge), judul, tipe akses (icon lock/unlock), periode aktif
- Record actions: "View Live" (new tab) + "Edit" (ke halaman edit Filament)

---

## Panel Admin (AdminPanelProvider)

**File:** `app/Providers/Filament/AdminPanelProvider.php`

- ID: `admin`, path: `/admin`
- Login: built-in (`->login()`)
- Color: `Color::Amber`
- Render hook: setelah form login → inject tombol SSO SIPETRA (`auth.sso-button.blade.php`)
- Auto-discover: Resources, Pages, Widgets dari direktori `app/Filament/`
- Widgets yang terdaftar manual: `MicrositeStatsOverview`, `ActiveMicrositesTable`

---

## Autentikasi & SSO

### SIPETRA SSO (BPS Internal)

**Provider:** `SipetraSocialiteProvider` di `app/Providers/SipetraSocialiteProvider.php`  
**Controller:** `app/Http/Controllers/Auth/SsoController.php`

**Alur:**
1. User klik tombol SSO → `GET /auth/sipetra/redirect` → redirect ke SIPETRA OAuth
2. Callback → `GET /auth/sipetra/callback` → SsoController ambil user data
3. Upsert user di DB berdasarkan `sipetra_id`, set tokens
4. Login user ke session Laravel

**Env yang diperlukan:**
```
SIPETRA_BASE_URL=https://portal.bpsdemak.com
SIPETRA_CLIENT_ID=xxx
SIPETRA_CLIENT_SECRET=xxx
```

**Route penting:**
- `route('login')` → alias untuk `sipetra.login`
- Restricted microsite redirect ke `route('login')` jika belum autentikasi

---

## Frontend Templates

**Lokasi:** `resources/views/templates/`  
**Dipanggil oleh:** `RedirectController` berdasarkan `microsite->template_key`

### Template `minimal-grid`
- Layout kartu grid/list dengan glassmorphism
- Menggunakan `microsite->theme_color` dan `accent_color` sebagai CSS variables
- Header: logo + judul + deskripsi
- Jika microsite punya `series` → tampilkan **tab navigasi tahun** (sibling microsites dalam series yang sama)
- Web Share API untuk mobile, fallback clipboard-copy untuk desktop
- Alpine.js untuk interaktivitas (collapsible cards, share modal)
- Sections dirender sesuai `type` (grid/list)
- Links dirender berjenjang: parent card → children di bawahnya

### Template `soft-gradient`
- Varian alternatif (definisi serupa, styling berbeda)

---

## Fitur: Sibling Year Tabs (Navigasi Antar Tahun)

Jika sebuah microsite memiliki `series_id`, di halaman publik akan muncul tab-tab navigasi ke microsite lain dalam series yang sama.

**Logika di template:** Query `Microsite` where `series_id = $microsite->series_id` and `is_published = true`, sorted chronologically → render tab dengan tahun dari `start_date` atau judul microsite.

---

## Fitur: URL Shortener

- Code: 6 karakter alfanumerik, unik (tidak boleh clash dengan slug microsite)
- Auto-generate saat create jika `code` kosong
- Tracking clicks via `incrementClicks()`
- Support expiry date (`expires_at`)
- `isAccessible()` = `is_active && !isExpired()`

---

## Fitur: Microsite Duplication

Tersedia sebagai action di tabel Microsite. Proses:
1. Clone microsite → judul + "(Copy)", slug baru (+ 4 karakter random), `is_published = false`
2. Clone tiap section
3. Clone tiap parent link per section (dengan microsite_id & section_id baru)
4. Clone tiap child link (dengan parent_id yang menunjuk ke parent baru)

---

## Testing

**Framework:** Pest PHP v4  
**Lokasi:** `tests/Feature/`

| File | Apa yang diuji |
|------|----------------|
| `SlugGenerationTest.php` | HasSlug trait: auto-generate, tidak overwrite yang sudah ada |
| `MicrositeFrontendTest.php` | Tampilan publik, akses restricted, sibling year tabs |
| `LinkSelectionTest.php` | Filter dinamis parent_id & section_id di form |
| `DashboardTest.php` | Widget dashboard dapat diakses admin |
| `ShortLinkTest.php` | Redirect, click tracking, expiry, collision avoidance |

**Helper di `tests/Pest.php`:** setup database refresh, auth helpers, factories.

---

## Konvensi & Aturan Koding

### Slug
- Jangan pernah tampilkan input `slug` di form (auto-generated)
- Tambahkan `HasSlug` trait ke model baru yang butuh slug
- Override `protected string $slugSource = 'field_name'` jika bukan `name`

### Form & Table
- Form schema di class terpisah `Schemas/ModelNameForm.php`
- Table schema di class terpisah `Tables/ModelNamesTable.php`
- Untuk resource sederhana boleh inline di `*Resource.php`

### Reactive Fields
- Gunakan `->live()` pada field trigger
- Gunakan `Get $get` dalam closure `modifyQueryUsing` untuk dependent selects
- Jika tidak ada nilai trigger → tampilkan empty (`whereNull('id')`)

### Arsitektur
- Gunakan Trait untuk behavior yang dipakai lebih dari satu model (contoh: `HasSlug`)
- Gunakan Action class untuk logic bisnis yang kompleks (bukan inline closure panjang)
- Proactively suggest Trait/Service/Action ketika ada logika yang berulang

### Filament Icons
- Gunakan `Heroicon::OutlinedXxx` untuk navigation icon (bukan string literal)
- Gunakan string `heroicon-o-xxx` / `heroicon-m-xxx` di dalam form/table actions

### Tests
- Setiap fitur baru **wajib** ada test
- Gunakan factories untuk test data
- Jalankan: `php artisan test --compact`

---

## Migrasi Database (Urutan Kronologis)

1. `create_users_table` — users + remember_tokens + sessions
2. `create_cache_table` — cache
3. `create_jobs_table` — queue jobs
4. `create_microsites_table` — tabel utama microsite
5. `create_microsite_sections_table` — sections
6. `create_microsite_links_table` — links (basic)
7. `add_parent_id_to_microsite_links_table` — parent-child links
8. `create_short_links_table` — URL shortener
9. `create_categories_table` — categories
10. `add_category_id_to_microsites_table` — FK category
11. `drop_category_column` (x2) — hapus kolom lama
12. `add_sso_fields_to_users_table` — SSO: sipetra_id, nip, jabatan, tokens
13. `add_is_public_to_microsites_table` — access control
14. `make_password_nullable_on_users_table` — SSO-only users
15. `create_series_table` — series
16. `add_series_id_to_microsites_table` — FK series di microsite

---

## Environment Variables Penting

```env
APP_URL=https://microsite.bpsdemak.com
APP_KEY=...
DB_CONNECTION=sqlite          # atau mysql
DB_DATABASE=/absolute/path    # untuk SQLite

SIPETRA_BASE_URL=https://portal.bpsdemak.com
SIPETRA_CLIENT_ID=...
SIPETRA_CLIENT_SECRET=...

FILESYSTEM_DISK=public        # untuk logo upload
```

---

## Docker / Deployment

**File:** `Dockerfile`, `docker-compose.yml`, `Caddyfile`

- Base image: `dunglas/frankenphp:php8.4`
- Node: disalin dari `node:20`
- Composer: disalin dari `composer:2`
- Container name: `microsite-franken`
- Port: 80 (Caddy)
- Volume: project di `/app`

**Perintah deploy setelah update:**
```bash
git pull origin main
docker compose build microsite-franken
docker compose up -d microsite-franken
docker exec microsite-franken composer install --no-dev --optimize-autoloader
docker exec microsite-franken php artisan migrate --force
docker exec microsite-franken php artisan config:cache
docker exec microsite-franken php artisan route:cache
```
