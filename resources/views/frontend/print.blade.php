<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $document->document_number }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #111; margin: 0; padding: 24px; }
        .doc-header { border-bottom: 1px solid #ddd; padding-bottom: 12px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; }
        .doc-header .meta { font-size: 12px; color: #555; }
        .toolbar { position: fixed; top: 12px; right: 12px; }
        .toolbar button { padding: 6px 12px; background: #4f46e5; color: #fff; border: 0; border-radius: 4px; cursor: pointer; font-size: 13px; }
        .doc-body { max-width: 800px; margin: 0 auto; }
        @media print {
            .toolbar, .doc-header { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="toolbar">
        <button onclick="window.print()">Print</button>
        <button onclick="window.close()" style="background:#64748b;">Close</button>
    </div>

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
