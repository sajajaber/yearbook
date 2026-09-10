<x-guest-layout>
    <div class="guest-login">
        <h1 class="guest-heading">Welcome back</h1>
        <p class="guest-subtitle">Sign in to the Digital Yearbook administration portal.</p>

        <x-auth-session-status class="guest-status" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="guest-form">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="guest-error" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="guest-error" />
            </div>

            <div class="guest-actions">
                <label for="remember_me" class="guest-remember">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="guest-forgot" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <button type="submit" class="guest-button">
                {{ __('Log in') }}
            </button>
        </form>
    </div>
</x-guest-layout>
