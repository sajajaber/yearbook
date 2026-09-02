<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Yearbook') }} - Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <main class="login-page">
        <div class="login-panel">

            <div class="login-header">
                <p class="eyebrow">Yearbook Administration</p>

                <h1>Welcome Back</h1>

                <p class="login-description">
                    Sign in to access the yearbook administration portal.
                </p>
            </div>

            @if (session('status'))
            <div class="notice notice-success">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <div class="form-field">
                    <label for="email">Email</label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username">

                    @error('email')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="password">Password</label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password">

                    @error('password')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="login-options">
                    <label for="remember_me" class="remember-option">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember">

                        <span>Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="login-forgot">
                        Forgot your password?
                    </a>
                    @endif
                </div>

                <button type="submit" class="button button-navy login-button">
                    Log in
                </button>
            </form>

        </div>
    </main>
</body>

</html>


<style>
    /* =========================
   Login
   ========================= */

    .login-page {
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: var(--space-page);
        background: var(--paper);
    }

    .login-panel {
        width: min(100%, 460px);
        background: var(--white);
        border: 1px solid var(--line);
        padding: 36px;
    }

    .login-header {
        margin-bottom: 28px;
        padding-bottom: 22px;
        border-bottom: 1px solid var(--line);
    }

    .login-header h1 {
        margin: 0;
        color: var(--ink);
        font-family: "Merriweather", Georgia, serif;
        font-size: 32px;
        font-weight: 700;
    }

    .login-description {
        margin: 10px 0 0;
        color: var(--ink-soft);
        font-size: 12px;
        line-height: 1.6;
    }

    .login-form {
        display: grid;
        gap: 18px;
    }

    .login-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .remember-option {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--ink-soft);
        cursor: pointer;
        font-size: 11px;
        font-weight: 600;
    }

    .remember-option input {
        width: 15px;
        height: 15px;
        margin: 0;
        accent-color: var(--ink);
    }

    .login-forgot {
        color: var(--ink-soft);
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
    }

    .login-forgot:hover {
        color: var(--ink);
        text-decoration: underline;
    }

    .login-button {
        width: 100%;
        margin-top: 4px;
    }

    @media (max-width: 480px) {
        .login-page {
            padding: 20px;
        }

        .login-panel {
            padding: 26px 22px;
        }

        .login-options {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>