@extends('layouts.public')

@section('title', 'Syarat & Ketentuan - SIGAP KOMPLEK')

@section('content')
{{-- Wrapper untuk membuat konten di tengah dan memberi jarak dari footer --}}
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 md:py-20 px-6">
    <div class="max-w-4xl w-full bg-white p-8 md:p-12 rounded-2xl shadow-xl border border-gray-200/60">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 tracking-tight border-b pb-4">Syarat & Ketentuan</h1>

        <div class="mt-6 prose max-w-none prose-orange">
            <p>Selamat datang di SIGAP KOMPLEK. Dengan mendaftar atau menggunakan layanan kami, Anda setuju untuk terikat oleh syarat dan ketentuan berikut. Mohon baca dengan saksama.</p>
            
            <h2>1. Akun Pengguna</h2>
            <ul>
                <li>Anda bertanggung jawab penuh untuk menjaga kerahasiaan akun dan kata sandi Anda.</li>
                <li>Anda setuju untuk memberikan informasi yang akurat, terkini, dan lengkap saat proses pendaftaran.</li>
                <li>Setiap aktivitas yang terjadi di bawah akun Anda adalah tanggung jawab Anda.</li>
            </ul>

            <h2>2. Penggunaan Layanan</h2>
            <ul>
                <li>Layanan ini ditujukan untuk warga Kota Banjarmasin untuk mengajukan proposal dan pengaduan terkait Prasarana, Sarana, dan Utilitas Umum (PSU).</li>
                <li>Anda dilarang menggunakan layanan ini untuk tujuan yang melanggar hukum, menipu, atau merugikan pihak lain.</li>
                <li>Dilarang mengirimkan data yang tidak benar, bersifat spam, atau mengandung malware.</li>
            </ul>

            <h2>3. Konten Pengguna</h2>
            <p>Dengan mengirimkan pengaduan atau proposal, Anda memberikan kami hak untuk menggunakan, memproses, dan menampilkan informasi tersebut dalam rangka memberikan layanan.</p>

            <h2>4. Pembatasan Tanggung Jawab</h2>
            <p>Kami berusaha memberikan layanan terbaik, namun kami tidak menjamin bahwa layanan akan selalu bebas dari gangguan atau kesalahan. Kami tidak bertanggung jawab atas kerugian tidak langsung yang timbul dari penggunaan layanan ini.</p>

            <h2>5. Perubahan Ketentuan</h2>
            <p>Kami dapat mengubah syarat dan ketentuan ini dari waktu ke waktu. Perubahan akan diinformasikan melalui website. Dengan terus menggunakan layanan setelah perubahan, Anda dianggap menyetujui ketentuan yang baru.</p>

            <div class="mt-8 text-center">
                <a href="{{ url()->previous() }}" class="text-orange-600 font-semibold hover:underline">&larr; Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection