# Flutter Mobile App Plan — TunggalJayaTransport

Dokumen ini menjelaskan rencana pembuatan aplikasi mobile Flutter yang terhubung ke backend Laravel yang sudah ada.
Tujuannya bukan membuat konsep baru, tetapi memindahkan pengalaman pengguna inti ke mobile dengan koneksi yang jelas ke web/backend saat ini.

Dokumen pendamping:

- [Flutter Mobile Setup Guide](FLUTTER_MOBILE_SETUP_GUIDE.md)
- [Flutter Mobile UI Design](FLUTTER_MOBILE_UI_DESIGN.md)
- [Flutter Mobile Go-Live Guide](FLUTTER_MOBILE_GO_LIVE.md)
- [Flutter Mobile Backend Reference](FLUTTER_MOBILE_BACKEND_REFERENCE.md)

## 1. Tujuan Utama

- Menyediakan aplikasi Android/iOS untuk pencarian jadwal, booking kursi, pembayaran, dan riwayat tiket.
- Menggunakan backend Laravel yang sama dengan web agar data tetap konsisten.
- Memisahkan tampilan mobile dari web, tetapi tetap memakai sumber data, logika bisnis, dan pembayaran yang sama.
- Menjaga arsitektur agar backend tidak perlu ditulis ulang dari nol.

## 2. Prinsip Arsitektur

- Satu backend, banyak client.
- Web tetap memakai Inertia/Vue.
- Mobile memakai Flutter sebagai client API.
- Logika bisnis sensitif tetap di Laravel, bukan di Flutter.
- Flutter hanya menangani UI, state, navigasi, dan orkestrasi request.

## 3. Kondisi Backend Saat Ini

Backend sekarang masih dominan route web, tetapi sudah ada beberapa bagian yang bisa dijadikan dasar mobile:

- Payment controller sudah mengembalikan JSON untuk proses, status, dan webhook.
- Booking controller sudah punya beberapa response JSON ketika request meminta JSON.
- Ada endpoint API kecil untuk validasi promo.
- Laravel Sanctum sudah terpasang di project.
- Auth web masih berbasis session/redirect, jadi untuk mobile perlu layer API token.

Artinya: Flutter bisa langsung memakai backend ini, tetapi perlu tambahan API yang lebih rapi untuk mobile.

## 4. Strategi Koneksi Flutter ke Laravel

Rekomendasi yang paling aman adalah memakai API REST di Laravel dengan autentikasi token Sanctum.

### Kenapa ini dipilih

- Mobile tidak cocok bergantung pada session web.
- Token lebih mudah dipakai oleh Flutter.
- Lebih aman untuk pemisahan client mobile dan web.
- Bisa tetap memakai backend yang sama tanpa duplicating business logic.

### Opsi integrasi

1. Opsi sementara: gunakan endpoint web yang sudah mengeluarkan JSON saat `Accept: application/json` dikirim.
2. Opsi final: buat route API khusus mobile, misalnya `routes/api.php` atau `routes/api_mobile.php`.

Rekomendasi final adalah opsi 2.

## 5. Backend Yang Perlu Disiapkan Untuk Mobile

### 5.1 Authentication API

Buat endpoint mobile untuk:

- register
- login
- logout
- forgot password
- reset password
- verify email
- verify phone OTP
- refresh token atau re-login jika token habis

### 5.2 Public Data API

Buat endpoint untuk data yang bisa diakses tanpa login:

- daftar rute
- daftar origin dan destination untuk autocomplete
- pencarian jadwal
- detail jadwal
- berita/pengumuman
- data armada
- kontak dan halaman informatif

### 5.3 Booking API

Buat endpoint untuk:

- cek ketersediaan kursi
- pilih kursi
- buat booking
- lihat detail booking
- lihat daftar booking user
- batalkan booking jika aturan bisnis mengizinkan

### 5.4 Payment API

Buat endpoint untuk:

- proses pembayaran Midtrans
- cek status pembayaran
- webhook Midtrans
- download tiket atau data tiket digital

### 5.5 Promo API

Buat endpoint validasi promo yang bisa dipakai saat checkout.

## 6. Endpoint Yang Sudah Ada Dan Bisa Dijadikan Dasar

Ini endpoint/backend behavior yang sudah ada dan relevan untuk mobile:

- `POST /payment/webhook` untuk notifikasi Midtrans.
- `POST /admin/...` dan controller payment yang sudah JSON-ready untuk proses/status pembayaran.
- `POST /api/validate-promo` yang sudah ada di route web.
- `GET /autocomplete-data` untuk origin/destination autocomplete.
- `GET /search` dan booking routes yang sudah bisa merespons JSON bila request mengirim `Accept: application/json`.

