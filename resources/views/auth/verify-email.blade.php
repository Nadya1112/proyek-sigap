@extends('layouts.public')  {{-- Sesuaikan dengan layout Anda --}}

@section('title', 'Verifikasi Email')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full">

        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10">
            <div class="text-center">
                <div class="w-20 h-20 rounded-full bg-orange-100 grid place-content-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Verifikasi Email Anda</h1>
                <p class="text-gray-500 mt-2">
                    Kami telah mengirim kode verifikasi 4 digit ke <strong class="text-gray-700">{{ $email }}</strong>.
                </p>
            </div>

            @if ($errors->any())
            <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">{{ $errors->first() }}</span>
            </div>
            @endif

            @if (session('status'))
            <div class="mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
            @endif

            {{-- FORM UTAMA YANG AKAN DIKIRIM --}}
            <form id="verification-form" method="POST" action="{{ route('verification.verify') }}" class="mt-6">
                @csrf
                <p class="text-center text-sm font-medium text-gray-600 mb-2">Masukkan kode verifikasi</p>

                {{-- INPUT TERSEMBUNYI UNTUK MENYIMPAN KODE LENGKAP --}}
                <input type="hidden" name="verification_code" id="verification_code">
                <input type="hidden" name="email" value="{{ $email }}">

                {{-- 4 KOTAK INPUT YANG DILIHAT PENGGUNA --}}
                <div class="flex justify-center gap-3" id="otp-inputs">
                    <input type="text" class="otp-input w-14 h-14 text-center text-2xl font-bold text-gray-800 bg-gray-100 border-2 border-gray-200 rounded-lg focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" maxlength="1">
                    <input type="text" class="otp-input w-14 h-14 text-center text-2xl font-bold text-gray-800 bg-gray-100 border-2 border-gray-200 rounded-lg focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" maxlength="1">
                    <input type="text" class="otp-input w-14 h-14 text-center text-2xl font-bold text-gray-800 bg-gray-100 border-2 border-gray-200 rounded-lg focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" maxlength="1">
                    <input type="text" class="otp-input w-14 h-14 text-center text-2xl font-bold text-gray-800 bg-gray-100 border-2 border-gray-200 rounded-lg focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" maxlength="1">
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full rounded-xl py-3 text-white font-semibold bg-gradient-to-r from-[#FFA728] to-[#F25C3B] shadow-sm hover:brightness-95 active:scale-[.99] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#F59E0B] transition">
                        Verifikasi Akun
                    </button>
                </div>
            </form>

            <div class="mt-5 text-center text-sm text-gray-500">
                <p>Tidak menerima kode?</p>
                {{-- FORM UNTUK KIRIM ULANG KODE --}}
                <form method="POST" action="{{ route('verification.resend') }}" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="text-orange-600 hover:text-orange-700 font-semibold hover:underline">
                        Kirim ulang kode
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT PENTING UNTUK MENGGABUNGKAN KODE --}}
<script>
    const otpInputs = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('verification_code');
    const form = document.getElementById('verification-form');

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            // Hanya proses jika input adalah angka
            if (e.target.value.match(/^[0-9]$/)) {
                // Pindah ke input selanjutnya jika belum yang terakhir
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            }
            updateHiddenInput();
        });

        input.addEventListener('keydown', (e) => {
            // Pindah ke input sebelumnya saat menekan backspace jika input kosong
            if (e.key === 'Backspace' && index > 0 && !e.target.value) {
                otpInputs[index - 1].focus();
            }
        });
    });

    function updateHiddenInput() {
        let otp = '';
        otpInputs.forEach(input => {
            otp += input.value;
        });
        hiddenInput.value = otp;
    }
    
    // Pastikan untuk update hidden input sebelum form disubmit
    form.addEventListener('submit', function() {
        updateHiddenInput();
    });
</script>
@endsection