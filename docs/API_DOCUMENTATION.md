# 📖 Tunggal Jaya Transport - API Documentation (PostgreSQL Backend)

Dokumentasi lengkap RESTful API backend Tunggal Jaya Transport untuk platform **Web Client** dan **React Native Mobile App**.

---

## 🌐 Base URL
```text
Local Dev: http://tunggaljayatransport.test/api
Android Emulator: http://10.0.2.2:8000/api
```

---

## 🔐 1. Authentication (`/api/auth`)

| Endpoint | Method | Keterangan | Auth |
| :--- | :--- | :--- | :--- |
| `/api/auth/register` | `POST` | Pendaftaran akun baru (Name, Email, Password, Phone) | Publik |
| `/api/auth/login` | `POST` | Masuk akun & penerbitan Bearer Token Sanctum | Publik |
| `/api/auth/send-otp` | `POST` | Mengirim OTP via WhatsApp / SMS | Publik |
| `/api/auth/verify-otp` | `POST` | Memverifikasi nomor OTP yang dikirimkan | Publik |
| `/api/auth/profile` | `GET` | Mengambil data profil akun pengguna login | Bearer Token |
| `/api/auth/profile` | `PUT` | Memperbarui data profil pengguna | Bearer Token |
| `/api/auth/logout` | `POST` | Revoke Bearer Token | Bearer Token |

---

## 🚌 2. Routes, Bus & Schedules

| Endpoint | Method | Keterangan | Auth |
| :--- | :--- | :--- | :--- |
| `/api/routes` | `GET` | Daftar semua rute aktif (Kuningan, Jakarta, Cirebon, dll) | Publik |
| `/api/routes/origins-destinations` | `GET` | Autocomplete data kota asal & tujuan | Publik |
| `/api/buses` | `GET` | Daftar armada bus reguler & pariwisata | Publik |
| `/api/schedules` | `GET` | Jadwal keberangkatan bus (filter date, origin, destination) | Publik |
| `/api/schedules/{id}` | `GET` | Detail jadwal bus beserta denah kursi | Publik |

---

## 🎟️ 3. Bookings & Real-Time Seat Selection

| Endpoint | Method | Keterangan | Auth |
| :--- | :--- | :--- | :--- |
| `/api/bookings/select-seats` | `POST` | Mengunci kursi sementara secara real-time | Bearer Token |
| `/api/bookings` | `POST` | Membuat reservasi tiket bus AKAP | Bearer Token |
| `/api/bookings` | `GET` | Riwayat seluruh pesanan tiket pengguna | Bearer Token |
| `/api/bookings/{id}` | `GET` | Detail E-Tiket tiket beserta QR code | Bearer Token |
| `/api/bookings/process-payment` | `POST` | Inisiasi snap token Midtrans payment gateway | Bearer Token |

---

## 🚐 4. Pariwisata (Charter)

| Endpoint | Method | Keterangan | Auth |
| :--- | :--- | :--- | :--- |
| `/api/charter/request` | `POST` | Pengajuan penawaran sewa bus pariwisata | Bearer Token |
| `/api/charter/history` | `GET` | Riwayat pemesanan sewa bus pariwisata | Bearer Token |
| `/api/charter/{id}/cancel` | `POST` | Pembatalan permohonan sewa | Bearer Token |

---

## 🎁 5. Promo & Berita

| Endpoint | Method | Keterangan | Auth |
| :--- | :--- | :--- | :--- |
| `/api/promos` | `GET` | Daftar voucher promo aktif | Publik |
| `/api/validate-promo` | `POST` | Validasi kode voucher saat checkout | Publik |
| `/api/news` | `GET` | Daftar berita, artikel & pengumuman terbaru | Publik |
| `/api/news/{slug}` | `GET` | Detail artikel berita | Publik |
