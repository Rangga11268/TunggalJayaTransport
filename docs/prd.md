# Product Requirements Document (PRD) - Tunggal Jaya Transport

## 1. Pendahuluan
**Nama Proyek:** Tunggal Jaya Transport Ticketing & Charter System
**Platform:** 
- Mobile Application (Flutter) - Aplikasi Pelanggan
- Web Application (Laravel/Filament/Inertia Vue) - Admin Dashboard & Backend API
**Tujuan Dokumen:** Memberikan panduan menyeluruh terkait fungsionalitas, alur kerja, dan rincian halaman (Web & Mobile) untuk keperluan desain ulang (Redesign) antarmuka dan pengalaman pengguna menggunakan Google Stitch, termasuk pembaruan fitur sewa bus (Pariwisata).

---

## 2. Gambaran Umum Sistem
Sistem Tunggal Jaya Transport dirancang untuk digitalisasi proses pemesanan tiket bus dan penyewaan armada pariwisata. Sistem ini terdiri dari:
1. **Aplikasi Mobile (Customer-facing):** Memungkinkan penumpang untuk mencari rute tiket reguler, melihat jadwal, memilih kursi secara real-time, serta mengajukan permintaan sewa bus pariwisata (Charter).
2. **Dashboard Web Admin (Internal-facing):** Digunakan oleh staf dan manajemen Tunggal Jaya untuk mengelola armada bus, rute, jadwal, memantau pemesanan tiket reguler maupun permintaan charter, serta memproses penawaran harga.

---

## 3. Ruang Lingkup Proyek (Scope of Work)

### 3.1. In-Scope (Dalam Ruang Lingkup Redesign)
Proses *redesign* difokuskan secara komprehensif pada area berikut:
- **Revamp UI/UX Mobile App:** Mendesain ulang antarmuka aplikasi seluler bagi pelanggan agar lebih intuitif, mencakup alur onboarding, pencarian tiket reguler, dan integrasi pengajuan tiket pariwisata (charter).
- **Revamp UI/UX Admin Dashboard:** Pembaruan antarmuka web khusus bagi *Internal Management* dengan tata letak dasbor analitik yang informatif, manajemen data master, dan modul pengelolaan pengajuan *Charter*.
- **Implementasi Desain Sistem (Design System):** Membangun komponen antarmuka mandiri (*buttons, inputs, cards, typography, color palettes*) menggunakan Google Stitch.
- **Integrasi Prototype Visual:** Pembuatan visual dan interaksi *state* layar (Loading, Error, Empty, Success).

### 3.2. Out-of-Scope (Di Luar Ruang Lingkup Saat Ini)
Hal-hal yang tidak akan disentuh dalam fase *redesign* antarmuka kali ini:
- **Perubahan Platform Gateway:** Payment Gateway akan tetap menggunakan Midtrans; OTP Provider tetap menggunakan layanan yang sudah ada.
- **Pengembangan Modul Baru di Luar Scope:** Fitur di luar *Ticketing* dan *Charter* (seperti pengiriman paket/kargo via bus) tidak masuk dalam tahap desain ini.

---

## 4. Daftar Halaman Mobile App (Aplikasi Pelanggan - Flutter)

Berikut adalah daftar lengkap halaman (Screens) yang diimplementasikan pada Mobile App Tunggal Jaya:

### 4.1. Modul Otentikasi & Onboarding
1. **Splash Screen Page** - Layar awal saat aplikasi dibuka.
2. **Get Started / Onboarding Page** - Halaman pengenalan (*slider* keunggulan layanan).
3. **Login Page** - Halaman masuk menggunakan Email/HP dan Password.
4. **Register Page** - Halaman pembuatan akun pelanggan baru.
5. **OTP Verification Page** - Layar input kode OTP setelah registrasi/login (jika berlaku).
6. **Forgot Password Page** - Halaman input email untuk permintaan reset.
7. **Reset Password Page** - Form pembuatan kata sandi baru.
8. **Auth Success/Reset Success Page** - Halaman notifikasi keberhasilan otentikasi.

### 4.2. Modul Utama & Reguler
9. **Home Page** - Beranda utama (Menu, Pencarian Rute Reguler, Banner Promo, Banner Pariwisata CTA, Rute Populer, Berita).
10. **Routes List Page** - Halaman daftar rute lengkap yang dilayani PO Tunggal Jaya.
11. **Schedule List Page** - Hasil pencarian tiket reguler, menampilkan list bus, waktu, harga, dan sisa kursi.
12. **Seat Selection Page** - Halaman pemilihan kursi interaktif dengan visual denah bus (misal 2-2 atau 1-1-1).
13. **Checkout / Booking Summary Page** - Halaman rincian pesanan dan form data penumpang sebelum proses pembayaran.
14. **Payment Gateway Page** - Halaman yang merender Webview/Integrasi SDK Midtrans untuk pelunasan tiket reguler.

