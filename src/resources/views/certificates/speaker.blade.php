<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Speaker Certificate - {{ $registration->name }}</title>
<style>
    @page { margin: 0; padding: 0; }
    body {
        margin: 0;
        padding: 0;
        font-family: 'Helvetica', 'Arial', sans-serif;
        color: #0f172a;
        width: 841.89pt;
        height: 595.28pt;
    }
    .frame {
        position: absolute;
        top: 0;
        left: 0;
        width: 841.89pt;
        height: 595.28pt;
        background: #0a4f7a;
    }
    .inner {
        position: absolute;
        top: 10pt;
        left: 10pt;
        width: 821.89pt;
        height: 575.28pt;
        background: #ffffff;
        border: 2pt solid #dbeafe;
    }
    .header {
        text-align: center;
        margin-top: 48pt;
    }
    .org {
        font-size: 16pt;
        letter-spacing: 1pt;
        font-weight: 700;
        color: #0a4f7a;
        text-transform: uppercase;
    }
    .title {
        margin-top: 16pt;
        font-size: 34pt;
        font-weight: 800;
        letter-spacing: 1pt;
        color: #0f172a;
        text-transform: uppercase;
    }
    .subtitle {
        margin-top: 8pt;
        font-size: 12pt;
        color: #475569;
        letter-spacing: 2pt;
        text-transform: uppercase;
    }
    .content {
        text-align: center;
        margin: 50pt 70pt 0;
    }
    .name {
        font-size: 36pt;
        font-weight: 700;
        font-style: italic;
        color: #0f172a;
    }
    .line {
        width: 420pt;
        margin: 10pt auto 20pt;
        border-bottom: 1pt solid #94a3b8;
    }
    .desc {
        font-size: 14pt;
        line-height: 1.8;
        color: #1e293b;
    }
    .event {
        color: #0a4f7a;
        font-weight: 700;
    }
    .meta {
        margin-top: 18pt;
        font-size: 11pt;
        color: #475569;
    }
    .footer {
        position: absolute;
        left: 40pt;
        right: 40pt;
        bottom: 30pt;
    }
    .verify {
        float: left;
        width: 260pt;
    }
    .verify-box {
        background: #f8fafc;
        border: 1pt solid #e2e8f0;
        padding: 8pt;
    }
    .verify-code {
        margin-top: 6pt;
        font-family: 'Courier New', monospace;
        font-size: 9pt;
        font-weight: 700;
        color: #0f172a;
    }
    .signature {
        float: right;
        width: 260pt;
        text-align: center;
    }
    .sig-line {
        margin-top: 58pt;
        border-top: 1pt solid #94a3b8;
    }
    .sig-name {
        margin-top: 6pt;
        font-size: 11pt;
        font-weight: 700;
        color: #0f172a;
    }
    .sig-title {
        margin-top: 2pt;
        font-size: 7pt;
        text-transform: uppercase;
        letter-spacing: 1pt;
        color: #475569;
    }
</style>
</head>
<body>
<div class="frame">
<div class="inner">
    <div class="header">
        <div class="org">Internet Society Indonesia Jakarta Chapter</div>
        <div class="title">Certificate of Appreciation</div>
        <div class="subtitle">Awarded to Speaker</div>
    </div>

    <div class="content">
        <div class="name">{{ $registration->name }}</div>
        <div class="line"></div>
        <div class="desc">
            in recognition of valuable contribution as <strong>Speaker</strong><br/>
            at <span class="event">{{ $event->title }}</span>
            @if($event->date || $event->location)
            <div class="meta">
                @if($event->date)
                    {{ $event->date->format('F d, Y') }}
                @endif
                @if($event->date && $event->location)
                    &bull;
                @endif
                @if($event->location)
                    {{ $event->location }}
                @endif
            </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <div class="verify">
            <div class="verify-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode(url('/verify/' . $registration->registration_code)) }}" width="50" height="50" alt="QR"/>
                <div class="verify-code">{{ $registration->registration_code }}</div>
            </div>
        </div>

        <div class="signature">
            <div class="sig-line"></div>
            <div class="sig-name">Tinuk Andriyanti, M.Kom</div>
            <div class="sig-title">Chairwoman, ISOC Indonesia</div>
        </div>
    </div>
</div>
</div>
</body>
</html>
