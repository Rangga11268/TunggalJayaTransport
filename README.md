# 🚌 Tunggal Jaya Transport - Monorepo

Repository monorepo resmi untuk ekosistem Tunggal Jaya Transport:
- **`backend/`**: Laravel 12 Backend API with PostgreSQL 18 & Sanctum Auth.
- **`web/`**: Web Frontend Client (Vue 3, Inertia.js, Tailwind CSS).
- **`mobile/`**: React Native (Expo + TypeScript) Mobile Application dengan tema **Ultra-Modern Obsidian Dark Luxury** (RedBus design reference).
- **`assets/`**: Shared Brand Identity, Logos, Bus fleet cutouts, Banners.
- **`docs/`**: API documentation & Architecture specifications.

---

## 🚀 Quick Start

### 1. Backend (Laravel & PostgreSQL)
```bash
cd backend
composer install
php artisan migrate:fresh --seed
php artisan serve
```

### 2. Mobile App (React Native & Expo)
```bash
cd mobile
npm install
npx expo start
```

### 3. Web Client
```bash
cd backend
npm run dev
```

---

## 🗄️ Database Info
- **Database Engine**: PostgreSQL 18
- **Database Name**: `tunggal_jaya_db`
- **Host / Port**: `127.0.0.1:5432`
- **Default Auth**: PostgreSQL user `postgres`
