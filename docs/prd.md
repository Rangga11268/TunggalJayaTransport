# Product Requirements Document (PRD) - Tunggal Jaya Transport

## 1. Pendahuluan
**Nama Proyek:** Tunggal Jaya Transport Ticketing System
**Platform:** 
- Mobile Application (Flutter) - Aplikasi Pelanggan
- Web Application (Laravel/Filament) - Admin Dashboard & Backend API
**Tujuan Dokumen:** Memberikan panduan menyeluruh terkait fungsionalitas, alur kerja, dan rincian halaman (Web & Mobile) untuk keperluan desain ulang (Redesign) antarmuka dan pengalaman pengguna menggunakan Google Stitch.

---

## 2. Gambaran Umum Sistem
Sistem Tunggal Jaya Transport dirancang untuk digitalisasi proses pemesanan tiket bus. Sistem ini terdiri dari:
1. **Aplikasi Mobile (Customer-facing):** Memungkinkan penumpang untuk mencari rute, melihat jadwal, memilih kursi secara real-time, dan melakukan pembayaran tiket bus.
2. **Dashboard Web Admin (Internal-facing):** Digunakan oleh staf dan manajemen Tunggal Jaya untuk mengelola armada bus, rute, jadwal, memantau pemesanan, dan laporan keuangan.

---

## 3. Ruang Lingkup Proyek (Scope of Work)

### 3.1. In-Scope (Dalam Ruang Lingkup Redesign)
Proses *redesign* akan difokuskan secara komprehensif pada area berikut:
- **Revamp UI/UX Mobile App:** Mendesain ulang antarmuka aplikasi seluler bagi pelanggan agar lebih intuitif, mencakup alur onboarding, pencarian tiket, visualisasi pemilihan kursi (seat selection layout), dan *checkout* pembayaran.
- **Revamp UI/UX Admin Dashboard:** Pembaruan antarmuka web khusus bagi *Internal Management* (Filament/Laravel) dengan tata letak dasbor analitik yang lebih informatif, serta tabel pengelolaan master data (CRUD) yang lebih interaktif.
- **Implementasi Desain Sistem (Design System):** Membangun komponen antarmuka mandiri (*buttons, inputs, cards, typography, color palettes*) menggunakan Google Stitch, yang akan menjadi standar tunggal (*single source of truth*) untuk Web dan Mobile.
- **Integrasi Prototype Visual:** Pembuatan visual dan interaksi *state* layar (Loading, Error, Empty, Success).

### 3.2. Out-of-Scope (Di Luar Ruang Lingkup Saat Ini)
Hal-hal yang tidak akan disentuh dalam fase *redesign* antarmuka kali ini:
- **Perombakan Arsitektur Database:** Skema tabel (seperti `buses`, `routes`, `schedules`, `bookings`) di sisi *backend* Laravel tidak akan diubah kecuali bersifat krusial untuk menunjang UI.
- **Perubahan Platform Gateway:** Payment Gateway akan tetap menggunakan Midtrans; OTP Provider tetap menggunakan layanan yang sudah ada.
- **Pengembangan Modul Baru:** Fitur di luar *Ticketing* (seperti pengiriman paket/kargo via bus) tidak masuk dalam tahap desain ini.

---

## 4. Struktur Aplikasi Mobile (Pelanggan)

Aplikasi mobile dirancang untuk kemudahan dan kecepatan transaksi. Berikut adalah struktur halamannya:

### 4.1. Modul Otentikasi & Onboarding
- **Splash Screen:** Halaman transisi saat aplikasi pertama kali dibuka. Menampilkan logo perusahaan.
- **Get Started / Onboarding:** Layar pengenalan fitur utama aplikasi (Cari Tiket, Pilih Kursi, Bayar Mudah).
- **Login:** Masuk menggunakan Email dan Password.
- **Register:** Pendaftaran akun baru (Nama, Email, No. HP, Password).
- **Verifikasi OTP:** Verifikasi nomor HP menggunakan WhatsApp atau Email setelah registrasi.
- **Lupa Password:** Meminta link/OTP untuk reset password.
- **Reset Password:** Mengatur ulang kata sandi baru.

