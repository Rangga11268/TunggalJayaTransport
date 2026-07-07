# Flutter Mobile Setup Guide — TunggalJayaTransport

Dokumen ini adalah panduan setup praktis untuk membuat aplikasi mobile Flutter yang terhubung ke backend Laravel TunggalJayaTransport.

Isi dokumen ini fokus ke hal yang benar-benar perlu dipersiapkan supaya proyek mobile bisa langsung jalan, terhubung ke backend, dan siap dikembangkan bertahap.

Untuk versi yang lebih lengkap dan sebagai master reference, lihat [Flutter Mobile Go-Live Guide](FLUTTER_MOBILE_GO_LIVE.md).

## 1. Gambaran Setup

Aplikasi mobile akan memakai:

- Flutter untuk UI mobile
- Laravel backend yang sudah ada sebagai sumber data utama
- Sanctum token untuk autentikasi API
- Dio untuk request HTTP
- Secure storage untuk menyimpan token login
- GoRouter untuk navigasi
- Riverpod atau Bloc untuk state management
- WebView untuk pembayaran Midtrans jika dibutuhkan

### Target setup

1. Flutter project bisa menjalankan app di Android dan iOS.
2. App bisa login ke backend Laravel.
3. App bisa membaca data publik seperti home, rute, jadwal, berita, dan armada.
4. App bisa melakukan booking dan pembayaran dengan alur yang aman.
5. Struktur code rapi dan siap dikembangkan fitur per fitur.

## 2. Prasyarat

### 2.1 Tools yang perlu ada

- Flutter SDK versi stabil
- Dart bawaan Flutter
- Android Studio atau VS Code
- Android SDK / emulator
- Xcode jika target iOS
- Git

### 2.2 Persiapan backend

Backend Laravel harus siap dengan:

- project bisa dijalankan lokal
- database sudah terisi minimal data dasar
- route web dan payment sudah aktif
- Sanctum sudah terpasang
- endpoint JSON untuk mobile disiapkan atau direncanakan

## 3. Struktur Project Flutter Yang Disarankan

Pola yang disarankan adalah feature-based.

### Folder utama

- `lib/core/`
    - konfigurasi umum
    - network client
    - constants
    - storage helper
    - utility
- `lib/features/auth/`
- `lib/features/home/`
- `lib/features/search/`
- `lib/features/routes/`
- `lib/features/booking/`
- `lib/features/payment/`
- `lib/features/tickets/`
- `lib/features/news/`
- `lib/features/fleet/`
- `lib/features/profile/`
- `lib/features/settings/`

### Isi tiap feature

Contoh struktur dalam satu feature:

- `data/`
    - models
    - datasources
    - repositories
- `domain/`
    - entities
    - use cases
- `presentation/`
    - pages
    - widgets
    - controllers / providers / blocs

Kalau ingin lebih sederhana, domain layer bisa dibuat belakangan. Untuk awal, `data` dan `presentation` sudah cukup.

## 4. Paket Flutter Yang Disarankan

### Core packages

- `dio` untuk HTTP request
- `flutter_secure_storage` untuk simpan token
- `go_router` untuk navigasi
- `flutter_riverpod` atau `flutter_bloc` untuk state management
- `freezed` untuk immutable model
- `json_serializable` untuk parsing JSON
- `intl` untuk format tanggal dan rupiah
- `cached_network_image` untuk gambar berita dan armada
- `flutter_dotenv` untuk environment variable

### UI packages

- `lucide_flutter` atau `phosphor_flutter` untuk icon
- `shimmer` untuk loading state
- `smooth_page_indicator` jika onboarding dipakai

### Payment packages

- `webview_flutter` atau `inappwebview` untuk membuka payment page

### Optional

- `connectivity_plus` untuk cek koneksi internet
- `flutter_launcher_icons` untuk icon app
- `flutter_native_splash` untuk splash screen

## 5. Setup Project Flutter Baru

### 5.1 Buat project

```bash
flutter create tunggal_jaya_mobile
```

### 5.2 Masuk folder project

```bash
cd tunggal_jaya_mobile
```

### 5.3 Tambahkan dependencies

Tambahkan package yang diperlukan di `pubspec.yaml`, lalu jalankan:

```bash
flutter pub get
```

### 5.4 Atur linter dan formatting

Disarankan aktifkan aturan yang rapi dari awal:

- `flutter_lints`
- penamaan file dan class konsisten
- satu file untuk satu halaman atau satu widget utama

## 6. Konfigurasi Environment

### 6.1 File `.env`

Buat file environment untuk menyimpan konfigurasi API.

Contoh isi:

```env
API_BASE_URL=http://10.0.2.2:8000
API_PREFIX=/api/mobile
APP_NAME=Tunggal Jaya Transport
```

