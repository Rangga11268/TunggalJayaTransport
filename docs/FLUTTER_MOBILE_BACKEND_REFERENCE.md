# Flutter Mobile Backend Reference — TunggalJayaTransport

Dokumen ini merangkum bagian backend web Laravel yang paling relevan untuk aplikasi mobile Flutter.
Tujuannya supaya implementasi mobile tidak bekerja dari asumsi, tetapi dari komponen backend yang benar-benar ada.

## 1. Ringkasan Peran Backend

Backend Laravel ini sudah menjadi pusat data dan logika bisnis untuk:

- auth user
- pencarian jadwal
- booking kursi
- pembayaran Midtrans
- riwayat tiket
- konten berita
- armada, rute, jadwal
- OTP dan verifikasi phone/email

Untuk mobile, backend ini sebaiknya dipakai sebagai source of truth, sedangkan Flutter hanya client.

## 2. Model Inti

### 2.1 `User`

Peran:

- akun login utama
- relasi ke booking dan artikel
- role/permission via Spatie

Field penting:

- `name`
- `email`
- `phone`
- `password`
- `google_id`
- verifikasi email/phone

Relasi penting:

- `bookings()`
- `articles()`

Relevansi mobile:

- login, register, profile, verifikasi, booking history

### 2.2 `Booking`

Peran:

- inti transaksi booking tiket

Field penting:

- `booking_code`
- `user_id`
- `schedule_id`
- `passenger_name`
- `passenger_phone`
- `passenger_email`
- `seat_numbers`
- `number_of_seats`
- `total_price`
- `booking_status`
- `payment_status`
- `midtrans_transaction_id`
- `booking_date`

Relasi penting:

- `user()`
- `schedule()`
- `promoCode()`
- `paymentHistories()`

Relevansi mobile:

- booking flow, seat selection, payment, history, ticket detail

### 2.3 `Schedule`

Peran:

- jadwal perjalanan bus

Field penting:

- `bus_id`
- `route_id`
- `departure_time`
- `arrival_time`
- `price`
- `status`
- `is_daily`
- `days_of_week`

Relasi penting:

- `bus()`
- `route()`
- `bookings()`

Relevansi mobile:

- search, availability, detail jadwal, seat map

### 2.4 `Bus`

Peran:

- data armada

Field penting:

- `name`
- `plate_number`
- `capacity`
- `bus_type`
- `status`

Relasi penting:

- `schedules()`
- `drivers()`
- `conductors()`

Relevansi mobile:

- fleet list, detail bus, badge kelas

### 2.5 `Route`

Peran:

- definisi asal, tujuan, dan informasi rute

Field penting:

- `origin`
- `destination`
- `distance`
- `duration`
- `waypoints`

Relasi penting:

- `schedules()`

Relevansi mobile:

- search autocomplete, route list, route detail

### 2.6 `PaymentHistory`

Peran:

- log status transaksi pembayaran

Field penting:

- `booking_id`
- `transaction_id`
- `payment_method`
- `gross_amount`
- `transaction_status`
- `fraud_status`
- `payment_url`
- `metadata`

Relasi penting:

- `booking()`

Relevansi mobile:

- payment status check, audit pembayaran

### 2.7 `PromoCode`

Peran:

- diskon booking

Field penting:

- `code`
- `discount_type`
- `discount_value`
- `min_purchase_amount`
- `usage_limit`
- `valid_from`
- `valid_until`
- `status`

Relevansi mobile:

- validasi promo saat checkout

### 2.8 `NewsArticle`

Peran:

- konten berita dan pengumuman

Field penting:

- `title`
- `slug`
- `content`
- `is_published`
- `category_id`
- `user_id`

Relasi penting:

- `category()`
- `user()`

Relevansi mobile:

- news list, news detail

### 2.9 `Category`

Peran:

- kategori berita atau konten

Relasi penting:

- parent-child category
- article relation

Relevansi mobile:

- news grouping, filter kategori

