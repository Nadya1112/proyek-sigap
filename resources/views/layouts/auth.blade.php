<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>@yield('title','Masuk - SIGAP KOMPLEK')</title>

  {{-- Kamu pakai Mix --}}
   @vite(['resources/css/app.css','resources/js/app.js'])



  {{-- Alpine untuk toggle password --}}
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#F7F8FA] min-h-screen">
  @yield('content')
</body>
</html>
