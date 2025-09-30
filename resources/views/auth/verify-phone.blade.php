<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Terima kasih telah mendaftar! Sebelum memulai, apakah nomor telepon Anda sudah benar? Jika belum, silakan perbarui dan verifikasi nomor Anda dengan kode OTP yang akan dikirim.') }}
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda saat pendaftaran.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.phone.send') }}">
            @csrf

            <div>
                <x-input-label for="phone" :value="__('Nomor Telepon')" />
                <x-text-input 
                    id="phone" 
                    class="block mt-1 w-full" 
                    type="tel" 
                    name="phone" 
                    :value="old('phone', auth()->user()->phone)" 
                    required 
                    autofocus 
                    autocomplete="tel" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-4">
                <x-primary-button>
                    {{ __('Kirim Kode OTP') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Form untuk verifikasi OTP -->
    <div class="mt-6">
        <form method="POST" action="{{ route('verification.phone.verify') }}">
            @csrf

            <div>
                <x-input-label for="otp" :value="__('Kode OTP')" />
                <x-text-input 
                    id="otp" 
                    class="block mt-1 w-full" 
                    type="text" 
                    name="otp" 
                    required 
                    maxlength="6" />
                <x-input-error :messages="$errors->get('otp')" class="mt-2" />
                
                @if(session('status'))
                    <div class="text-sm text-green-600 dark:text-green-400 mt-2">
                        {{ session('status') }}
                    </div>
                @endif
            </div>

            <div class="flex justify-between items-center mt-4">
                <button type="submit" class="ms-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Verifikasi OTP') }}
                </button>
                
                <form method="POST" action="{{ route('verification.phone.resend') }}" class="inline">
                    @csrf
                    <button type="submit" class="ms-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Kirim Ulang OTP') }}
                    </button>
                </form>
            </div>
        </form>
    </div>
    
    <!-- Tampilkan OTP untuk keperluan development -->
    @if(isset($debugOtp) && $debugOtp)
        <div class="mt-6 p-4 bg-yellow-100 border border-yellow-300 rounded-lg">
            <p class="text-sm text-yellow-700">
                <strong>DEVELOPMENT MODE:</strong> Kode OTP untuk debugging: <span class="font-bold">{{ $debugOtp }}</span>
            </p>
        </div>
    @endif
    
    <div class="mt-6">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Setelah verifikasi selesai, Anda bisa melanjutkan ke dashboard.
        </p>
    </div>
</x-guest-layout>