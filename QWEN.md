# Qwen V1 Update: Chatbot AI Implementation Plan for Tunggal Jaya Transport

## Overview

Dokumen ini merinci rencana implementasi untuk fitur chatbot AI pada website Tunggal Jaya Transport. Chatbot ini akan berfungsi sebagai customer service digital yang dapat menjawab berbagai pertanyaan seputar website, jadwal perjalanan, layanan, dan informasi lainnya terkait transportasi.

## Business Requirements

Chatbot AI harus mampu:

1. Menjawab pertanyaan umum seputar jadwal, rute, harga tiket, dan layanan
2. Membantu proses booking dan memberikan panduan kepada pengguna
3. Memberikan informasi terkait armada dan fasilitas yang tersedia
4. Menjawab pertanyaan seputar kebijakan perusahaan (refund, bagasi, dll.)
5. Menyediakan rekomendasi destinasi berdasarkan preferensi pengguna
6. Berintegrasi dengan sistem booking untuk menyediakan informasi real-time

## Technical Architecture

### A. Backend Components

1. **Laravel Controller**: `ChatBotController` untuk menangani permintaan chat
2. **Natural Language Processing**: Integrasi dengan OpenAI API atau solusi berbasis aturan
3. **Knowledge Base**: Tabel database untuk menyimpan FAQ dan data pelatihan
4. **Session Management**: Untuk menjaga konteks percakapan
5. **Logging System**: Untuk melacak interaksi chat untuk perbaikan

### B. Frontend Components

1. **Chat Interface**: Widget mengambang di semua halaman
2. **Message Display**: Tampilan percakapan real-time
3. **Input Handling**: Input teks dengan fungsi kirim
4. **Indikator Pengetikan**: Umpan balik visual saat pemrosesan

## Database Schema

### A. Tabel Statik Informasi (`chatbot_knowledge`)

-   `id` (bigint, primary key)
-   `category` (string) - booking, fleet, routes, policies, contact, dll.
-   `question` (text) - pertanyaan dari pengguna
-   `answer` (text) - jawaban dari sistem
-   `keywords` (json/array) - kata kunci terkait
-   `priority` (integer) - prioritas jawaban
-   `is_active` (boolean) - status aktif/non-aktif

### B. Tabel Riwayat Percakapan (`chatbot_conversations`)

-   `id` (bigint, primary key)
-   `user_id` (foreign key, nullable) - jika pengguna login
-   `session_id` (string) - untuk pengguna tidak login
-   `created_at`, `updated_at` (timestamp)

### C. Tabel Pesan Individu (`chatbot_messages`)

-   `id` (bigint, primary key)
-   `conversation_id` (foreign key)
-   `sender_type` (enum: 'user', 'bot')
-   `message` (text)
-   `intent` (string, nullable) - maksud dari pesan
-   `created_at` (timestamp)

## Core Logic Components

### A. Pengenalan Maksud (Intent Recognition)

Chatbot akan menggunakan kombinasi:

1. **Pencocokan Kata Kunci**: Untuk pertanyaan umum tentang rute, jadwal, harga
2. **API Pembelajaran Mesin**: Untuk pertanyaan kompleks (menggunakan OpenAI atau sejenisnya)
3. **Respons Berbasis Aturan**: Untuk logika bisnis tertentu (prosedur booking, kebijakan)

### B. Generasi Respons

1. **Respons Statis**: Untuk pertanyaan sering diajukan
2. **Respons Dinamis**: Mengambil data langsung (jadwal, ketersediaan)
3. **Respons Cadangan**: Saat tidak dapat memahami permintaan

### C. Fungsi Utama

#### 1. Kueri Informasi

-   Informasi rute dan jadwal
-   Detail harga
-   Spesifikasi armada
-   Informasi perusahaan

#### 2. Bantuan Pemesanan

-   Panduan proses booking
-   Rekomendasi jadwal
-   Bantuan pembayaran

#### 3. Dukungan Akun Pengguna

-   Pencarian riwayat booking
-   Bantuan manajemen profil
-   Bantuan reset sandi

#### 4. Resolusi Masalah

-   Penanganan FAQ
-   Resolusi masalah umum
-   Eskalasi ke customer service manusia bila diperlukan

## Implementation Plan

### Phase 1: Persiapan Database dan Model (Hari 1-2)