### 4.2. Modul Beranda (Home)
- **Header:** Menampilkan sapaan pengguna, foto profil kecil, dan notifikasi.
- **Banner Promo/Berita:** Carousel gambar untuk promosi, diskon, atau informasi operasional terbaru.
- **Form Pencarian Cepat:** 
  - Input Kota Asal
  - Input Kota Tujuan
  - Input Tanggal Keberangkatan
  - Tombol "Cari Tiket"
- **Rute Populer:** Kartu geser horizontal yang menampilkan rute-rute yang paling sering dipesan (contoh: Kuningan - Jakarta), lengkap dengan estimasi jarak dan durasi.
- **Berita Terbaru:** Cuplikan artikel atau pengumuman penting terkait Tunggal Jaya.

### 4.3. Modul Pencarian & Jadwal
- **Halaman Semua Rute (Routes Page):** Daftar lengkap seluruh rute yang dilayani perusahaan beserta detail fasilitas kelas bus.
- **Daftar Jadwal (Schedule List):** 
  - Muncul setelah user melakukan pencarian.
  - Menampilkan daftar bus yang tersedia pada tanggal tersebut.
  - Detail tiap kartu jadwal: Jam keberangkatan, jam tiba, harga tiket, tipe bus, fasilitas, sisa kursi kosong.
  - Fitur Filter & Sortir (berdasarkan harga termurah, jam keberangkatan awal/akhir).

### 4.4. Modul Pemesanan & Pembayaran (Booking Flow)
- **Pemilihan Kursi (Seat Selection):** 
  - Visualisasi denah kursi bus (contoh: format 2-2 atau 2-1).
  - Status kursi dengan warna berbeda: Tersedia, Dipilih, Sudah Dipesan/Tidak Tersedia.
- **Detail Penumpang (Passenger Details):** 
  - Form pengisian data penumpang (bisa otomatis terisi jika memesan untuk diri sendiri).
  - Ringkasan pesanan (Total harga, rute, tanggal, nomor kursi).
- **Pembayaran (Payment):**
  - Integrasi Midtrans untuk berbagai metode pembayaran (GoPay, Virtual Account BCA/Mandiri, QRIS, dsb).
  - Halaman status pembayaran (Sukses / Menunggu Pembayaran / Gagal).

### 4.5. Modul Tiket / Riwayat Pesanan
- **Daftar Pesanan (Booking History):**
  - Tab Aktif: Tiket untuk perjalanan yang belum terjadi.
  - Tab Selesai: Riwayat perjalanan masa lalu.
  - Tab Dibatalkan: Pemesanan yang gagal bayar atau dibatalkan.
- **E-Ticket (Detail Pesanan):** 
  - Menampilkan QR Code/Barcode tiket.
  - Detail kode booking (PNR), nomor kursi, plat nomor bus (jika sudah di-assign).

### 4.6. Modul Profil
- **Halaman Profil:** Menampilkan ringkasan data diri.
- **Ubah Profil:** Edit nama, no HP, email.
- **Ubah Password:** Ganti kata sandi demi keamanan.
- **Pusat Bantuan & Syarat Ketentuan.**
- **Tombol Keluar (Logout).**

---

## 5. Struktur Web Application (Admin Dashboard)

Dashboard web dibangun menggunakan ekosistem Laravel Filament, dirancang untuk efisiensi manajemen data (CRUD) dan pemantauan operasional.

### 5.1. Modul Autentikasi Admin
- **Login Admin:** Akses khusus staf menggunakan kredensial admin.

### 5.2. Modul Dashboard Utama
- **Statistik & Metrik (Overview):**
  - Total pendapatan hari ini / bulan ini.
  - Total tiket terjual.
  - Jumlah bus beroperasi hari ini.
  - Grafik tren pemesanan tiket.

