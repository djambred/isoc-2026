<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Certificate - {{ $registration->name }}</title>
<style>
    @page { margin: 0; padding: 0; }
    body {
        margin: 0; padding: 0;
        font-family: 'Helvetica', 'Arial', sans-serif;
        color: #191c1d;
        width: 841.89pt;
        height: 595.28pt;
    }

    /* Blue border = full page */
    .frame {
        position: absolute; top: 0; left: 0;
        width: 841.89pt; height: 595.28pt;
        background: #0050a0;
    }

    /* White card inside border */
    .inner {
        position: absolute; top: 7pt; left: 7pt;
        width: 827.89pt; height: 581.28pt;
        background: #ffffff;
    }

    /* Background image - centered watermark */
    .bg-img {
        position: absolute;
        top: 180pt;
        left: 114pt;
        width: 600pt;
    }

    /* --- HEADER --- */
    .hdr {
        position: absolute; top: 28pt; left: 0; width: 827.89pt;
        text-align: center;
    }
    .hdr-name { font-size: 20pt; font-weight: 800; color: #001833; }
    .hdr-chap { font-size: 13pt; font-weight: 600; color: #0060ac; }

    /* --- GRAY BAND --- */
    .band {
        position: absolute; top: 82pt; left: 0; width: 827.89pt;
        background: #eef0f2; text-align: center;
        padding: 16pt 0 12pt 0;
    }
    .band-title { font-size: 30pt; font-weight: 900; color: #001833; letter-spacing: 2pt; text-transform: uppercase; }
    .band-sub { font-size: 8pt; color: #5a5e66; font-weight: 600; letter-spacing: 4pt; text-transform: uppercase; margin-top: 4pt; }

    /* --- BODY --- */
    .content {
        position: absolute; top: 170pt; left: 80pt; width: 667.89pt;
        text-align: center;
    }
    .pname { font-size: 34pt; font-weight: 700; font-style: italic; color: #001833; }
    .pline { border-bottom: 1pt solid #c0c4cc; width: 400pt; margin: 6pt auto 14pt auto; }
    .desc { font-size: 12pt; color: #333; line-height: 1.7; }
    .ename { color: #0060ac; font-weight: 700; }

    /* --- META --- */
    .meta {
        position: absolute; top: 330pt; left: 0; width: 827.89pt;
        text-align: center;
    }
    .meta table { margin: 0 auto; border-collapse: collapse; }
    .meta td { padding: 0 14pt; font-size: 10pt; font-weight: 600; color: #5a5e66; }

    /* --- FOOTER: 3 columns, absolute inside .inner --- */
    /* QR block - left */
    .ftr-qr {
        position: absolute; bottom: 20pt; left: 25pt; width: 250pt;
    }
    .qr-box { background: #f4f5f6; padding: 8pt; }
    .qr-tbl { border-collapse: collapse; }
    .qr-tbl td { vertical-align: middle; }
    .qr-img { width: 54pt; height: 54pt; }
    .qr-text { padding-left: 8pt; }
    .qr-label { font-size: 6pt; font-weight: 700; color: #5a5e66; text-transform: uppercase; letter-spacing: 1pt; }
    .qr-code { font-size: 8.5pt; font-weight: 700; color: #002d56; font-family: 'Courier New', monospace; margin-top: 2pt; }
    .qr-link { font-size: 6.5pt; color: #0060ac; font-weight: 600; margin-top: 2pt; }

    /* Logo - center */
    .ftr-logo {
        position: absolute; bottom: 20pt; left: 314pt; width: 200pt;
        text-align: center;
    }
    .logo-img { height: 56pt; }
    .logo-txt { font-size: 5pt; font-weight: 700; color: #aaa; text-transform: uppercase; letter-spacing: 1pt; margin-top: 3pt; }

    /* Signature - right */
    .ftr-sig {
        position: absolute; bottom: 20pt; right: 25pt; width: 240pt;
        text-align: center;
    }
    .sig-box { width: 52pt; height: 52pt; background: #1a1a2e; margin: 0 auto 5pt auto; }
    .sig-line { border-top: 1pt solid #c0c4cc; width: 170pt; margin: 0 auto 4pt auto; }
    .sig-name { font-size: 11pt; font-weight: 700; color: #001833; }
    .sig-title { font-size: 6pt; color: #5a5e66; text-transform: uppercase; letter-spacing: 2pt; font-weight: 600; margin-top: 2pt; }
</style>
</head>
<body>
<div class="frame">
<div class="inner">

    {{-- Background image --}}
    <img src="{{ public_path('images/bgsertif.jpg') }}" class="bg-img"/>

    {{-- Header --}}
    <div class="hdr">
        <div class="hdr-name">Internet Society</div>
        <div class="hdr-chap">Indonesia Jakarta Chapter</div>
    </div>

    {{-- Gray band --}}
    <div class="band">
        <div class="band-title">Certificate of Attendance</div>
        <div class="band-sub">This is to certify that</div>
    </div>

    {{-- Body --}}
    <div class="content">
        <div class="pname">{{ $registration->name }}</div>
        <div class="pline"></div>
        <div class="desc">
            has successfully attended the<br/>
            <span class="ename">&ldquo;{{ $event->title }}&rdquo;</span><br/>
            organized by Internet Society (ISOC) Indonesia Jakarta Chapter.
        </div>
    </div>

    {{-- Meta --}}
    <div class="meta">
        <table><tr>
            @if($event->date)
            <td>on {{ $event->date->format('F d, Y') }}</td>
            @endif
            @if($event->location)
            <td>at {{ $event->location }}</td>
            @endif
        </tr></table>
    </div>

    {{-- Footer left: QR --}}
    <div class="ftr-qr">
        <div class="qr-box">
            <table class="qr-tbl"><tr>
                <td><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/verify/' . $registration->registration_code)) }}" class="qr-img"/></td>
                <td class="qr-text">
                    <div class="qr-label">Authenticity ID</div>
                    <div class="qr-code">{{ $registration->registration_code }}</div>
                    <div class="qr-link">Verify Online</div>
                </td>
            </tr></table>
        </div>
    </div>

    {{-- Footer center: Logo --}}
    <div class="ftr-logo">
        <img src="{{ public_path('images/isoc-logo.png') }}" class="logo-img"/>
        <div class="logo-txt">ISOC Indonesia Chapter</div>
    </div>

    {{-- Footer right: Signature --}}
    <div class="ftr-sig">
        <div class="sig-box"></div>
        <div class="sig-line"></div>
        <div class="sig-name">Tinuk Andriyanti, M.Kom</div>
        <div class="sig-title">Chairwoman, ISOC Indonesia</div>
    </div>

</div>
</div>
</body>
</html>