1. Buat tabel database yang diperlukan
    - `chatbot_knowledge`
    - `chatbot_conversations`
    - `chatbot_messages`
2. Buat model-model terkait
    - `ChatbotKnowledge`
    - `ChatbotConversation`
    - `ChatbotMessage`

### Phase 2: Pengembangan API Backend (Hari 3-4)

1. Buat `ChatBotController` dengan:
    - Metode `sendMessage` - untuk memproses input pengguna dan mengembalikan respons yang sesuai
    - Metode `getSuggestions` - menyediakan saran balasan cepat
    - Metode `trainBot` - memungkinkan admin menambahkan/memperbarui basis pengetahuan
2. Integrasikan layanan NLP:
    - Pilihan 1: Integrasi OpenAI API untuk pemahaman canggih
    - Pilihan 2: Sistem berbasis aturan untuk solusi hemat biaya
    - Pendekatan hibrida menggabungkan keduanya
3. Buat `ChatbotService` dengan:
    - Logika pengenalan maksud
    - Generasi respons
    - Fungsi pencarian basis pengetahuan

### Phase 3: Pengembangan Frontend (Hari 5-6)

1. Buat widget chat:
    - Tombol mengambang yang memperluas ke antarmuka chat penuh
    - Desain responsif untuk desktop dan mobile
    - Integrasi dengan perlindungan CSRF Laravel
2. Implementasikan pesan real-time:
    - Gunakan Laravel Echo dengan Pusher untuk pembaruan real-time
    - Indikator pengetikan
    - Indikator status pesan
3. Tambahkan riwayat chat:
    - Simpan percakapan di localStorage browser
    - Muat interaksi terbaru saat pengguna kembali

### Phase 4: Populasi Basis Pengetahuan (Hari 7-8)

1. Isi dengan pertanyaan umum:
    - Ekstrak FAQ dari konten yang ada
    - Tambahkan informasi rute dan jadwal
    - Sertakan panduan proses pemesanan
2. Latih sistem:
    - Buat dataset awal untuk model AI
    - Uji akurasi respons
    - Sempurnakan algoritma pencocokan

### Phase 5: Integrasi dan Pengujian (Hari 9-10)

1. Integrasi ke halaman yang ada:
    - Tambahkan widget chat ke semua halaman publik
    - Hubungkan ke informasi akun pengguna
    - Tautkan ke riwayat booking saat pengguna login
2. Pengujian:
    - Uji unit untuk logika backend
    - Uji integrasi untuk alur penuh
    - Uji penerimaan pengguna

### Phase 6: Fitur Lanjutan (Hari 11-12)

1. Personalisasi:
    - Tampilkan respons yang dipersonalisasi berdasarkan riwayat booking
    - Ingat preferensi pengguna
2. Alih tangan ke agen manusia:
    - Deteksi kapan intervensi manusia diperlukan
    - Sediakan opsi untuk terhubung ke customer service

## Teknologi yang Digunakan

-   **Backend**: Laravel 12 dengan PHP 8.2+
-   **Frontend**: Alpine.js untuk interaktivitas, Tailwind CSS untuk styling
-   **AI/ML**: OpenAI API atau sistem berbasis aturan
-   **Real-time**: Laravel Echo, Pusher atau Laravel WebSockets
-   **Database**: MySQL (seperti yang digunakan dalam proyek saat ini)

## Pertimbangan Keamanan

1. Terapkan pembatasan laju untuk permintaan chat
2. Sanitasi input pengguna untuk mencegah serangan XSS
3. Pastikan riwayat chat hanya dapat diakses oleh pengguna yang terotentikasi
4. Enkripsi informasi sensitif pengguna dalam log chat

## Pertimbangan Kinerja

1. Terapkan caching untuk pertanyaan sering diajukan
2. Gunakan sistem antrian untuk permintaan API AI agar tidak memblokir
3. Optimalkan kueri database untuk pencarian basis pengetahuan

## Integrasi dengan Fitur yang Ada

### A. Integrasi dengan Sistem Booking

Chatbot akan terintegrasi dengan sistem booking untuk:

-   Memberikan informasi real-time tentang ketersediaan jadwal
-   Mendapatkan informasi booking pengguna saat login
-   Memberikan rekomendasi berdasarkan riwayat booking

### B. Integrasi dengan Rekomendasi

-   Menggunakan algoritma rekomendasi yang sudah ada untuk memberikan saran destinasi
-   Memperkaya rekomendasi berdasarkan dialog dengan pengguna

