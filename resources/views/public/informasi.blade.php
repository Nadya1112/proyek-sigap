@extends('layouts.public')

@section('title','Informasi FASUM')

@section('content')
<section class="py-12">
  <div class="container mx-auto px-4">
    <h2 class="text-2xl font-semibold mb-4">Informasi FASUM</h2>

    <form method="GET" class="mb-4">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama perumahan atau alamat..." class="border rounded p-2 w-full md:w-1/3" />
    </form>

    <div class="overflow-x-auto bg-white rounded shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Perumahan</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Sertifikat</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Serah</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach($fasums as $i => $f)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap">{{ $fasums->firstItem() + $i }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $f->nama_perumahan }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $f->alamat }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($f->status_sertifikat === 'sudah')
                  <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs">Sudah</span>
                @else
                  <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs">Belum</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $f->tanggal_serah ?? '-' }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $f->keterangan ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $fasums->links() }}
    </div>
  </div>
</section>
@endsection