### 2.10 `Driver` dan `Conductor`

Peran:

- data kru armada

Relasi penting:

- many-to-many ke `Bus`

Relevansi mobile:

- biasanya tidak tampil dominan, tapi bisa dipakai di detail bus atau info armada

### 2.11 `OtpCode`

Peran:

- OTP verifikasi

Field penting:

- `identifier`
- `method`
- `otp`
- `attempts`
- `expires_at`
- `used`
- `phone`
- `ip_address`

Relevansi mobile:

- verify phone, resend OTP, forgot password flow jika dipakai

## 3. Controller Inti

### 3.1 Frontend controllers

#### `HomeController`

Fungsi:

- homepage frontend
- featured routes
- latest news
- fleet preview
- recommendation data

Relevansi mobile:

- home feed / landing content

#### `SearchController`

Fungsi:

- pencarian jadwal
- filter asal, tujuan, tanggal, class, time

Relevansi mobile:

- search page dan result list

#### `RouteController`

Fungsi:

- daftar rute
- detail rute

Relevansi mobile:

- route list/detail

#### `FleetController`

Fungsi:

- daftar armada

Relevansi mobile:

- fleet list/detail

#### `NewsController`

Fungsi:

- daftar berita
- detail berita

Relevansi mobile:

- news list/detail

#### `BookingController`

Fungsi:

- booking flow utama
- seat selection
- booking creation
- payment preparation
- confirmation
- success view
- ticket download

Relevansi mobile:

- ini controller paling penting untuk app mobile

#### `ContactController`

Fungsi:

- form kontak

Relevansi mobile:

- halaman contact/help

#### `BookingHistoryController`

Fungsi:

- riwayat booking user

Relevansi mobile:

- history list/detail

### 3.2 Auth controllers

#### `AuthenticatedSessionController`

Fungsi:

- login/logout web session

Relevansi mobile:

- jadi referensi perilaku auth, tapi mobile sebaiknya pakai token API

#### `RegisteredUserController`

Fungsi:

- register user web

Relevansi mobile:

- referensi registrasi mobile

#### `PasswordResetLinkController`, `NewPasswordController`, `PasswordController`

Fungsi:

- forgot password, reset password, update password

Relevansi mobile:

- flow forgot/reset password di mobile

#### `PhoneVerificationController`

Fungsi:

- kirim dan verifikasi OTP phone

Relevansi mobile:

- verifikasi phone user

#### `GoogleAuthController`

Fungsi:

- login Google

Relevansi mobile:

- kalau nanti mau social login di Flutter

### 3.3 Admin controllers

#### `DashboardController`

Fungsi:

- ringkasan dashboard admin

#### `BusController`, `RouteController`, `ScheduleController`

Fungsi:

- manajemen armada, rute, dan jadwal

Relevansi mobile:

- data master yang dipakai aplikasi

#### `BookingController` (admin)

Fungsi:

- data booking untuk admin

#### `ReportController`

Fungsi:

- laporan penjualan, occupancy, custom report

#### `NewsController` (admin)

Fungsi:

- CRUD berita dan upload gambar

#### `UserController`, `CustomerController`

Fungsi:

- manajemen user/customer

#### `PromoCodeController`

Fungsi:

- CRUD promo

#### `NotificationController`

Fungsi:

- notifikasi admin

## 4. Service Inti

### 4.1 `PaymentService`

Peran:

- membangun payload pembayaran Midtrans
- membuat transaksi
- menyimpan payment history
- mengubah status booking menjadi pending

Relevansi mobile:

- paling penting untuk checkout

### 4.2 `MidtransService`

Peran:

- wrapper Midtrans SDK
- create transaction
- status check
- webhook validation

Relevansi mobile:

- payment lifecycle

### 4.3 `BookingFilterService`

Peran:

- membantu filtering booking/schedule data

Relevansi mobile:

- hasil search dan filter booking

### 4.4 `OtpService`

Peran:

