<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bank Doc Gen') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Figtree', system-ui, sans-serif;
            margin: 0;
            background-color: #f8fafc;
            background-image:
                radial-gradient(circle at 20% 0%, rgba(99, 102, 241, 0.10) 0px, transparent 40%),
                radial-gradient(circle at 80% 100%, rgba(16, 185, 129, 0.08) 0px, transparent 40%);
            min-height: 100vh;
            color: #1e293b;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            margin-bottom: 1.75rem;
        }

        .auth-logo-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white;
            font-weight: 700;
            font-size: 20px;
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.3);
        }

        .auth-logo-text { line-height: 1.2; }
        .auth-logo-name { font-weight: 700; font-size: 18px; color: #0f172a; }
        .auth-logo-tag { font-size: 12px; color: #64748b; margin-top: 2px; }

        .auth-card {
            width: 100%;
            max-width: 26rem;
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08);
        }

        .auth-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0 0 0.25rem; letter-spacing: -0.02em; }
        .auth-subtitle { font-size: 0.875rem; color: #64748b; margin: 0 0 1.75rem; }

        .auth-field { margin-bottom: 1.125rem; }
        .auth-field-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.375rem; }

        .auth-label { display: block; font-size: 0.8125rem; font-weight: 600; color: #334155; margin-bottom: 0.375rem; }
        .auth-link-small { font-size: 0.75rem; color: #4f46e5; text-decoration: none; font-weight: 500; }
        .auth-link-small:hover { color: #4338ca; }

        .auth-input {
            display: block;
            width: 100%;
            padding: 0.625rem 0.875rem;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 0.875rem;
            font-family: inherit;
            color: #0f172a;
            background: white;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .auth-input::placeholder { color: #94a3b8; }
        .auth-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18);
        }

        .auth-error { color: #dc2626; font-size: 0.75rem; margin: 0.375rem 0 0; }

        .auth-checkbox-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            color: #475569;
            margin: 0.5rem 0 1rem;
        }
        .auth-checkbox-row input[type="checkbox"] {
            width: 16px; height: 16px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            accent-color: #4f46e5;
        }

        .auth-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            height: 42px;
            padding: 0 1rem;
            border-radius: 6px;
            background-color: #4f46e5;
            color: white;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            border: 1px solid #4338ca;
            cursor: pointer;
            transition: background-color 0.15s;
            line-height: 1;
        }
        .auth-button:hover { background-color: #4338ca; }
        .auth-button:active { background-color: #3730a3; }
        .auth-button:focus { outline: 2px solid #c7d2fe; outline-offset: 2px; }

        .auth-divider-text {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            font-size: 0.8125rem;
            color: #64748b;
        }
        .auth-divider-text a {
            color: #4f46e5;
            font-weight: 600;
            text-decoration: none;
        }
        .auth-divider-text a:hover { color: #4338ca; }

        .auth-footer {
            margin-top: 1.5rem;
            font-size: 0.75rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .auth-footer-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #10b981;
        }
    </style>
</head>
<body>

    <div class="auth-wrapper">

        <a href="/" class="auth-logo">
            <span class="auth-logo-mark">D</span>
            <div class="auth-logo-text">
                <div class="auth-logo-name">Bank Doc Gen</div>
                <div class="auth-logo-tag">Dynamic Document System</div>
            </div>
        </a>

        <div class="auth-card">
            {{ $slot }}
        </div>

        <div class="auth-footer">
            <span class="auth-footer-dot"></span>
            Secured with role-based access · &copy; {{ date('Y') }} {{ config('app.name') }}
        </div>
    </div>

</body>
</html>
