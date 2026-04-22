# BPS Event & Activity Microsite Builder

A dynamic, multi-tenant microsite builder built with Laravel 13 and Filament V5. This application allows administrators to instantly generate beautiful, customizable landing pages for various government activities, training events, surveys, and integrity zones without writing any code.

## 🚀 Key Features

- **Dynamic Microsite Generation:** Create distinct landing pages mapped to unique slugs (e.g., `/event-name`).
- **Filament Admin Panel:** A sleek, user-friendly interface to build and manage microsite content.
- **Section Builder:** Build layouts using pre-defined sections:
  - **Grid Card Links:** Clean, rounded cards with hover effects for resource links.
  - **List Links:** Compact, horizontal list style for links.
- **Custom Icon Picker:** A performant, built-in icon picker modal with 3 icon sets (Heroicons, Font Awesome, Phosphor Icons), tab navigation, search filtering, and one-click selection.
- **Automated SEO:** Automatically generates fallback meta titles, descriptions, and OpenGraph social share images based on the microsite's content and logo.
- **Web Share API Integration:** Native sharing capabilities for mobile users and fallback clipboard-copy for desktop.
- **Modern UI Styling:** Beautiful gradient backgrounds, Tailwind CSS utility classes, and glassmorphism touches.
- **Dynamic Theming:** Select Theme and Accent colors right from the admin dashboard.

## 🛠 Tech Stack

- **Framework:** Laravel 13.x
- **Admin Panel:** Filament V5
- **Styling:** Tailwind CSS (Frontend & Backend)
- **Database:** SQLite / MySQL / PostgreSQL (Configurable)
- **Icons:** Heroicons, Font Awesome (`blade-fontawesome`), Phosphor Icons (`blade-phosphor-icons`)
- **Testing:** Pest PHP

## ⚙️ Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/muhshi/microsite.git
   cd microsite
   ```

2. **Install Composer dependencies:**
   ```bash
   composer install
   ```

3. **Install NPM dependencies and build assets:**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Setup:**
   Copy the example environment file and generate a new application key.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup:**
   Configure your `.env` to point to an empty SQLite file or your preferred database, then run migrations and seed the initial admin account.
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Note: The default `DatabaseSeeder` should be configured to create an Admin user)*

6. **Storage Link:**
   Ensure the storage link is created so uploaded logos and SEO images are publicly accessible.
   ```bash
   php artisan storage:link
   ```

7. **Run the Development Server:**
   ```bash
   php artisan serve
   ```
   Access the admin panel at `http://localhost:8000/admin`.

## 🎨 Built-in Templates
- `minimal-grid`: A clean, centrally-aligned layout perfect for event link-trees, featuring dynamic branding colors. 

## 📋 Changelog

### 2026-04-22
- **Bug Fix:** Fixed `MassAssignmentException` across all models (`Category`, `Microsite`, `MicrositeSection`, `MicrositeLink`, `ShortLink`, `User`) by replacing the experimental `#[Fillable]` and `#[Hidden]` attributes with the standard `$fillable` and `$hidden` properties.
- **UI Fix:** Updated the microsite footer to always use the default BPS logo, ensuring consistent branding regardless of the user-uploaded logo used in the header.
- **Docker Fix:** Switched to a more robust Node.js installation method by copying from the official Node image. This resolves "npm not found" issues caused by inconsistent package manager behavior in different environments.

- **Docker Fix:** Added `.dockerignore` to exclude `storage`, `vendor`, and other unnecessary files from the build context. This resolves the "permission denied" error during image building caused by the `livewire-tmp` directory.
- **Docker Update:** Added Node.js 20.x and npm installation to the Dockerfile to support frontend asset builds inside the container.
- **UI Enhancements:** Improved navbar gradient behavior and share button visibility on the `minimal-grid` template to ensure high contrast and dynamic color compatibility.

