@extends('layouts.public')

@section('title', 'Kebijakan Privasi - SIGAP KOMPLEK')

@section('content')
{{-- Wrapper untuk membuat konten di tengah dan memberi jarak dari footer --}}
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 md:py-20 px-6">
    <div class="max-w-4xl w-full bg-white p-8 md:p-12 rounded-2xl shadow-xl border border-gray-200/60">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 tracking-tight border-b pb-4">Kebijakan Privasi</h1>

        <div class="mt-6 prose max-w-none prose-orange">
            <p>Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda saat Anda menggunakan layanan SIGAP KOMPLEK.</p>
            
            <h2>1. Informasi yang Kami Kumpulkan</h2>
            <ul>
                <li><strong>Data Pendaftaran:</strong> Nama, alamat email, dan nomor telepon yang Anda berikan saat membuat akun.</li>
                <li><strong>Data Penggunaan:</strong> Informasi tentang pengaduan dan proposal yang Anda ajukan, termasuk lampiran dan deskripsi.</li>
                <li><strong>Data Teknis:</strong> Alamat IP, jenis browser, dan sistem operasi yang digunakan untuk mengakses layanan kami.</li>
            </ul>

            <h2>2. Bagaimana Kami Menggunakan Informasi Anda</h2>
            <ul>
                <li>Untuk menyediakan, mengoperasikan, dan memelihara layanan kami.</li>
                <li>Untuk memproses dan menanggapi pengaduan serta proposal Anda.</li>
                <li>Untuk berkomunikasi dengan Anda, termasuk mengirimkan notifikasi dan pembaruan terkait layanan.</li>
                <li>Untuk meningkatkan keamanan dan mencegah penyalahgunaan.</li>
            </ul>

            <h2>3. Keamanan Data</h2>
            <p>Kami menerapkan langkah-langkah keamanan yang wajar untuk melindungi informasi Anda dari akses, pengubahan, atau penghancuran yang tidak sah. Kata sandi Anda dienkripsi dan kami tidak pernah menyimpannya dalam bentuk teks biasa.</p>

            <h2>4. Cookie</h2>
            <p>Kami menggunakan cookie sesi untuk menjaga Anda tetap login. Cookie adalah file teks kecil yang disimpan di perangkat Anda dan tidak mengandung informasi pribadi yang sensitif.</p>

            <h2>5. Hubungi Kami</h2>
            <p>Jika Anda memiliki pertanyaan mengenai Kebijakan Privasi ini, silakan hubungi kami melalui halaman Kontak.</p>

            <div class="mt-8 text-center">
                <a href="{{ url()->previous() }}" class="text-orange-600 font-semibold hover:underline">&larr; Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection