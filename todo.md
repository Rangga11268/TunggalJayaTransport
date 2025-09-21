## TODO LIST

-- fix bagian routes yang jadwal nya masih belum sesuai


## SUMMARRY
Ringkasan Perbaikan dan Peningkatan Sistem Jadwal

  Permasalahan yang Ditemukan
   1. Method `getNextAvailableDate()` tidak ditemukan di model Schedule, menyebabkan error sistem
   2. Logika penjadwalan mingguan tidak konsisten - jadwal tidak muncul dengan benar di minggu berikutnya
   3. Kurangnya fitur jadwal harian yang berulang - tidak ada opsi untuk jadwal yang tersedia setiap hari
   4. Filter pencarian jadwal tidak akurat - tidak menangani semua jenis jadwal dengan benar
   5. Tiket masih bisa dipesan setelah bus berangkat
   6. Admin tidak bisa menghapus jadwal harian yang sudah berangkat atau membuat jadwal untuk hari berikutnya
   7. Ketidakjelasan tentang zona waktu dalam pengecekan jadwal keberangkatan
   8. Waktu ditampilkan dalam format UTC di frontend yang membingungkan pengguna

  Perbaikan dan Peningkatan yang Dilakukan

  1. Perbaikan Model Schedule
   - Menambahkan method `getNextAvailableDate()` yang hilang untuk menghitung tanggal keberangkatan mingguan berikutnya
   - Memperbaiki method `getActualDepartureTime()` dan `getActualArrivalTime()` untuk menangani semua jenis jadwal (mingguan, harian
     berulang, dan harian)
   - Menambahkan field `is_daily` untuk mendukung jadwal harian yang berulang
   - Memperbaiki method `hasDeparted()` untuk mengecek ketersediaan semua jenis jadwal
   - Menambahkan scope `dailyRecurring()` untuk memfilter jadwal harian yang berulang
   - Menambahkan method `getDepartureTimeWIB()` dan `getArrivalTimeWIB()` untuk konversi waktu ke zona waktu lokal

  2. Penambahan Fitur Jadwal Harian yang Berulang
   - Jadwal yang tersedia setiap hari dengan waktu keberangkatan yang sama
   - Logika penanganan waktu yang cerdas:
     - Jika waktu keberangkatan hari ini belum lewat → tampilkan hari ini
     - Jika waktu keberangkatan hari ini sudah lewat → tampilkan besok
   - Integrasi penuh dengan sistem - bekerja dengan pencarian, booking, dan tampilan

  3. Perbaikan Logika Penjadwalan Mingguan
   - Perhitungan tanggal yang akurat:
     - Jika hari ini adalah hari jadwal dan waktu keberangkatan belum lewat → hari ini
     - Jika hari ini adalah hari jadwal dan waktu keberangkatan sudah lewat → minggu depan
     - Jika hari ini bukan hari jadwal → kemunculan berikutnya
   - Jadwal mingguan sekarang muncul dengan benar di minggu berikutnya

  4. Perbaikan Pencarian Jadwal
   - Filter tanggal yang bekerja untuk semua jenis jadwal
   - Penggunaan method `getActualDepartureTime()` yang konsisten di seluruh aplikasi
   - Akurasi tinggi dalam menampilkan jadwal yang tersedia

  5. Perbaikan Tampilan dan Antarmuka
   - Pembedaan visual antara jenis jadwal:
     - Ikon hijau untuk jadwal mingguan
     - Ikon kuning untuk jadwal harian berulang
     - Ikon biru untuk jadwal harian
   - Informasi tanggal dan waktu yang akurat untuk semua jenis jadwal
   - Pembaruan semua view untuk menggunakan method yang benar
   - Menambahkan indikasi visual yang jelas untuk jadwal yang sudah berangkat
   - Menampilkan waktu dalam format WIB (Asia/Jakarta) di semua tampilan frontend untuk menghindari kebingungan

  6. Perbaikan Controller dan Sistem
   - Memperbarui ScheduleController untuk menangani pembuatan dan edit semua jenis jadwal
   - Memperbarui BookingController untuk pencarian jadwal yang akurat
   - Menambahkan validasi dan penanganan yang tepat untuk semua jenis jadwal

  7. Perbaikan Sistem Booking untuk Mencegah Pemesanan Setelah Keberangkatan
   - Memperbarui metode `isAvailableForBooking()` untuk mengecek apakah jadwal sudah berangkat
   - Memperbarui controller Booking untuk memastikan pengecekan yang konsisten di semua metode
   - Memperbarui command `ResetDepartedTicketsCommand` untuk membatalkan booking yang belum dibayar ketika jadwal sudah berangkat
   - Menjadwalkan command tersebut untuk dijalankan secara otomatis setiap jam

  8. Peningkatan Fitur Admin untuk Manajemen Jadwal Harian
   - Menambahkan kemampuan untuk menghapus jadwal harian yang sudah berangkat
   - Menambahkan fitur untuk membuat jadwal harian untuk hari berikutnya berdasarkan jadwal harian berulang yang sudah berangkat
   - Menambahkan pengecekan yang ketat untuk mencegah pengeditan jadwal harian yang sudah berangkat

  9. Penjelasan Zona Waktu untuk Pengecekan Jadwal
   - Semua waktu disimpan dalam format UTC di database
   - Waktu dibandingkan dalam zona waktu Asia/Jakarta (WIB) untuk pengecekan keberangkatan
   - Waktu keberangkatan jadwal harian harus disimpan dalam format UTC yang sesuai dengan waktu WIB yang diinginkan
     Contoh: Jika ingin jadwal berangkat pukul 09:00 WIB, waktu harus disimpan sebagai 02:00:00 UTC
   - Waktu ditampilkan dalam format WIB di semua tampilan frontend untuk menghindari kebingungan pengguna

  Hasil Akhir

  Fungsionalitas yang Lebih Baik
   1. Jadwal mingguan sekarang muncul dengan benar di minggu berikutnya
   2. Jadwal harian yang berulang tersedia setiap hari tanpa batas tanggal
   3. Pencarian jadwal bekerja akurat untuk semua jenis jadwal
   4. Tampilan informasi menunjukkan tanggal dan waktu yang benar sesuai konteks
   5. Tiket tidak bisa dipesan lagi setelah bus berangkat
   6. Booking yang belum dibayar akan dibatalkan secara otomatis ketika jadwal sudah berangkat
   7. Admin bisa dengan mudah mengelola jadwal harian yang sudah berangkat
   8. Pengguna tidak bingung dengan format waktu karena semua waktu ditampilkan dalam WIB

  Keuntungan Bisnis
   1. Fleksibilitas tinggi - mendukung berbagai model bisnis transportasi
   2. Pengalaman pengguna yang lebih baik - informasi jadwal yang akurat dan konsisten
   3. Pemeliharaan yang lebih mudah - logika yang terpusat dan konsisten
   4. Skalabilitas - sistem siap dikembangkan lebih lanjut
   5. Pencegahan kesalahan - sistem mencegah pemesanan tiket untuk jadwal yang sudah berangkat
   6. Kemudahan manajemen - admin bisa dengan mudah mengelola jadwal harian yang sudah berangkat
   7. Klarifikasi zona waktu - sistem bekerja dengan konsisten menggunakan zona waktu WIB
   8. Pengalaman pengguna yang lebih baik - waktu ditampilkan dalam format yang mudah dipahami

  Kompatibilitas Penuh
   - Semua fitur lama tetap berfungsi dengan baik
   - Tidak ada perubahan yang merusak (backward compatible)
   - Performa sistem tetap optimal

  Sistem sekarang dapat menangani berbagai skenario dunia nyata dengan akurat, memberikan pengalaman yang jauh lebih baik bagi pengguna
  dan administrator.