### 2026-04-14
- **Laravel 13 Upgrade:** Successfully upgraded the entire framework core to Laravel 13.4.0, ensuring compatibility with PHP 8.4 and optimizing the project for the latest ecosystem features.
- **Dependency Modernization:** Updated critical dependencies including `laravel/tinker` to v3.0, `owenvoke/blade-fontawesome` to v3.2, and `pestphp/pest` to v4.5 to resolve compatibility conflicts during the upgrade process.
- **Eloquent Attribute Adoption:** Began refactoring Eloquent models to use the new Laravel 13 native PHP Attributes (e.g., `#[Fillable]`, `#[Hidden]`) for a cleaner, more declarative model configuration.

### 2026-04-13
- **Premium Landing Page UI:** Overhauled the welcome page with a modern dark-themed interface, refined visual balance, dynamic scrolling marquees, and a dashboard overview image to better showcase the platform's capabilities.
- **Microsite Model Architecture:** Refactored the `Microsite` model to fully adopt a relational `category_id` architecture, replacing the obsolete string-based category implementation, backed by accurate database migrations.
- **Filament Table & Form Improvements:** Updated the `MicrositeForm` and `MicrositesTable` to seamlessly utilize the `belongsTo` relationship for categorizations, complete with specific resource pages under `app/Filament/Resources/Categories`.
- **Bug Fix:** Fixed an issue where the category in the microsite template was displayed as a JSON string; it now correctly displays the category name.
- **Template & Styling Enhancements:** Refined the `minimal-grid` template and `app.css` to adhere to the modernized visual language and new UI components.

### 2026-04-08
- **Landing Page Redesign:** Redesigned landing page with modern glassmorphism UI, BPS-inspired gradients, and enhanced typography using Plus Jakarta Sans. Updated `app.css` to use Tailwind CSS v4 `@theme`.
- **App Configuration Fix:** Fixed `MissingAppKeyException` by generating a new application encryption key.
- **Database Connection Fix:** Updated `.env` with the correct relative path for the SQLite database file and synchronized `APP_URL` with the active environment.
- **Admin Setup:** Created initial admin account for the Filament dashboard.
- **Category Management:** Added a new `Category` resource to group microsites. Integrated a searchable relationship select in the microsite builder for easy categorization.

### 2026-03-26
- **Link Manager:** Created a standalone Filament resource for managing links independently from microsites, featuring drag-and-drop sorting and filtering.
- **URL Shortener:** Added a new URL shortener feature that auto-generates unique 6-character short codes, tracks clicks, handles expirations, and integrates with a universal redirect controller to prevent slug conflicts.
- **Microsite UI Redesign:** Revamped the frontend templates to a modern light mode with a frosted-glass navigation bar, subtle radial gradients, and Alpine.js-powered collapsible section cards.
- **Parent-Child Links Hierarchy:** Added `parent_id` support to `MicrositeLink`, allowing links to be grouped under collapsible parent cards on the microsite grid and list views.

### 2026-02-26
- **Custom Icon Picker Modal:** Replaced the slow `guava/filament-icon-picker` with a custom icon picker modal supporting 3 icon groups (Heroicons, Font Awesome, Phosphor Icons), tab navigation, search filtering, and one-click selection.
- **Icon input click-to-open:** Clicking the icon text field now directly opens the icon picker modal (not just the suffix button).
- **Enlarged logo on live view:** Logo on the public microsite page enlarged to 6rem with reduced top spacing for better visual balance.
- **Landing page update:** Elegant UI, localized copy, meta tags, and logo for the welcome page.
- **Proxy & HTTPS:** Trust all proxies and force HTTPS on production.

### 2026-02-25
- **Database migration update:** Fixed migration compatibility for SQLite and MySQL.
- **Docker configuration:** Added Docker configuration for containerized deployment.
- **Microsite builder phase 7:** Complete redesign with new sections and templates.

## 📝 License
This project is open-source and available under the [MIT License](LICENSE).
