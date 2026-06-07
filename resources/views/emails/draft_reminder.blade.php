<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draft Reminder</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f6f9; color: #333; }
        .wrapper { max-width: 620px; margin: 30px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #1e40af, #3b82f6); padding: 30px 35px; text-align: center; }
        .header h1 { color: #fff; font-size: 22px; font-weight: 700; }
        .header p { color: #bfdbfe; font-size: 13px; margin-top: 5px; }
        .body { padding: 30px 35px; }
        .greeting { font-size: 16px; color: #1e3a8a; font-weight: 600; margin-bottom: 12px; }
        .message { font-size: 14px; color: #555; line-height: 1.7; margin-bottom: 25px; }
        .count-badge { display: inline-block; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; border-radius: 20px; padding: 4px 14px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
        .doc-card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px 18px; margin-bottom: 14px; background: #fafafa; }
        .doc-card:last-child { margin-bottom: 0; }
        .doc-title { font-size: 14px; font-weight: 700; color: #1e3a8a; margin-bottom: 6px; }
        .doc-meta { font-size: 12px; color: #6b7280; }
        .doc-meta span { margin-right: 14px; }
        .doc-meta .label { color: #9ca3af; }
        .btn { display: inline-block; margin-top: 12px; background: #2563eb; color: #fff; text-decoration: none; padding: 8px 18px; border-radius: 6px; font-size: 13px; font-weight: 600; }
        .btn:hover { background: #1d4ed8; }
        .divider { border: none; border-top: 1px solid #f0f0f0; margin: 25px 0; }
        .footer { background: #f8fafc; padding: 20px 35px; text-align: center; font-size: 12px; color: #9ca3af; }
        .footer a { color: #3b82f6; text-decoration: none; }
        .warning-box { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px; padding: 14px 16px; margin-bottom: 22px; font-size: 13px; color: #92400e; }
        .warning-box strong { color: #c2410c; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <h1>📋 Pending Draft Reminder</h1>
        <p>{{ config('app.name') }}</p>
    </div>

    <div class="body">

        <p class="greeting">Hello, {{ $user->name }}! 👋</p>

        <p class="message">
            You have some documents in <strong>draft</strong> state — meaning you started filling them
            but have not yet <strong>finalized</strong> them. Use the links below to complete them.
        </p>

        <div class="warning-box">
            <strong>⚠️ Please note:</strong> Draft documents are not valid until you complete the <strong>Preview → Finalize</strong> step.
        </div>

        <div class="count-badge">📂 {{ $drafts->count() }} Draft Document{{ $drafts->count() > 1 ? 's' : '' }} Pending</div>

        @foreach ($drafts as $draft)
        <div class="doc-card">
            <div class="doc-title">{{ $draft->template?->template_name ?? 'Template' }}</div>
            <div class="doc-meta">
                <span><span class="label">Template ID: </span>#{{ $draft->template_id }}</span>
                <span><span class="label">Template Code: </span>{{ $draft->template?->template_code ?? '—' }}</span>
            </div>
            <div class="doc-meta" style="margin-top:5px;">
                <span><span class="label">Bank: </span>{{ $draft->bank?->bank_name ?? '—' }}</span>
                <span><span class="label">Doc No: </span>{{ $draft->document_number }}</span>
                <span><span class="label">Date: </span>{{ $draft->created_at->format('d M Y') }}</span>
            </div>
            <a href="{{ route('generate.editDraft', $draft->id) }}" class="btn">✏️ Complete Now</a>
        </div>
        @endforeach

        <hr class="divider">

        <p class="message" style="font-size:13px; color:#888;">
            If you need any help, please contact us.<br>
            This is an automated reminder — please do not reply to this email.
        </p>

    </div>

    <div class="footer">
        <p>© {{ date('Y') }} <a href="{{ config('app.url') }}">{{ config('app.name') }}</a>. All rights reserved.</p>
        <p style="margin-top:5px;">You received this email because your account has pending drafts.</p>
    </div>

</div>
</body>
</html>
