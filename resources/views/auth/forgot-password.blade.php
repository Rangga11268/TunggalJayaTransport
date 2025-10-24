<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Development Reset Link Display -->
    @if(session('dev_reset_link'))
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
            <p class="text-sm font-medium text-blue-800">Development Mode:</p>
            <p class="text-sm text-blue-600 mt-1">Password reset link generated for: <strong>{{ session('dev_email') }}</strong></p>
            <p class="text-sm text-blue-600 mt-1">Click the link below to reset your password:</p>
            <a href="{{ session('dev_reset_link') }}" 
               class="mt-2 inline-block px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
               Reset Password
            </a>
            <p class="text-xs text-blue-500 mt-2">Note: This link is only for development purposes</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
    
    <!-- Development Helper -->
    @if(app()->environment('local', 'development'))
    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
        <p class="text-xs text-yellow-800 font-medium">Development Note:</p>
        <p class="text-xs text-yellow-600 mt-1">In development mode, reset links are displayed on this page instead of being sent via email.</p>
    </div>
    @endif
</x-guest-layout>
