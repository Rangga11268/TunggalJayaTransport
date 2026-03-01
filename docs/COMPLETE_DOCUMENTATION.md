# COMPLETE DOCUMENTATION — TunggalJayaTransport

Comprehensive, merged documentation for the project. This file combines `PROJECT_OVERVIEW.md`, `docs/file_list.md`, and `docs/detailed_summaries.md` into a single reference.

Generated on: 2026-02-28

---

## Table of Contents

- Project Summary
- Folder Structure
- Key Findings
- File List (selected)
- Detailed Summaries (controllers, models, services)
- Database & Migrations
- Risks & Recommendations
- Next Steps / Checklist

---

## Project Summary

- Project: TunggalJayaTransport
- Type: Bus ticket reservation web app (Laravel backend + Inertia/Vue frontend)
- Backend: Laravel 12, PHP ^8.2
- Frontend: Inertia + Vue 3 + Vite + Tailwind
- Payments: Midtrans via `app/Services/MidtransService.php` & `app/Services/PaymentService.php`
- Media: Spatie Media Library
- Auth & Roles: Laravel Breeze, Sanctum, Spatie Permission

---

## Folder Structure (high level)

- `app/`
    - Controllers: `app/Http/Controllers/` (Admin, Frontend, Auth). Main features: booking flow, admin CRUD for buses/drivers/routes/schedules, reports.
    - Models: `app/Models/` (User, Booking, Schedule, Bus, Route, PaymentHistory, PromoCode, NewsArticle, OtpCode, Driver, Conductor, Category, ScheduleBackup).
    - Services: `app/Services/` (PaymentService, MidtransService, TicketPdfService, OtpService, WhatsAppNotificationService).
    - Console Commands: `app/Console/Commands/` (maintenance, reports, reminders).
    - Middleware & Providers: custom middleware (phone verification, roles), providers for barcode alias and middleware.

- `routes/` — `web.php` (frontend routes + booking + webhook), `admin.php` (admin routes), `auth.php`, `console.php`.
- `resources/` — `js/` (Inertia pages & components), `views/` (Blade templates for emails & PDFs), `css/`.
- `config/` — midtrans.php, media-library.php, dompdf.php, excel.php, permission.php, etc.
- `database/` — migrations & seeders (buses, routes, schedules, roles, users, etc.).
- `public/` — assets (img, video, sw.js, sample ticket PDF).

---

## Key Findings (summary of important behavior)

- Booking flow
    - `app/Http/Controllers/Frontend/BookingController.php` prepares schedule filters, availability checks, seat selection, booking creation, payment processing, confirmation and ticket download flows.
    - `app/Models/Booking.php` stores `seat_numbers` as CSV, validates `number_of_seats` vs bus capacity, provides helper `getOccupiedSeatsForSchedule($scheduleId, $date)`, payment expiry (30 minutes), and `startPayment()`.

- Payments
    - `app/Services/PaymentService.php` prepares Midtrans payloads, calls `MidtransService`, writes `PaymentHistory`, and updates `Booking` to `pending` with `midtrans_transaction_id`.
    - Webhook: POST `/payment/webhook` in `routes/web.php` (accessible without auth).

- Auth & Verification
    - Middleware `phone.verified` is required on booking and dashboard routes. `User` model includes phone verification logic and `isFullyVerified()`.
    - OTPs handled via `app/Mail/OtpMail.php` and `app/Services/OtpService.php`.

- PDF & Exports
    - Ticket PDF produced via `barryvdh/laravel-dompdf` and `app/Services/TicketPdfService.php`.
    - Reports via `maatwebsite/excel` using `app/Exports/CustomReportExport.php`.

- Admin
    - Admin controllers in `app/Http/Controllers/Admin/*` manage CRUD and reports. Admin frontend in `resources/js/Pages/Admin/*`.

- Maintenance & Jobs
    - Multiple `Console/Commands` exist for status updates, reminders, cleanups, and report generation.

---

## File List (selected)

### Controllers (selected)

