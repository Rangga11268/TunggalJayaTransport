# Qwen V1 Update: Fitur Rekomendasi & History Pemesanan

##### JANGAN ASAL ME ROLLBACK MIGRASI JIKA INGINME ROLLBACK INGAT KANMIGRASI APA SAJA YANG TEROLLBACK DAN KEMBALIKAN 

## Rollback Information: Ticket System Refactoring

### 1. Database Changes
- Migration: `2025_10_04_130024_create_ticket_customizations_table.php`
  - Created `ticket_customizations` table with fields for layout, paper size, appearance settings, etc.
  - Added default settings record with ID=1
  - To rollback: Drop the `ticket_customizations` table

### 2. New Files Created
- `app/Models/TicketCustomization.php` - Model for ticket settings
- `app/Services/TicketPdfService.php` - Service for PDF ticket generation
- `resources/views/frontend/booking/ticket-pdf-landscape.blade.php` - Landscape PDF ticket view
- `resources/views/frontend/booking/ticket-pdf-portrait.blade.php` - Portrait PDF ticket view
- `resources/views/frontend/ticket-settings.blade.php` - Settings management page

### 3. Files Modified
- `app/Http/Controllers/Frontend/BookingController.php`
  - Updated `downloadTicket()` method to use `TicketPdfService`
  - Added `updateTicketSettings()` and `getTicketSettings()` methods
- `resources/views/booking-history/show.blade.php`
  - Removed recommendation link
- `resources/views/frontend/booking/success.blade.php`
  - Removed duplicate "Lihat Tiket" and "Unduh Tiket (PDF)" buttons
- `resources/views/frontend/booking/ticket-preview.blade.php`
  - Converted from standalone HTML to Laravel blade template
  - Uncommented the viewTicket route in web.php
- `resources/views/frontend/home.blade.php`
  - Added booking history button for authenticated users
- `routes/web.php`
  - Added ticket settings routes
  - Uncommented the viewTicket route

### 4. Migration Rollback Command
```bash
php artisan migrate:rollback --step=1
```
This will rollback the ticket customization table migration.

### 5. Manual Cleanup (if needed)
If you need to completely rollback the changes:
1. Delete the new files mentioned above
2. Revert modifications in the modified files to their original state
3. Remove the added routes from web.php
4. Remove the new controller methods from BookingController

## Overview
Dokumen ini merinci rencana dan implementasi untuk menambahkan dua fitur penting ke sistem Tunggal Jaya Transport:
1. Fitur history pemesanan
2. Fitur rekomendasi destinasi setelah pemesanan

## Analisis Struktur Database Saat Ini

Setelah menganalisis migrasi database yang ada, berikut adalah struktur tabel utama:

### Tabel `bookings`
- `id`: Primary key
- `user_id`: Foreign key ke users
- `schedule_id`: Foreign key ke schedules
- `booking_code`: Kode unik pemesanan
- `passenger_name`: Nama penumpang
- `passenger_phone`: Nomor telepon penumpang
- `passenger_email`: Email penumpang
- `seat_numbers`: Nomor kursi yang dipesan
- `total_price`: Total harga
- `payment_status`: Status pembayaran (pending, paid, failed, refunded)
- `booking_status`: Status pemesanan (pending, confirmed, cancelled, completed)
- `created_at`, `updated_at`: Timestamp

### Tabel `schedules`
- `id`: Primary key
- `bus_id`: Foreign key ke buses
- `route_id`: Foreign key ke routes
- `departure_time`: Waktu keberangkatan
- `arrival_time`: Waktu kedatangan
- `price`: Harga tiket
- `status`: Status jadwal (active, cancelled, delayed)

### Tabel `routes`
- `id`: Primary key
- `name`: Nama rute
- `origin`: Asal keberangkatan
- `destination`: Tujuan
- `distance`: Jarak dalam km
- `duration`: Durasi perjalanan dalam menit
- `description`: Deskripsi rute

### Tabel `users`
- `id`: Primary key
- `name`: Nama pengguna
- `email`: Email pengguna
- `password`: Kata sandi
- `created_at`, `updated_at`: Timestamp

