@extends('layouts.admin_app')

@section('title', 'Tambah Pasien Baru - Admin')

@section('content')
    <h2 class="text-3xl font-semibold text-gray-800 mb-6">Tambah Pasien Baru</h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.patients.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-8">
        @csrf
        
        {{-- Informasi Hewan --}}
        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">Informasi Hewan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Hewan <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="species" class="block text-gray-700 text-sm font-bold mb-2">Jenis Hewan <span class="text-red-500">*</span></label>
                    <input type="text" id="species" name="species" value="{{ old('species') }}" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="breed" class="block text-gray-700 text-sm font-bold mb-2">Ras</label>
                    <input type="text" id="breed" name="breed" value="{{ old('breed') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Umur Hewan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <select id="age_years" name="age_years"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Tahun</option>
                                @for ($i = 0; $i <= 30; $i++)
                                    <option value="{{ $i }}" {{ old('age_years') == $i ? 'selected' : '' }}>{{ $i }} th</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <select id="age_months" name="age_months"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Bulan</option>
                                @for ($i = 0; $i <= 11; $i++)
                                    <option value="{{ $i }}" {{ old('age_months') == $i ? 'selected' : '' }}>{{ $i }} bln</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="gender" class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih</option>
                        <option value="Jantan" {{ old('gender') == 'Jantan' ? 'selected' : '' }}>Jantan</option>
                        <option value="Betina" {{ old('gender') == 'Betina' ? 'selected' : '' }}>Betina</option>
                    </select>
                </div>
                <div>
                    <label for="weight" class="block text-gray-700 text-sm font-bold mb-2">Berat (kg)</label>
                    <input type="number" step="0.01" id="weight" name="weight" value="{{ old('weight') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- Informasi Pemilik --}}
        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">Informasi Pemilik</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="owner_name" class="block text-gray-700 text-sm font-bold mb-2">Nama Pemilik <span class="text-red-500">*</span></label>
                    <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="owner_phone" class="block text-gray-700 text-sm font-bold mb-2">No. HP Pemilik</label>
                    <input type="text" id="owner_phone" name="owner_phone" value="{{ old('owner_phone') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label for="owner_address" class="block text-gray-700 text-sm font-bold mb-2">Alamat Pemilik</label>
                    <textarea id="owner_address" name="owner_address" rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('owner_address') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label for="owner_email" class="block text-gray-700 text-sm font-bold mb-2">Email Pemilik</label>
                    <input type="email" id="owner_email" name="owner_email" value="{{ old('owner_email') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-8 pt-6 border-t">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md focus:outline-none focus:shadow-outline transition duration-150">
                Simpan Pasien
            </button>
            <a href="{{ route('admin.patients.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">Batal</a>
        </div>
    </form>
@endsection