### 6.2 Catatan base URL

- Android emulator: `http://10.0.2.2:8000`
- Android device fisik: pakai IP lokal komputer backend
- iOS simulator: biasanya `http://localhost:8000` jika backend lokal bisa diakses
- production: domain resmi

### 6.3 Struktur config

Disarankan buat class config terpusat seperti:

- `AppConfig`
- `ApiEndpoints`
- `AppColors`
- `AppSpacing`

## 7. Konfigurasi Flutter Assets

### Asset yang perlu disiapkan

- logo app
- logo perusahaan
- ilustrasi onboarding jika dipakai
- placeholder image
- icon social / payment jika perlu

### Contoh pengaturan

Di `pubspec.yaml`:

```yaml
flutter:
    assets:
        - assets/images/
        - assets/icons/
        - assets/logo/
        - .env
```

## 8. Setup Network Layer

### 8.1 Dio client

Buat satu HTTP client terpusat untuk semua request.

Yang perlu ditangani di client:

- base URL
- header default
- bearer token
- timeout
- logging request/response
- error mapping

### 8.2 Header standar

Setiap request API mobile sebaiknya memakai:

- `Accept: application/json`
- `Content-Type: application/json`
- `Authorization: Bearer <token>` jika user login

### 8.3 Token interceptor

Buat interceptor supaya token otomatis ikut request.

Alur:

1. token dibaca dari secure storage
2. token disisipkan ke header request
3. jika token hilang, request guest tetap jalan untuk endpoint publik

### 8.4 Error handling

Normalisasi error menjadi bentuk yang mudah dipakai UI:

- 401: login ulang
- 403: akses ditolak
- 422: validasi input
- 500: server error
- timeout: jaringan lambat atau mati

## 9. Setup Auth Flow

### 9.1 Alur login

1. User input email/phone dan password.
2. Flutter kirim request ke backend.
3. Laravel validasi kredensial.
4. Laravel balas token Sanctum.
5. Flutter simpan token di secure storage.
6. Flutter redirect ke home.

### 9.2 Alur register

1. User isi nama, email, phone, password.
2. Flutter kirim request register.
3. Laravel buat user baru.
4. Laravel balas token atau arahan verifikasi.
5. Flutter arahkan user ke verifikasi phone/email jika diperlukan.

### 9.3 Alur logout

1. Flutter panggil endpoint logout.
2. Token dihapus dari secure storage.
3. State user di-reset.
4. User kembali ke login.

### 9.4 Penyimpanan token

Gunakan secure storage, jangan simpan token di shared preferences biasa.

## 10. Setup Endpoint Mobile Di Laravel

Karena backend sekarang lebih dominan web, mobile sebaiknya memakai API khusus.

### Route yang disarankan

- `POST /api/mobile/auth/login`
- `POST /api/mobile/auth/register`
- `POST /api/mobile/auth/logout`
- `GET /api/mobile/home`
- `GET /api/mobile/routes`
- `GET /api/mobile/schedules`
- `POST /api/mobile/bookings`
- `POST /api/mobile/payments/process`
- `GET /api/mobile/bookings/{id}`

### Prinsip response

Gunakan format respons yang konsisten, misalnya:

```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "token": "...",
        "user": {}
    }
}
```

### Kenapa perlu API khusus

- response lebih konsisten untuk Flutter
- auth mobile lebih aman
- tidak tergantung session web
- lebih mudah diuji dan di-maintain

## 11. Setup State Management

### Opsi yang disarankan

- `Riverpod` jika ingin struktur ringan dan modern
- `Bloc` jika tim suka pola event-state formal

### Pola implementasi

Untuk feature sederhana:

- provider untuk auth state
- provider untuk home data
- provider untuk search result
- provider untuk booking process

Untuk flow kompleks:

- gunakan state class terpisah
- simpan loading, data, error, empty state

## 12. Setup Navigasi

### GoRouter

Gunakan router deklaratif dengan guard untuk auth.

### Rekomendasi route utama

- splash
- onboarding optional
- login
- register
- home
- search
- route detail
- booking detail
- seat selection
- payment
- ticket
- booking history
- profile

### Auth guard

- jika user belum login, redirect ke login
- jika user login, direct ke home
- jika token invalid, logout otomatis

## 13. Setup UI Theme

### Warna

Gunakan palet minimal:

- primary: navy / biru tua
- accent: teal / hijau
- background: putih / slate sangat muda
- text: charcoal

### Font

Rekomendasi:

- `Inter`
- `Plus Jakarta Sans`

### Komponen global

Buat komponen reusable:

- primary button
- secondary button
- text field
- status badge
- card umum
- loading shimmer
- empty state
- error state

## 14. Setup Icon Dan Splash

### Icon

