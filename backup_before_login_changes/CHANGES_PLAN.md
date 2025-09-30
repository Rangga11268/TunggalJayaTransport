# Rencana Perubahan Sistem Login

## File yang Akan Dimodifikasi
1. `app/Models/User.php` - Menambah kolom phone dan status verifikasi
2. `database/migrations/xxxx_xx_xx_xx_add_phone_verification_to_users_table.php` - Migration untuk menambah kolom baru ke tabel users
3. `app/Http/Controllers/Auth/AuthenticatedSessionController.php` - Memperbarui logika pasca-login
4. `app/Http/Controllers/Auth/RegisteredUserController.php` - Memperbarui logika registrasi dengan verifikasi tambahan
5. `routes/auth.php` - Menambah route untuk verifikasi tambahan

## File yang Akan Dibuat Baru
1. `app/Models/OtpCode.php` - Model untuk menyimpan kode OTP
2. `database/migrations/xxxx_xx_xx_xx_create_otp_codes_table.php` - Migration tabel OTP
3. `app/Services/OtpService.php` - Service untuk generate dan verifikasi OTP
4. `app/Http/Middleware/AdditionalVerification.php` - Middleware untuk memeriksa status verifikasi user
5. `app/Http/Controllers/Auth/PhoneVerificationController.php` - Controller untuk verifikasi phone dengan OTP
6. View files untuk form verifikasi

## Langkah-langkah Implementasi
1. Buat migration dan model OTP
2. Perbarui model User
3. Buat service OTP
4. Perbarui controller Auth
5. Buat middleware verifikasi tambahan
6. Tambahkan route dan view

## Prosedur Rollback
Jika perlu kembali ke kondisi sebelum perubahan:

```bash
# Kembalikan file-file yang diubah
copy backup_before_login_changes\User.php app\Models\User.php
copy backup_before_login_changes\Auth\* app\Http\Controllers\Auth\
copy backup_before_login_changes\LoginRequest.php app\Http\Requests\Auth\LoginRequest.php
copy backup_before_login_changes\RoleMiddleware.php app\Http\Middleware\RoleMiddleware.php
copy backup_before_login_changes\auth.php routes\auth.php

# Hapus file-file baru yang dibuat (jika ada)
# Migration OTP dan file lain yang dibuat selama proses

# Jalankan rollback migration
php artisan migrate:rollback
```