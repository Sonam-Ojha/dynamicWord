<x-guest-layout>
    <h1 class="auth-title">Welcome back</h1>
    <p class="auth-subtitle">Login to your account to continue.</p>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="auth-field">
            <label for="email" class="auth-label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   autocomplete="username" placeholder="you@example.com" class="auth-input">
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="auth-field">
            <div class="auth-field-row">
                <label for="password" class="auth-label" style="margin-bottom:0;">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link-small">Forgot?</a>
                @endif
            </div>
            <input id="password" name="password" type="password" required
                   autocomplete="current-password" placeholder="••••••••" class="auth-input">
            @error('password') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <label for="remember_me" class="auth-checkbox-row">
            <input id="remember_me" type="checkbox" name="remember">
            <span>Remember me on this device</span>
        </label>

        <button type="submit" class="auth-button">
            <span>Login</span>
            <span style="font-size: 1.1em; line-height: 1;">→</span>
        </button>
    </form>

    @if (Route::has('register'))
        <div class="auth-divider-text">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </div>
    @endif
</x-guest-layout>
