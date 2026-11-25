@extends('layouts.admin_app')

@section('title', 'Edit Obat')
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
            <h1 class="text-xl font-bold text-slate-800">Edit Obat</h1>
            <p class="text-sm text-slate-500">{{ $medicine->nama_obat }}</p>
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

        <form action="{{ route('admin.medicines.update', $medicine) }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                {{-- Nama Obat --}}
                <div>
                    <label for="nama_obat" class="block text-sm font-medium text-slate-700 mb-1">
                        Nama Obat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_obat" name="nama_obat" value="{{ old('nama_obat', $medicine->nama_obat) }}" required
                           class="w-full px-4 py-2 rounded-lg border {{ $errors->has('nama_obat') ? 'border-red-500' : 'border-slate-200' }} focus:border-[#970747] focus:ring-1 focus:ring-[#970747]">
                </div>

                {{-- Satuan & Dosis --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="satuan" class="block text-sm font-medium text-slate-700 mb-1">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <select id="satuan" name="satuan" required
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747] bg-white">
                            @foreach(['tablet', 'kapsul', 'botol', 'vial', 'ampul', 'tube', 'sachet', 'strip'] as $sat)
                                <option value="{{ $sat }}" {{ old('satuan', $medicine->satuan) === $sat ? 'selected' : '' }}>{{ ucfirst($sat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="dosis" class="block text-sm font-medium text-slate-700 mb-1">Dosis</label>
                        <input type="text" id="dosis" name="dosis" value="{{ old('dosis', $medicine->dosis) }}"
                               class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747]"
                               placeholder="Contoh: 250mg, 500mg">
                    </div>
                </div>

                {{-- Harga --}}
                <div>
                    <label for="harga_jual" class="block text-sm font-medium text-slate-700 mb-1">
                        Harga Jual (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $medicine->harga_jual) }}" required min="0"
                           class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747]">
                </div>

                {{-- Stok & Stok Minimum --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="stok" class="block text-sm font-medium text-slate-700 mb-1">
                            Stok <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="stok" name="stok" value="{{ old('stok', $medicine->stok) }}" required min="0"
                               class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747]">
                    </div>
                    <div>
                        <label for="stok_minimum" class="block text-sm font-medium text-slate-700 mb-1">
                            Stok Minimum
                        </label>
                        <input type="number" id="stok_minimum" name="stok_minimum" value="{{ old('stok_minimum', $medicine->stok_minimum) }}" min="0"
                               class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747]">
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                              class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747]">{{ old('keterangan', $medicine->keterangan) }}</textarea>
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                           {{ old('is_active', $medicine->is_active) ? 'checked' : '' }}
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
                    Update Obat
                </button>
            </div>
        </form>
    </div>
@endsection