### 4.3. Modul Pariwisata / Sewa Bus (Charter)
15. **Charter Landing Page** - Halaman pengenalan layanan *Premium Service* pariwisata (Armada, Fasilitas, Layanan Kru).
16. **Charter Request Page** - Form pengajuan penyewaan armada (Tanggal Jemput, Tanggal Pulang, Lokasi, Tujuan, Tipe Bus, Catatan Tambahan). *Memiliki validasi pembatasan kalender min H-3*.

### 4.4. Modul Riwayat & Tiket
17. **Booking History Page (Multi-Tab)** - Halaman riwayat pemesanan yang terbagi menjadi tab **Reguler** dan **Pariwisata**, dan sub-tab **Aktif, Selesai, Dibatalkan**. Di sini juga terdapat fitur pembatalan (*cancel*) sewa pariwisata (cutoff H-0 jam 08:00).
18. **Ticket Detail / E-Ticket Page** - Rincian tiket reguler yang berhasil dibayar, berisi *QR Code*, Nomor Kursi, PNR, dan detail keberangkatan.

### 4.5. Modul Profil
19. **Profile Page** - Halaman manajemen akun, pengaturan data diri, bantuan, dan *logout*.
20. **News List / Detail Page** - Halaman untuk membaca pembaruan atau promo selengkapnya.

---

## 5. Daftar Halaman Web Application (Admin Dashboard - Laravel/Filament/Inertia Vue)

Berikut adalah daftar lengkap antarmuka web untuk admin dan manajemen internal:

### 5.1. Modul Otentikasi Admin
1. **Admin Login Page** - Halaman *login* aman khusus internal manajemen.

### 5.2. Modul Dasbor & Analitik
2. **Dashboard Overview Page** - Halaman utama (*landing admin*) yang menampilkan widget ringkasan pendapatan, tiket terjual harian, permintaan pariwisata masuk, dan grafik pemesanan.

### 5.3. Modul Master Data (Manajemen Internal)
3. **Bus Management Page (List & Form)** - Halaman tabel armada bus, penambahan armada baru (Kapasitas, Plat Nomor, Fasilitas).
4. **Driver Management Page (List & Form)** - Halaman data supir.
5. **Conductor Management Page (List & Form)** - Halaman data kondektur asisten supir.
6. **Routes Management Page (List & Form)** - Halaman penetapan rute, kota asal, kota tujuan, dan jarak tempuh.
7. **Schedules Management Page (List & Form)** - Halaman penjadwalan reguler (Penugasan bus, jam keberangkatan, harga tiket rute, plotting supir).

### 5.4. Modul Operasional Pemesanan (Reguler & Pariwisata)
8. **Regular Bookings Page** - Tabel riwayat pemesanan tiket harian (Status *Paid, Unpaid, Expired*), manajemen manifest, dan pembatalan sepihak (jika diperlukan admin).
9. **Charter / Pariwisata Requests Page** - Halaman utama untuk memantau pengajuan sewa masuk dari aplikasi.
10. **Charter Detail & Quotation Page** - Halaman detail pengajuan di mana Admin bisa melihat request pelanggan dan mengirimkan **Penawaran Harga** (*Quotation*) atau melakukan **Penolakan** (*Reject*). Admin memantau pembayaran DP (30%) dan pelunasan (H-1).

### 5.5. Modul Lainnya
11. **News/Promo Content Management Page** - Halaman manajemen konten (artikel & *banner*) yang akan tampil di aplikasi *mobile*.
12. **User/Customer Management Page** - Tabel data seluruh pelanggan terdaftar di aplikasi untuk kepentingan *Customer Service*.
13. **Role & Permission Management Page** - Halaman pembagian level admin (Superadmin, Kasir, Manager).

---

## 6. Pedoman Redesign (Persiapan Google Stitch)

### 6.1. Objektif Desain Aplikasi Mobile
1. **User-Centric & Modern:** Desain harus terlihat kekinian dan memprioritaskan kemudahan, terutama pada form input sewa yang panjang.
2. **Warna & Tipografi:** Palet premium Tunggal Jaya (tipografi modern seperti Plus Jakarta Sans atau Inter).
3. **Micro-Interactions & State Handling:** Transisi mulus, Empty State pada riwayat pesanan (baik reguler maupun pariwisata).

### 6.2. Objektif Desain Web Dashboard
1. **Fokus pada Data:** Ruang layar dimaksimalkan untuk menampilkan tabel (khususnya tabel persetujuan *Charter* admin).
2. **Navigasi yang Cepat & Visualisasi Padat.**

---

## 7. Aturan Bisnis & Sistem Kunci (Business Rules)
1. **Charter (Pariwisata) Time Buffer:** Pengajuan sewa hanya dapat dilakukan selambatnya **H-3** dari tanggal penjemputan.
2. **Charter (Pariwisata) Payment Flow:** Booking fee / DP sebesar 30%, dengan sisa pelunasan harus dibayarkan maksimal **H-1** dari hari keberangkatan.
3. **Charter (Pariwisata) Cancellation:** Pembatalan dari pelanggan sah dan diterima sistem jika dilakukan sebelum jam **08:00 Pagi** di hari keberangkatan.
4. **Validasi Kursi Reguler:** Tidak boleh ada *double-booking* kursi bus reguler yang sama di rute/jadwal yang sama secara bersamaan.