Catatan: untuk mobile, lebih baik endpoint tersebut dipindah atau dibungkus ulang ke API khusus supaya URL dan format respons konsisten.

## 7. Rekomendasi Struktur API Mobile

Contoh struktur endpoint yang disarankan:

### Auth

- `POST /api/mobile/auth/register`
- `POST /api/mobile/auth/login`
- `POST /api/mobile/auth/logout`
- `POST /api/mobile/auth/forgot-password`
- `POST /api/mobile/auth/reset-password`
- `POST /api/mobile/auth/verify-phone`
- `POST /api/mobile/auth/resend-otp`

### Master Data

- `GET /api/mobile/home`
- `GET /api/mobile/routes`
- `GET /api/mobile/routes/{id}`
- `GET /api/mobile/origins-destinations`
- `GET /api/mobile/news`
- `GET /api/mobile/news/{slug}`
- `GET /api/mobile/fleet`

### Booking

- `GET /api/mobile/schedules`
- `GET /api/mobile/schedules/{id}`
- `POST /api/mobile/bookings`
- `POST /api/mobile/bookings/select-seats`
- `POST /api/mobile/bookings/check-availability`
- `GET /api/mobile/bookings`
- `GET /api/mobile/bookings/{id}`

### Payment

- `POST /api/mobile/payments/process`
- `GET /api/mobile/payments/status/{orderId}`
- `GET /api/mobile/bookings/{id}/ticket`

### Promo

- `POST /api/mobile/promo/validate`

## 8. Skema Autentikasi Yang Disarankan

Untuk Flutter, gunakan Sanctum personal access token.

Alur ideal:

1. User login dari Flutter.
2. Laravel memvalidasi kredensial.
3. Laravel membuat token Sanctum.
4. Token dikirim ke Flutter.
5. Flutter menyimpan token di secure storage.
6. Semua request terproteksi memakai header `Authorization: Bearer <token>`.

### Endpoint yang perlu dilindungi

- booking create
- pilih kursi
- proses pembayaran
- riwayat booking
- profil user
- verifikasi phone/email

### Endpoint yang bisa publik

- home
- news
- route list
- search schedule
- promo validation jika dibatasi dengan aturan aman

## 9. Integrasi Payment Midtrans Di Mobile

Backend sudah mengembalikan `snap_token` dan `redirect_url` dari proses pembayaran.

Rencana mobile yang disarankan:

- Flutter memanggil endpoint proses pembayaran.
- Laravel membuat transaksi Midtrans.
- Laravel mengembalikan `snap_token` dan `redirect_url`.
- Flutter membuka payment page dengan salah satu metode berikut:
    - InAppWebView atau WebView biasa untuk redirect_url
    - package Midtrans Flutter jika ingin integrasi native yang lebih kuat
- Setelah pembayaran, Flutter memanggil endpoint status transaksi untuk sinkronisasi.
- Webhook Midtrans tetap menjadi sumber kebenaran di server.

### Kenapa webhook tetap wajib

- UI mobile bisa tertutup saat user pindah aplikasi bank atau e-wallet.
- Status akhir harus ditentukan server, bukan hasil UI lokal.
- Webhook memastikan booking dan payment history selalu sinkron.

## 10. Flow Pengguna Mobile

### 10.1 Guest flow

1. Buka aplikasi.
2. Lihat home, rute populer, berita, armada.
3. Cari jadwal berdasarkan asal, tujuan, tanggal, kelas, jam.
4. Lihat detail jadwal.
5. Login atau register sebelum booking.

### 10.2 Booking flow

1. User login.
2. Pilih rute dan jadwal.
3. Lihat seat map.
4. Pilih kursi.
5. Isi data penumpang.
6. Tambahkan promo jika ada.
7. Buat booking.
8. Proses pembayaran.
9. Lihat status pembayaran.
10. Unduh atau tampilkan tiket.

### 10.3 Riwayat booking

1. User buka menu riwayat.
2. Lihat daftar booking aktif dan selesai.
3. Buka detail booking.
4. Lihat status pembayaran dan tiket.

## 11. Rekomendasi Struktur Flutter Project

Disarankan memakai pola feature-based.

Contoh folder:

- `lib/core/`
    - config
    - constants
    - network
    - storage
    - utils
- `lib/features/auth/`
- `lib/features/home/`
- `lib/features/routes/`
- `lib/features/search/`
- `lib/features/booking/`
- `lib/features/payment/`
- `lib/features/tickets/`
- `lib/features/news/`
- `lib/features/profile/`
- `lib/features/notifications/`

### Package Flutter yang disarankan

