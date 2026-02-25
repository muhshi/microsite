# BPS Event & Activity Microsite Builder

A dynamic, multi-tenant microsite builder built with Laravel 12 and Filament V3. This application allows administrators to instantly generate beautiful, customizable landing pages for various government activities, training events, surveys, and integrity zones without writing any code.

## 🚀 Key Features

- **Dynamic Microsite Generation:** Create distinct landing pages mapped to unique slugs (e.g., `/event-name`).
- **Filament Admin Panel:** A sleek, user-friendly interface to build and manage microsite content.
- **Section Builder:** Build layouts using pre-defined sections:
  - **Grid Card Links:** Clean, rounded cards with hover effects for resource links.
  - **List Links:** Compact, horizontal list style for links.
- **Visual Icon Picker:** Navigate hundreds of icons (Heroicons & FontAwesome) via a built-in emoji-style picker plugin.
- **Automated SEO:** Automatically generates fallback meta titles, descriptions, and OpenGraph social share images based on the microsite's content and logo.
- **Web Share API Integration:** Native sharing capabilities for mobile users and fallback clipboard-copy for desktop.
- **Modern UI Styling:** Beautiful gradient backgrounds, Tailwind CSS utility classes, and glassmorphism touches matching the *Government Training & Development Summit* template.
- **Dynamic Theming:** Select Theme and Accent colors right from the admin dashboard.

## 🛠 Tech Stack

- **Framework:** Laravel 12.x
- **Admin Panel:** Filament V3
- **Styling:** Tailwind CSS (Frontend & Backend)
- **Database:** SQLite / MySQL / PostgreSQL (Configurable)
- **Icons:** `guava/filament-icon-picker`, `blade-ui-kit/blade-fontawesome`
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

## 📝 License
This project is open-source and available under the [MIT License](LICENSE).
