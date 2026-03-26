# BPS Event & Activity Microsite Builder

A dynamic, multi-tenant microsite builder built with Laravel 12 and Filament V3. This application allows administrators to instantly generate beautiful, customizable landing pages for various government activities, training events, surveys, and integrity zones without writing any code.

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

- **Framework:** Laravel 12.x
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
