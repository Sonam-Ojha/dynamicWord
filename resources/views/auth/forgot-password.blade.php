<x-guest-layout>
    <h1 class="auth-title">Forgot password?</h1>
    <p class="auth-subtitle">Enter your email and we'll send you a reset link.</p>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="auth-field">
            <label for="email" class="auth-label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   placeholder="you@example.com" class="auth-input">
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="auth-button" style="margin-top: 0.5rem;">
            <span>Send Reset Link</span>
            <span style="font-size: 1.1em; line-height: 1;">→</span>
        </button>
    </form>

    <div class="auth-divider-text">
        Remembered it? <a href="{{ route('login') }}">Back to login</a>
    </div>
</x-guest-layout>