### 5.3. Modul Manajemen Armada & Kru (Master Data)
- **Data Bus:**
  - Tambah/Edit bus (Plat Nomor, Nama Armada, Kapasitas Kursi, Kelas/Tipe, Fasilitas).
- **Data Supir (Driver):**
  - Profil supir, nomor lisensi, kontak.
- **Data Kondektur (Conductor):**
  - Profil kondektur, kontak.

### 5.4. Modul Rute & Jadwal Operasional
- **Manajemen Rute (Routes):**
  - Kota Asal, Kota Tujuan, Jarak tempuh, Estimasi Waktu.
- **Manajemen Jadwal (Schedules):**
  - Pengaturan jadwal keberangkatan bus tertentu pada rute tertentu.
  - Status jadwal harian (Recurring) vs Sekali jalan (One-time).
  - Assign Bus, Supir, dan Kondektur ke jadwal tertentu.
  - Pengaturan harga tiket per jadwal.

### 5.5. Modul Pemesanan & Transaksi
- **Manajemen Pemesanan (Bookings):**
  - Daftar seluruh transaksi pelanggan.
  - Status pembayaran (Pending, Paid, Cancelled, Expired).
  - Fitur cetak tiket fisik atau verifikasi tiket pelanggan.
  - Fitur pembatalan atau *reschedule* manual oleh admin.
- **Manajemen Promo / Diskon:**
  - Pembuatan kode voucher, persentase diskon, batas penggunaan.

### 5.6. Modul Konten & Notifikasi
- **Manajemen Berita (News):** CRUD artikel atau promo untuk ditampilkan di aplikasi mobile.
- **Notifikasi Push (Push Notifications):** Modul untuk mengirimkan pesan pengingat keberangkatan atau promo (opsional/future development).

### 5.7. Modul Pengguna & Hak Akses
- **Data Pelanggan (Customers):** Melihat riwayat transaksi pengguna tertentu.
- **Manajemen Hak Akses (Roles & Permissions):** Mengatur level akses admin (Super Admin, Kasir, Manager Operasional).

---

## 6. Pedoman Redesign (Persiapan Google Stitch)

Mengingat Anda akan melakukan proses *redesign* antarmuka menggunakan **Google Stitch**, berikut adalah parameter desain yang diharapkan:

### 6.1. Objektif Desain Aplikasi Mobile
1. **User-Centric & Modern:** Desain harus terlihat kekinian, bersih, dan memprioritaskan kemudahan membaca (legibility), terutama saat memilih rute dan kursi.
2. **Warna & Tipografi:** 
   - Gunakan palet warna yang premium (sesuai *brand identity* Tunggal Jaya) dengan kontras yang baik.
   - Gunakan tipografi modern (seperti Plus Jakarta Sans atau Inter).
3. **Micro-Interactions:** Tambahkan efek transisi mulus saat pengguna menekan kartu jadwal atau memilih kursi bus.
4. **State Handling:** Desain dengan cermat layar untuk *Empty State* (Jadwal tidak ditemukan), *Loading State* (Shimmer effect), dan *Error State*.

### 6.2. Objektif Desain Web Dashboard
1. **Fokus pada Data (Data-Heavy Interface):** Ruang layar dimaksimalkan untuk menampilkan tabel data pemesanan dan jadwal dengan filter tingkat lanjut.
2. **Navigasi yang Cepat:** Sidebar (kiri) yang responsif dan mudah dilipat (collapsible).
3. **Card & Widget:** Dashboard harus terasa ringan namun padat informasi berkat penggunaan visualisasi data (Chart/Graph).

---

## 7. Integrasi Sistem & API
- Semua interaksi dari Mobile App ke database berjalan melalui **Laravel REST API**.
- Payment Gateway difasilitasi oleh **Midtrans**.
- Otentikasi diamankan melalui **Laravel Sanctum**.
