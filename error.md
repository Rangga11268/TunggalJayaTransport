
-- buat responsive ukuran sm 420px kebawah di bagian detail routes khusus nya di bagian Available Schedules agar bisa di buat lebih baik 

-- dan ada minor juga ketika di ukuran 760 - 1024 di bagian bookings lebih tepatnya Search for Schedules tombol search nya agak kurang pas 

-- fix logic harusnya ketika bus sudah berangkat tiket nya ada lagi kesokan harinya dan seat nya ke reset kembali

-- logic untuk jadwal yang setiap satu minggu sekali juga buruk sepertinya akan saya hapus

-- masih ada masalah di Search for Schedules home bookings frontend

-- BUG: Tiket weekly seat berkurang untuk hari berikutnya meskipun customer memesan tiket untuk hari ini. Tiket yang seharusnya tersedia untuk hari esok berkurang ketika ada pemesanan untuk hari ini. Ini menunjukkan bahwa sistem tidak membedakan antara seat yang dipesan untuk hari ini dan seat yang tersedia untuk hari esok. Perlu dilakukan pemeriksaan pada logika perhitungan seat availability berdasarkan tanggal pemesanan.