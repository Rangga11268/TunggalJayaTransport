# Flutter Mobile UI Design — TunggalJayaTransport

Dokumen ini menjelaskan arah desain aplikasi mobile Flutter untuk TunggalJayaTransport.
Fokusnya adalah modern, simple, rapi, dan tidak ramai warna.

Untuk versi implementasi dan checklist go-live, lihat [Flutter Mobile Go-Live Guide](FLUTTER_MOBILE_GO_LIVE.md).

## 1. Arah Visual

Desain yang disarankan:

- modern minimal
- warna sedikit, aksen jelas
- banyak ruang kosong
- komponen tegas, tidak berlebihan
- tampilan terasa cepat dan mudah dipahami

### Karakter visual

- clean
- calm
- profesional
- sedikit bold di area penting seperti CTA dan status pembayaran
- menghindari efek dekoratif yang tidak perlu

### Prinsip utama

1. Satu warna utama, satu warna aksen, sisanya netral.
2. Hindari gradasi yang berlebihan.
3. Gunakan kartu, garis tipis, dan bayangan lembut.
4. Jangan pakai banyak variasi icon style.
5. CTA utama harus langsung terlihat.

## 2. Sistem Warna

Pakai palet yang sederhana, tidak terlalu banyak warna.

### Palet dasar yang disarankan

- Primary: biru tua atau navy gelap
- Accent: hijau atau teal untuk aksi positif
- Neutral: putih, abu muda, slate, charcoal

### Contoh arah warna

- Background utama: `#F8FAFC`
- Surface/card: `#FFFFFF`
- Primary text: `#0F172A`
- Secondary text: `#64748B`
- Primary brand: `#0F2A43`
- Accent action: `#14B8A6` atau `#22C55E`
- Border subtle: `#E2E8F0`

### Aturan warna

- Satu halaman cukup memakai 1 warna utama + 1 aksen.
- Status pakai warna fungsional saja:
    - success: hijau
    - warning: amber
    - error: merah
    - info: biru
- Jangan mencampur banyak warna cerah dalam satu layar.

## 3. Tipografi

Rekomendasi font modern yang bersih:

- `Inter`
- `Plus Jakarta Sans`
- `Manrope`

### Skala teks

- H1: 28-32 px, semi-bold
- H2: 22-24 px, semi-bold
- H3: 18-20 px, medium
- Body: 14-16 px
- Caption: 12-13 px

### Aturan tipografi

- Heading singkat dan tegas.
- Body text jangan terlalu panjang per baris.
- Gunakan line-height lega.
- Hindari huruf kapital semua kecuali label kecil.

## 4. Grid, Spacing, Dan Radius

### Spacing standar

- margin layar: 16 px
- jarak antar section: 20-24 px
- jarak antar elemen kecil: 8-12 px

### Radius

- card: 16 px
- button: 14-16 px
- input: 14 px
- chip: 999 px untuk pill

### Elevation

- bayangan sangat lembut
- jangan pakai shadow tebal
- lebih baik border tipis daripada shadow berat

## 5. Iconography

Gunakan satu keluarga ikon saja agar konsisten.

Rekomendasi:

- `lucide_flutter`
- `phosphor_flutter`
- `material_symbols` jika ingin standar Flutter

### Gaya ikon

- outline tipis
- ukuran umum 20-24 px
- ikon utama di bottom navigation 24 px
- hindari campuran filled dan outline tanpa alasan

### Contoh ikon yang cocok

- Home: house
- Search: search
- Booking: ticket / receipt-text
- History: clock-rotate-left / history
- News: newspaper
- Fleet: bus
- Profile: user-circle
- Payment: credit-card / wallet
- Promo: percent / tag
- Back: arrow-left
- Close: x
- Filter: sliders-horizontal
- Seat: armchair / grid

## 6. Struktur Navigasi Utama

Pakai bottom navigation 4 atau 5 tab.

### Rekomendasi bottom nav

1. Home
2. Search
3. Booking / Tickets
4. News
5. Profile

Kalau mau lebih ringkas, `Booking` dan `Tickets` bisa digabung ke satu tab `Trips`.

### Pola navigasi

- bottom nav hanya untuk halaman utama
- halaman detail masuk via push navigation
- flow booking pakai step-by-step, bukan semua jadi satu layar penuh
- layar pembayaran ditampilkan sebagai full-screen flow atau webview modal

### Aturan navigasi

- jangan sembunyikan tombol back
- halaman detail punya app bar yang jelas
- hasil pencarian harus bisa kembali ke filter sebelumnya
- state booking tidak boleh hilang saat pindah step

## 7. App Shell

### App bar

- simple
- background putih atau surface
- judul singkat
- action icon di kanan bila perlu

