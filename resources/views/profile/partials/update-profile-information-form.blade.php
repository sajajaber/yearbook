<section>
    <div class="form-section-heading">
        <p class="eyebrow">Account</p>

        <h2>
            {{ __('Profile Information') }}
        </h2>

        <p class="panel-meta">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="form-grid">
        @csrf
        @method('patch')

        <div class="form-field">
            <label for="name" class="form-field-label">
                {{ __('Name') }}
            </label>

            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name">

            @error('name')
            <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-field">
            <label for="email" class="form-field-label">
                {{ __('Email') }}
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username">

            @error('email')
            <span class="form-error">{{ $message }}</span>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="notice notice-error">
                <strong>{{ __('Your email address is unverified.') }}</strong>

                <button
                    form="send-verification"
                    class="text-button"
                    type="submit">
                    {{ __('Re-send verification email') }}
                </button>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <div class="form-actions">
            <button type="submit" class="button button-navy">
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
            <span
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="notice-success">
                {{ __('Saved successfully.') }}
            </span>
            @endif
        </div>
    </form>

</section>