<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <title>Verifikasi Email</title>
  </head>
  <body style="font-family: Arial, sans-serif; line-height:1.6; color:#111;">
    <h2 style="margin:0 0 12px;">Verifikasi Email Anda</h2>

    <p>Hai <strong>{{ $name }}</strong>,</p>

    <p>Gunakan kode berikut untuk memverifikasi email Anda di <strong>SIGAP-KOMPLEK</strong>:</p>

    <div style="font-size:28px; letter-spacing:6px; font-weight:bold; margin:16px 0;">
      {{ $code }}
    </div>

    @if($expiresAt)
      <p style="font-size:12px; color:#666;">
        Kode ini berlaku sampai <strong>{{ \Carbon\Carbon::parse($expiresAt)->format('d/m/Y H:i') }}</strong>.
      </p>
    @endif

    <hr style="border:none;border-top:1px solid #eee; margin:20px 0;">
    <p style="font-size:12px; color:#666;">&copy; {{ date('Y') }} SIGAP KOMPLEK</p>
  </body>
</html>
