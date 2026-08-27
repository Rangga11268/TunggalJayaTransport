<!-- Tombol Aksi with Enhanced UX -->
<div class="flex flex-col gap-3 mt-4 sm:mt-6 mobile-button-group">
    <!-- Primary Action: Download PDF (Most Important) -->
    <a href="{{ route('frontend.booking.download-ticket', $bookingId ?? 0) }}" 
       id="downloadPdfBtn"
       class="flex items-center justify-center px-4 py-3.5 sm:px-6 sm:py-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] text-sm sm:text-base mobile-action-button group relative overflow-hidden">
        <!-- Shimmer Effect -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
        
        <svg class="mr-2 h-5 w-5 sm:h-6 sm:w-6 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <span class="relative z-10">Download E-Tiket PDF</span>
        <span class="ml-2 px-2 py-0.5 bg-yellow-400 text-yellow-900 text-xs font-bold rounded-full relative z-10 animate-pulse">BARU</span>
    </a>

    <!-- Secondary Actions -->
    <div class="grid grid-cols-2 gap-2 sm:gap-3">
        <!-- Print Button (Desktop Only) -->
        <button onclick="window.print()" 
                class="hidden sm:flex items-center justify-center px-4 py-2.5 bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold rounded-lg transition duration-200 text-sm mobile-action-button">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak
        </button>

        <!-- Share via WhatsApp -->
        <button onclick="shareViaWhatsApp()" 
                class="flex items-center justify-center px-4 py-2.5 bg-white border-2 border-green-600 text-green-600 hover:bg-green-50 font-semibold rounded-lg transition duration-200 text-sm mobile-action-button">
            <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Share
        </button>

        <!-- Share via Email (Desktop) / Direct Share (Mobile) -->
        <button onclick="shareTicket()" 
                class="sm:flex items-center justify-center px-4 py-2.5 bg-white border-2 border-purple-600 text-purple-600 hover:bg-purple-50 font-semibold rounded-lg transition duration-200 text-sm mobile-action-button col-span-2 sm:col-span-1">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
            </svg>
            <span class="hidden sm:inline">Kirim Email</span>
            <span class="sm:hidden">Bagikan</span>
        </button>
    </div>
</div>

<!-- Toast Notification (Hidden by default) -->
<div id="downloadToast" class="hidden fixed bottom-4 right-4 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
    <div class="flex items-center">
        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="font-medium">Tiket berhasil diunduh!</span>
    </div>
</div>

<script>
    // Show loading state on download
    document.getElementById('downloadPdfBtn').addEventListener('click', function(e) {
        const btn = this;
        const originalHTML = btn.innerHTML;
        
        // Show loading state
        btn.classList.add('opacity-75', 'cursor-wait');
        btn.innerHTML = `
            <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mengunduh...
        `;
        
        // Show success toast after delay
        setTimeout(() => {
            btn.classList.remove('opacity-75', 'cursor-wait');
            btn.innerHTML = originalHTML;
            
            const toast = document.getElementById('downloadToast');
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }, 1500);
    });

    // Share via WhatsApp
    function shareViaWhatsApp() {
        const text = encodeURIComponent(`🎫 Tiket Bus Saya - Tunggal Jaya Transport\n\n📋 Kode Booking: {{ $bookingCode }}\n🚌 {{ $origin }} → {{ $destination }}\n📅 {{ $departureDate }} | 🕐 {{ $departureTime }}\n\n🔗 Lihat detail: ${window.location.href}`);
        window.open(`https://wa.me/?text=${text}`, '_blank');
    }

    // Share ticket (Email on desktop, native share on mobile)
    function shareTicket() {
        if (navigator.share) {
            // Use native share API on mobile
            navigator.share({
                title: 'Tiket Bus - Tunggal Jaya Transport',
                text: `Kode Booking: {{ $bookingCode }}\n{{ $origin }} → {{ $destination }}\nTanggal: {{ $departureDate }}`,
                url: window.location.href
            }).catch(err => console.log('Share failed:', err));
        } else {
            // Email fallback for desktop
            const subject = encodeURIComponent('Tiket Bus Saya - Tunggal Jaya Transport');
            const body = encodeURIComponent(`Berikut adalah detail tiket bus saya:\n\nKode Booking: {{ $bookingCode }}\nRute: {{ $origin }} → {{ $destination }}\nTanggal: {{ $departureDate }}\nJam Keberangkatan: {{ $departureTime }}\n\nLink: ${window.location.href}`);
            window.location.href = `mailto:?subject=${subject}&body=${body}`;
        }
    }
</script>
