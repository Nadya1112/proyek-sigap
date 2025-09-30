@extends('layouts.public')

@section('title','E-Proposal PSU')

@section('content')

{{-- ========== HERO ========== --}}
<section class="relative overflow-hidden">
  <div class="relative" style="background: linear-gradient(135deg, #F7A623 0%, #FF7A00 45%, #F25C3B 70%, #F04949 100%);">
    <div class="pointer-events-none absolute -top-6 -left-10 w-[380px] h-[380px] opacity-70" style="background: radial-gradient(closest-side, rgba(255,179,73,0.55) 0%, rgba(255,179,73,0.28) 34%, rgba(255,179,73,0.12) 60%, transparent 72%); filter: blur(2px);"></div>
    <div class="pointer-events-none absolute -bottom-10 -right-8 w-[360px] h-[360px] opacity-65" style="background: radial-gradient(closest-side, rgba(255,120,120,0.45) 0%, rgba(255,120,120,0.24) 35%, rgba(255,120,120,0.10) 58%, transparent 75%); filter: blur(2px);"></div>
    <div class="container mx-auto px-6 py-14 md:py-20 relative text-center text-white">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 ring-1 ring-white/25">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
            E-Proposal PSU
        </span>
        <h1 class="mt-4 text-4xl md:text-5xl font-extrabold tracking-tight drop-shadow-[0_1px_2px_rgba(0,0,0,0.12)]">
          Pengajuan Proposal Bantuan PSU
        </h1>
        <p class="mt-4 text-base md:text-lg text-white/90 max-w-3xl mx-auto">
          Ajukan proposal bantuan prasarana, sarana, dan utilitas perumahan. Data anda akan kami proses dengan transparan dan cepat.
        </p>
        {{-- ... (kode hero section di atasnya) --}}
        
        <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">
            {{-- Card 1: Total Proposal --}}
            <div class="group rounded-[14px] px-8 py-6 text-center bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
              <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $stats['total'] ?? 0 }}</p>
              <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Total Proposal</p>
            </div>
            {{-- Card 2: Proposal Diajukan --}}
            <div class="group rounded-[14px] px-8 py-6 text-center bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
              <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $stats['diajukan'] ?? 0 }}</p>
              <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Proposal Diajukan</p>
            </div>
            {{-- Card 3: Sudah Diverifikasi --}}
            <div class="group rounded-[14px] px-8 py-6 text-center bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
              <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $stats['diverifikasi'] ?? 0 }}</p>
              <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Sudah Diverifikasi</p>
            </div>
            {{-- Card 4: Sudah Disetujui --}}
            <div class="group rounded-[14px] px-8 py-6 text-center bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
              <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $stats['disetujui'] ?? 0 }}</p>
              <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Sudah Disetujui</p>
            </div>
        </div>

        {{-- ... (sisa kode hero section di bawahnya) ... --}}
    </div>
  </div>
</section>