### C. Integrasi dengan Multi-bahasa

-   Mendukung bahasa Indonesia dan Inggris sesuai dengan sistem terjemahan yang sudah ada

## Evaluasi dan Pengembangan Berkelanjutan

1. Kumpulkan umpan balik dari pengguna
2. Analisis interaksi chat untuk mengidentifikasi area perbaikan
3. Perbarui basis pengetahuan secara berkala
4. Tingkatkan akurasi respons melalui machine learning

## Potensi Tantangan dan Solusi

1. **Pemahaman bahasa alami**: Gunakan kombinasi NLP dan basis pengetahuan statis
2. **Respons yang tidak akurat**: Sediakan fitur pelaporan dan mekanisme perbaikan
3. **Ketersediaan data real-time**: Integrasi langsung dengan sistem booking dan jadwal
4. **Pemrosesan bahasa lokal**: Tambahkan kamus dan frasa lokal ke basis pengetahuan

## Rollback Plan

Jika dalam proses implementasi atau setelah implementasi Anda berubah pikiran dan ingin menghapus fitur chatbot, berikut adalah rencana rollback yang dapat diikuti:

### A. Menghapus Migrasi Database

-   Migration: `create_chatbot_knowledge_table`, `create_chatbot_conversations_table`, `create_chatbot_messages_table`
    -   Untuk menghapus: `php artisan migrate:rollback --step=3`
    -   Atau secara manual drop tabel: `chatbot_knowledge`, `chatbot_conversations`, `chatbot_messages`

### B. Menghapus File yang Dibuat

-   `app/Models/ChatbotKnowledge.php` - Model untuk basis pengetahuan
-   `app/Models/ChatbotConversation.php` - Model untuk percakapan
-   `app/Models/ChatbotMessage.php` - Model untuk pesan individual
-   `app/Http/Controllers/ChatBotController.php` - Controller utama chatbot
-   `app/Services/ChatbotService.php` - Service untuk logika chatbot
-   `resources/views/components/chatbot.blade.php` - Komponen chatbot frontend
-   `resources/js/chatbot.js` - File JavaScript untuk fungsionalitas chatbot

### C. Menghapus Konfigurasi dan Route

-   Dalam `routes/web.php` atau `routes/api.php`: hapus rute chatbot
-   Dalam `config/services.php`: hapus konfigurasi API (jika ada)
-   Dalam `.env`: hapus variabel lingkungan untuk API chatbot (jika ada)

### D. Menghapus Integrasi Frontend

-   Dalam `resources/views/layouts/app.blade.php` atau file layout utama: hapus script chatbot
-   Dalam file-fie halaman utama: hapus panggilan komponen chatbot

### E. Membersihkan Referensi Kode

-   Dalam controller yang terintegrasi: hapus panggilan ke service chatbot
-   Dalam middleware (jika ada): hapus middleware terkait chatbot
-   Dalam file konfigurasi lainnya: hapus konfigurasi yang terkait

## Verifikasi Rollback

Setelah melaksanakan rollback, pastikan:

1. Tidak ada error saat menjalankan `php artisan migrate:status`
2. Aplikasi tetap berjalan normal tanpa fitur chatbot
3. Tidak ada file sisa yang terkait dengan chatbot
4. Tidak ada konfigurasi atau route yang mengarah ke fitur yang sudah dihapus

Chatbot ini akan menjadi bagian penting dari pengalaman pengguna di Tunggal Jaya Transport, meningkatkan layanan pelanggan dan efisiensi operasional sambil menyediakan informasi yang akurat dan real-time kepada pengguna.

# Catatan Penting: Migrasi dan Rollback

## Kebijakan Migrasi dan Rollback

Dalam pengerjaan proyek Tunggal Jaya Transport, penting untuk diingat bahwa:

### 1. Catatan Migrasi

-   Setiap perubahan yang melibatkan migrasi database harus dicatat secara rinci
-   Catat tabel yang dibuat, dimodifikasi, atau dihapus
-   Catat perubahan struktur database yang terjadi

### 2. Prosedur Rollback

-   Harus selalu diingat apa yang dirollback dan bagaimana caranya
-   Simpan catatan rinci tentang langkah-langkah rollback
-   Pastikan setiap komponen yang dihapus bisa dimigrasi kembali jika diperlukan