Gunakan satu set icon saja agar konsisten, misalnya `lucide_flutter`.

### Splash screen

Pakai `flutter_native_splash` untuk splash sederhana dengan logo tengah dan background bersih.

## 15. Setup Midtrans Di Mobile

Backend sudah mengembalikan `snap_token` dan `redirect_url`.

### Alur payment mobile

1. Flutter membuat booking.
2. Flutter kirim request proses pembayaran.
3. Backend buat transaksi Midtrans.
4. Backend balas `snap_token` / `redirect_url`.
5. Flutter buka payment page.
6. Setelah selesai, Flutter cek status pembayaran.
7. Webhook server tetap menjadi sumber status final.

### Pilihan implementasi

- webview jika ingin cepat
- SDK/payment package jika ingin integrasi lebih native

### Catatan penting

Jangan simpan status final pembayaran hanya di client.
Harus tetap divalidasi server.

## 16. Setup Fitur Per Modul

### Home

- tampilkan search card
- featured routes
- latest news
- fleet preview

### Search

- origin
- destination
- date
- class
- time
- hasil list jadwal

### Booking

- pilih jadwal
- seat selection
- data penumpang
- promo code
- ringkasan booking

### Payment

- metode pembayaran
- payment status
- redirect/webview checkout

### History

- daftar booking
- detail booking
- tiket digital

### Profile

- profil user
- ubah password
- verifikasi phone
- logout

## 17. Setup Data Model Flutter

Model yang sebaiknya dibuat sejak awal:

- UserModel
- AuthResponse
- RouteModel
- BusModel
- ScheduleModel
- BookingModel
- PaymentHistoryModel
- PromoCodeModel
- NewsArticleModel
- CategoryModel

### Aturan model

- gunakan `fromJson` dan `toJson`
- field nullable ditangani dengan aman
- field tanggal dikonversi ke `DateTime`
- field angka rupiah dibaca sebagai `int`

## 18. Setup Testing Dasar

### Yang perlu diuji minimal

- login berhasil dan gagal
- register validasi
- fetch home data
- search jadwal
- pilih kursi
- proses pembayaran
- logout

### Jenis test

- unit test untuk parser/model
- widget test untuk komponen penting
- integration test untuk flow auth dan booking jika memungkinkan

## 19. Setup Build Dan Run

### Android emulator

1. Jalankan backend Laravel.
2. Pastikan port backend bisa diakses.
3. Jalankan emulator Android.
4. Jalankan app Flutter.

### Perangkat fisik

1. Pastikan HP dan laptop satu jaringan.
2. Ganti base URL ke IP lokal komputer.
3. Pastikan firewall tidak memblokir koneksi.

### Build release

```bash
flutter build apk
```

atau

```bash
flutter build appbundle
```

## 20. Setup Deployment

### Backend

- siapkan domain production
- aktifkan HTTPS
- pastikan endpoint API mobile di production sudah stabil
- konfigurasi SANCTUM_STATEFUL_DOMAINS bila diperlukan

### Mobile

- ganti base URL ke production
- update logo, splash, dan icon
- build release APK / IPA

## 21. Checklist Setup Awal

### Flutter

- [ ] install Flutter SDK
- [ ] buat project baru
- [ ] pasang dependencies utama
- [ ] siapkan `.env`
- [ ] buat network client
- [ ] buat secure storage
- [ ] buat router
- [ ] buat theme

### Laravel

- [ ] siapkan route API mobile
- [ ] buat controller auth mobile
- [ ] buat endpoint home/search/booking/payment
- [ ] pakai token Sanctum
- [ ] pastikan webhook Midtrans aktif

### UI

- [ ] buat splash
- [ ] buat bottom navigation
- [ ] buat home
- [ ] buat search
- [ ] buat booking flow
- [ ] buat payment flow

## 22. Urutan Implementasi Yang Disarankan

1. Setup project Flutter.
2. Setup theme, router, dan struktur folder.
3. Setup auth login/register.
4. Setup data home dan search.
5. Setup booking dan seat selection.
6. Setup payment Midtrans.
7. Setup booking history dan ticket.
8. Rapikan UX, loading, empty state, dan error handling.

## 23. Risiko Yang Harus Diantisipasi

- API belum konsisten akan bikin Flutter sulit berkembang.
- Session web jangan dipaksa masuk ke mobile.
- Booking dan payment harus divalidasi server.
- Seat availability harus aman dari double booking.

## 24. Kesimpulan

Setup mobile app ini paling aman kalau Flutter diposisikan sebagai client API yang bersih, sedangkan Laravel tetap menjadi pusat logika bisnis.

Kalau setup dari awal mengikuti panduan ini, proyek mobile akan lebih rapi, mudah dirawat, dan lebih siap untuk dikembangkan ke Android maupun iOS.