## Rencana Implementasi

### 1. Fitur History Pemesanan

Untuk fitur history pemesanan, kita sebenarnya sudah memiliki sebagian besar struktur yang diperlukan melalui tabel `bookings`. Namun, kita mungkin ingin menambahkan beberapa kolom tambahan untuk meningkatkan pengalaman pengguna:

#### A. Tabel `bookings` (penambahan)
Kita mungkin ingin menambahkan kolom berikut:
- `booking_date`: Tanggal pemesanan (sudah ditambahkan di migrasi 2025_09_26_120000)
- Kolom tambahan yang mungkin berguna (akan ditentukan lebih lanjut)

#### B. Relasi dan Model
- Model `Booking` sudah ada, dengan relasi ke `User` dan `Schedule`
- Model `Route` dan `Schedule` juga sudah ada, dengan relasi yang sesuai
- Membuat controller untuk menampilkan history pemesanan

#### C. UI/UX
- Membuat antarmuka untuk menampilkan history pemesanan
- Membuat detail pemesanan

### 2. Fitur Rekomendasi Destinasi

Untuk fitur rekomendasi, kita memiliki beberapa pendekatan yang bisa digunakan:

#### A. Berdasarkan Destinasi Asal yang Sama
- Rekomendasikan destinasi lain dengan asal keberangkatan yang sama dengan destinasi yang baru dipesan

#### B. Berdasarkan Frekuensi Pemesanan
- Menampilkan destinasi yang sering dipesan oleh pengguna lain setelah memesan destinasi tertentu

#### C. Berdasarkan Pola Perjalanan
- Menggunakan data historis untuk menentukan pola perjalanan umum

#### D. Tabel tambahan (opsional)
Jika diperlukan pendekatan lebih kompleks, kita bisa membuat tabel tambahan seperti:
- `booking_recommendations` atau
- `destination_patterns` untuk menyimpan data rekomendasi

## Struktur Model Yang Tersedia

Setelah memeriksa struktur aplikasi, berikut model-model yang sudah tersedia:

### Model `Booking`
- Relasi ke `User` (belongsTo)
- Relasi ke `Schedule` (belongsTo)
- Atribut penting: `user_id`, `schedule_id`, `booking_code`, `passenger_name`, `passenger_phone`, `passenger_email`, `seat_numbers`, `total_price`, `payment_status`, `booking_status`, `booking_date`

### Model `Schedule`
- Relasi ke `Bus` (belongsTo)
- Relasi ke `Route` (belongsTo)
- Relasi ke `Booking` (hasMany)
- Atribut penting: `bus_id`, `route_id`, `departure_time`, `arrival_time`, `price`, `status`, `is_daily`

### Model `Route`
- Relasi ke `Schedule` (hasMany)
- Atribut penting: `name`, `origin`, `destination`, `distance`, `duration`, `description`

### Model `User`
- Relasi ke `Booking` (hasMany)
- Atribut penting: `id`, `name`, `email`

## Algoritma Rekomendasi Destinasi

Setelah menganalisis kebutuhan, kita akan mengimplementasikan kombinasi dari beberapa pendekatan untuk mendapatkan rekomendasi yang relevan:

### 1. Pendekatan Berbasis Konten (Content-Based Filtering)
- Analisis destinasi yang baru dipesan oleh pengguna
- Rekomendasikan destinasi dengan asal/destinasi yang serupa
- Gunakan informasi dari tabel `routes`

### 2. Pendekatan Kolaboratif (Collaborative Filtering)
- Temukan pengguna dengan pola pemesanan serupa
- Rekomendasikan destinasi yang sering dipesan oleh pengguna dengan pola serupa
- Gunakan data historis dari tabel `bookings`

### 3. Pendekatan Berbasis Populeritas
- Menampilkan destinasi populer yang sering dipesan setelah destinasi tertentu
- Gunakan data agregatif dari histori pemesanan

### 4. Implementasi Spesifik Algoritma

