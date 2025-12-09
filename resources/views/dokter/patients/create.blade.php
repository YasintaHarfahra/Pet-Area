@extends('layouts.dokter_app')

@section('title', 'Tambah Pasien Baru')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Pasien Baru</h2>
            <a href="{{ route('dokter.patients.index') }}" class="text-gray-600 hover:text-gray-900 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm leading-5 font-medium text-red-800">
                            Terdapat kesalahan pada input Anda
                        </h3>
                        <div class="mt-2 text-sm leading-5 text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('dokter.patients.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            
            <div class="p-8 space-y-8">
                {{-- Informasi Hewan --}}
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                        Informasi Hewan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Hewan <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Mochi"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        </div>
                        <div>
                            <label for="species" class="block text-sm font-medium text-gray-700 mb-1">Jenis Hewan <span class="text-red-500">*</span></label>
                            <input type="text" id="species" name="species" value="{{ old('species') }}" required placeholder="Contoh: Kucing, Anjing"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        </div>
                        <div>
                            <label for="breed" class="block text-sm font-medium text-gray-700 mb-1">Ras</label>
                            <input type="text" id="breed" name="breed" value="{{ old('breed') }}" placeholder="Contoh: Persia, Golden Retriever"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Umur Hewan</label>
                            <div class="grid grid-cols-2 gap-3">
                                <select id="age_years" name="age_years" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Tahun</option>
                                    @for ($i = 0; $i <= 30; $i++)
                                        <option value="{{ $i }}" {{ old('age_years') == $i ? 'selected' : '' }}>{{ $i }} th</option>
                                    @endfor
                                </select>
                                <select id="age_months" name="age_months" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Bulan</option>
                                    @for ($i = 0; $i <= 11; $i++)
                                        <option value="{{ $i }}" {{ old('age_months') == $i ? 'selected' : '' }}>{{ $i }} bln</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select id="gender" name="gender" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Jantan" {{ old('gender') == 'Jantan' ? 'selected' : '' }}>Jantan</option>
                                <option value="Betina" {{ old('gender') == 'Betina' ? 'selected' : '' }}>Betina</option>
                            </select>
                        </div>
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Berat (kg)</label>
                            <input type="number" step="0.01" id="weight" name="weight" value="{{ old('weight') }}" placeholder="0.00"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Informasi Pemilik --}}
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        Informasi Pemilik
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik <span class="text-red-500">*</span></label>
                            <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required placeholder="Nama Lengkap Pemilik"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        </div>
                        <div>
                            <label for="owner_phone" class="block text-sm font-medium text-gray-700 mb-1">No. HP Pemilik</label>
                            <input type="text" id="owner_phone" name="owner_phone" value="{{ old('owner_phone') }}" placeholder="08xxxxxxxxxx"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        </div>
                        <div class="md:col-span-2">
                            <label for="owner_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Pemilik</label>
                            <textarea id="owner_address" name="owner_address" rows="3" placeholder="Alamat Lengkap"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">{{ old('owner_address') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label for="owner_email" class="block text-sm font-medium text-gray-700 mb-1">Email Pemilik</label>
                            <input type="email" id="owner_email" name="owner_email" value="{{ old('owner_email') }}" placeholder="email@contoh.com"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-8 py-6 flex items-center justify-end gap-4 border-t border-gray-100">
                <a href="{{ route('dokter.patients.index') }}" class="px-6 py-2.5 rounded-xl text-gray-700 font-medium hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all transform hover:-translate-y-0.5">
                    Simpan Pasien
                </button>
            </div>
        </form>
    </div>
@endsection