- app/Http/Controllers/ProfileController.php
- app/Http/Controllers/PaymentController.php
- app/Http/Controllers/BookingHistoryController.php
- app/Http/Controllers/Admin/ScheduleController.php
- app/Http/Controllers/Frontend/BookingController.php
- app/Http/Controllers/Admin/ReportController.php
- app/Http/Controllers/Admin/PromoCodeController.php
- app/Http/Controllers/Admin/DriverController.php
- app/Http/Controllers/Admin/BusController.php
- app/Http/Controllers/Admin/BookingController.php

### Models

- app/Models/Booking.php
- app/Models/Schedule.php
- app/Models/Bus.php
- app/Models/Route.php
- app/Models/PaymentHistory.php
- app/Models/PromoCode.php
- app/Models/User.php

### Services

- app/Services/PaymentService.php
- app/Services/MidtransService.php
- app/Services/TicketPdfService.php
- app/Services/OtpService.php
- app/Services/WhatsAppNotificationService.php

### JS Pages (examples)

- resources/js/Pages/Frontend/Booking/Index.vue
- resources/js/Pages/Frontend/Booking/Show.vue
- resources/js/Pages/Frontend/Booking/SeatSelection.vue
- resources/js/Pages/Admin/Reports/Sales.vue

### Migrations (high level)

- see `database/migrations/` — includes complete\_\* tables, payment_histories, promo_codes, otp_codes, media, notifications, etc.

---

## Detailed Summaries

### Controllers (intisari)

- `Frontend/BookingController.php`
    - Responsibilities: search/filter schedules, compute availability, validate selected seats, create bookings, start payment (via `PaymentService`), confirmation & ticket download.
    - Notes: Uses caching for origins/destinations, eager-loading for booked seat counts, performs date/daily schedule handling.

- `PaymentController.php`
    - Responsibilities: handles payment requests, status checks, and incoming webhook. Delegates to `PaymentService` and `MidtransService`.

- `Admin/*` controllers
    - Responsibilities: CRUD for Buses, Drivers, Conductors, Routes, Schedules, Promo Codes, Users; reporting endpoints to support admin UI.

### Models (intisari)

- `Booking`
    - Stores booking data including `seat_numbers` CSV, `number_of_seats`, `total_price`, payment and booking statuses, timestamps for payment started.
    - Helpers: `getBookedSeatNumbersAttribute()`, `getOccupiedSeatsForSchedule()`, `isPaymentExpired()`, `startPayment()`.

- `Schedule`
    - Supports daily and non-daily schedules, has helpers `getActualDepartureTime()`, scopes `available()` and `isAvailableForBooking()`.

- `User`
    - Role constants defined (`admin`, `schedule_manager`, `customer`, `driver`, `conductor`), uses Spatie `HasRoles`, and verifications for email/phone.

### Services (intisari)

- `PaymentService`
    - Creates Midtrans order payload, sanitizes phone/name, sets `enabled_payments`, persists `PaymentHistory`, updates booking to `pending` and returns snap token and redirect URL.

- `MidtransService`
    - Wraps Midtrans PHP SDK for creating transactions and verifying webhooks (check implementation for signature verification).

- `TicketPdfService`
    - Renders ticket Blade template to PDF via DomPDF.

## Detailed Database Schema & Relationships

### Core Table Schema

| Table               | Primary Key | Foreign Keys             | Key Columns                                                     | Description                            |
| ------------------- | ----------- | ------------------------ | --------------------------------------------------------------- | -------------------------------------- |
| `users`             | `id`        | -                        | `name`, `email`, `password`, `role`                             | standard user management               |
| `buses`             | `id`        | -                        | `name`, `plate_number`, `capacity`, `status`                    | fleet management                       |
| `routes`            | `id`        | -                        | `origin`, `destination`, `distance`, `waypoints` (JSON)         | travel path definitions                |
| `schedules`         | `id`        | `bus_id`, `route_id`     | `departure_time`, `arrival_time`, `price`, `is_daily`           | mapping of buses to routes with timing |
| `bookings`          | `id`        | `user_id`, `schedule_id` | `booking_code`, `seat_numbers`, `total_price`, `payment_status` | customer reservations                  |
| `drivers`           | `id`        | -                        | `employee_id`, `license_number`, `status`                       | driver profiles                        |
| `conductors`        | `id`        | -                        | `employee_id`, `phone`, `status`                                | conductor profiles                     |
| `payment_histories` | `id`        | `booking_id`             | `transaction_id`, `transaction_status`, `payment_url`           | audit log for Midtrans payments        |
| `promo_codes`       | `id`        | -                        | `code`, `discount_amount`, `expiry_date`                        | marketing & discounts                  |

