@extends('layouts.public')
@section('title','E-Proposal PSU')

{{-- Tambahkan CSRF Token di head layout utama Anda jika belum ada --}}
@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

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

{{-- ========== KONTEN UTAMA (Revisi Final + Jarak Rapi + Form Daftar Selalu Ada) ========== --}}
<main class="py-16 lg:py-24 bg-gray-50">
  <div class="container mx-auto px-6">
    <div class="max-w-4xl mx-auto space-y-8"> {{-- Jarak antar elemen utama --}}

        {{-- Kotak Informasi Persyaratan --}}
        <div class="p-6 rounded-lg bg-blue-50 border border-blue-200 flex gap-4">
            <div class="flex-shrink-0 text-blue-500 pt-1"> {{-- Sesuaikan pt --}}
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg text-blue-800">Perhatikan Persyaratan Berikut</h3>
                <p class="text-sm text-blue-700 mt-1 mb-3">Pastikan Anda memenuhi kriteria sebelum mengajukan proposal:</p>
                <ul class="list-disc list-inside text-sm text-blue-700 space-y-1">
                    <li>Proposal diajukan oleh perwakilan resmi warga (RT/RW) atau Pengembang.</li>
                    <li>Status aset PSU (lahan) perumahan **sudah diserahkan** kepada Pemerintah Kota.</li>
                    <li>Melampirkan dokumen teknis dasar (jika ada, misal: Site Plan).</li>
                    <li>Menggunakan **template proposal** yang disediakan (dapat diunduh di Langkah 2).</li>
                    <li>Proposal menjelaskan urgensi dan detail PSU yang dibutuhkan.</li>
                </ul>
            </div>
        </div>

        {{-- FORM UTAMA --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
            @auth
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Formulir Pengajuan Proposal</h2>
                <p class="text-gray-600 mb-6">Pilih lokasi dan nama perumahan Anda untuk melanjutkan.</p>

                {{-- Notifikasi Sukses --}}
                @if(session('success'))
                  <div x-data="{ show: true }" x-show="show" x-transition
                       x-init="setTimeout(() => show = false, 5000)" {{-- Otomatis hilang setelah 5 detik --}}
                       class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md flex items-start space-x-3"
                       role="alert">
                       <svg class="w-5 h-5 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"> {{-- Tambah mt-0.5 --}}
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.06 0l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-bold">Berhasil!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                  </div>
                @endif

               {{-- Logika Alpine.js --}}
               <div x-data="proposalForm()">
                    <form action="{{ route('eproposal.store') }}" method="POST" enctype="multipart/form-data"> {{-- Hapus space-y-6 dari form --}}
                      @csrf
                      <input type="hidden" name="kompleks_id" x-model="kompleksId">

                      {{-- Langkah 1 --}}
                      <div class="p-5 rounded-lg border-2 space-y-4 mb-6" {{-- Tambah mb-6 --}}
                           :class="kompleksId ? 'border-green-300 bg-green-50' : 'border-gray-200 bg-gray-50'">
                          <h3 class="font-bold text-gray-800">Langkah 1: Pilih Perumahan Anda</h3>
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                              {{-- Dropdown Kecamatan --}}
                              <div>
                                  <label for="kecamatan" class="form-label">1. Pilih Kecamatan</label>
                                  <select id="kecamatan" x-model="kecamatanId" @change="fetchKelurahans" class="form-input" :disabled="!!kompleksId">
                                      <option value="">-Pilih Kecamatan-</option>
                                      @foreach($kecamatans as $kecamatan)
                                        <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                                      @endforeach
                                  </select>
                              </div>
                              {{-- Dropdown Kelurahan --}}
                              <div>
                                  <label for="kelurahan" class="form-label">2. Pilih Kelurahan</label>
                                  <select id="kelurahan" x-model="kelurahanId" @change="fetchKompleks" class="form-input" :disabled="!kecamatanId || kelurahansLoading || !!kompleksId">
                                      <option value="">-Pilih Kelurahan-</option>
                                      <option x-show="kelurahansLoading" disabled>Memuat Kelurahan...</option>
                                      <template x-for="kelurahan in kelurahans" :key="kelurahan.id">
                                          <option :value="kelurahan.id" x-text="kelurahan.nama_kelurahan"></option>
                                      </template>
                                  </select>
                              </div>
                          </div>
                          {{-- Dropdown Nama Komplek --}}
                          <div x-show="kelurahanId" x-transition>
                              <label for="kompleks_select" class="form-label">3. Pilih Nama Perumahan</label>
                              <select id="kompleks_select" x-model="kompleksId" @change="handleKompleksSelection" class="form-input" :disabled="!kelurahanId || kompleksLoading || !!kompleksId">
                                  <option value="">-- Pilih Nama Komplek Perumahan --</option>
                                  <option x-show="kompleksLoading" disabled>Memuat Komplek...</option>
                                  <option x-show="isKompleksListEmpty" disabled value="">-Pilih Nama Komplek Perumahan-</option>
                                  <template x-for="komplek in kompleksList" :key="komplek.id">
                                      <option :value="komplek.id" x-text="komplek.nama_komplek"></option>
                                  </template>
                              </select>
                              @error('kompleks_id')<p class="form-error">Anda harus memilih komplek dari daftar.</p>@enderror
                          </div>

                           {{-- Tombol Reset --}}
                           <div x-show="!!kompleksId" x-transition class="text-right pt-2"> {{-- Sedikit padding atas --}}
                               <button type="button" @click="resetSelection" class="text-sm font-semibold text-blue-600 hover:underline">Pilih Lokasi Lain</button>
                           </div>
                      </div>

                      {{-- Kartu Ajukan Komplek Baru (Selalu muncul jika kelurahan dipilih & komplek belum) --}}
                      <div x-show="kelurahanId && !kompleksId" x-transition class="mb-6">
                          <div class="p-6 rounded-lg border-2 border-dashed border-orange-300 bg-orange-50 text-center">
                                <h3 class="font-bold text-orange-800">Perumahan Belum Terdaftar?</h3>
                                <p class="text-sm text-orange-700 mt-2 max-w-lg mx-auto">Jika nama perumahan Anda tidak ada dalam daftar di atas untuk Kelurahan <strong x-text="selectedKelurahanName || 'yang dipilih'"></strong>, Anda dapat mengajukan pendaftaran komplek baru.</p>
                                {{-- Form Pendaftaran --}}
                                <div class="mt-6 text-left max-w-lg mx-auto space-y-4">
                                    <div>
                                        <label for="new_kompleks_name" class="form-label text-sm text-gray-700">Nama Perumahan Baru</label>
                                        <input type="text" id="new_kompleks_name" placeholder="Contoh: Komplek Melati Indah" class="form-input text-sm">
                                    </div>
                                    <div>
                                        <label for="new_kompleks_alamat" class="form-label text-sm text-gray-700">Alamat Singkat Perumahan</label>
                                        <input type="text" id="new_kompleks_alamat" placeholder="Contoh: Jl. Melati RT 05 RW 01" class="form-input text-sm">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="form-label text-sm text-gray-700">Kecamatan</label>
                                            <input type="text" :value="getSelectedKecamatanName()" disabled class="form-input text-sm bg-gray-100 cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label class="form-label text-sm text-gray-700">Kelurahan</label>
                                            <input type="text" :value="selectedKelurahanName" disabled class="form-input text-sm bg-gray-100 cursor-not-allowed">
                                        </div>
                                    </div>
                                    <div class="pt-2 flex justify-between items-center gap-4">
                                         {{-- Tombol Batal --}}
                                        <button type="button" @click="resetSelection(false)" class="text-sm font-semibold text-gray-600 hover:underline">Batal</button>
                                        {{-- Tombol Ajukan (Placeholder) --}}
                                        <button type="button" class="px-5 py-2.5 text-sm font-semibold text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition shadow">
                                            Ajukan Pendaftaran Komplek Baru
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-3 text-center">Pengajuan akan ditinjau oleh Admin.</p>
                                </div>
                          </div>
                      </div>

                      {{-- Langkah 2 --}}
                      <fieldset :disabled="!kompleksId" class="border-t pt-6 mt-6"> {{-- Jarak diatur di sini --}}
                          <legend class="font-bold text-gray-800 mb-4">Langkah 2: Lengkapi Detail Proposal</legend>
                          <div class="space-y-6"> {{-- Jarak antar baris form --}}
                              <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"> {{-- Jarak grid --}}
                                  {{-- Nama Pengaju --}}
                                  <div>
                                    <label for="nama_pengaju" class="form-label">Nama Lengkap Pengaju</label>
                                    <input id="nama_pengaju" name="nama_pengaju" value="{{ old('nama_pengaju', auth()->user()->name) }}" required class="form-input" :disabled="!kompleksId"/>
                                    @error('nama_pengaju')<p class="form-error">{{ $message }}</p>@enderror
                                  </div>
                                  {{-- Kontak Pengaju --}}
                                  <div>
                                    <label for="kontak_pengaju" class="form-label">Nomor Kontak (WA/Telepon)</label>
                                    <input id="kontak_pengaju" name="kontak_pengaju" value="{{ old('kontak_pengaju', auth()->user()->kontak) }}" required class="form-input" :disabled="!kompleksId"/>
                                    @error('kontak_pengaju')<p class="form-error">{{ $message }}</p>@enderror
                                  </div>
                              </div> {{-- Akhir Grid Nama & Kontak --}}

                              {{-- Alamat --}}
                              <div>
                                <label for="alamat" class="form-label">Detail Alamat</label>
                                <input id="alamat" name="alamat" value="{{ old('alamat') }}" required class="form-input" placeholder="Jln. Griya, No. 12, Blok A, RT/RW..." :disabled="!kompleksId"/>
                                @error('alamat')<p class="form-error">{{ $message }}</p>@enderror
                              </div>

                              {{-- Download Template --}}
                              <div class="pt-2"> {{-- Kurangi pt --}}
                                  <label class="form-label">Template Proposal</label>
                                  <a href="/path/ke/template-proposal.docx" {{-- GANTI PATH --}}
                                     download="Template-Proposal-PSU-SIGAP.docx"
                                     class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold text-orange-700 bg-orange-100 border border-orange-200 hover:bg-orange-200 transition"
                                     :class="{ 'opacity-50 pointer-events-none': !kompleksId }">
                                      <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                      Unduh Template Proposal (.docx)
                                  </a>
                                  <p class="text-xs text-gray-500 mt-1">Gunakan template ini untuk pengajuan Anda.</p>
                              </div>

                              {{-- Unggah Proposal --}}
                              <div>
                                <label for="proposal" class="form-label">Unggah Proposal <span class="font-normal text-gray-500">(Sesuai Template, Format: PDF, DOC, DOCX. Maks 10MB)</span></label>
                                <input id="proposal" type="file" name="proposal" accept=".pdf,.doc,.docx" required class="w-full rounded-xl border border-gray-200 bg-gray-50 file:mr-4 file:rounded-lg file:border-0 file:bg-[#FFA72B] file:px-4 file:py-2 file:text-white hover:file:brightness-95 focus:border-[#F39B28] focus:ring-2 focus:ring-[#F39B28]/40 transition" :disabled="!kompleksId"/>
                                @error('proposal')<p class="form-error">{{ $message }}</p>@enderror
                              </div>

                              {{-- Catatan --}}
                              <div>
                                <label for="catatan" class="form-label">Catatan <span class="font-normal text-gray-500">(Opsional)</span></label>
                                <textarea id="catatan" name="catatan" rows="4" class="form-input" :disabled="!kompleksId">{{ old('catatan') }}</textarea>
                              </div>

                          </div> {{-- Akhir space-y-6 dalam fieldset --}}

                          {{-- Tombol Kirim --}}
                          <div class="flex justify-end pt-6"> {{-- Jarak dari field terakhir --}}
                            <button type="submit" class="btn-gradient w-full md:w-auto" :disabled="!kompleksId">Kirim Proposal</button>
                          </div>
                      </fieldset>
                    </form>
                </div>
            @else
                {{-- Kartu notifikasi login --}}
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
                        </a>
                        <a href="{{ route('register') }}"
                            class="font-semibold text-orange-600 hover:text-orange-700 transition hover:underline">
                            Buat Akun Baru
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</main>

<style>
    .form-label { display: block; margin-bottom: 0.5rem; /* Tambah jarak sedikit */ font-size: 0.875rem; line-height: 1.25rem; font-weight: 600; color: #374151; }
    .form-error { margin-top: 0.25rem; font-size: 0.75rem; line-height: 1rem; color: #EF4444; }
    .form-input { width: 100%; border-radius: 0.75rem; border-width: 1px; border-color: #D1D5DB; background-color: #FFFFFF; padding: 0.75rem 1rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); transition: all 0.2s ease-in-out; }
    .form-input:disabled { background-color: #f3f4f6; cursor: not-allowed; color: #9ca3af; /* Warna sedikit lebih gelap */ border-color: #e5e7eb; } /* Border abu-abu */
    .form-input:focus { border-color: #F97316; outline: 2px solid transparent; outline-offset: 2px; --tw-ring-color: rgba(249, 115, 22, 0.5); box-shadow: 0 0 0 2px var(--tw-ring-color); } /* Ring oranye saat fokus */
    .btn-gradient { /* ... (style tombol gradient tidak berubah) ... */ }
    .btn-gradient:disabled { /* ... (style tombol disabled tidak berubah) ... */ }
</style>

<script>
    function proposalForm() {
        // Ambil data kecamatan dari Blade
        const allKecamatans = @json($kecamatans->keyBy('id') ?? []);

        return {
            kecamatanId: '', kelurahanId: '', kompleksId: '',
            kelurahans: [], kompleksList: [],
            kelurahansLoading: false, kompleksLoading: false,
            // showNotFoundCard dihapus
            selectedKelurahanName: '',

            // Properti komputasi: True jika TIDAK loading, KELURAHAN dipilih, DAN list komplek KOSONG
            get isKompleksListEmpty() {
                return !this.kompleksLoading && this.kelurahanId && this.kompleksList.length === 0;
            },

            getSelectedKecamatanName() {
                return allKecamatans[this.kecamatanId] ? allKecamatans[this.kecamatanId].nama_kecamatan : '';
            },

            fetchKelurahans() {
                this.resetSelection(false);
                this.kelurahanId = ''; this.kompleksId = '';
                this.kelurahans = []; this.kompleksList = [];
                // this.showNotFoundCard = false; // Dihapus
                if (!this.kecamatanId) return;

                this.kelurahansLoading = true;
                fetch(`/get-kelurahan/${this.kecamatanId}`)
                    .then(response => response.ok ? response.json() : [])
                    .then(data => { this.kelurahans = data; })
                    .catch(error => console.error('Error fetching kelurahan:', error))
                    .finally(() => this.kelurahansLoading = false);
            },

            fetchKompleks() {
                this.kompleksId = ''; this.kompleksList = [];
                // this.showNotFoundCard = false; // Dihapus
                if (!this.kelurahanId) return;

                const selectedKel = this.kelurahans.find(k => k.id == this.kelurahanId);
                this.selectedKelurahanName = selectedKel ? selectedKel.nama_kelurahan : '';

                this.kompleksLoading = true;
                fetch(`/get-kompleks-by-kelurahan/${this.kelurahanId}`)
                    .then(response => response.ok ? response.json() : [])
                    .then(data => { this.kompleksList = data; })
                    .catch(error => { console.error('Error fetching kompleks:', error); this.kompleksList = []; })
                    .finally(() => this.kompleksLoading = false); // Pengecekan kekosongan list dilakukan oleh isKompleksListEmpty
            },

            handleKompleksSelection() {
                // this.showNotFoundCard = false; // Dihapus
            },

            resetSelection(resetKecamatan = true) {
                 if (resetKecamatan) this.kecamatanId = '';
                 this.kelurahanId = '';
                 this.kompleksId = '';
                 this.kelurahans = [];
                 this.kompleksList = [];
                 // this.showNotFoundCard = false; // Dihapus
                 this.selectedKelurahanName = '';
            }
        }
    }
</script>
{{-- PENTING: Pastikan meta csrf token ada di layout --}}
@endsection