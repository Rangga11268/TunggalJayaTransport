# Tunggal Jaya Transport - Premium Bus Booking System

<div align="center">
  <img src="public/img/logoNoBg.png" alt="Tunggal Jaya Transport Logo" width="200"/>
  <br/>
  <br/>

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-11.0-orange.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.0-green.svg)](https://vuejs.org/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.4-cyan.svg)](https://tailwindcss.com/)

**Platform Pemesanan Tiket Bus Modern dengan Estetika Premium & UI/UX Kelas Atas**

  <p align="center">
    <a href="#-fitur-unggulan">Fitur</a> •
    <a href="#-galeri">Galeri</a> •
    <a href="#-teknologi">Teknologi</a> •
    <a href="#-instalasi">Instalasi</a>
  </p>
</div>

---

## 🚌 Tentang Proyek

**Tunggal Jaya Transport** adalah solusi digital end-to-end untuk perusahaan transportasi bus. Dirancang dengan fokus pada **User Experience (UX) Premium**, sistem ini menggabungkan kemudahan pemesanan tiket bagi penumpang dengan dashboard manajemen yang powerful bagi admin.

### ✨ Highlights

- **Desain Mewah:** Mengusung tema "Noir Glassmorphism" dengan dominasi warna hitam, putih, dan aksen Rose Gold.
- **Interaktif:** Animasi halus menggunakan GASP & Framer Motion.
- **Responsif:** Tampilan optimal di Desktop, Tablet, dan Mobile.

---

## 🖼️ Galeri Tampilan

### 🏠 Landing Page & Info Perjalanan

<div align="center">
  <img src="public/img/homeTujago.png" alt="Landing Page" width="100%" style="border-radius: 12px; margin-bottom: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);"/>
  
  <p><em>Section Info Perjalanan Baru dengan Poster Interaktif</em></p>
  <div style="display: flex; gap: 10px; justify-content: center; margin-top: 10px;">
    <img src="public/img/poster/bentas01.webp" width="30%" style="border-radius: 8px;"/>
    <img src="public/img/poster/bentas02.webp" width="30%" style="border-radius: 8px;"/>
    <img src="public/img/poster/bentas03.webp" width="30%" style="border-radius: 8px;"/>
  </div>
</div>

### 📊 Dashboard Admin & Analitik

<div align="center">
  <br/>
  <img src="public/img/adminTujago.png" alt="Admin Dashboard" width="100%" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);"/>
  <p><em>Dashboard dengan Grafik Analitik Pendapatan Real-time & Manajemen Okupansi</em></p>
</div>

### 🎫 E-Ticket & PDF

<div align="center">
  <br/>
  <img src="public/img/TiketPdf.png" alt="E-Ticket PDF" width="48%" style="border-radius: 8px; border: 1px solid #eee;"/>
  <img src="public/img/PreviewTiket.png" alt="Ticket Preview" width="48%" style="border-radius: 8px; border: 1px solid #eee;"/>
</div>

---

## ✨ Fitur Unggulan

### 🛍️ Untuk Penumpang

- **Sistem Booking Intuitif**: Pilih rute, tanggal, dan kursi dengan visualisasi denah kursi (Seat Selection).
- **Pembayaran Midtrans**: Support berbagai metode pembayaran (QRIS, VA, E-Wallet).
- **Kode Promo**: Gunakan kode unik untuk mendapatkan diskon (Persentase/Nominal).
- **Notifikasi WhatsApp**: E-Ticket dan Reminder keberangkatan H-1 otomatis terkirim ke WA.
- **Flash Sale Banner**: Info promo menarik langsung di halaman depan.

### 🛠️ Untuk Admin

- **Dashboard Analytics**: Grafik tren pendapatan, rute populer, dan jam sibuk (ApexCharts).
- **Manajemen Armada & Rute**: Kelola bus, fasilitas, harga, dan jadwal keberangkatan.
- **Laporan Okupansi**: Cek keterisian kursi per keberangkatan.
- **Manajemen Kode Promo**: Buat dan atur kuota kode diskon.
- **Validasi Tiket**: Scan QR Code tiket penumpang.

---

## 🛠️ Teknologi

- **Backend**: Laravel 11.x
- **Frontend**: Vue.js 3 (Composition API) + Inertia.js
- **Styling**: Tailwind CSS + Custom Design System
- **Database**: MySQL 8.0
- **Integrasi Pihak Ketiga**:
    - **Midtrans**: Payment Gateway (Snap API)
    - **Fonnte**: WhatsApp API Gateway
    - **ApexCharts**: Data Visualization

---

## 🚀 Instalasi & Konfigurasi

Ikuti langkah ini untuk menjalankan project di local:

1. **Clone Repositori**

    ```bash
    git clone https://github.com/your-repo/tunggal-jaya-transport.git
    cd tunggal-jaya-transport
    ```

2. **Setup Backend**

    ```bash
    composer install
    cp .env.example .env
    php artisan key:generate
    ```

3. **Setup Database & Environment**
   Edit file `.env` dan sesuaikan kredensial database, serta API Key:

    ```env
    DB_DATABASE=laravel_tunggal_jaya_db

    # Midtrans
    MIDTRANS_SERVER_KEY=your-server-key
    MIDTRANS_CLIENT_KEY=your-client-key

    # Fonnte (WhatsApp)
    FONNTE_TOKEN=your-fonnte-token
    ```

4. **Migrasi & Seeding**

    ```bash
    php artisan migrate --seed
    ```

    _Seeder akan membuat akun admin default dan data dummy armada._

5. **Setup Frontend**

    ```bash
    npm install
    npm run build
    ```

6. **Jalankan Project**

    ```bash
    # Terminal 1
    php artisan serve

    # Terminal 2 (jika mode dev)
    npm run dev
    ```

7. **Scheduler (Opsional)**
   Untuk fitur reminder H-1 WhatsApp, jalankan scheduler:
    ```bash
    php artisan schedule:work
    ```

---

## 🔒 Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE).
<br/>
© 2024-2025 **Tunggal Jaya Transport**. Dibuat dengan ❤️ dan ☕.