Berikut langkah-langkah spesifik untuk mengimplementasikan algoritma rekomendasi:

#### A. Identifikasi destinasi terbaru yang dipesan oleh pengguna
- Dapatkan destinasi terbaru dari tabel `bookings` berdasarkan `user_id`
- Gunakan relasi `booking->schedule->route` untuk mendapatkan informasi destinasi

#### B. Hitung rekomendasi berdasarkan destinasi asal
- Temukan destinasi lain yang memiliki asal keberangkatan (`origin`) yang sama dengan destinasi tujuan (`destination`) dari pemesanan terakhir
- Gunakan relasi antara `bookings` → `schedules` → `routes`

#### C. Hitung rekomendasi berdasarkan pola pengguna serupa
- Temukan pengguna lain yang pernah memesan destinasi yang sama
- Ambil destinasi yang sering dipesan oleh pengguna tersebut setelah destinasi tertentu

#### D. Pemberian skor pada rekomendasi
- Prioritaskan destinasi berdasarkan frekuensi kemunculan dalam pola
- Berikan bobot tambahan untuk destinasi dengan rating tinggi atau popularitas tinggi

#### E. Menyajikan hasil rekomendasi
- Tampilkan 3-5 destinasi teratas berdasarkan skor rekomendasi
- Sertakan informasi relevan seperti harga, durasi perjalanan, dan jam keberangkatan

## Implementasi Fitur

### A. Fitur History Pemesanan

Telah diimplementasikan dengan:

#### 1. Controller
- Membuat `BookingHistoryController` dengan metode `index()` dan `show()`
- Menggunakan middleware `auth` untuk memastikan hanya pengguna terotentikasi yang bisa mengakses fitur
- Menyediakan fungsi untuk menampilkan daftar booking dan detail booking

#### 2. View
- Membuat `resources/views/booking-history/index.blade.php` untuk menampilkan daftar booking
- Membuat `resources/views/booking-history/show.blade.php` untuk menampilkan detail booking
- Menampilkan informasi lengkap termasuk rute, waktu keberangkatan, status, dan harga

#### 3. Routing
- Menambahkan rute `/booking-history` dan `/booking-history/{id}` di `routes/web.php`
- Menggunakan prefix dan naming convention yang konsisten

### B. Fitur Rekomendasi Destinasi

Telah diimplementasikan dengan:

#### 1. Controller
- Membuat `RecommendationController` dengan metode `show()`
- Mengimplementasikan algoritma rekomendasi dengan beberapa faktor penilaian
- Menyediakan fungsi untuk menampilkan destinasi rekomendasi berdasarkan asal perjalanan

#### 2. Algoritma Rekomendasi
- Mencari rute berdasarkan destinasi asal yang sama
- Menghitung skor berdasarkan popularitas, harga, durasi, dan jenis bus
- Menyortir hasil berdasarkan skor tertinggi

#### 3. View
- Membuat `resources/views/recommendations/index.blade.php` untuk menampilkan destinasi rekomendasi
- Menampilkan informasi penting seperti harga, durasi, waktu keberangkatan, dan jenis bus
- Memberikan tombol untuk langsung memesan perjalanan

#### 4. Routing
- Menambahkan rute `/recommendations` di `routes/web.php`
- Menggunakan prefix dan naming convention yang konsisten

## Testing dan Verifikasi Fungsionalitas

### A. Testing Unit

Telah dibuat test case untuk memverifikasi fungsionalitas dari kedua fitur:

#### 1. Testing Controller History Pemesanan
- Menguji bahwa pengguna dapat melihat history pemesanan mereka
- Menguji bahwa pengguna dapat melihat detail dari pemesanan tertentu
- Menguji bahwa guest tidak dapat mengakses fitur history pemesanan

#### 2. Testing Controller Rekomendasi
- Menguji bahwa halaman rekomendasi dapat ditampilkan dengan parameter origin
- Menguji bahwa halaman rekomendasi dapat menampilkan rekomendasi berdasarkan booking terakhir pengguna
- Menguji bahwa halaman rekomendasi mengalihkan ke halaman booking jika tidak ada origin

