# 🏛️ Tunggal Jaya Transport - Monorepo Architecture

Struktur Monorepo Tunggal Jaya Transport:

```
TunggalJayaTransport/
├── assets/          # Brand Identity, Logos, Bus Fleet Cutouts, Hero Banners
├── backend/         # Laravel 12 Backend API with PostgreSQL 18 & Sanctum Auth
├── docs/            # Architecture Specs, ERD, API Reference
├── mobile/          # React Native (Expo) Mobile App (Obsidian Dark Luxury Theme)
├── web/             # Web Frontend Client (Inertia Vue 3 / Vite)
├── .gitignore
└── README.md
```

---

## 🗄️ Database: PostgreSQL 18

- **Host**: `127.0.0.1:5432`
- **Database**: `tunggal_jaya_db`
- **Tabel Utama**:
  - `users`, `roles`, `permissions`
  - `buses`, `routes`, `schedules`
  - `bookings`, `payment_histories`
  - `charter_bookings`, `charter_booking_bus`
  - `promo_codes`, `news_articles`, `drivers`, `conductors`

---

## 📱 Mobile: React Native (Expo + TypeScript)

- **UI Style**: Ultra-Modern Obsidian Dark Luxury (RedBus reference design)
- **Primary Accent**: Vibrant Crimson Red (`#FF2E44`)
- **Canvas / Surface**: `#0B0B0F` (Canvas), `#14141E` (Card), `#1E1E2C` (Container)
- **Key Screens**:
  - `GetStartedScreen` (Hero Full-Bleed Onboarding)
  - `HomeScreen` (Explore Header, Search Capsule, Category Chips, Hero Bus Card, Stories, Schedules)
  - `ScheduleListScreen` (Route Summary, Deals/Details/Reviews Tabs, Schedules, Bottom Booking Bar)
  - `SeatSelectionScreen` (Cabin Map, Driver Area, Real-time Lock, Auto Departure Guard)
  - `CheckoutScreen` (Passenger Form, Voucher, Midtrans Gateway)
  - `TicketDetailScreen` (Boarding Pass E-Ticket with QR Code)
  - `BookingHistoryScreen` (AKAP & Pariwisata History)
  - `CharterScreen` (Pariwisata Quote Request)
  - `PromoScreen` (Voucher Center)
  - `ProfileScreen` (VIP Gold Loyalty Card & TJ Points)
  - `HelpScreen` (24/7 WhatsApp Support & FAQ Accordion)