### Entity Relationship Mapping (ER Diagram Logic)

- **Schedules**:
    - `belongsTo` **Bus** (1:N)
    - `belongsTo` **Route** (1:N)
    - `hasMany` **Bookings** (1:N)
- **Bookings**:
    - `belongsTo` **User** (Optional, 1:N)
    - `belongsTo` **Schedule** (1:N)
    - `hasMany` **PaymentHistories** (1:N)
- **Fleet Management**:
    - **Bus** `belongsToMany` **Driver** (via `bus_driver` pivot)
    - **Bus** `belongsToMany` **Conductor** (via `bus_conductor` pivot)
- **Content**:
    - **NewsArticle** `belongsTo` **Category** (1:N)
    - **Spatie Media Library** handles attachments for Buses, Drivers, and Conductors.

---

## Database & Migrations (notes)

- Migrations include core tables named `complete_*` for users, buses, routes, schedules, and bookings — likely representing denormalized or composite tables for reporting or simplified queries.
- Payment histories table tracks Midtrans details and redirect URLs.
- Promo codes, OTP codes, media collections, and notifications are present.

---

## Risks & Recommendations

- Concurrency / Race Conditions
    - Risk: simultaneous seat selection could allow double booking if booking creation isn't atomic with seat allocation.
    - Recommendation: wrap booking creation and seat assignment inside a DB transaction and, if needed, use row/table locks (SELECT ... FOR UPDATE) or optimistic locking.

- seat_numbers Storage
    - Risk: storing seat_numbers as CSV complicates queries and indexing.
    - Recommendation: consider a normalized `booking_seats` table if per-seat operations or analytics grow.

- Webhook Security
    - Risk: webhook endpoint must verify Midtrans signatures to prevent spoofed notifications.
    - Recommendation: verify signature using Midtrans server key/secret and reject invalid requests.

- Input Validation
    - Ensure all public controllers validate input strictly and sanitize customer-provided data.

---

## Next Steps / Checklist

1. Audit `BookingController::store` — ensure DB transaction & locking.
2. Inspect `MidtransService` webhook handling and add signature verification if missing.
3. Run tests (or add unit/integration tests) for booking payment flows and webhook handling.
4. Optionally normalize `seat_numbers` into a separate `booking_seats` table.

---

If you want, I can now:

- A: Walk through each controller file and append a per-file summary (implementation snippets and important lines).
- B: Audit `BookingController::store` and produce a patch to make booking atomic.
- C: Inspect `app/Services/MidtransService.php` for webhook signature handling and add verification.
  I have completed reading the frontend pages and added the frontend summaries and webhook audit below.

---

## Frontend Pages — Read Summary (completed)

