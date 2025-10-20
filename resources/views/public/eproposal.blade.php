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
        
        <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">
            <div class="group rounded-[14px] px-8 py-6 text-center bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
              <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $stats['total'] ?? 0 }}</p>
              <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Total Proposal</p>
            </div>
            <div class="group rounded-[14px] px-8 py-6 text-center bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
              <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $stats['diajukan'] ?? 0 }}</p>
              <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Proposal Diajukan</p>
            </div>
            <div class="group rounded-[14px] px-8 py-6 text-center bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
              <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $stats['diverifikasi'] ?? 0 }}</p>
              <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Sudah Diverifikasi</p>
            </div>
            <div class="group rounded-[14px] px-8 py-6 text-center bg-white/18 ring-1 ring-white/35 shadow-[inset_0_1px_0_rgba(255,255,255,0.35)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-white/45">
              <p class="text-3xl md:text-4xl font-extrabold text-white leading-none">{{ $stats['disetujui'] ?? 0 }}</p>
              <p class="mt-3 text-[11px] tracking-wide uppercase text-white/85">Sudah Disetujui</p>
            </div>
        </div>
    </div>
  </div>
</section>

{{-- ========== FORM CARD ========== --}}
<main class="py-16 lg:py-24 bg-gray-50">
  <div class="container mx-auto px-6">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg border border-gray-100 p-8">
        @auth
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Formulir Pengajuan Proposal</h2>
            <p class="text-gray-600 mb-6">Lengkapi Langkah 1 untuk melanjutkan pengajuan proposal.</p>

            @if(session('success'))
              <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
              </div>
            @endif

           <div x-data="proposalForm()">
                <form action="{{ route('eproposal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                  @csrf
                  
                  {{-- ======================================================= --}}
                  {{-- === INOVASI: LANGKAH 1 - VERIFIKASI LOKASI & NAMA === --}}
                  {{-- ======================================================= --}}
                  <div class="p-5 rounded-lg border-2" :class="{
                      'border-gray-200 bg-gray-50': status === 'idle',
                      'border-blue-300 bg-blue-50': status === 'checking',
                      'border-green-300 bg-green-50': status === 'found',
                      'border-red-300 bg-red-50': status === 'notFound'
                  }">
                      <h3 class="font-bold text-gray-800">Langkah 1: Verifikasi Perumahan</h3>
                      <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                          {{-- Dropdown Kecamatan --}}
                          <div>
                              <label for="kecamatan" class="form-label">1. Pilih Kecamatan</label>
                              <select id="kecamatan" x-model="kecamatanId" @change="fetchKelurahans" class="form-input" :disabled="status === 'found'">
                                  <option value="">-- Pilih Kecamatan --</option>
                                  @foreach($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                                  @endforeach
                              </select>
                          </div>
                          {{-- Dropdown Kelurahan --}}
                          <div>
                              <label for="kelurahan" class="form-label">2. Pilih Kelurahan</label>
                              <select id="kelurahan" x-model="kelurahanId" class="form-input" :disabled="!kecamatanId || kelurahans.length === 0 || status === 'found'">
                                  <option value="">-- Pilih Kelurahan --</option>
                                  <template x-for="kelurahan in kelurahans" :key="kelurahan.id">
                                      <option :value="kelurahan.id" x-text="kelurahan.nama_kelurahan"></option>
                                  </template>
                              </select>
                          </div>
                      </div>
                      <div class="mt-4" x-show="kelurahanId">
                          <label for="search_kompleks" class="form-label">3. Ketik Nama Perumahan, lalu Cek</label>
                          <div class="flex items-center gap-3">
                              <input type="text" id="search_kompleks" autocomplete="off"
                                     x-model.debounce.300ms="searchQuery"
                                     :disabled="status === 'found' || status === 'checking'"
                                     @keydown.enter.prevent="checkKompleks"
                                     placeholder="Contoh: Griya Permata"
                                     class="form-input flex-grow">
                              
                              <button type="button" @click="checkKompleks" x-show="status === 'idle' || status === 'notFound'" :disabled="searchQuery.length < 3"
                                      class="px-5 py-2.5 text-sm font-semibold text-white bg-orange-500 rounded-lg hover:bg-orange-600 disabled:bg-gray-300 disabled:cursor-not-allowed transition">
                                  Cek
                              </button>
                              
                              <div x-show="status === 'checking'" class="flex items-center gap-2 text-blue-600 font-semibold text-sm">
                                  <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                  Mengecek...
                              </div>
                          </div>
                      </div>

                      {{-- HASIL DITEMUKAN (SUCCESS) --}}
                      <div x-show="status === 'found'" x-transition class="mt-4 p-4 bg-white border border-green-300 rounded-lg">
                          <div class="flex items-center justify-between">
                              <div>
                                  <p class="font-bold text-green-700" x-text="foundKompleks.nama_komplek"></p>
                                  <p class="text-xs text-gray-600" x-text="`${foundKompleks.kelurahan.nama_kelurahan}, ${foundKompleks.kelurahan.kecamatan.nama_kecamatan}`"></p>
                              </div>
                              <button type="button" @click="resetSearch" class="text-sm font-semibold text-blue-600 hover:underline">Ganti</button>
                          </div>
                      </div>
                      
                      {{-- HASIL TIDAK DITEMUKAN (ERROR) --}}
                      <div x-show="status === 'notFound'" x-transition class="mt-4 p-6 rounded-lg border-2 border-dashed border-red-300 bg-red-50 text-center">
                        <h3 class="font-bold text-red-800">Nama Perumahan Tidak Ditemukan</h3>
                        <p class="text-sm text-red-700 mt-2 max-w-lg mx-auto">Perumahan "<strong x-text="searchQuery"></strong>" belum terdaftar di database kami. Anda dapat mengajukan pendaftaran komplek baru agar dapat mengajukan proposal di masa mendatang.</p>
                        <button type="button" class="mt-4 px-5 py-2.5 text-sm font-semibold text-white bg-red-500 rounded-lg hover:bg-red-600 transition shadow">
                            Ajukan Pendaftaran Komplek Baru
                        </button>
                        <p class="text-xs text-gray-500 mt-3">Tombol ini akan tersedia di pembaruan selanjutnya.</p>
                      </div>
                  </div>
                  
                  <input type="hidden" name="kompleks_id" x-model="kompleksId">
                  @error('kompleks_id')<p class="form-error">Anda harus memilih komplek yang valid.</p>@enderror

                  {{-- LANGKAH 2 (TERKUNCI) --}}
                  <div class="border-t pt-6 mt-6 space-y-6" x-show="status === 'found'" x-transition>
                      <h3 class="font-bold text-gray-800">Langkah 2: Lengkapi Detail Proposal</h3>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
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
                          <div class="md:col-span-2">
                            <label for="alamat" class="form-label">Detail Alamat</label>
                            <input id="alamat" name="alamat" value="{{ old('alamat') }}" required class="form-input" placeholder="Jln. Griya, No. 12, Blok A, RT/RW..."/>
                            @error('alamat')<p class="form-error">{{ $message }}</p>@enderror>
                          </div>
                          <div class="md:col-span-2">
                            <label for="proposal" class="form-label">Unggah Proposal <span class="font-normal text-gray-500">(Format: PDF, DOC, DOCX. Maks 10MB)</span></label>
                            <input id="proposal" type="file" name="proposal" accept=".pdf,.doc,.docx" required class="w-full rounded-xl border border-gray-200 bg-gray-50
                          file:mr-4 file:rounded-lg file:border-0 file:bg-[#FFA72B] file:px-4 file:py-2 file:text-white
                          hover:file:brightness-95 focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 transition"/>
                            @error('proposal')<p class="form-error">{{ $message }}</p>@enderror>
                          </div>
                          <div class="md:col-span-2">
                            <label for="catatan" class="form-label">Catatan <span class="font-normal text-gray-500">(Opsional)</span></label>
                            <textarea id="catatan" name="catatan" rows="4" class="form-input">{{ old('catatan') }}</textarea>
                          </div>
                      </div>
                      <div class="flex justify-end pt-4">
                        <button type="submit" class="btn-gradient w-full md:w-auto">Kirim Proposal</button>
                      </div>
                  </div>
                </form>
            </div>
        @else
            <div class="text-center bg-orange-50/50 rounded-xl p-8 md:p-12 border border-orange-200/80">
                <div class="mx-auto w-16 h-16 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center shadow-lg mb-5">
                    <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H4.5a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Silakan Masuk Terlebih Dahulu</h3>
                <p class="text-gray-600 mt-2 mb-6 max-w-md mx-auto">
                    Anda harus memiliki akun dan masuk untuk dapat mengajukan proposal.
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
    .btn-gradient { position: relative; display: inline-flex; justify-content: center; align-items: center; padding: 0.75rem 1.5rem; font-weight: 600; color: white; background: linear-gradient(to right, #FFA72B, #F16A00); border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1); transition: all 0.2s ease-in-out; }
    .btn-gradient:disabled { background: #D1D5DB; cursor: not-allowed; }
</style>

<script>
    function proposalForm() {
        return {
            kecamatanId: '',
            kelurahanId: '',
            kelurahans: [],
            searchQuery: '',
            status: 'idle', // idle, checking, found, notFound
            foundKompleks: null,
            kompleksId: '',

            fetchKelurahans() {
                this.resetSearch(); // Reset pencarian jika kecamatan berubah
                this.kelurahanId = '';
                this.kelurahans = [];
                if (!this.kecamatanId) return;
                
                fetch(`/get-kelurahan/${this.kecamatanId}`)
                    .then(response => response.json())
                    .then(data => {
                        this.kelurahans = data;
                    });
            },

            checkKompleks() {
                if (this.searchQuery.length < 3 || !this.kelurahanId) return;
                
                this.status = 'checking';
                const controller = new AbortController();
                const timeoutId = setTimeout(() => {
                    controller.abort();
                    this.status = 'notFound'; // Anggap not found jika timeout
                }, 10000); // 10 detik timeout

                fetch(`/verify-kompleks`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        q: this.searchQuery,
                        kelurahan_id: this.kelurahanId
                    }),
                    signal: controller.signal
                })
                .then(response => {
                    clearTimeout(timeoutId);
                    if (!response.ok) throw new Error('Server response not ok');
                    return response.json();
                })
                .then(result => {
                    if (result.status === 'found') {
                        this.status = 'found';
                        this.foundKompleks = result.data;
                        this.kompleksId = result.data.id;
                    } else {
                        this.status = 'notFound';
                    }
                })
                .catch(error => {
                    this.status = 'notFound';
                    console.error('Fetch error:', error.name === 'AbortError' ? 'Request timed out' : error);
                });
            },

            resetSearch() {
                this.searchQuery = '';
                this.status = 'idle';
                this.foundKompleks = null;
                this.kompleksId = '';
            }
        }
    }
</script>
{{-- PENTING: Pastikan Anda menambahkan baris ini di dalam <head> pada file layout utama Anda (misal: layouts/public.blade.php) --}}
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
@endsection