### Bottom navigation

- background putih
- border atas tipis
- label kecil
- icon outline
- tab aktif diberi warna primary

### Floating action button

Tidak wajib.

Kalau dipakai, hanya untuk aksi yang sangat jelas, misalnya:

- pesan cepat
- booking ulang

Namun untuk aplikasi ini, bottom nav dan CTA card sudah cukup. FAB sebaiknya tidak dipakai jika tidak ada fungsi khusus yang benar-benar penting.

## 8. Komponen Umum

### Primary button

- warna primary solid
- teks putih
- radius 14-16 px
- tinggi sekitar 48-52 px
- full width untuk CTA utama

### Secondary button

- border tipis
- background putih
- teks primary
- dipakai untuk aksi sekunder seperti `Lihat detail`, `Simpan`, `Batal`

### Tertiary button / text button

- dipakai untuk link kecil seperti `Lupa password`, `Lihat semua`, `Ubah filter`

### Input field

- label di atas input
- placeholder pendek
- border abu muda
- fokus diberi border primary
- error state merah tipis, bukan merah agresif

### Card

- latar putih
- border halus
- radius 16 px
- isi ringkas
- gunakan spacing konsisten

### Chip / pill

- untuk filter kelas, jam, status, promo
- bentuk rounded penuh
- active state solid atau tinted

### Badge status

- pending: amber
- paid: green
- failed: red
- expired: slate atau red muda
- booked: blue

## 9. Pola Layout Per Halaman

Di bawah ini adalah arahan halaman demi halaman.

## 10. Splash Screen

### Tujuan

- memberikan identitas aplikasi singkat
- loading awal aplikasi

### Layout

- logo di tengah
- nama aplikasi di bawah logo
- background clean, polos, atau sangat lembut

### Visual

- background putih atau off-white
- logo jadi fokus utama
- tidak perlu animasi ramai

## 11. Onboarding

Onboarding hanya dipakai jika memang perlu.
Kalau terlalu banyak langkah, bisa langsung ke home.

### Jika dipakai

- 2-3 slide saja
- isi: cari jadwal, booking kursi, bayar, simpan tiket
- satu CTA utama: `Mulai`

### Layout

- ilustrasi sederhana
- teks singkat
- tombol utama di bawah

## 12. Login / Register

### Layout umum

- logo kecil di atas
- judul besar: `Masuk` atau `Daftar`
- form stacked vertikal
- tombol utama full width

### Field login

- email / phone
- password
- opsi tampilkan password

### Field register

- nama
- email
- phone
- password
- confirm password

### Tombol dan link

- primary button: `Masuk`
- secondary text: `Lupa password?`
- link pindah halaman: `Belum punya akun? Daftar`

### Ikon yang dipakai

- email: mail
- phone: phone
- password: lock
- show/hide password: eye / eye-off

### Gaya desain

- sederhana, tidak penuh ornamen
- form fokus pada kecepatan input
- jangan taruh terlalu banyak teks bantuan

## 13. Forgot Password / Reset Password

### Flow

1. User input email/phone.
2. Kirim link atau OTP.
3. User isi kode atau klik link reset.
4. User set password baru.

### Layout

- satu tugas per layar
- ada progress ringkas jika perlu
- button utama jelas

### Ikon

- key / lock-reset
- mail atau phone sesuai metode

## 14. Home

Home adalah layar paling penting setelah login atau saat guest membuka app.

### Tujuan

- memberi akses cepat ke pencarian
- menampilkan rute populer
- menampilkan berita terbaru
- menampilkan armada unggulan

### Struktur layout

1. Header ringkas.
2. Search card besar di bagian atas.
3. Shortcut chips.
4. Featured routes.
5. Promo / info ringkas.
6. News preview.
7. Fleet preview.

### Header

- salam singkat
- nama user jika login
- icon notifikasi di kanan

### Search card

- asal
- tujuan
- tanggal
- tombol cari

### Shortcut yang cocok

- Booking tiket
- Riwayat
- Promo
- Berita
- Armada

### Ikon home

- notifikasi: bell
- rute: map-pinned
- tiket: ticket
- berita: newspaper
- armada: bus

### CTA utama di home

- `Cari Jadwal`

### Gaya visual

- card besar dengan border halus
- grid rapi
- jangan terlalu banyak banner besar

## 15. Search / Find Schedule

### Tujuan

- mencari jadwal berdasarkan asal, tujuan, tanggal, kelas, jam

### Layout

- filter panel di atas
- hasil berupa card list
- filter chips untuk class dan time
- tombol `Reset` dan `Terapkan`

### Komponen filter

- origin dropdown/search field
- destination dropdown/search field
- date picker
- class chips
- time chips

