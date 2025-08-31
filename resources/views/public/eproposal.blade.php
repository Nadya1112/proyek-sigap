@extends('layouts.public')

@section('title','E-Proposal PSU')

@section('content')
<section class="py-12">
  <div class="container mx-auto px-4">
    <h2 class="text-2xl font-semibold mb-4">Pengajuan Proposal Bantuan PSU</h2>

    @if(session('success'))
      <div class="p-4 bg-green-100 text-green-700 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('eproposal.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @csrf
      <div>
        <label class="block text-sm font-medium">Nama Pengaju</label>
        <input name="nama_pengaju" value="{{ old('nama_pengaju') }}" class="mt-1 block w-full border rounded p-2" />
        @error('nama_pengaju')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium">Nomor Kontak</label>
        <input name="kontak" value="{{ old('kontak') }}" class="mt-1 block w-full border rounded p-2" />
        @error('kontak')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium">Nama Perumahan</label>
        <input name="nama_perumahan" value="{{ old('nama_perumahan') }}" class="mt-1 block w-full border rounded p-2" />
      </div>

      <div>
        <label class="block text-sm font-medium">Alamat Perumahan</label>
        <input name="alamat" value="{{ old('alamat') }}" class="mt-1 block w-full border rounded p-2" />
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium">Unggah Proposal (PDF/DOCX, max 10MB)</label>
        <input type="file" name="proposal" class="mt-1 block w-full" />
        @error('proposal')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium">Catatan (opsional)</label>
        <textarea name="catatan" rows="4" class="mt-1 block w-full border rounded p-2">{{ old('catatan') }}</textarea>
      </div>

      <div class="md:col-span-2">
        <button type="submit" class="bg-sigap-yellow px-4 py-2 rounded font-semibold">Kirim Proposal</button>
      </div>
    </form>
  </div>
</section>
@endsection
