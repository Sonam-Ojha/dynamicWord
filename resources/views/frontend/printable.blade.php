<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $document->document_number }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #111; margin: 0; padding: 24px; }
        .doc-header { border-bottom: 1px solid #ddd; padding-bottom: 12px; margin-bottom: 24px; display: flex; justify-content: space-between; }
        .doc-header .meta { font-size: 12px; color: #555; }
        .doc-body { max-width: 800px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="doc-header">
        <div>
            <strong>{{ $document->bank?->bank_name }}</strong>
            <div class="meta">{{ $document->template?->template_name }}</div>
        </div>
        <div class="meta">
            Doc #: {{ $document->document_number }}<br>
            Date: {{ $document->created_at->format('Y-m-d') }}
        </div>
    </div>

    <div class="doc-body">
        {!! $rendered !!}
    </div>
</body>
</html>
