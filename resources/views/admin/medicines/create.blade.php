@extends('layouts.admin_app')

@section('title', 'Tambah Obat Baru')
@section('header_title', 'Stok Obat')

@section('content')
    <div class="max-w-2xl mx-auto px-4 pb-8">
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('admin.medicines.index') }}" class="text-sm text-[#970747] hover:text-[#7a0538] flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Obat
            </a>
            <h1 class="text-xl font-bold text-slate-800">Tambah Obat Baru</h1>
            <p class="text-sm text-slate-500">Masukkan informasi obat baru</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.medicines.store') }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6">
            @csrf
            
            <div class="space-y-4">
                {{-- Nama Obat --}}
                <div>
                    <label for="nama_obat" class="block text-sm font-medium text-slate-700 mb-1">
                        Nama Obat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_obat" name="nama_obat" value="{{ old('nama_obat') }}" required
                           class="w-full px-4 py-2 rounded-lg border {{ $errors->has('nama_obat') ? 'border-red-500' : 'border-slate-200' }} focus:border-[#970747] focus:ring-1 focus:ring-[#970747]"
                           placeholder="Contoh: Amoxicillin 500mg">
                </div>

                {{-- Satuan & Dosis --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="satuan" class="block text-sm font-medium text-slate-700 mb-1">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <select id="satuan" name="satuan" required
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747] bg-white">
                            <option value="tablet" {{ old('satuan') === 'tablet' ? 'selected' : '' }}>Tablet</option>
                            <option value="kapsul" {{ old('satuan') === 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                            <option value="botol" {{ old('satuan') === 'botol' ? 'selected' : '' }}>Botol</option>
                            <option value="vial" {{ old('satuan') === 'vial' ? 'selected' : '' }}>Vial</option>
                            <option value="ampul" {{ old('satuan') === 'ampul' ? 'selected' : '' }}>Ampul</option>
                            <option value="tube" {{ old('satuan') === 'tube' ? 'selected' : '' }}>Tube</option>
                            <option value="sachet" {{ old('satuan') === 'sachet' ? 'selected' : '' }}>Sachet</option>
                            <option value="strip" {{ old('satuan') === 'strip' ? 'selected' : '' }}>Strip</option>
                        </select>
                    </div>
                    <div>
                        <label for="dosis" class="block text-sm font-medium text-slate-700 mb-1">Dosis</label>
                        <input type="text" id="dosis" name="dosis" value="{{ old('dosis') }}"
                               class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747]"
                               placeholder="Contoh: 250mg, 500mg">
                    </div>
                </div>

                {{-- Harga --}}
                <div>
                    <label for="harga_jual" class="block text-sm font-medium text-slate-700 mb-1">
                        Harga Jual (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', 0) }}" required min="0"
                           class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747]"
                           placeholder="0">
                </div>

                {{-- Stok & Stok Minimum --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="stok" class="block text-sm font-medium text-slate-700 mb-1">
                            Stok Awal <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="stok" name="stok" value="{{ old('stok', 0) }}" required min="0"
                               class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747]"
                               placeholder="0">
                    </div>
                    <div>
                        <label for="stok_minimum" class="block text-sm font-medium text-slate-700 mb-1">
                            Stok Minimum
                        </label>
                        <input type="number" id="stok_minimum" name="stok_minimum" value="{{ old('stok_minimum', 10) }}" min="0"
                               class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747]"
                               placeholder="10">
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                              class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747]"
                              placeholder="Deskripsi atau catatan tentang obat ini">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked
                           class="rounded border-slate-300 text-[#970747] focus:ring-[#970747]">
                    <label for="is_active" class="text-sm text-slate-700">Obat Aktif (bisa diresepkan)</label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <a href="{{ route('admin.medicines.index') }}" 
                   class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors">
                    Simpan Obat
                </button>
            </div>
        </form>
    </div>
@endsection
