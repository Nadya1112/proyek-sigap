@extends('layouts.public')

@section('title','Beranda - SIGAP KOMPLEK')

@section('content')
<header class="relative h-[80vh] bg-cover bg-center" style="background-image: url('{{ asset('img/perumahan.jpg') }}');">
  <div class="absolute inset-0 bg-black bg-opacity-40"></div> <!-- overlay -->
  
  <div class="relative z-10 container mx-auto h-full flex flex-col md:flex-row items-center justify-center md:justify-between px-6">
    
    <!-- Text -->
    <div class="text-white max-w-xl">
      <p class="mb-2">Selamat Datang</p>
      <h1 class="text-4xl md:text-5xl font-bold">SIGAP - KOMPLEK</h1>
      <p class="italic mt-2">( Sinergi Gerak Aksi PSU Komplek Perumahan )</p>
      <p class="mt-4">Melayani Penanganan PSU (Prasarana, Sarana, dan Utilitas Umum) Perumahan Kota Banjarmasin.</p>
    </div>

    <!-- Logo -->
    <div class="mt-8 md:mt-0 flex justify-center md:justify-end">
      <div class="bg-white p-4 rounded-full shadow-lg">
        <img src="{{ asset('img/logo-sigap.png') }}" alt="logo sigap" class="w-48 md:w-64 h-auto object-contain">
      </div>
    </div>
  </div>
</header>


  <div class="text-center py-3">
    <span class="inline-block bg-white p-2 rounded-full shadow">
      <svg width="12" height="12" viewBox="0 0 12 12"><circle cx="6" cy="6" r="6" fill="#0d6efd"/></svg>
      <svg width="12" height="12" viewBox="0 0 12 12" class="ml-1"><circle cx="6" cy="6" r="6" fill="#f8f9fa"/></svg>
      <svg width="12" height="12" viewBox="0 0 12 12" class="ml-1"><circle cx="6" cy="6" r="6" fill="#f8f9fa"/></svg>
    </span>
  </div>

  <section class="bg-white py-16">
  <div class="container mx-auto px-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      
      <!-- Card -->
      <div class="bg-white rounded-2xl shadow-lg p-6 text-center hover:shadow-xl transition">
        <div class="w-20 h-20 mx-auto flex items-center justify-center bg-sigap-yellow rounded-xl mb-4 font-bold">ICON</div>
        <h3 class="font-semibold text-lg">Informasi FASUM</h3>
        <p class="text-gray-600 text-sm mt-2">Data tabel sertifikat FASUM yang telah & belum diserahkan.</p>
        <a href="{{ route('informasi') }}" class="mt-4 inline-block text-sm font-semibold text-blue-600">Lihat</a>
      </div>

      <!-- Card -->
      <div class="bg-white rounded-2xl shadow-lg p-6 text-center hover:shadow-xl transition">
        <div class="w-20 h-20 mx-auto flex items-center justify-center bg-sigap-yellow rounded-xl mb-4 font-bold">ICON</div>
        <h3 class="font-semibold text-lg">E - Proposal PSU</h3>
        <p class="text-gray-600 text-sm mt-2">Tempat untuk pengajuan proposal bantuan PSU.</p>
        <a href="{{ route('eproposal') }}" class="mt-4 inline-block text-sm font-semibold text-blue-600">Lihat</a>
      </div>

      <!-- Card -->
      <div class="bg-white rounded-2xl shadow-lg p-6 text-center hover:shadow-xl transition">
        <div class="w-20 h-20 mx-auto flex items-center justify-center bg-sigap-yellow rounded-xl mb-4 font-bold">ICON</div>
        <h3 class="font-semibold text-lg">Pengaduan Masyarakat</h3>
        <p class="text-gray-600 text-sm mt-2">Layanan Pengaduan Masyarakat.</p>
        <a href="{{ route('pengaduan') }}" class="mt-4 inline-block text-sm font-semibold text-blue-600">Lihat</a>
      </div>

    </div>
  </div>
</section>

@endsection
