@extends('layouts.auth')

@section('title','Verifikasi Email - SIGAP KOMPLEK')

@section('content')
<div class="min-h-screen flex items-center justify-center relative px-4">

  {{-- Latar Belakang --}}
  <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
    <div class="absolute -top-24 -left-24 w-[520px] h-[520px] rounded-full opacity-20 blur-3xl"
         style="background: radial-gradient(50% 50% at 30% 30%, rgba(255,160,67,.50) 0%, rgba(255,160,67,.08) 60%, transparent 70%);"></div>
  </div>

  {{-- CARD --}}
  <div class="w-full max-w-md md:max-w-lg bg-white rounded-[26px] shadow-xl border border-gray-100 mx-auto">
    <div class="px-8 pt-9 pb-9">

      {{-- Ikon Email --}}
      <div class="flex justify-center mb-5">
        <div class="w-16 h-16 rounded-full bg-orange-100 grid place-content-center">
            <svg class="w-8 h-8 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>
      </div>

      <h1 class="text-center text-[20px] font-extrabold text-gray-800 tracking-wide">
        Verifikasi Email Anda
      </h1>
      <p class="mt-1 text-center text-[13px] text-gray-500">
        Kami telah mengirim kode verifikasi 4 digit ke <strong>{{ $email }}</strong>.
      </p>

      {{-- Alert status --}}
      @if (session('status'))
        <div class="mt-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl px-4 py-2">
          {{ session('status') }}
        </div>
      @endif
      @if ($errors->any())
        <div class="mt-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl px-4 py-2">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
      @endif


      {{-- FORM KODE --}}
      <div x-data="verificationController('{{ session('registration_data.verification_expires_at', now()->addMinutes(10)) }}')" x-init="init()">
        <form method="POST" action="{{ route('verification.verify') }}" class="mt-7 space-y-6">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}"/>
            {{-- Input tersembunyi yang akan diisi oleh Alpine.js --}}
            <input type="hidden" name="code" x-model="otpCode">

            <div>
                <label class="block text-center text-[13px] font-semibold text-gray-700 mb-2">Masukkan kode verifikasi</label>
                {{-- KOLOM INPUT 4 KOTAK --}}
                <div class="flex justify-center gap-3">
                    <template x-for="i in 4" :key="i">
                        <input
                            type="text"
                            maxlength="1"
                            inputmode="numeric"
                            class="w-14 h-14 text-center text-2xl font-bold rounded-2xl border-2 border-gray-200 bg-[#F7F8FA] transition
                                   focus:bg-white focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/45 outline-none"
                            x-on:keydown="handleKeyDown($event, i-1)"
                            x-on:input="handleInput($event, i-1)"
                            :id="'otp-' + i"
                        >
                    </template>
                </div>
            </div>

            <button type="submit"
                    class="w-full rounded-2xl py-3 text-white font-semibold shadow-sm
                           bg-gradient-to-r from-[#FFA72B] to-[#F16A00]
                           hover:brightness-95 active:scale-[.99]
                           focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#F39B28] transition">
              Verifikasi Akun
            </button>
        </form>
        
        <div class="mt-5 text-center text-[13px] text-gray-500">
            {{-- Tampilkan timer atau tombol kirim ulang --}}
            <div x-show="!expired" x-text="countdown" class="transition-opacity duration-300"></div>

            <div x-show="expired" style="display: none;" class="transition-opacity duration-300">
                <p>Tidak menerima kode?
                    <form method="POST" action="{{ route('verification.resend') }}" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}"/>
                        <button type="submit" class="text-[#F39B28] font-semibold hover:underline">
                          Kirim ulang kode
                        </button>
                    </form>
                </p>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
<script>
          function verificationController(expiresAtTimestamp) {
              return {
                  otpCode: '',
                  expiresAt: new Date(expiresAtTimestamp),
                  countdown: 'Memuat...',
                  expired: false,
                  
                  init() {
                      this.startCountdown();
                  },

                  startCountdown() {
                      const interval = setInterval(() => {
                          const now = new Date();
                          const timeLeft = Math.round((this.expiresAt - now) / 1000);

                          if (timeLeft <= 0) {
                              clearInterval(interval);
                              this.expired = true;
                              this.countdown = 'Waktu habis!';
                              return;
                          }

                          const minutes = Math.floor(timeLeft / 60);
                          let seconds = timeLeft % 60;
                          seconds = seconds < 10 ? '0' + seconds : seconds;

                          this.countdown = `Kode akan kedaluwarsa dalam ${minutes}:${seconds}`;
                      }, 1000);
                  },

                  handleInput(event, index) {
                      const input = event.target;
                      const value = input.value;

                      if (value.match(/^[0-9]$/)) {
                          if (index < 3) {
                              document.getElementById(`otp-${index + 2}`).focus();
                          }
                      } else {
                          input.value = '';
                      }
                      this.updateOtpCode();
                  },

                  handleKeyDown(event, index) {
                      if (event.key === "Backspace" && index > 0 && event.target.value === '') {
                          document.getElementById(`otp-${index}`).focus();
                      }
                  },

                  updateOtpCode() {
                      let code = '';
                      for (let i = 1; i <= 4; i++) {
                          code += document.getElementById(`otp-${i}`).value;
                      }
                      this.otpCode = code;
                  }
              }
          }
</script>
