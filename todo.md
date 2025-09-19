## TODO LIST

-- fix bagian routes yang jadwal nya masih belum sesuai


## SUMMARRY
Ringkasan Perbaikan dan Peningkatan Sistem Jadwal

  Permasalahan yang Ditemukan
   1. Method `getNextAvailableDate()` tidak ditemukan di model Schedule, menyebabkan error sistem
   2. Logika penjadwalan mingguan tidak konsisten - jadwal tidak muncul dengan benar di minggu berikutnya
   3. Kurangnya fitur jadwal harian yang berulang - tidak ada opsi untuk jadwal yang tersedia setiap hari
   4. Filter pencarian jadwal tidak akurat - tidak menangani semua jenis jadwal dengan benar

  Perbaikan dan Peningkatan yang Dilakukan

  1. Perbaikan Model Schedule
   - Menambahkan method `getNextAvailableDate()` yang hilang untuk menghitung tanggal keberangkatan mingguan berikutnya
   - Memperbaiki method `getActualDepartureTime()` dan `getActualArrivalTime()` untuk menangani semua jenis jadwal (mingguan, harian
     berulang, dan harian)
   - Menambahkan field `is_daily` untuk mendukung jadwal harian yang berulang
   - Memperbaiki method `hasDeparted()` untuk mengecek ketersediaan semua jenis jadwal
   - Menambahkan scope `dailyRecurring()` untuk memfilter jadwal harian yang berulang

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

  6. Perbaikan Controller dan Sistem
   - Memperbarui ScheduleController untuk menangani pembuatan dan edit semua jenis jadwal
   - Memperbarui BookingController untuk pencarian jadwal yang akurat
   - Menambahkan validasi dan penanganan yang tepat untuk semua jenis jadwal

  Hasil Akhir

  Fungsionalitas yang Lebih Baik
   1. Jadwal mingguan sekarang muncul dengan benar di minggu berikutnya
   2. Jadwal harian yang berulang tersedia setiap hari tanpa batas tanggal
   3. Pencarian jadwal bekerja akurat untuk semua jenis jadwal
   4. Tampilan informasi menunjukkan tanggal dan waktu yang benar sesuai konteks

  Keuntungan Bisnis
   1. Fleksibilitas tinggi - mendukung berbagai model bisnis transportasi
   2. Pengalaman pengguna yang lebih baik - informasi jadwal yang akurat dan konsisten
   3. Pemeliharaan yang lebih mudah - logika yang terpusat dan konsisten
   4. Skalabilitas - sistem siap dikembangkan lebih lanjut

  Kompatibilitas Penuh
   - Semua fitur lama tetap berfungsi dengan baik
   - Tidak ada perubahan yang merusak (backward compatible)
   - Performa sistem tetap optimal

  Sistem sekarang dapat menangani berbagai skenario dunia nyata dengan akurat, memberikan pengalaman yang jauh lebih baik bagi pengguna
  dan administrator.