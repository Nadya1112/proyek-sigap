<x-filament::page>
    {{-- Header actions --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold">Daftar Dokumen</h2>

        <x-filament::button icon="heroicon-o-plus" color="warning" wire:click="openCreate">
            Tambah Dokumen
        </x-filament::button>
    </div>

    {{-- Grid card --}}
    @if (count($docs))
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($docs as $d)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-base font-semibold text-gray-800">{{ $d['title'] }}</div>
                            <div class="mt-1 text-xs text-gray-500">
                                {{ $d['ext'] }}
                                @if($d['year']) • {{ $d['year'] }} @endif
                                @if($d['size']) • {{ number_format($d['size']) }} KB @endif
                            </div>
                        </div>
                        <x-filament::badge color="warning">{{ $d['ext'] }}</x-filament::badge>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <x-filament::button size="sm" color="gray" icon="heroicon-o-arrow-down-tray" wire:click="download('{{ $d['id'] }}')">
                            Unduh
                        </x-filament::button>

                        <x-filament::button size="sm" color="info" icon="heroicon-o-pencil-square" wire:click="openEdit('{{ $d['id'] }}')">
                            Edit
                        </x-filament::button>

                        <x-filament::button size="sm" color="primary" icon="heroicon-o-arrow-path" wire:click="openReplace('{{ $d['id'] }}')">
                            Ganti File
                        </x-filament::button>

                        <x-filament::button size="sm" color="danger" icon="heroicon-o-trash"
                            wire:click="delete('{{ $d['id'] }}')"
                            x-on:confirm="{
                                title: 'Hapus dokumen?',
                                icon: 'warning',
                                accept: { label: 'Hapus', method: () => $wire.delete('{{ $d['id'] }}') },
                                cancel: { label: 'Batal' }
                            }">
                            Hapus
                        </x-filament::button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <x-filament::empty-state icon="heroicon-o-document-text"
                                 heading="Belum ada dokumen"
                                 description="Unggah dokumen pertama Anda untuk ditampilkan di halaman publik." />
    @endif

    {{-- ===== Modals ===== --}}

    {{-- Create Modal --}}
    <x-filament::modal wire:model="createOpen" width="lg">
        <x-slot name="header">
            <h3 class="text-lg font-semibold">Tambah Dokumen</h3>
        </x-slot>

        <div class="space-y-4">
            <x-filament::input.wrapper wire:ignore>
                <x-filament::input.label>Judul Dokumen</x-filament::input.label>
                <x-filament::input wire:model.defer="createTitle" placeholder="cth. Perda No 1 Tahun 2023" />
                @error('createTitle') <p class="text-danger-600 text-sm mt-1">{{ $message }}</p> @enderror
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.label>Tahun (opsional)</x-filament::input.label>
                <x-filament::input.numeric wire:model.defer="createYear" placeholder="2024" />
                @error('createYear') <p class="text-danger-600 text-sm mt-1">{{ $message }}</p> @enderror
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.label>File (PDF/DOC/DOCX, maks 20MB)</x-filament::input.label>
                <x-filament::file-upload wire:model="createFile" accept=".pdf,.doc,.docx" />
                @error('createFile') <p class="text-danger-600 text-sm mt-1">{{ $message }}</p> @enderror
            </x-filament::input.wrapper>
        </div>

        <x-slot name="footer">
            <x-filament::button color="gray" wire:click="$set('createOpen', false)">Batal</x-filament::button>
            <x-filament::button color="warning" wire:click="submitCreate">Unggah</x-filament::button>
        </x-slot>
    </x-filament::modal>

    {{-- Edit Modal --}}
    <x-filament::modal wire:model="editOpen" width="lg">
        <x-slot name="header">
            <h3 class="text-lg font-semibold">Edit Dokumen</h3>
        </x-slot>

        <div class="space-y-4">
            <x-filament::input.wrapper>
                <x-filament::input.label>Judul Dokumen</x-filament::input.label>
                <x-filament::input wire:model.defer="editTitle" />
                @error('editTitle') <p class="text-danger-600 text-sm mt-1">{{ $message }}</p> @enderror
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.label>Tahun (opsional)</x-filament::input.label>
                <x-filament::input.numeric wire:model.defer="editYear" />
                @error('editYear') <p class="text-danger-600 text-sm mt-1">{{ $message }}</p> @enderror
            </x-filament::input.wrapper>
        </div>

        <x-slot name="footer">
            <x-filament::button color="gray" wire:click="$set('editOpen', false)">Batal</x-filament::button>
            <x-filament::button color="warning" wire:click="submitEdit">Simpan</x-filament::button>
        </x-slot>
    </x-filament::modal>

    {{-- Replace Modal --}}
    <x-filament::modal wire:model="replaceOpen" width="lg">
        <x-slot name="header">
            <h3 class="text-lg font-semibold">Ganti File</h3>
        </x-slot>

        <div class="space-y-4">
            <x-filament::input.wrapper>
                <x-filament::input.label>Judul (opsional, kosongkan bila sama)</x-filament::input.label>
                <x-filament::input wire:model.defer="replaceTitle" />
                @error('replaceTitle') <p class="text-danger-600 text-sm mt-1">{{ $message }}</p> @enderror
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.label>Tahun (opsional)</x-filament::input.label>
                <x-filament::input.numeric wire:model.defer="replaceYear" />
                @error('replaceYear') <p class="text-danger-600 text-sm mt-1">{{ $message }}</p> @enderror
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.label>File Baru (PDF/DOC/DOCX)</x-filament::input.label>
                <x-filament::file-upload wire:model="replaceFile" accept=".pdf,.doc,.docx" />
                @error('replaceFile') <p class="text-danger-600 text-sm mt-1">{{ $message }}</p> @enderror
            </x-filament::input.wrapper>
        </div>

        <x-slot name="footer">
            <x-filament::button color="gray" wire:click="$set('replaceOpen', false)">Batal</x-filament::button>
            <x-filament::button color="warning" wire:click="submitReplace">Ganti</x-filament::button>
        </x-slot>
    </x-filament::modal>
</x-filament::page>