- generate dan validasi OTP

Relevansi mobile:

- phone verification, resend OTP

### 4.5 `TicketPdfService`

Peran:

- generate tiket PDF

Relevansi mobile:

- download ticket

### 4.6 `WhatsAppNotificationService`

Peran:

- notifikasi via WhatsApp

Relevansi mobile:

- bisa dipakai untuk booking reminder atau OTP jika alurnya mendukung

## 5. Frontend Views Yang Relevan

### 5.1 Home

File group:

- `resources/js/Pages/Frontend/Home.vue`

Isi utama:

- hero/summary
- featured routes
- latest news
- fleet preview

Relevansi mobile:

- jadi acuan home screen Flutter

### 5.2 Search

File group:

- `resources/js/Pages/Frontend/Search/Index.vue`

Isi utama:

- search form
- hasil pencarian jadwal

Relevansi mobile:

- search screen

### 5.3 Routes

File group:

- `resources/js/Pages/Frontend/Routes/Index.vue`
- `resources/js/Pages/Frontend/Routes/Show.vue`

Relevansi mobile:

- route list/detail

### 5.4 Fleet

File group:

- `resources/js/Pages/Frontend/Fleet/Index.vue`

Relevansi mobile:

- fleet list

### 5.5 News

File group:

- `resources/js/Pages/Frontend/News/Index.vue`
- `resources/js/Pages/Frontend/News/Show.vue`

Relevansi mobile:

- news list/detail

### 5.6 Booking

File group:

- `resources/js/Pages/Frontend/Booking/Index.vue`
- `resources/js/Pages/Frontend/Booking/Show.vue`
- `resources/js/Pages/Frontend/Booking/SeatSelection.vue`
- `resources/js/Pages/Frontend/Booking/Success.vue`

Relevansi mobile:

- ini acuan langsung flow booking Flutter

### 5.7 Booking history

File group:

- `resources/js/Pages/Frontend/BookingHistory/Index.vue`
- `resources/js/Pages/Frontend/BookingHistory/Show.vue`

Relevansi mobile:

- booking history list/detail

### 5.8 Profile

File group:

- `resources/js/Pages/Frontend/Profile/Edit.vue`

Relevansi mobile:

- profile page

### 5.9 Contact dan About

File group:

- `resources/js/Pages/Frontend/Contact.vue`
- `resources/js/Pages/Frontend/About.vue`

Relevansi mobile:

- contact/help dan about page

## 6. Pemetaan Mobile Feature Ke Backend

### Home

Backend sumber:

- `HomeController`
- `NewsController`
- `FleetController`
- `RouteController`

### Search

Backend sumber:

- `SearchController`
- `BookingController`
- `Schedule` model

### Booking

Backend sumber:

- `BookingController`
- `Booking` model
- `Schedule` model
- `PaymentService`

### Payment

Backend sumber:

- `PaymentController`
- `PaymentService`
- `MidtransService`
- `PaymentHistory`

### Tickets

Backend sumber:

- `BookingController`
- `TicketPdfService`
- `PaymentHistory`

### Profile & Auth

Backend sumber:

- auth controllers
- `User` model
- `PhoneVerificationController`

## 7. Catatan Penting Untuk Flutter

- Booking harus memeriksa seat availability di server.
- Payment status harus diambil dari server atau webhook, bukan hanya dari UI.
- Auth mobile sebaiknya token-based.
- Response JSON harus distandarkan agar Flutter tidak bergantung pada output web.
- Data yang sifatnya operasional, seperti `snap_token`, harus diperlakukan sebagai runtime payload, bukan kolom model tetap.

## 8. Kesimpulan

Kalau Flutter dibangun di atas referensi backend ini, implementasinya akan lebih stabil karena semua fitur utama sudah punya asal logika yang jelas di Laravel.

Dokumen ini bisa dipakai sebagai peta kerja saat membangun API mobile, model Flutter, dan integrasi UI ke backend.