{{-- ========== FORM CARD ========== --}}
<main class="py-16 lg:py-24 bg-gray-50">
  <div class="container mx-auto px-6">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg border border-gray-100 p-8">
        @auth
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Formulir Pengajuan Proposal</h2>
            <p class="text-gray-600 mb-6">Isi data berikut dengan benar untuk mempercepat proses verifikasi.</p>

            @if(session('success'))
              <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
              </div>
            @endif

            <div x-data="dependentDropdown()">
                <form action="{{ route('eproposal.store') }}" method="POST" enctype="multipart/form-data"
                      class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                  @csrf

                  <div>
                    <label for="nama_pengaju" class="form-label">Nama Lengkap Pengaju</label>
                    <input id="nama_pengaju" name="nama_pengaju" value="{{ old('nama_pengaju', auth()->user()->name) }}" required class="form-input"/>
                    @error('nama_pengaju')<p class="form-error">{{ $message }}</p>@enderror
                  </div>

                  <div>
                    <label for="kontak_pengaju" class="form-label">Nomor Kontak (WA/Telepon)</label>
                    <input id="kontak_pengaju" name="kontak_pengaju" value="{{ old('kontak_pengaju', auth()->user()->kontak) }}" required class="form-input"/>
                    @error('kontak_pengaju')<p class="form-error">{{ $message }}</p>@enderror
                  </div>

                  <div>
                    <label for="kecamatan_id" class="form-label">Kecamatan</label>
                    <select id="kecamatan_id" name="kecamatan_id" x-model="kecamatanId" @change="fetchKelurahans" required class="form-input">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach ($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                    @error('kecamatan_id')<p class="form-error">{{ $message }}</p>@enderror
                  </div>
                  
                  <div>
                    <label for="kelurahan_id" class="form-label">Kelurahan</label>
                    <select id="kelurahan_id" name="kelurahan_id" x-model="kelurahanId" required class="form-input" :disabled="loading || kelurahans.length === 0">
                        <option value="">-- Pilih Kelurahan --</option>
                        <template x-if="loading">
                            <option>Memuat...</option>
                        </template>
                        <template x-for="kelurahan in kelurahans" :key="kelurahan.id">
                            <option :value="kelurahan.id" x-text="kelurahan.nama_kelurahan"></option>
                        </template>
                    </select>
                    @error('kelurahan_id')<p class="form-error">{{ $message }}</p>@enderror
                  </div>

                  <div class="md:col-span-2">
                    <label for="nama_perumahan" class="form-label">Nama Perumahan</label>
                    <input id="nama_perumahan" name="nama_perumahan" value="{{ old('nama_perumahan') }}" required class="form-input" placeholder="Perumahan Griya / Komplek Griya"/>
                    @error('nama_perumahan')<p class="form-error">{{ $message }}</p>@enderror
                  </div>
                  
                  <div class="md:col-span-2">
                    <label for="alamat" class="form-label">Detail Alamat</label>
                    <input id="alamat" name="alamat" value="{{ old('alamat') }}" required class="form-input" placeholder="Jln. Griya, Komplek Griya, No. 12, Blok A, Kel.. , Kec.. "/>
                    @error('alamat')<p class="form-error">{{ $message }}</p>@enderror
                  </div>

                  <div class="md:col-span-2">
                    <label for="proposal" class="form-label">
                      Unggah Proposal <span class="font-normal text-gray-500">(Format: PDF, DOC, DOCX. Maks 10MB)</span>
                    </label>
                    <input id="proposal" type="file" name="proposal" accept=".pdf,.doc,.docx" required class="w-full rounded-xl border border-gray-200 bg-gray-50
                          file:mr-4 file:rounded-lg file:border-0 file:bg-[#FFA72B] file:px-4 file:py-2 file:text-white
                          hover:file:brightness-95 focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 transition"/>
                    @error('proposal')<p class="form-error">{{ $message }}</p>@enderror
                  </div>

                  <div class="md:col-span-2">
                    <label for="catatan" class="form-label">Catatan <span class="font-normal text-gray-500">(Opsional)</span></label>
                    <textarea id="catatan" name="catatan" rows="4" class="form-input">{{ old('catatan') }}</textarea>
                  </div>

                  <div class="md:col-span-2 flex justify-end pt-4">
                    <button type="submit" class="btn-gradient w-full md:w-auto">
                        <span class="relative z-10">Kirim Proposal</span>
                        <div class="btn-gradient-hover"></div>
                    </button>
                  </div>
                </form>
            </div>
        @else
            {{-- Kartu notifikasi login --}}
       
                    <div class="text-center bg-orange-50/50 rounded-xl p-8 md:p-12 border border-orange-200/80">
                        {{-- Icon --}}
                        <div
                            class="mx-auto w-16 h-16 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center shadow-lg mb-5">
                            <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H4.5a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-800">Silakan Masuk Terlebih Dahulu</h3>
                        <p class="text-gray-600 mt-2 mb-6 max-w-md mx-auto">
                            Anda harus memiliki akun dan masuk untuk dapat membuat pengaduan.
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="{{ route('login') }}" class="btn-gradient rounded-lg px-8 py-3">
                                <span class="relative z-10 font-semibold">Masuk ke Akun</span>
                                <div class="btn-gradient-hover rounded-lg"></div>
                            </a>
                            <a href="{{ url('/register') }}"
                                class="font-semibold text-orange-600 hover:text-orange-700 transition">
                                Buat Akun Baru
                            </a>
                        </div>
                    </div>
        @endauth
    </div>
  </div>
</main>

<style>
    .form-label { display: block; margin-bottom: 0.375rem; font-size: 0.875rem; line-height: 1.25rem; font-weight: 600; color: #374151; }
    .form-error { margin-top: 0.25rem; font-size: 0.75rem; line-height: 1rem; color: #EF4444; }
    .form-input { width: 100%; border-radius: 0.75rem; border-width: 1px; border-color: #D1D5DB; background-color: #FFFFFF; padding: 0.75rem 1rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); transition: all 0.2s ease-in-out; }
    .form-input:focus { border-color: #F97316; outline: 2px solid transparent; outline-offset: 2px; --tw-ring-color: #F97316; --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color); --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color); box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000); }
    .form-file-input { display: block; width: 100%; border-radius: 0.75rem; border: 1px solid #D1D5DB; font-size: 0.875rem; line-height: 1.25rem; color: #6B7280; }
    .form-file-input::file-selector-button { border-radius: 0.5rem 0 0 0.5rem; margin-right: 1rem; border-width: 0px; background-color: #F3F4F6; padding: 0.75rem 1rem; font-weight: 600; color: #374151; }
    .form-file-input:hover::file-selector-button { background-color: #E5E7EB; }
</style>

<script>
    function dependentDropdown() {
        return {
            kecamatanId: '{{ old('kecamatan_id') }}',
            kelurahanId: '{{ old('kelurahan_id') }}',
            kelurahans: [],
            loading: false,
            fetchKelurahans() {
                if (!this.kecamatanId) {
                    this.kelurahans = [];
                    this.kelurahanId = '';
                    return;
                }
                this.loading = true;
                // Pastikan rute ini ada di web.php
                fetch(`/kelurahan-by-kecamatan/${this.kecamatanId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.kelurahans = data;
                        this.loading = false;
                        // Jika ada data kelurahan lama, coba set kembali
                        if (this.kelurahanId) {
                            // Cek jika kelurahanId lama ada di data baru
                            const oldKelurahanExists = this.kelurahans.some(k => k.id == this.kelurahanId);
                            if (!oldKelurahanExists) {
                                this.kelurahanId = '';
                            }
                        }
                    });
            },
            init() {
                this.$watch('kecamatanId', () => {
                    this.kelurahanId = ''; // Reset kelurahan jika kecamatan berubah
                });
                
                // Jika ada data kecamatan lama saat halaman dimuat, panggil fetch
                if (this.kecamatanId) {
                    this.fetchKelurahans();
                }
            }
        }
    }
</script>
@endsection