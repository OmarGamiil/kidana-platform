# Kidana Platform (منصة كدانة)

<div align="center">

![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel 13](https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Inertia.js 3](https://img.shields.io/badge/Inertia.js-3-9553E9?style=for-the-badge&logo=inertia&logoColor=white)
![Vue 3](https://img.shields.io/badge/Vue.js-3.5-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white)
![TypeScript](https://img.shields.io/badge/TypeScript-5.2-3178C6?style=for-the-badge&logo=typescript&logoColor=white)
![Tailwind CSS 4](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Pest PHP](https://img.shields.io/badge/Tests-Pest_PHP-F43F5E?style=for-the-badge&logo=pest&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge)

<p align="center">
  <b>Enterprise holy sites & Hajj operational management platform designed for scale, compliance, and real-time operational excellence.</b>
</p>

</div>

---

## 📌 Overview

**Kidana Platform** is a specialized, enterprise-grade operations and management system tailored for Hajj office workflows, holy sites readiness, and seasonal operational logistics. Built using a modern full-stack architecture (**Laravel 13 + Inertia.js 3 + Vue 3 + TypeScript + Tailwind CSS 4**), the platform provides end-to-end tooling for office administration, season management, field observations, work plan tracking, and multi-tenant operational oversight.

---

## ✨ Key Features

### 🏢 Hajj Office Management & Multi-Tenancy
* **Multi-tenant Office Isolation:** Dedicated context for Hajj offices (`hajj_office`) with strict account-type authorization guards.
* **Season Lifecycle & Readiness:** Track office readiness, contracts, and operational state across Hajj seasons.
* **Role-Based Membership:** Office membership roles with fine-grained capability checks.

### 🔐 Secure Multi-Step Onboarding Pipeline
* **Tokenized Activation:** Public-ID based activation links that isolate raw invite tokens from subsequent sessions.
* **Session-Bound State Machine:** Progressive activation flow (`account` ➔ `verify` ➔ `password`) protected by stateful session validation.
* **Two-Factor OTP Security:** Cryptographic OTP delivery with aggressive rate limiting (`throttle:otp`), expiration guards, and retry thresholds.
* **Credential Hardening:** Strict password policy enforcement via Laravel Fortify.

### 📊 Field Operations & Observability
* **Field Observations:** Record, categorize, and follow up on on-site observations during active seasons.
* **Work Plan Tracking:** Operational schedules, milestones, and deliverables mapped against station allocations.
* **Station & City Mapping:** Geographic modeling across holy sites, stations, and municipal sectors.
* **Comprehensive Audit Trail:** Immutable `AuditLogEntry` telemetry tracking administrative and operational mutations.

### 🎨 Modern Reactive UI & Localization
* **Single Page Application (SPA):** Instant client-side transitions via Inertia.js v3 and Vue 3 Composition API (`<script setup>`).
* **Design System:** Custom utility UI using Tailwind CSS v4, Reka UI, and Lucide icons.
* **Bilingual Support (RTL/LTR):** Arabic and English internationalization powered by `vue-i18n`.

---

## 🏗️ Architecture & Technology Stack

| Layer | Technologies |
|---|---|
| **Backend Framework** | [Laravel 13](https://laravel.com), PHP 8.3+ |
| **Authentication & Security** | [Laravel Fortify](https://laravel.com/docs/fortify), Custom OTP Pipeline, Rate Limiting |
| **Frontend Framework** | [Vue 3.5](https://vuejs.org) (Composition API), [Inertia.js v3](https://inertiajs.com) |
| **Type Safety** | [TypeScript](https://www.typescriptlang.org), [Laravel Wayfinder](https://github.com/laravel/wayfinder) (auto-generated typed route actions) |
| **Styling & Components** | [Tailwind CSS v4](https://tailwindcss.com), [Reka UI](https://reka-ui.com), [Lucide Vue](https://lucide.dev) |
| **Testing & Quality** | [Pest PHP v4](https://pestphp.com), [Larastan](https://github.com/larastan/larastan) (PHPStan), [Laravel Pint](https://laravel.com/docs/pint), [ESLint](https://eslint.org) |
| **Bundling & Tooling** | [Vite 8](https://vitejs.dev), Concurrently |

---

## 📂 Project Structure

```text
kidana-platform/
├── app/
│   ├── Actions/            # Single-responsibility domain actions (Fortify & custom)
│   ├── DTOs/               # Strongly-typed Data Transfer Objects
│   ├── Enums/              # Typed enumerations for roles, statuses, and types
│   ├── Http/
│   │   ├── Controllers/    # Thin HTTP controllers (Activation, Office, Marketing)
│   │   └── Middleware/     # Security, account verification, and session guards
│   ├── Models/             # Eloquent models (Office, Season, ActivationInvite, etc.)
│   └── Services/           # Business logic and domain service classes
├── database/
│   ├── factories/          # Model factories for robust testing
│   ├── migrations/         # Relational database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── js/
│   │   ├── components/     # Reusable Vue components & design system primitives
│   │   ├── layouts/        # App, Marketing, and Office layout shells
│   │   ├── pages/          # Inertia page views (marketing, office, auth, activation)
│   │   └── types/          # TypeScript definitions & Wayfinder generated types
│   └── css/                # Tailwind CSS v4 entrypoints
├── routes/
│   ├── web.php             # Core platform and office routes
│   ├── activation.php      # Secure account activation workflow routes
│   └── settings.php        # User profile and security settings
└── tests/
    ├── Feature/            # Pest feature test suites (Activation, Office, Auth)
    └── Unit/               # Unit and domain logic tests
```

---

## 🚀 Getting Started

### Prerequisites

* **PHP:** `^8.3` (with `pdo`, `mbstring`, `openssl`, `curl`, `bcmath`)
* **Composer:** `^2.7`
* **Node.js:** `^20.0` or `^22.0`
* **Package Manager:** `npm` or `pnpm`
* **Database:** MySQL 8.0+ or SQLite 3.35+

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/OmarGamiil/kidana-platform.git
   cd kidana-platform
   ```

2. **Automated Setup (Recommended):**
   ```bash
   composer run setup
   ```
   *This command installs Composer dependencies, configures `.env`, generates app keys, runs migrations, installs npm packages, and builds frontend assets.*

3. **Manual Setup (Alternative):**
   ```bash
   # Install PHP dependencies
   composer install

   # Environment configuration
   cp .env.example .env
   php artisan key:generate

   # Run database migrations
   php artisan migrate

   # Install frontend dependencies & build
   npm install
   npm run build
   ```

---

## 💻 Development Workflow

Start the unified development environment (Laravel server + Queue worker + Vite dev server):

```bash
composer run dev
```

Or run services individually:

```bash
# Terminal 1: Laravel Web Server
php artisan serve

# Terminal 2: Vite Dev Server with HMR
npm run dev

# Terminal 3: Queue Worker
php artisan queue:listen
```

Access the application in your browser at `http://localhost:8000`.

---

## 🧪 Testing & Code Quality

Maintain high software quality using the built-in test and linting pipelines:

```bash
# Run the full Pest test suite
composer run test

# Run code style fixer (Laravel Pint)
composer run lint

# Static analysis (PHPStan / Larastan)
composer run types:check

# Frontend TypeScript checking
npm run types:check

# Complete CI sanity check
composer run ci:check
```

---

## 🔒 Security Vulnerabilities

If you discover any security-related issues, please avoid public disclosure and report them directly to the maintainer.

---

## 👤 Author

* **Omar Gamil** &mdash; [@OmarGamiil](https://github.com/OmarGamiil)

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE).
