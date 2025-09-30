# Panduan Rollback - Sistem Login Baru

Dokumen ini menjelaskan langkah-langkah untuk melakukan rollback ke sistem login sebelum perubahan, jika diperlukan.

## File dan Perubahan yang Dibuat

### File Baru yang Dibuat
1. `app/Models/OtpCode.php`
2. `app/Services/OtpService.php`
3. `app/Http/Middleware/AdditionalVerification.php`
4. `app/Http/Controllers/Auth/PhoneVerificationController.php`
5. `database/migrations/2025_01_01_000000_create_otp_codes_table.php`
6. `database/migrations/2025_01_01_000001_add_phone_verification_to_users_table.php`
7. `resources/views/auth/verify-phone.blade.php`
8. `app/Http/Kernel.php` (file ini diganti)

### File yang Dimodifikasi
1. `app/Models/User.php`
2. `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
3. `app/Http/Controllers/Auth/RegisteredUserController.php`
4. `routes/auth.php`
5. `resources/views/auth/register.blade.php`
6. `app/Http/Requests/Auth/LoginRequest.php`
7. `resources/views/auth/login.blade.php`

## Langkah-Langkah Rollback

### 1. Hapus File Baru
```bash
# Hapus model OTP
rm app/Models/OtpCode.php

# Hapus service OTP
rm app/Services/OtpService.php

# Hapus middleware verifikasi tambahan
rm app/Http/Middleware/AdditionalVerification.php

# Hapus controller verifikasi phone
rm app/Http/Controllers/Auth/PhoneVerificationController.php

# Hapus view verifikasi phone
rm resources/views/auth/verify-phone.blade.php

# Jika ingin mengembalikan Kernel asli Laravel (opsional)
# Hapus dulu file kita dan salin dari vendor
rm app/Http/Kernel.php
cp vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php app/Http/Kernel.php
```

### 2. Kembalikan File yang Dimodifikasi
```bash
# Kembalikan model User
cp backup_before_login_changes/User.php app/Models/User.php

# Kembalikan controller login
cp backup_before_login_changes/Auth/AuthenticatedSessionController.php app/Http/Controllers/Auth/AuthenticatedSessionController.php

# Kembalikan controller register
cp backup_before_login_changes/Auth/RegisteredUserController.php app/Http/Controllers/Auth/RegisteredUserController.php

# Kembalikan route auth
cp backup_before_login_changes/auth.php routes/auth.php

# Kembalikan view register
cp backup_before_login_changes/register.blade.php resources/views/auth/register.blade.php

# Kembalikan request login
cp backup_before_login_changes/LoginRequest.php app/Http/Requests/Auth/LoginRequest.php

# Kembalikan view login
cp backup_before_login_changes/login.blade.php resources/views/auth/login.blade.php
```

### 3. Rollback Migration Database
```bash
# Kembalikan struktur database ke versi sebelumnya
php artisan migrate:rollback --step=2
```

Perintah di atas akan:
- Menghapus tabel `otp_codes`
- Menghapus kolom `phone`, `phone_verified_at`, dan `is_verified` dari tabel `users`

### 4. Hapus Middleware dari Kernel (jika Kernel tidak diganti)
Jika Anda menggunakan Kernel asli Laravel, hapus baris ini dari `app/Http/Kernel.php`:
```php
'additional.verification' => \App\Http\Middleware\AdditionalVerification::class,
```

### 5. Hapus Route yang Ditambahkan (jika tidak menggunakan Kernel baru)
Hapus atau komentari route-route berikut dari `routes/auth.php`:
```php
// Hapus bagian ini
Route::get('verify-phone', [PhoneVerificationController::class, 'show'])
    ->name('verification.phone.show');
    
Route::post('verify-phone/otp', [PhoneVerificationController::class, 'sendOtp'])
    ->name('verification.phone.send');
    
Route::post('verify-phone', [PhoneVerificationController::class, 'verifyOtp'])
    ->name('verification.phone.verify');
    
Route::post('verify-phone/resend', [PhoneVerificationController::class, 'resendOtp'])
    ->name('verification.phone.resend');
```

## Validasi Setelah Rollback

Setelah melakukan rollback, pastikan untuk:
1. Mengakses halaman login dan register untuk memastikan berfungsi seperti sebelumnya
2. Menguji proses login dengan email seperti sebelumnya
3. Menguji registrasi user baru
4. Memastikan admin tetap bisa login ke dashboard

## Catatan Penting

- Jika data OTP atau data tambahan dari user telah terkumpul selama sistem baru beroperasi, data tersebut akan hilang setelah rollback migration.
- Pastikan tidak ada user yang aktif saat melakukan rollback pada production.
- Selalu backup database sebelum melakukan rollback.

## Jika Ingin Menjaga Sebagian Fitur

Jika Anda hanya ingin menghapus sebagian fitur (misalnya hanya OTP, tapi tetap ingin login dengan phone), Anda bisa:
1. Hapus hanya file-file terkait OTP:
   - `app/Models/OtpCode.php`
   - `app/Services/OtpService.php`
   - Migration OTP
2. Hapus route, controller, dan view OTP
3. Jaga modifikasi pada User model, LoginRequest, dan login view