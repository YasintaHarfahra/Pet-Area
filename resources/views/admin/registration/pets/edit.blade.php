@extends('layouts.admin_app')

@section('title', 'Edit Hewan - Pet Area Clinic')

@push('styles')
<style>
    .fade-up {
        animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .input-focus {
        transition: all 0.2s ease;
    }
    .input-focus:focus {
        box-shadow: 0 0 0 4px rgba(151, 7, 71, 0.1);
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    
    {{-- Breadcrumb --}}
    <nav class="mb-8 fade-up">
        <a href="{{ route('admin.registration.owners.show', $pet->owner) }}" 
           class="inline-flex items-center gap-2 text-gray-500 hover:text-[#970747] transition-colors group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span class="text-sm font-medium">Kembali ke {{ $pet->owner->nama }}</span>
        </a>
    </nav>

    {{-- Error Messages --}}
    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3 fade-up">
        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <ul class="text-red-700 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- LEFT: Pet Info Card --}}
        <div class="lg:col-span-4 fade-up" style="animation-delay: 0.05s">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-6">
                @php
                    $avatarColors = [
                        'Anjing' => 'bg-amber-500',
                        'Kucing' => 'bg-violet-500',
                        'Burung' => 'bg-sky-500',
                        'Kelinci' => 'bg-pink-500',
                        'Hamster' => 'bg-emerald-500',
                    ];
                    $avatarColor = $avatarColors[$pet->jenis] ?? 'bg-gray-500';
                @endphp
                <div class="text-center mb-6">
                    <div class="w-20 h-20 mx-auto {{ $avatarColor }} rounded-full flex items-center justify-center mb-4 shadow-lg">
                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr($pet->nama_hewan, 0, 1)) }}</span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#970747]/10 rounded-lg mb-2">
                        <span class="text-sm font-bold text-[#970747]">{{ $pet->no_rm }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900">{{ $pet->nama_hewan }}</h3>
                    <p class="text-sm text-gray-500">{{ $pet->jenis }}{{ $pet->ras ? ' · ' . $pet->ras : '' }}</p>
                </div>
                
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <div>
                            <p class="text-xs text-gray-400">Pemilik</p>
                            <span class="text-gray-700 font-medium">{{ $pet->owner->nama }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-gray-700">{{ $pet->owner->no_hp }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>No. Rekam Medis tidak dapat diubah</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Form --}}
        <div class="lg:col-span-8 fade-up" style="animation-delay: 0.1s">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-100">
                    <h1 class="text-xl font-bold text-gray-900">Edit Data Hewan</h1>
                    <p class="text-sm text-gray-500 mt-1">Ubah informasi hewan peliharaan</p>
                </div>

                {{-- Form --}}
                <form action="{{ route('admin.registration.pets.update', $pet) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-6">
                        
                        {{-- Row 1: Nama --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Hewan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_hewan" value="{{ old('nama_hewan', $pet->nama_hewan) }}" required
                                   class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none"
                                   placeholder="contoh: Milo, Kitty, Max">
                        </div>

                        {{-- Row 2: Jenis & Ras --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jenis Hewan <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis" required
                                        class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none bg-white">
                                    <option value="">Pilih jenis...</option>
                                    <option value="Anjing" {{ old('jenis', $pet->jenis) == 'Anjing' ? 'selected' : '' }}>🐕 Anjing</option>
                                    <option value="Kucing" {{ old('jenis', $pet->jenis) == 'Kucing' ? 'selected' : '' }}>🐈 Kucing</option>
                                    <option value="Kelinci" {{ old('jenis', $pet->jenis) == 'Kelinci' ? 'selected' : '' }}>🐇 Kelinci</option>
                                    <option value="Hamster" {{ old('jenis', $pet->jenis) == 'Hamster' ? 'selected' : '' }}>🐹 Hamster</option>
                                    <option value="Burung" {{ old('jenis', $pet->jenis) == 'Burung' ? 'selected' : '' }}>🐦 Burung</option>
                                    <option value="Reptil" {{ old('jenis', $pet->jenis) == 'Reptil' ? 'selected' : '' }}>🦎 Reptil</option>
                                    <option value="Lainnya" {{ old('jenis', $pet->jenis) == 'Lainnya' ? 'selected' : '' }}>📦 Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Ras</label>
                                <input type="text" name="ras" value="{{ old('ras', $pet->ras) }}"
                                       class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none"
                                       placeholder="contoh: Golden Retriever, Persia">
                            </div>
                        </div>

                        {{-- Row 3: Tgl Lahir & Kelamin --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $pet->tgl_lahir?->format('Y-m-d')) }}"
                                       max="{{ date('Y-m-d') }}"
                                       class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                                <select name="jenis_kelamin"
                                        class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none bg-white">
                                    <option value="">Pilih...</option>
                                    <option value="jantan" {{ old('jenis_kelamin', $pet->jenis_kelamin) == 'jantan' ? 'selected' : '' }}>♂️ Jantan</option>
                                    <option value="betina" {{ old('jenis_kelamin', $pet->jenis_kelamin) == 'betina' ? 'selected' : '' }}>♀️ Betina</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 4: Status Hidup --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Status Hewan <span class="text-red-500">*</span>
                            </label>
                            <select name="status_hidup" id="status_hidup"
                                    class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none bg-white">
                                <option value="hidup" {{ old('status_hidup', $pet->status_hidup ?? 'hidup') == 'hidup' ? 'selected' : '' }}>Hidup (aktif)</option>
                                <option value="mati" {{ old('status_hidup', $pet->status_hidup) == 'mati' ? 'selected' : '' }}>Meninggal</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Hewan</label>
                            <input type="file" name="foto_hewan" id="foto_hewan" accept=".jpg,.jpeg,.png,.webp"
                                   class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none bg-white">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG/WEBP, maksimal 2 MB</p>
                            <img id="foto_preview"
                                 src="{{ $pet->foto_hewan_url ?? '' }}"
                                 alt="Preview Foto Hewan"
                                 class="mt-3 w-28 h-28 rounded-xl object-cover border border-gray-200 {{ $pet->foto_hewan_url ? '' : 'hidden' }}">
                        </div>

                        <div id="kematian_fields" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kematian</label>
                                <input type="date" name="tanggal_kematian" value="{{ old('tanggal_kematian', $pet->tanggal_kematian?->format('Y-m-d')) }}"
                                       max="{{ date('Y-m-d') }}"
                                       class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Penyebab Kematian</label>
                                <input type="text" name="penyebab_kematian" value="{{ old('penyebab_kematian', $pet->penyebab_kematian) }}"
                                       class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none"
                                       placeholder="Opsional, contoh: Gagal ginjal kronis">
                            </div>
                        </div>

                        {{-- Row 5: Catatan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                            <textarea name="catatan" rows="4"
                                      class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#970747] focus:outline-none resize-none"
                                      placeholder="Catatan khusus tentang hewan (alergi, kondisi khusus, dll)">{{ old('catatan', $pet->catatan) }}</textarea>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.registration.owners.show', $pet->owner) }}" 
                           class="px-5 py-2.5 text-gray-600 hover:text-gray-800 font-medium text-sm transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-[#970747] hover:bg-[#7a0538] text-white rounded-xl font-semibold text-sm transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleKematianFields() {
        const status = document.getElementById('status_hidup').value;
        const fields = document.getElementById('kematian_fields');
        fields.style.display = status === 'mati' ? 'grid' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const status = document.getElementById('status_hidup');
        status.addEventListener('change', toggleKematianFields);
        toggleKematianFields();

        const fotoInput = document.getElementById('foto_hewan');
        const fotoPreview = document.getElementById('foto_preview');
        fotoInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) {
                return;
            }
            fotoPreview.src = URL.createObjectURL(file);
            fotoPreview.classList.remove('hidden');
        });
    });
</script>
@endpush
