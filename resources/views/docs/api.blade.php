<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - Tunggal Jaya Transport</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        code, pre { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-200">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside class="w-80 bg-slate-900 border-r border-slate-800 flex flex-col shrink-0 overflow-y-auto">
            <div class="p-6 border-b border-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-600/30">
                    <i class="fas fa-bus"></i>
                </div>
                <div>
                    <h1 class="font-bold text-white text-base leading-tight">Tunggal Jaya Transport</h1>
                    <p class="text-xs text-blue-400 font-semibold mt-0.5">REST API v1.0 Docs</p>
                </div>
            </div>

            <div class="p-4 border-b border-slate-800 bg-slate-900/50">
                <a href="/docs/postman_collection.json" download class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-600 hover:bg-orange-500 text-white rounded-xl font-semibold text-xs transition-all shadow-md shadow-orange-600/20">
                    <i class="fas fa-download"></i> Download Postman Collection
                </a>
            </div>

            <nav class="p-4 space-y-6 text-sm">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-2 px-3">Overview</span>
                    <a href="#getting-started" class="flex items-center gap-2 px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white font-medium transition-colors">
                        <i class="fas fa-play text-blue-400 text-xs w-4"></i> Getting Started
                    </a>
                    <a href="#authentication" class="flex items-center gap-2 px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white font-medium transition-colors">
                        <i class="fas fa-key text-[#10207a] text-xs w-4"></i> Autentikasi Token
                    </a>
                </div>

                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-2 px-3">Authentication & Profile</span>
                    <div class="space-y-1">
                        <a href="#ep-register" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>Register User</span>
                            <span class="px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold">POST</span>
                        </a>
                        <a href="#ep-login" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>Login User</span>
                            <span class="px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold">POST</span>
                        </a>
                        <a href="#ep-send-otp" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>Kirim OTP (WA)</span>
                            <span class="px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold">POST</span>
                        </a>
                        <a href="#ep-verify-otp" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>Verifikasi OTP</span>
                            <span class="px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold">POST</span>
                        </a>
                        <a href="#ep-profile" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>User Profile</span>
                            <span class="px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold">GET</span>
                        </a>
                    </div>
                </div>

                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-2 px-3">Master & Pencarian</span>
                    <div class="space-y-1">
                        <a href="#ep-routes" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>Master Rute</span>
                            <span class="px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold">GET</span>
                        </a>
                        <a href="#ep-schedules" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>Cari Jadwal Bus</span>
                            <span class="px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold">GET</span>
                        </a>
                        <a href="#ep-buses" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>Daftar Armada</span>
                            <span class="px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold">GET</span>
                        </a>
                    </div>
                </div>

                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-2 px-3">Booking Tiket AKAP</span>
                    <div class="space-y-1">
                        <a href="#ep-create-booking" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>Buat Booking Tiket</span>
                            <span class="px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold">POST</span>
                        </a>
                        <a href="#ep-select-seats" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>Kunci Kursi</span>
                            <span class="px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold">POST</span>
                        </a>
                        <a href="#ep-process-payment" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>Bayar Midtrans</span>
                            <span class="px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold">POST</span>
                        </a>
                    </div>
                </div>

                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-2 px-3">Sewa Pariwisata</span>
                    <div class="space-y-1">
                        <a href="#ep-request-charter" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>Pengajuan Sewa Bus</span>
                            <span class="px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold">POST</span>
                        </a>
                        <a href="#ep-charter-history" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 text-xs font-medium">
                            <span>Riwayat Sewa Bus</span>
                            <span class="px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold">GET</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Content View -->
        <main class="flex-1 overflow-y-auto bg-slate-950 p-8 md:p-12 space-y-12">
            
            <!-- Header Section -->
            <section id="getting-started" class="border-b border-slate-800 pb-8">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 text-xs font-bold border border-blue-500/20">Official Developer Docs</span>
                    <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-bold border border-emerald-500/20">Base URL: http://localhost:8000/api</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight mb-3">Tunggal Jaya Transport REST API</h1>
                <p class="text-slate-400 text-base max-w-3xl leading-relaxed">
                    Dokumentasi API lengkap untuk integrasi pemesanan tiket bus AKAP, pengajuan sewa bus pariwisata (charter), autentikasi OTP WhatsApp, hingga gateway pembayaran Midtrans Snap.
                </p>
            </section>

            <!-- Authentication Guide Section -->
            <section id="authentication" class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 space-y-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-shield-alt text-[#10207a]"></i> Autentikasi API (Bearer Token)
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Setiap endpoint terlindungi memerlukan header HTTP <code class="text-amber-400 bg-slate-800 px-2 py-0.5 rounded">Authorization</code> menggunakan format <code class="text-amber-400 bg-slate-800 px-2 py-0.5 rounded">Bearer {token}</code> yang diperoleh dari response endpoint login/register.
                </p>
                <div class="bg-slate-950 p-4 rounded-xl border border-slate-800 font-mono text-xs text-slate-300">
                    <span class="text-slate-500">// Contoh Header Request</span><br>
                    <span class="text-blue-400">Authorization</span>: Bearer 1|A8f9D2e3XyZ...<br>
                    <span class="text-blue-400">Accept</span>: application/json<br>
                    <span class="text-blue-400">Content-Type</span>: application/json
                </div>
            </section>

            <!-- Endpoints Detail Cards -->
            <div class="space-y-12">
                
                <!-- Register Endpoint -->
                <section id="ep-register" class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-6 md:p-8 space-y-6">
                    <div class="flex items-center justify-between flex-wrap gap-4 border-b border-slate-800 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-400 font-extrabold text-sm border border-emerald-500/30">POST</span>
                            <h3 class="text-lg font-bold text-white font-mono">/api/auth/register</h3>
                        </div>
                        <span class="text-xs text-slate-400">Public Endpoint</span>
                    </div>
                    <p class="text-slate-300 text-sm">Pendaftaran akun penumpang baru.</p>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Request Body (JSON)</h4>
                            <pre class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-xs text-emerald-300 overflow-x-auto">
{
  "name": "Ahmad Budi",
  "email": "budi@example.com",
  "phone": "081234567890",
  "password": "Password123!",
  "password_confirmation": "Password123!"
}</pre>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Response 201 Created</h4>
                            <pre class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-xs text-slate-300 overflow-x-auto">
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "user": { "id": 5, "name": "Ahmad Budi", "email": "budi@example.com" },
    "token": "1|A8f9D2e3XyZ...",
    "needs_verification": true
  }
}</pre>
                        </div>
                    </div>
                </section>

                <!-- Search Schedules Endpoint -->
                <section id="ep-schedules" class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-6 md:p-8 space-y-6">
                    <div class="flex items-center justify-between flex-wrap gap-4 border-b border-slate-800 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 rounded-lg bg-blue-500/20 text-blue-400 font-extrabold text-sm border border-blue-500/30">GET</span>
                            <h3 class="text-lg font-bold text-white font-mono">/api/schedules</h3>
                        </div>
                        <span class="text-xs text-slate-400">Public Endpoint</span>
                    </div>
                    <p class="text-slate-300 text-sm">Pencarian keberangkatan bus AKAP berdasarkan rute & tanggal.</p>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Query Parameters</h4>
                            <div class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-xs space-y-2 text-slate-300 font-mono">
                                <div><span class="text-blue-400">origin</span>: Kuningan</div>
                                <div><span class="text-blue-400">destination</span>: Jakarta</div>
                                <div><span class="text-blue-400">date</span>: 2026-08-05</div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Response 200 OK</h4>
                            <pre class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-xs text-slate-300 overflow-x-auto">
{
  "success": true,
  "data": [
    {
      "id": 12,
      "departure_time": "07:00",
      "price": 160000,
      "available_seats": 28,
      "route": { "origin": "Kuningan", "destination": "Jakarta" },
      "bus": { "name": "Shantika Exec", "capacity": 32 }
    }
  ]
}</pre>
                        </div>
                    </div>
                </section>

                <!-- Create Booking Endpoint -->
                <section id="ep-create-booking" class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-6 md:p-8 space-y-6">
                    <div class="flex items-center justify-between flex-wrap gap-4 border-b border-slate-800 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-400 font-extrabold text-sm border border-emerald-500/30">POST</span>
                            <h3 class="text-lg font-bold text-white font-mono">/api/bookings</h3>
                        </div>
                        <span class="text-xs text-amber-400 font-semibold"><i class="fas fa-lock"></i> Auth Required</span>
                    </div>
                    <p class="text-slate-300 text-sm">Membuat pesanan tiket bus baru.</p>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Request Body (JSON)</h4>
                            <pre class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-xs text-emerald-300 overflow-x-auto">
{
  "schedule_id": 12,
  "booking_date": "2026-08-05",
  "passenger_name": "Ahmad Budi",
  "passenger_phone": "081234567890",
  "passenger_email": "budi@example.com",
  "seat_numbers": ["1A", "1B"],
  "promo_code": "DISCON10"
}</pre>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Response 201 Created</h4>
                            <pre class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-xs text-slate-300 overflow-x-auto">
{
  "success": true,
  "message": "Pemesanan berhasil dibuat",
  "data": {
    "id": 88,
    "booking_code": "TJT-88X9A",
    "total_price": 288000,
    "payment_status": "pending"
  }
}</pre>
                        </div>
                    </div>
                </section>

                <!-- Process Payment Endpoint -->
                <section id="ep-process-payment" class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-6 md:p-8 space-y-6">
                    <div class="flex items-center justify-between flex-wrap gap-4 border-b border-slate-800 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-400 font-extrabold text-sm border border-emerald-500/30">POST</span>
                            <h3 class="text-lg font-bold text-white font-mono">/api/bookings/process-payment</h3>
                        </div>
                        <span class="text-xs text-amber-400 font-semibold"><i class="fas fa-lock"></i> Auth Required</span>
                    </div>
                    <p class="text-slate-300 text-sm">Membuat Midtrans Snap Token untuk pembayaran.</p>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Request Body (JSON)</h4>
                            <pre class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-xs text-emerald-300 overflow-x-auto">
{
  "booking_id": 88,
  "payment_method": "gopay"
}</pre>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Response 200 OK</h4>
                            <pre class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-xs text-slate-300 overflow-x-auto">
{
  "status": "success",
  "snap_token": "4956d782-b2fa-4a6c-...",
  "redirect_url": "https://app.sandbox.midtrans.com/snap/v2/vtweb/..."
}</pre>
                        </div>
                    </div>
                </section>

                <!-- Request Charter Endpoint -->
                <section id="ep-request-charter" class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-6 md:p-8 space-y-6">
                    <div class="flex items-center justify-between flex-wrap gap-4 border-b border-slate-800 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-400 font-extrabold text-sm border border-emerald-500/30">POST</span>
                            <h3 class="text-lg font-bold text-white font-mono">/api/charter/request</h3>
                        </div>
                        <span class="text-xs text-amber-400 font-semibold"><i class="fas fa-lock"></i> Auth Required</span>
                    </div>
                    <p class="text-slate-300 text-sm">Pengajuan permohonan sewa bus pariwisata.</p>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Request Body (JSON)</h4>
                            <pre class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-xs text-emerald-300 overflow-x-auto">
{
  "pickup_location": "Kuningan, Jawa Barat",
  "pickup_address": "Jl. Ahmad Yani No. 45",
  "destination": "Bali, Nusa Tenggara",
  "destination_address": "Pantai Kuta",
  "pickup_date": "2026-08-10",
  "pickup_time": "08:00",
  "return_date": "2026-08-15",
  "bus_requests": [
    { "type": "Leg Rest", "count": 1 }
  ],
  "notes": "Mohon selimut steril"
}</pre>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Response 201 Created</h4>
                            <pre class="bg-slate-950 p-4 rounded-xl border border-slate-800 text-xs text-slate-300 overflow-x-auto">
{
  "success": true,
  "message": "Permintaan sewa bus berhasil diajukan",
  "data": {
    "id": 14,
    "charter_code": "CHRT-QS5G7WMK",
    "status": "pending"
  }
}</pre>
                        </div>
                    </div>
                </section>

            </div>

            <!-- Footer -->
            <footer class="pt-8 border-t border-slate-800 text-center text-xs text-slate-500">
                <p>&copy; 2026 Tunggal Jaya Transport. All rights reserved.</p>
            </footer>
        </main>
    </div>
</body>
</html>
