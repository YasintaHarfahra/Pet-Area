@extends('layouts.dokter_app')

@section('title', 'Manajemen Staff')

@section('content')
<div class="max-w-lg mx-auto py-8 px-4 md:px-8">
    {{-- Top Header - Minimalist --}}
    <div class="flex items-center justify-between mb-3">
        <div>
            <h1 class="text-3xl font-extrabold text-[#970747] drop-shadow mb-1">Tambah Admin</h1>
            <p class="text-base text-slate-500 font-medium">Lengkapi formulir di bawah untuk mendaftarkan staf baru.</p>
        </div>
        <a href="{{ route('dokter.staff.index') }}" class="text-xs font-bold text-pink-600 hover:text-pink-800 transition-all flex items-center gap-1 uppercase tracking-wider">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>

    {{-- Error Banner - Clean & Professional --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-100 p-4 mb-8 rounded-xl flex gap-3 items-start">
            <div class="text-red-500 mt-0.5">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-red-800">Mohon perbaiki kesalahan berikut:</p>
                <ul class="text-xs text-red-600 list-disc list-inside mt-1">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Form Section - Split Layout Concept --}}
    <form action="{{ route('dokter.staff.store') }}" method="POST" class="bg-white rounded-2xl shadow-xl border border-pink-100 overflow-hidden">
        @csrf
        <div class="p-8 space-y-7">
            
            {{-- Bagian Profil --}}
            <section class="space-y-6">
                <h3 class="text-xs font-black text-pink-600 uppercase tracking-[0.2em] border-b border-pink-50 pb-2">Informasi Profil</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-bold text-pink-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                           <input type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full bg-pink-50 border border-pink-200 rounded-xl px-4 py-3 text-base focus:bg-white focus:border-[#970747] focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-pink-700 uppercase tracking-wider mb-2">Email Staf</label>
                           <input type="email" name="email" value="{{ old('email') }}" required 
                               class="w-full bg-pink-50 border border-pink-200 rounded-xl px-4 py-3 text-base focus:bg-white focus:border-[#970747] focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-pink-700 uppercase tracking-wider mb-2">Username</label>
                           <input type="text" name="username" value="{{ old('username') }}" required 
                               class="w-full bg-pink-50 border border-pink-200 rounded-xl px-4 py-3 text-base focus:bg-white focus:border-[#970747] focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                    </div>
                </div>
            </section>

            {{-- Bagian Keamanan --}}
            <section class="space-y-6 pt-4">
                <h3 class="text-xs font-black text-pink-600 uppercase tracking-[0.2em] border-b border-pink-50 pb-2">Keamanan Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[13px] font-bold text-pink-700 uppercase tracking-wider mb-2">Password</label>
                           <input type="password" name="password" required 
                               class="w-full bg-pink-50 border border-pink-200 rounded-xl px-4 py-3 text-base focus:bg-white focus:border-[#970747] focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-pink-700 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                           <input type="password" name="password_confirmation" required 
                               class="w-full bg-pink-50 border border-pink-200 rounded-xl px-4 py-3 text-base focus:bg-white focus:border-[#970747] focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                    </div>
                </div>
            </section>
        </div>

        {{-- Action Footer --}}
        <div class="bg-pink-50 px-8 py-5 flex flex-col md:flex-row items-center justify-between gap-3 border-t border-pink-100">
            <p class="text-xs text-pink-500 font-medium italic">Pastikan data sudah benar sebelum menyimpan.</p>
            <button type="submit" 
                    class="bg-[#970747] hover:bg-[#7a0538] text-white font-bold py-2 px-6 rounded-xl shadow-lg shadow-pink-200 transition-all active:scale-95 text-base uppercase tracking-widest">
                Daftarkan Admin
            </button>
        </div>
    </form>
</div>
@endsection