### 3. Konfirmasi Rollback

-   Sebelum dan setelah melakukan rollback, konfirmasi ke pengguna tentang:
    -   Apa yang telah dirollback
    -   Apa yang masih bisa dimigrasi kembali
    -   Jalur migrasi kembali yang tersedia

### 4. Dokumentasi

-   Pastikan dokumentasi migrasi dan rollback selalu diperbarui
-   Simpan informasi dalam tempat yang mudah diakses untuk referensi masa depan

# Qwen V1.1 Update: Improving PDF Ticket Design for Tunggal Jaya Transport

## Overview

Rekomendasi ini memberikan peningkatan terhadap tampilan tiket PDF pada website Tunggal Jaya Transport. Saat ini, tampilan tiket PDF masih sederhana dan perlu ditingkatkan agar lebih profesional, informatif, dan menarik secara visual bagi pengguna. Integrasi antara tampilan tiket frontend dengan PDF perlu ditingkatkan untuk konsistensi pengalaman pengguna.

## Business Requirements

Tiket PDF harus:

1. Tampil lebih profesional dan menarik secara visual
2. Menyertakan informasi yang lengkap dan mudah dibaca
3. Menampilkan logo dan identitas perusahaan dengan baik `@public/img/logoNoBg`
4. Dapat dipindai dengan mudah (barcode dan QR code yang jelas)
5. Menyediakan informasi penting seperti aturan, kontak layanan, dan titik boarding
6. Konsisten dengan desain brand perusahaan
7. Menggunakan format landscape yang mirip dengan tiket pesawat/kereta di Indonesia
8. Menampilkan gambar dari `@public/img/heroImg.jpg` dan logo Tunggal Jaya secara menarik
9. TAMPILKAN DI UKURAN KERTAS A4


## Technical Implementation

### A. Backend Components

1. **TicketPdfService**: Buat kelas baru untuk menggantikan pendekatan sebelumnya
2. **BookingController**: Perbarui fungsi `downloadTicket` untuk menggunakan kelas baru
3. **PDF Generation**: Gunakan DOMPDF dengan pendekatan blade template untuk kemudahan kustomisasi

### B. Frontend Components (View)

1. **ticket-pdf.blade.php**: Buat tiket PDF landscape baru dengan elemen visual yang lebih menarik
2. **Konsistensi tampilan**: Pastikan tampilan frontend dan PDF serupa
3. **CSS Styling**: Terapkan gaya modern dan responsif dalam format PDF

## Design Improvements

### A. Layout dan Struktur (Landscape)

1. **Header Tiket**

    - Logo Tunggal Jaya dalam ukuran lebih besar dan posisi menonjol
    - Warna latar belakang gradient yang sesuai dengan brand
    - Informasi nama perusahaan dan slogan dalam ukuran yang lebih besar
    - Tambahkan elemen desain visual seperti garis dekoratif atau ikon
    - Background image dari `public/img/heroImg.jpg` dengan opacity rendah

2. **Bagian Utama (Body)**

    - Layout grid untuk informasi penting (rute, penumpang, kursi, dll.)
    - Bagian rute perjalanan yang lebih besar dan menonjol
    - Pemisah antar informasi dengan elemen visual yang menarik
    - Gunakan ikon kecil untuk setiap informasi (penumpang, kode booking, keberangkatan, dll.)
    - Tampilkan informasi bus secara lebih mencolok

3. **Bagian Kode Pemindaian**

    - Barcode dan QR code yang lebih besar dan jelas untuk pemindaian
    - Kode booking besar dan mudah dibaca

4. **Footer Tiket**
    - Informasi kontak dan alamat website yang lebih mudah terlihat
    - Instruksi perjalanan dalam bentuk visual yang menarik

### B. Penambahan Fitur Desain

1. **Desain Visual**

    - Tambahkan elemen border atau garis dekoratif di sekeliling tiket
    - Gunakan warna yang konsisten dengan brand perusahaan
    - Tambahkan background pattern atau watermark dari `public/img/heroImg.jpg` secara halus

2. **Tata Letak Informasi**

    - Prioritaskan informasi penting (rute, tanggal, jam keberangkatan)
    - Gunakan ukuran font yang berbeda untuk menonjolkan informasi penting
    - Atur jarak antar elemen agar lebih lega dan mudah dibaca
    - Gunakan layout landscape yang menyerupai tiket pesawat/kereta

3. **Pemilihan Warna**
    - Gunakan skema warna yang konsisten dengan identitas brand
    - Jaga kontras yang cukup untuk kemudahan pembacaan
    - Terapkan sistem warna untuk status dan informasi khusus

### C. Fungsi dan Fitur Tambahan

#### 1. Konsistensi dengan Tampilan Frontend

-   Pastikan desain tiket PDF mencerminkan tampilan frontend
-   Gunakan elemen dan warna yang serupa antara frontend dan PDF
-   Jaga keseragaman tipografi dan tata letak informasi

#### 2. Background Image

-   Gunakan gambar `public/img/heroImg.jpg` sebagai elemen dekoratif di tiket
-   Terapkan dengan opacity rendah agar tidak mengganggu pembacaan informasi

#### 3. Peningkatan Informasi

-   Tambahkan informasi bus secara lebih lengkap (nomor kendaraan, tipe, dll.)
-   Tambahkan informasi boarding point secara lebih menonjol
-   Tambahkan informasi estimasi durasi perjalanan

## Implementation Plan

### Phase 1: Persiapan (Hari 1)

1. Buat perencanaan desain tiket baru
    - Buat wireframe desain tiket landscape
    - Persiapkan elemen visual (ikon, logo, background dari `public/img/heroImg.jpg`)
    - Siapkan contoh layout dalam format landscape
2. Review dan validasi desain dengan stakeholder

### Phase 2: Pengembangan Backend (Hari 2-3)

1. Buat kelas `TicketPdfService`
    - Implementasi fungsi generate tiket PDF
    - Gunakan DOMPDF untuk konversi HTML ke PDF
2. Perbarui `BookingController`
    - Perbarui fungsi `downloadTicket` untuk menggunakan kelas baru
3. Uji fungsi dasar generate PDF

### Phase 3: Pengembangan Frontend - PDF (Hari 4-5)

1. Implementasikan desain baru di view tiket PDF
    - Buat `ticket-pdf.blade.php` baru dengan layout landscape
    - Tambahkan elemen visual sesuai rencana desain
2. Implementasi desain responsif untuk format PDF
    - Pastikan tampilan tetap bagus dalam format landscape
    - Uji tampilan pada berbagai ukuran kertas
3. Tambahkan background image dari `public/img/heroImg.jpg`

### Phase 4: Konsistensi dengan Frontend (Hari 6-7)

1. Bandingkan dan sesuaikan tampilan tiket PDF dengan tampilan frontend
    - Gunakan warna dan elemen yang serupa
    - Pastikan informasi ditampilkan dengan cara yang konsisten
2. Tambahkan elemen dari partial tiket frontend ke PDF
3. Uji konsistensi tampilan dan informasi

### Phase 5: Testing dan Validasi (Hari 8-9)

1. Uji coba tampilan PDF di berbagai perangkat dan pembaca PDF
2. Validasi kualitas barcode dan QR code untuk kemudahan scanning
3. Uji aksesibilitas dan kemudahan membaca informasi
4. Kumpulkan feedback dari tim dan user testing

### Phase 6: Penyempurnaan dan Peluncuran (Hari 10)

1. Lakukan perbaikan berdasarkan hasil testing
2. Pastikan kompatibilitas dengan sistem booking yang ada
3. Deploy ke staging dan uji secara menyeluruh
4. Deploy ke production dengan pengawasan ketat

## Teknologi yang Digunakan

-   **Backend**: Laravel 12 dengan PHP 8.2+
-   **PDF Generation**: DomPDF untuk pembuatan tiket PDF
-   **Frontend**: Blade template, CSS3 untuk styling
-   **Barcode/QR Generation**: Milon/barcode package
-   **Database**: MySQL untuk menyimpan informasi booking

## Pertimbangan Desain

### A. Keterbacaan

1. Gunakan ukuran font yang cukup besar dan kontras yang baik
2. Jaga jarak antar informasi agar tidak terlalu padat
3. Gunakan warna yang kontras antara teks dan latar belakang

### B. Pemindaian

1. Pastikan barcode dan QR code memiliki ukuran yang cukup besar
2. Jaga margin yang cukup di sekitar barcode/QR code
3. Gunakan warna yang memungkinkan pemindaian yang mudah

### C. Estetika

1. Gunakan layout landscape yang seimbang dan proporsional
2. Terapkan prinsip desain visual (alignment, proximity, contrast, repetition)
3. Gunakan warna yang mencerminkan profesionalisme perusahaan
4. Integrasi background `public/img/heroImg.jpg` dengan tepat

## Integrasi dengan Fitur yang Ada

### A. Integrasi dengan Sistem Booking

-   Tetap jaga kompatibilitas dengan proses booking yang sudah ada
-   Pastikan informasi yang ditampilkan tetap akurat dan sinkron

### B. Integrasi dengan Tampilan Frontend

-   Gunakan elemen desain yang serupa antara halaman sukses booking dan tiket PDF
-   Jaga konsistensi informasi dan posisi elemen antara frontend dan PDF

### C. Integrasi dengan Multi-bahasa

-   Pastikan elemen teks dalam tiket dapat diubah sesuai bahasa
-   Tambahkan dukungan untuk layout right-to-left jika diperlukan

## Evaluasi dan Pengembangan Berkelanjutan

1. Kumpulkan umpan balik dari pengguna terkait tampilan tiket baru
2. Analisis tingkat keberhasilan pemindaian barcode/QR code
3. Evaluasi tingkat kepuasan pengguna terhadap tampilan tiket
4. Lakukan peningkatan berkelanjutan berdasarkan data dan umpan balik

## Potensi Tantangan dan Solusi

1. **Kompatibilitas PDF Reader**: Gunakan teknik CSS yang didukung secara luas oleh DomPDF
2. **Pemrosesan Waktu**: Optimalkan ukuran file dan elemen visual agar tidak membebani server
3. **Pengaturan Cetak**: Pastikan desain tetap bagus dalam mode cetak hitam-putih
4. **Ukuran File**: Optimalkan gambar background agar tidak membuat file PDF terlalu besar
5. **Konsistensi Tampilan**: Gunakan pendekatan komponen yang sama antara frontend dan PDF

## Rollback Plan

Jika implementasi desain baru bermasalah, berikut adalah rencana rollback:

### A. Mengembalikan Fungsi Lama

-   Kembalikan BookingController ke versi sebelumnya sebelum implementasi `TicketPdfService`
-   Hapus file `TicketPdfService.php` jika telah dibuat

### B. Mengembalikan View Lama

-   Kembalikan file `ticket-pdf.blade.php` ke versi sebelumnya
-   Hapus perubahan yang tidak diperlukan

## Progress Update

### Implementation Status

Setelah completing the V1.1 update for the PDF ticket design, the following changes have been successfully implemented:

#### 1. Backend Implementation
- Created `TicketPdfService` class in `app/Services/TicketPdfService.php`
- Updated `BookingController` to use dependency injection for the service
- Maintained all security checks and validation in the downloadTicket method

#### 2. Frontend Implementation 
- Completely redesigned `ticket-pdf.blade.php` with modern layout
- Implemented proper portrait orientation for A4 paper size
- Added comprehensive information sections with proper organization
- Integrated company logo and background image (`heroImg.jpg`)

#### 3. Design Enhancements
- Created a two-column layout with journey details on the left and payment/QR on the right
- Added professional styling with blue color scheme that matches brand identity
- Implemented proper information grouping with card-like sections
- Added visual elements like gradients, subtle shadows, and decorative patterns
- Added a background watermark using `heroImg.jpg` with low opacity
- Enhanced the route section with prominent styling
- Implemented a centered layout that properly fits on A4 paper
- Added decorative elements like a bus icon and gradient borders

#### 4. Information Organization
- Created separate cards for Journey Details, Passenger Details, and Payment Information
- Optimized font sizes and spacing for readability while maximizing information density
- Ensured all essential information is clearly visible: booking code, route, date/time, passenger details, seat numbers, price, and QR code
- Added proper terms and conditions in the footer
- Implemented proper contact information

#### 5. Technical Improvements
- Fixed the "Array to string conversion" error in barcode generation
- Resolved issues with image paths in DomPDF context
- Ensured the design fits properly on a single page without overflow
- Implemented responsive spacing that works well in PDF format
- Used appropriate functions (`base_path()`) for image assets

Desain tiket PDF yang ditingkatkan ini akan memberikan pengalaman yang lebih baik bagi pengguna, dengan tampilan yang lebih profesional dan informasi yang lebih mudah dibaca dan dipahami, serta konsisten antara tampilan frontend dan PDF.
