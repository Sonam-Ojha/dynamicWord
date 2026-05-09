<x-guest-layout>
    <h1 class="auth-title">Create account</h1>
    <p class="auth-subtitle">Get started with Bank Doc Gen in seconds.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="auth-field">
            <label for="name" class="auth-label">Full Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                   autocomplete="name" placeholder="Your name" class="auth-input">
            @error('name') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="auth-field">
            <label for="email" class="auth-label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                   autocomplete="username" placeholder="you@example.com" class="auth-input">
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="auth-field">
            <label for="password" class="auth-label">Password</label>
            <input id="password" name="password" type="password" required
                   autocomplete="new-password" placeholder="Min 8 characters" class="auth-input">
            @error('password') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="auth-field">
            <label for="password_confirmation" class="auth-label">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   autocomplete="new-password" placeholder="Re-enter password" class="auth-input">
            @error('password_confirmation') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="auth-button" style="margin-top: 0.5rem;">
            <span>Create Account</span>
            <span style="font-size: 1.1em; line-height: 1;">→</span>
        </button>
    </form>

    <div class="auth-divider-text">
        Already have an account? <a href="{{ route('login') }}">Login</a>
    </div>
</x-guest-layout>