### Hasil pencarian

Setiap card jadwal menampilkan:

- nama rute
- waktu berangkat
- waktu tiba
- durasi
- harga
- sisa kursi
- badge kelas bus

### Tombol pada hasil

- primary: `Pilih`
- secondary: `Detail`

### Ikon

- origin/destination: location-pin
- date: calendar
- time: clock
- filter: sliders-horizontal

## 16. Route List / Route Detail

### Route list

- list card sederhana
- tampilkan origin, destination, durasi, estimasi harga

### Route detail

- hero section kecil
- ringkasan rute
- peta / waypoint jika tersedia
- daftar jadwal terkait

### CTA

- `Lihat Jadwal`

### Ikon

- map
- arrow-right
- route / path

## 17. Fleet List / Fleet Detail

### Fleet list

- card bus dengan foto
- nama bus
- tipe bus
- kapasitas
- status

### Fleet detail

- foto besar di atas
- detail spesifikasi di bawah
- fitur bus ditampilkan sebagai chips

### CTA

- `Lihat Jadwal Bus Ini` jika perlu

### Ikon

- bus
- seat
- fuel / comfort icons bila ada

## 18. News List / News Detail

### News list

- list card vertikal
- thumbnail di kiri atau atas
- judul singkat
- tanggal
- ringkasan 2 baris

### News detail

- gambar cover di atas
- judul besar
- meta tanggal dan kategori
- isi artikel nyaman dibaca

### Gaya visual

- fokus ke readability
- jangan pakai elemen berlebihan

### Ikon

- news: newspaper
- date: calendar
- author: user

## 19. Booking Index / Booking Result

### Tujuan

- menampilkan hasil jadwal yang bisa dibooking

### Layout

- ringkas dan fokus ke hasil
- filter summary di atas
- list jadwal sebagai card
- CTA langsung ke detail atau seat selection

### Card jadwal

- nama rute
- jadwal berangkat
- harga
- kapasitas / sisa kursi
- badge `Tersedia`, `Penuh`, `Hampir Penuh`

### Tombol

- `Pilih Kursi`
- `Detail`

## 20. Booking Detail / Show

### Tujuan

- memberi detail perjalanan sebelum seat selection

### Isi halaman

- ringkasan rute
- nama bus
- kelas bus
- waktu berangkat/tiba
- durasi
- harga
- aturan booking

### CTA

- `Pilih Kursi`

### Button style

- sticky button bawah untuk CTA utama

## 21. Seat Selection

Ini salah satu halaman paling penting.

### Layout utama

- bagian atas: info ringkas booking
- bagian tengah: seat map
- bagian bawah: legend dan CTA

### Seat map

- grid kursi yang jelas
- kursi tersedia = outline netral
- kursi terpilih = primary solid
- kursi terisi = abu gelap / disabled
- kursi dekat driver / depan diberi label kecil bila perlu

### Legend

- Available
- Selected
- Occupied
- Disabled

### Interaksi

- tap kursi untuk pilih / batal pilih
- tampilkan jumlah kursi terpilih secara real-time
- jika jumlah kursi melebihi batas, beri error inline

### CTA

- `Lanjutkan`

### Ikon

- seat: armchair / grid
- info: info-circle
- warning: triangle-alert

## 22. Booking Form / Passenger Data

### Tujuan

- mengisi identitas penumpang dan detail booking

### Layout

- section per grup data
- nama penumpang
- nomor telepon
- email
- catatan jika perlu
- promo code

### Promo section

- input code kecil
- button `Gunakan`
- hasil promo tampil sebagai baris ringkas

### CTA

- `Buat Booking`

### Ikon

- user
- phone
- mail
- percent / ticket

## 23. Payment Screen

### Tujuan

- menyelesaikan pembayaran tanpa membingungkan user

### Layout

- ringkasan tagihan di atas
- metode pembayaran sebagai list radio/card
- tombol `Bayar Sekarang`

### Metode pembayaran

- e-wallet
- bank transfer
- qris
- credit card jika diaktifkan

### Gaya pilihan metode

- card kecil
- logo metode di kiri
- radio di kanan
- status aktif diberi border primary

### Ikon

- wallet
- card
- bank
- qr-code

## 24. Payment Status / Result

### Tujuan

- memberi kepastian status transaksi

### Layout

- ikon status besar di atas
- status text singkat
- ringkasan order
- tombol berikutnya

### Status state

- success: hijau
- pending: amber
- failed: merah

### CTA

- `Lihat Tiket`
- `Cek Status`
- `Kembali ke Home`

### Gaya

- jangan terlalu banyak teks
- status harus langsung terbaca

## 25. Ticket / Success Detail

### Tujuan