- `dio` untuk HTTP client
- `flutter_secure_storage` untuk token
- `go_router` untuk navigasi
- `flutter_riverpod` atau `bloc` untuk state management
- `freezed` + `json_serializable` untuk model data
- `cached_network_image` untuk gambar armada dan berita
- `intl` untuk format tanggal dan rupiah
- `webview_flutter` atau `inappwebview` untuk Midtrans checkout

## 12. Model Data Flutter Yang Perlu Disiapkan

Model minimal yang perlu ada di Flutter:

- User
- AuthToken / Session
- Route
- Bus
- Schedule
- Booking
- PaymentHistory
- PromoCode
- NewsArticle
- Category
- OtpCode response

### Field penting yang harus diperhatikan

- `booking_code`
- `seat_numbers`
- `number_of_seats`
- `booking_status`
- `payment_status`
- `midtrans_transaction_id`
- `snap_token`
- `redirect_url`
- `departure_time`
- `arrival_time`
- `days_of_week`
- `is_daily`

## 13. State Management Yang Disarankan

Rekomendasi praktis:

- `Riverpod` jika ingin ringan, modern, dan mudah di-maintain.
- `Bloc` jika tim sudah terbiasa dengan event/state yang lebih formal.

Untuk project ini, `Riverpod` cukup cocok karena flow-nya banyak request API dan state booking cukup kompleks.

## 14. Detail Integrasi Ke Web Backend

### 14.1 Base URL

Flutter harus memakai base URL backend Laravel, misalnya:

- lokal: `http://10.0.2.2:8000` untuk Android emulator
- device fisik: IP lokal machine dev
- production: domain resmi

### 14.2 Header standar

Setiap request API mobile sebaiknya mengirim:

- `Accept: application/json`
- `Content-Type: application/json`
- `Authorization: Bearer <token>` jika login

### 14.3 Error handling

Flutter harus siap menangani:

- 401 unauthorized
- 403 forbidden
- 422 validation error
- 429 throttle/rate limit
- 500 server error

### 14.4 Sinkronisasi status

Beberapa status tidak boleh hanya dipercaya dari client:

- payment status
- booking status
- seat availability

Status ini harus dikonfirmasi dari server.

## 15. Backend Change List Yang Disarankan

Ini daftar pekerjaan backend yang perlu ditambahkan agar mobile rapi:

1. Tambahkan route API khusus mobile.
2. Tambahkan controller API untuk auth, home, search, booking, news, profile.
3. Buat response JSON yang konsisten.
4. Aktifkan token auth Sanctum untuk mobile.
5. Pastikan endpoint booking memakai transaksi database agar seat tidak double-booked.
6. Pastikan webhook Midtrans tetap aktif dan tervalidasi.
7. Sediakan endpoint download tiket atau data tiket digital.
8. Tambahkan throttling untuk login, OTP, dan forgot password.

## 16. Rencana Implementasi Bertahap

### Phase 1 — Audit dan API Foundation

- inventaris endpoint yang sudah ada
- tentukan format response standar
- buat auth token mobile
- siapkan API route prefix

### Phase 2 — Flutter Skeleton

- setup project Flutter
- buat theme, router, storage, and network client
- buat auth flow dasar
- tampilkan home dan search

### Phase 3 — Booking Flow

- implement search jadwal
- detail jadwal
- seat selection
- create booking
- promo validation

### Phase 4 — Payment Flow

- proses Midtrans
- buka payment page
- polling status
- tampilkan success/fail

### Phase 5 — Ticket dan History

- booking history
- detail booking
- ticket view/download

### Phase 6 — Polish

- caching
- empty state
- error state
- pull to refresh
- push notification jika diperlukan

## 17. Risiko Teknis

- Jika tetap memakai session web, Flutter akan sulit stabil untuk auth.
- Jika seat availability tidak diproteksi transaksi, double booking bisa terjadi.
- Jika payment hanya mengandalkan UI mobile, status bisa tidak sinkron.
- Jika API response tidak konsisten, Flutter akan cepat jadi rapuh.

## 18. Rekomendasi Prioritas

Prioritas paling masuk akal:

1. Selesaikan API auth token.
2. Buat endpoint master data dan search.
3. Buka booking flow.
4. Integrasikan Midtrans.
5. Tambahkan riwayat tiket dan profile.

## 19. Kesimpulan

Mobile app Flutter sangat memungkinkan untuk project ini karena backend Laravel sudah punya logika bisnis utama, payment, booking, dan sebagian response JSON.

Yang paling penting adalah tidak memaksa Flutter membaca web session. Jalan yang benar adalah membangun API mobile di atas backend yang sama, lalu memakai Sanctum token auth dan payment flow Midtrans yang sudah ada.

Kalau rencana ini diikuti, web dan mobile akan tetap satu sumber data, tetapi pengalaman pengguna bisa berbeda dan lebih optimal di masing-masing platform.