### B. Testing Fungsional

Fitur-fitur yang telah diimplementasikan telah diverifikasi secara fungsional:

#### 1. Fitur History Pemesanan
- Pengguna dapat mengakses halaman history setelah login
- Halaman history menampilkan semua pemesanan milik pengguna
- Detail pemesanan ditampilkan dengan lengkap
- Pengguna dapat melihat status booking, harga, dan informasi perjalanan

#### 2. Fitur Rekomendasi
- Algoritma rekomendasi bekerja sesuai dengan rancangan
- Menampilkan destinasi berdasarkan destinasi akhir dari perjalanan terakhir
- Menyediakan informasi lengkap tentang destinasi rekomendasi
- Menyediakan tombol untuk langsung memesan perjalanan yang direkomendasikan

### C. Integrasi
- Link menuju fitur history pemesanan ditambahkan di dropdown menu pengguna
- Link menuju fitur rekomendasi ditambahkan di halaman utama untuk pengguna yang login
- Link rekomendasi juga ditambahkan di halaman sukses booking
- Semua link berfungsi sesuai dengan rancangan dan mengarah ke rute yang benar

## Implementasi Detail

### A. Fitur History Pemesanan

1. Membuat model `Booking` (jika belum ada)
2. Mengembangkan controller `BookingController` dengan fungsi:
   - `index()`: Menampilkan daftar pemesanan pengguna
   - `show()`: Menampilkan detail pemesanan
3. Membuat middleware untuk memastikan hanya pengguna terotentikasi yang bisa melihat history
4. Membuat view untuk history pemesanan

### B. Fitur Rekomendasi Destinasi

1. Membuat algoritma rekomendasi
2. Membuat fungsi di controller untuk menampilkan rekomendasi berdasarkan pemesanan terakhir
3. Membuat view untuk menampilkan rekomendasi

## Algoritma Rekomendasi Detail

### Pendekatan Kolaboratif Filtering Sederhana
1. Menemukan pengguna dengan pola pemesanan serupa
2. Rekomendasikan destinasi yang sering dipesan oleh pengguna dengan pola serupa

### Pendekatan Berbasis Isi (Content-Based)
1. Analisis destinasi yang pernah dipesan oleh pengguna
2. Rekomendasikan destinasi dengan karakteristik serupa (asal/destinasi yang sama)

### Kombinasi Pendekatan
1. Gunakan pendekatan hibrida untuk hasil rekomendasi yang lebih akurat

## Struktur Tabel Tambahan (Jika Diperlukan)

Jika pendekatan sederhana tidak cukup, kita bisa membuat tabel tambahan:

```sql
-- Tabel untuk menyimpan pola rekomendasi
CREATE TABLE destination_recommendations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    route_from_id BIGINT UNSIGNED NOT NULL,
    route_to_id BIGINT UNSIGNED NOT NULL,
    recommendation_score DOUBLE DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (route_from_id) REFERENCES routes(id),
    FOREIGN KEY (route_to_id) REFERENCES routes(id)
);
```

## Rencana Implementasi Langkah demi Langkah

1. Rancang struktur database untuk history pemesanan ✓
2. Rancang algoritma rekomendasi destinasi
3. Buat model untuk pemesanan dan destinasi
4. Implementasi fitur history pemesanan
5. Implementasi fitur rekomendasi destinasi
6. Integrasi fitur ke dalam sistem existing
7. Testing dan verifikasi fungsionalitas

## Tabel `bookings` Sudah Lengkap
Setelah menganalisis migrasi yang ada, ternyata tabel `bookings` sudah memiliki semua informasi penting untuk history pemesanan. Ada penambahan kolom `booking_date` di migrasi 2025_09_26_120000, yang menunjukkan bahwa fitur history sebenarnya sudah direncanakan sejak awal.

## Langkah Berikutnya
Sekarang kita akan melanjutkan dengan:
1. Membuat model `Booking`
2. Membuat algoritma rekomendasi
3. Mengimplementasikan fitur dalam controller dan view