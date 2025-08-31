<nav class="bg-white shadow sticky top-0 z-50">
  <div class="container mx-auto px-6 flex items-center justify-between py-3">
    
    <!-- Logo Dinas -->
    <div class="flex items-center gap-3">
      <img src="{{ asset('img/logo-pemkot.jpg') }}" alt="logo pemkot" class="w-10 h-auto object-contain">
      <span class="font-semibold text-sm leading-tight">Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin</span>
    </div>
    
    <!-- Menu -->
    <div class="hidden md:flex gap-6 text-gray-600 font-medium">
      <a href="{{ route('home') }}" class="hover:text-black">Beranda</a>
      <a href="{{ route('fitur') }}" class="hover:text-black">Fitur</a>
      <a href="{{ route('sebaran') }}" class="hover:text-black">Sebaran Komplek</a>
      <a href="{{ route('informasi') }}" class="hover:text-black">Informasi</a>
      <a href="{{ route('kontak') }}" class="hover:text-black">Kontak</a>
    </div>
    
    <!-- Tombol masuk -->
    <a href="#" class="bg-sigap-yellow px-4 py-2 rounded-lg font-semibold">Masuk</a>
  </div>
</nav>