- menyimpan bukti booking yang mudah dibuka ulang

### Layout

- ticket card utama
- kode booking besar
- detail penumpang
- detail jadwal
- detail kursi
- status pembayaran

### Tombol

- `Unduh Tiket`
- `Bagikan`
- `Lihat Booking`

### Ikon

- download
- share
- ticket
- qr-code jika dipakai

## 26. Booking History

### Tujuan

- melihat daftar perjalanan dan statusnya

### Layout

- tab kecil: `Aktif`, `Selesai`, `Dibatalkan`
- list kartu booking
- search kecil jika diperlukan

### Card booking

- booking code
- rute
- tanggal
- status
- tombol detail

### Ikon

- history
- clock
- ticket

## 27. Booking History Detail

### Isi halaman

- ringkasan perjalanan
- kursi
- penumpang
- pembayaran
- tombol tiket

### CTA

- `Lihat Tiket`
- `Hubungi Admin` jika perlu

## 28. Profile

### Layout

- avatar di atas
- nama user
- email dan phone
- menu list bawahnya

### Menu yang cocok

- edit profil
- verifikasi phone
- ubah password
- booking history
- notifikasi
- logout

### Ikon

- user
- settings
- phone-check
- lock
- bell
- log-out

## 29. Contact / Help

### Tujuan

- memberi jalur kontak ke admin atau customer service

### Layout

- informasi kontak dalam card kecil
- tombol WhatsApp atau call
- form kontak jika diperlukan

### CTA

- `Chat WhatsApp`
- `Hubungi Kami`

### Ikon

- phone
- message-circle
- whatsapp jika ditampilkan

## 30. About

### Tujuan

- memberi identitas singkat perusahaan

### Layout

- logo
- deskripsi singkat
- poin layanan
- kontak singkat

### Gaya

- sangat ringkas
- bukan halaman marketing panjang

## 31. States Yang Harus Didukung

Setiap halaman sebaiknya punya 4 state utama:

- loading
- empty
- error
- success

### Loading state

- skeleton card
- shimmer tipis
- jangan blok UI terlalu lama tanpa indikator

### Empty state

- ikon kecil
- teks jelas
- CTA untuk cari lagi atau refresh

### Error state

- pesan singkat
- button retry

## 32. Responsiveness

Flutter mobile terutama fokus portrait.

### Aturan umum

- layout harus aman di layar kecil
- jangan pakai elemen horizontal berlebihan
- jika tabel atau detail banyak, gunakan card bertumpuk
- seat map harus tetap terbaca di layar sempit

### Tablet

- boleh menambah dua kolom pada layar besar
- tetap jangan terlalu padat

## 33. Animasi

Animasi harus minimal.

### Yang disarankan

- transisi page halus
- fade-in ringan pada card
- loading shimmer
- animasi button press kecil

### Yang tidak disarankan

- animasi berputar terlalu banyak
- parallax berlebihan
- efek visual yang tidak menambah fungsi

## 34. Dark Mode

Opsional.

Kalau diaktifkan, tetap jaga palet minimal dan netral.
Namun untuk fase awal, light mode saja sudah cukup agar konsisten dan cepat selesai.

## 35. Token UI Yang Disarankan

### Spacing

- xs: 4
- sm: 8
- md: 12
- lg: 16
- xl: 24

### Radius

- sm: 8
- md: 14
- lg: 20

### Font weight

- regular
- medium
- semi-bold

### Button height

- primary: 48-52 px
- small: 40-44 px

## 36. Rekomendasi Komponen Reusable

Komponen yang sebaiknya dibuat sekali dan dipakai ulang:

- AppScaffold
- PrimaryButton
- SecondaryButton
- AppTextField
- SearchFilterChip
- StatusBadge
- BookingCard
- ScheduleCard
- NewsCard
- FleetCard
- SeatTile
- PaymentMethodCard
- EmptyStateView
- ErrorStateView
- LoadingShimmer

## 37. Arah UI Yang Harus Dihindari

- terlalu banyak warna kontras
- gradien besar tanpa fungsi
- border tebal di semua komponen
- icon campur-aduk dari banyak set
- card terlalu kecil dan penuh teks
- tombol berbeda gaya dalam satu layar

## 38. Kesimpulan

Desain Flutter yang paling cocok untuk TunggalJayaTransport adalah desain modern minimal, dengan warna terbatas, navigasi sederhana, CTA yang jelas, dan komponen yang konsisten.

Fokus utamanya adalah kecepatan pakai dan keterbacaan, terutama untuk search, booking kursi, pembayaran, dan riwayat tiket.

Kalau dokumen ini diikuti, UI mobile akan terasa kekinian tetapi tetap rapi, profesional, dan tidak berlebihan.
