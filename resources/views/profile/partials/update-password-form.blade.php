<section>
    <div class="form-section-heading">
        <p class="eyebrow">Security</p>

        <h2>
            {{ __('Update Password') }}
        </h2>

        <p class="panel-meta">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="form-grid">
        @csrf
        @method('put')

        <div class="form-field">
            <label
                for="update_password_current_password"
                class="form-field-label">
                {{ __('Current Password') }}
            </label>

            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                autocomplete="current-password">

            @if ($errors->updatePassword->has('current_password'))
            <span class="form-error">
                {{ $errors->updatePassword->first('current_password') }}
            </span>
            @endif
        </div>

        <div class="form-field">
            <label
                for="update_password_password"
                class="form-field-label">
                {{ __('New Password') }}
            </label>

            <input
                id="update_password_password"
                name="password"
                type="password"
                autocomplete="new-password">

            @if ($errors->updatePassword->has('password'))
            <span class="form-error">
                {{ $errors->updatePassword->first('password') }}
            </span>
            @endif
        </div>

        <div class="form-field">
            <label
                for="update_password_password_confirmation"
                class="form-field-label">
                {{ __('Confirm Password') }}
            </label>

            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password">

            @if ($errors->updatePassword->has('password_confirmation'))
            <span class="form-error">
                {{ $errors->updatePassword->first('password_confirmation') }}
            </span>
            @endif
        </div>

        <div class="form-actions">
            <button type="submit" class="button button-navy">
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
            <span
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="notice-success">
                {{ __('Password updated successfully.') }}
            </span>
            @endif
        </div>
    </form>

</section>