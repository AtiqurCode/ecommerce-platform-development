# E-commerce Platform Development

Laravel + Vue + Inertia technical assessment by **Md. Atiqur Rahman** (`AtiqurCode`).

**Demo (full app walkthrough):**  
https://www.loom.com/share/5062dded6dbf407282e6754747bbd534

---

## START HERE (reviewers)

### Step 1 — Run the project

Pick **A (Docker)** or **B (local / Laravel Herd)**.

#### Option A — Docker

Requires [Docker Desktop](https://www.docker.com/products/docker-desktop/) only. No local PHP/MySQL/Node needed.

**Windows**

```powershell
copy .env.example .env
.\docker\setup.ps1
```

**macOS / Linux**

```bash
cp .env.example .env
chmod +x docker/setup.sh
./docker/setup.sh
```

Open: **http://localhost**

Port `80` busy? Put this in `.env`, then run setup again:

```env
APP_PORT=8080
APP_URL=http://localhost:8080
```

Useful Docker commands:

```bash
docker compose up -d
docker compose down
docker compose exec laravel.test bash
docker compose exec laravel.test php artisan migrate:fresh --seed --force
```

#### Option B — Local (Laravel Herd / Valet / similar)

Need PHP 8.4+, Composer, Node 22+, and MySQL (Herd includes these).

1. Link/park the project in Herd (or point your local site to `public/`).
2. Install and configure:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

3. Set MySQL for local (not Docker host `mysql`):

```env
APP_URL=http://ecommerce-platform-development.test
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_platform_development
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in Herd/MySQL if it does not exist yet.

4. Migrate, seed, link storage, build assets:

```bash
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
```

5. Run the app:

```bash
# Herd serves PHP automatically — just open your site URL
# For Vite HMR during UI work:
npm run dev

# Or without Herd:
composer run dev
```

Open your Herd URL (example: `http://ecommerce-platform-development.test`) or `http://127.0.0.1:8000` if using `php artisan serve`.

First seed downloads product images (needs network). Images are stored under `storage/app/public/products`.

### Step 2 — Log in as admin

| | |
|---|---|
| Email | `admin@example.com` |
| Password | `password` |

1. Open `/login`
2. Click **Admin login** (fills credentials)
3. Click **Log in**

Also available: `test@example.com` / `password`

### Step 3 — Click through the app

| Check | Where |
|-------|--------|
| Public catalogue | `/products` |
| Product detail (slug URL) | click any product |
| Dashboard | `/dashboard` |
| Categories CRUD | `/admin/categories` |
| Subcategories CRUD | `/admin/subcategories` |
| Products CRUD + image | `/admin/products` |
| Dependent dropdown | product create/edit → pick category, subcategory loads |
| Restricted delete | delete a category that still has subcategories → blocked |

### Step 4 — Run tests

```bash
# Docker
docker compose exec laravel.test php artisan test --compact

# Local / Herd
php artisan test --compact
```

---

## Check the code (feature → files)

Use this map while reviewing.

| Feature | Backend | Frontend |
|---------|---------|----------|
| Routes | `routes/web.php` | — |
| Categories CRUD | `app/Http/Controllers/Admin/CategoryController.php`  
`app/Http/Requests/Category/`  
`app/Services/CategoryService.php`  
`app/Models/Category.php` | `resources/js/pages/admin/categories/` |
| Subcategories CRUD | `app/Http/Controllers/Admin/SubcategoryController.php`  
`app/Http/Requests/Subcategory/`  
`app/Services/SubcategoryService.php`  
`app/Models/Subcategory.php` | `resources/js/pages/admin/subcategories/` |
| Products CRUD + images | `app/Http/Controllers/Admin/ProductController.php`  
`app/Http/Requests/Product/`  
`app/Services/ProductService.php`  
`app/Services/ProductImageService.php`  
`app/Models/Product.php` | `resources/js/pages/admin/products/` |
| Category → subcategory API | `app/Http/Controllers/Admin/CategorySubcategoryController.php` | product Create/Edit forms |
| Dashboard | `app/Http/Controllers/Admin/DashboardController.php`  
`app/Services/DashboardService.php` | `resources/js/pages/admin/Dashboard.vue` |
| Public catalogue / detail | `app/Http/Controllers/Frontend/ProductController.php`  
`app/Services/StorefrontProductService.php` | `resources/js/pages/frontend/products/`  
`resources/js/components/products/ProductCard.vue` |
| Slugs | `app/Services/SlugGenerator.php` | — |
| Seed / demo data | `database/seeders/`  
`app/Support/EcommerceCatalog.php` | — |
| Schema | `database/migrations/2026_11_07_*`  
`docs/database-diagram.png`  
`database/ecommerce_platform_development.sql` | — |
| Tests | `tests/Feature/CategoryTest.php`  
`tests/Feature/SubcategoryTest.php`  
`tests/Feature/ProductTest.php` | — |
| Docker setup | `compose.yaml`  
`docker/setup.sh` / `docker/setup.ps1` | — |

### Folder layout

```text
app/
  Http/Controllers/Admin/       # Admin API/pages
  Http/Controllers/Frontend/    # Public catalogue
  Http/Requests/                # Validation
  Models/                       # Category, Subcategory, Product
  Services/                     # Business logic
  Support/EcommerceCatalog.php  # Seed/factory English data
database/
  migrations/ factories/ seeders/
  ecommerce_platform_development.sql
resources/js/
  pages/admin/                  # Admin UI
  pages/frontend/products/      # Public UI
  components/admin/             # Shared admin UI
  layouts/                      # AdminLayout, StorefrontLayout
tests/Feature/
compose.yaml + docker/          # One-command reviewer setup
docs/database-diagram.png
```

---

## What the app does

- Admin CRUD for **Categories → Subcategories → Products**
- Product image upload / replace / delete
- Dependent subcategory dropdown
- Parent delete blocked when children exist
- Public catalogue + SEO slug details (`/products/{slug}`)
- Seeded English products with images

---

## Database

![Database diagram](docs/database-diagram.png)

```text
categories 1──* subcategories 1──* products
```

| Rule | Behavior |
|------|----------|
| Delete category with subcategories | Blocked |
| Delete subcategory with products | Blocked |
| Delete product | Allowed (image file removed) |

Products store only `subcategory_id`. Category comes from `product → subcategory → category`.

---

## Main routes

**Public**

| URI | Description |
|-----|-------------|
| `/` | Redirects to `/products` |
| `/products` | Catalogue |
| `/products/{slug}` | Details |

**Admin** (`auth` + `verified`)

| URI | Description |
|-----|-------------|
| `/dashboard` | Stats |
| `/admin/categories` | Category CRUD |
| `/admin/subcategories` | Subcategory CRUD |
| `/admin/products` | Product CRUD |
| `/admin/categories/{id}/subcategories` | Dependent dropdown JSON |

---

## Tech stack

| Layer | Choice |
|-------|--------|
| Backend | Laravel 13, PHP 8.4, MySQL, Eloquent |
| Auth | Laravel Fortify (session) |
| Frontend | Vue 3, TypeScript, Inertia.js v3, Tailwind 4 |
| Validation | Form Requests |
| Tests | Pest |
| Containers | Laravel Sail (`compose.yaml`) |

```text
Browser (Vue + Inertia)
  → Controllers → Form Requests
  → Services
  → Models + MySQL
```

Controllers stay thin. Domain logic lives in `app/Services`.
