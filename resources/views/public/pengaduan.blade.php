@extends('layouts.public')

@section('title','Pengaduan Masyarakat')

@section('content')
<section class="py-12">
  <div class="container mx-auto px-4">
    <h2 class="text-2xl font-semibold mb-4">Pengaduan Masyarakat</h2>

    @if(session('success'))
      <div class="p-4 bg-green-100 text-green-700 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-4">
      @csrf
      <div class="md:flex md:gap-4">
        <div class="md:flex-1">
          <label class="block text-sm font-medium">Nama</label>
          <input name="nama" value="{{ old('nama') }}" class="mt-1 block w-full border rounded p-2" />
        </div>
        <div class="md:flex-1 mt-4 md:mt-0">
          <label class="block text-sm font-medium">Kontak</label>
          <input name="kontak" value="{{ old('kontak') }}" class="mt-1 block w-full border rounded p-2" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium">Isi Pengaduan</label>
        <textarea name="isi" rows="5" class="mt-1 block w-full border rounded p-2">{{ old('isi') }}</textarea>
        @error('isi')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium">Bukti Foto (opsional)</label>
        <input type="file" name="bukti" class="mt-1 block w-full" accept="image/*" />
      </div>

      <div>
        <button type="submit" class="bg-sigap-yellow px-4 py-2 rounded font-semibold">Kirim Pengaduan</button>
      </div>
    </form>
  </div>
</section>
@endsection