- `resources/js/Pages/Frontend/Home.vue`: Hero search console, autocomplete origins/destinations, featured routes, latest news, fleet CTA. Search posts to `frontend.booking.index`.
- `resources/js/Pages/Frontend/Booking/Index.vue`: Schedule search UI (origin/destination/date/class/time filters), sidebar filters, schedule cards with `available_seats`, links to `frontend.booking.show`.
- `resources/js/Pages/Frontend/Booking/Show.vue`: Passenger form page. Submits to `frontend.booking.store` to create a booking (stores booking with seats null and number_of_seats set).
- `resources/js/Pages/Frontend/Booking/SeatSelection.vue`: Seat map UI (2-3 layout), shows `occupiedSeats` from backend, lets user select seats, validates selection count, posts to `frontend.booking.select-seats` (axios POST) then posts to `frontend.booking.process-payment`. Integrates Midtrans Snap (`window.snap.pay`) when `snap_token` returned. Supports promo validation via `api.promo.validate`.
- `resources/js/Pages/Frontend/Booking/Success.vue`: Ticket view + download (html2canvas) and payment-status polling via `frontend.payment.status` endpoint. Shows paid/pending states and e-ticket download link.
- `resources/js/Pages/Frontend/Routes/Index.vue` & `Routes/Show.vue`: Route listing and detail pages, show available schedules and CTA to booking index with prefilled origin/destination.
- `resources/js/Pages/Frontend/Fleet/Index.vue`: Fleet listing with filters and facility list; links to booking index.
- `resources/js/Pages/Frontend/News/Index.vue` & `News/Show.vue`: Article listing, categories, article page renders HTML `article.content`, related articles in sidebar.
- `resources/js/Pages/Frontend/Search/Index.vue`: Generic search UI used for news/routes across site; uses `frontend.search.index` route.
- `resources/js/Pages/Frontend/Profile/Edit.vue` (+ partials): Profile management forms (update profile, change password, delete account) wired to Laravel profile routes.
- `resources/js/Pages/Frontend/Contact.vue` & `About.vue`: Contact form posts to `contact.store`; About includes FAQs and video; static informational pages.
- `resources/js/Pages/Frontend/BookingHistory/*`: Customer booking history and detail views with download link for paid bookings.

Notes about frontend → backend interactions:

- Seat selection: frontend posts seat array to `frontend.booking.select-seats` which must atomically verify and persist seats (BookingController uses DB transaction + locks in selectSeats()).
- Payment: frontend calls `frontend.booking.process-payment` (POST) which delegates to `PaymentService` to create a Midtrans order and returns a `snap_token` or `redirect_url`.
- Success page polls `frontend.payment.status` (PaymentController::status) which queries Midtrans via `MidtransService::getTransactionStatus()` when necessary.

---

## Midtrans Webhook Audit — Findings & Recommendations

Findings (current code):

- The webhook endpoint is `POST /payment/webhook` → handled by `PaymentController::webhook()` which calls `MidtransService::validateNotification($payload)` then `MidtransService::handleWebhook($payload)`.
- Current `MidtransService::validateNotification()` implementation queries Midtrans API (`Transaction::status($orderId)`) and compares the returned `transaction_status` to the incoming payload's `transaction_status`.

Why this is insufficient:

- Relying solely on a status comparison allows an attacker to POST a fabricated payload that matches a previously queried status or to cause unnecessary load/queries. It also lacks cryptographic verification of the sender (signature). Midtrans provides `signature_key` (SHA512) in notifications for integrity verification.

Recommended hardening (apply quickly):

1. Prefer verifying `signature_key` using server key: compute SHA512 of `order_id + status_code + gross_amount + server_key` and compare with incoming `signature_key` using `hash_equals()` to prevent timing attacks.
2. Keep the API-status fallback only as a secondary check (e.g., if `signature_key` missing, reject or log and verify via API but do not trust payload without signature).
3. Make webhook handler idempotent and transactional: update `PaymentHistory` and `Booking` inside a DB transaction, and ensure repeated identical notifications don't change state incorrectly.
4. Add strict logging with correlation (order_id, payment_history_id), rate-limiting and alerting for invalid signature attempts.
5. Restrict endpoint exposure where possible: require HTTPS, consider IP allowlist for Midtrans IPs (if supported), and validate `Content-Type: application/json`.

Minimal example (illustrative) to verify signature in PHP:

```php
$serverKey = config('midtrans.server_key');
$expected = hash('sha512', $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $serverKey);
if (!hash_equals($expected, $payload['signature_key'])) {
    Log::warning('Invalid midtrans signature', ['order_id' => $payload['order_id'] ?? null]);
    return response()->json(['status'=>'error','message'=>'Invalid signature'], 400);
}
```

Action items added to checklist (already read and documented):

- Documented all major frontend pages and endpoints called.
- Documented current webhook validation behavior and explicit recommendations above.

---

If you want, I will now automatically append these findings into the codebase as a short `docs/AUDIT_WEBHOOK.md` file or create a PR patch for `MidtransService::validateNotification()` — but I will not change code unless you request it. The documentation in this file is updated and saved.
