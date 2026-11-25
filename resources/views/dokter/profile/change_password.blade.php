@extends('layouts.dokter_app')

@section('title', 'Ganti Password - Dokter')
@section('header_title', 'Ganti Password')

@section('content')
    <div class="max-w-2xl mx-auto fade-in pb-12">
        
        {{-- Header Section --}}
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Amankan Akun Anda</h2>
            <p class="text-gray-500">Perbarui password Anda secara berkala untuk menjaga keamanan akun.</p>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-100 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-4 shadow-sm">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 text-green-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="font-bold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-100 text-red-700 px-6 py-4 rounded-2xl mb-8 flex items-start gap-4 shadow-sm">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 text-red-600 mt-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div>
                    <p class="font-bold mb-1">Terjadi Kesalahan</p>
                    <ul class="list-disc list-inside text-sm space-y-1 ml-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Form Card --}}
        <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-pink-50/50 border border-pink-50 relative overflow-hidden">
            {{-- Decorative Background Element --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-pink-50 to-transparent rounded-full -mr-32 -mt-32 opacity-50 pointer-events-none"></div>

            <form action="{{ route('dokter.change_password') }}" method="POST" class="relative z-10 space-y-6">
                @csrf
                @method('PUT')

                {{-- Current Password --}}
                <div class="space-y-2">
                    <label for="current_password" class="block text-sm font-bold text-gray-700 ml-1">Password Saat Ini</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 w-12 flex items-center justify-center pointer-events-none text-gray-400 group-focus-within:text-[#970747] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" id="current_password" name="current_password" required
                               class="w-full h-12 pl-12 pr-4 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#970747] focus:border-transparent transition-all placeholder-gray-400"
                               placeholder="Masukkan password lama Anda">
                    </div>
                </div>

                <hr class="border-gray-100 my-6">

                {{-- New Password --}}
                <div class="space-y-2">
                    <label for="new_password" class="block text-sm font-bold text-gray-700 ml-1">Password Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 w-12 flex items-center justify-center pointer-events-none text-gray-400 group-focus-within:text-[#970747] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 19.464a3 3 0 01-.879.879l-2.121.707 1.414-1.414a2 2 0 01.293-2.536l.293-.293 2.828-2.828A6 6 0 0121 9zM7 13L5.414 14.586a2 2 0 00-.586 1.414V19a2 2 0 002 2h3a2 2 0 001.414-.586L12.586 15"></path></svg>
                        </div>
                        <input type="password" id="new_password" name="new_password" required
                               class="w-full h-12 pl-12 pr-4 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#970747] focus:border-transparent transition-all placeholder-gray-400"
                               placeholder="Minimal 8 karakter">
                    </div>
                    <p class="text-xs text-gray-500 ml-1">Gunakan kombinasi huruf, angka, dan simbol untuk keamanan lebih baik.</p>
                </div>

                {{-- Confirm Password --}}
                <div class="space-y-2">
                    <label for="new_password_confirmation" class="block text-sm font-bold text-gray-700 ml-1">Konfirmasi Password Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 w-12 flex items-center justify-center pointer-events-none text-gray-400 group-focus-within:text-[#970747] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                               class="w-full h-12 pl-12 pr-4 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#970747] focus:border-transparent transition-all placeholder-gray-400"
                               placeholder="Ulangi password baru">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 flex items-center gap-4">
                    <button type="submit"
                            class="flex-1 bg-[#970747] hover:bg-[#7a0538] text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-pink-600/30 hover:shadow-pink-600/50 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Update Password
                    </button>
                    <a href="{{ route('dokter.dashboard') }}" class="px-6 py-3.5 text-gray-500 font-bold hover:text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection