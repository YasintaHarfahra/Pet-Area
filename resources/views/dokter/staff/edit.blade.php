@extends('layouts.dokter_app')

@section('title', 'Edit Admin - Dokter')
@section('header_title', 'Edit Admin')

@section('content')
    <div class="flex items-center justify-between mb-3">
        <div>
            <h1 class="text-3xl font-extrabold text-[#970747] drop-shadow mb-1">Edit{{ $staff->name }}</h1>
            <p class="text-base text-slate-500 font-medium">Edit data staf di bawah ini.</p>
        </div>
        <a href="{{ route('dokter.staff.index') }}" class="text-xs font-bold text-pink-600 hover:text-pink-800 transition-all flex items-center gap-1 uppercase tracking-wider">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>

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

    <form action="{{ route('dokter.staff.update', $staff->id_user) }}" method="POST" class="bg-white rounded-2xl shadow-xl border border-pink-100 overflow-hidden max-w-lg mx-auto">
        @csrf
        @method('PUT')
        <div class="p-8 space-y-7">
            <section class="space-y-6">
                <h3 class="text-xs font-black text-pink-600 uppercase tracking-[0.2em] border-b border-pink-50 pb-2">Informasi Profil</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-bold text-pink-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $staff->name) }}" required 
                               class="w-full bg-pink-50 border border-pink-200 rounded-xl px-4 py-3 text-base focus:bg-white focus:border-[#970747] focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-pink-700 uppercase tracking-wider mb-2">Email Staf</label>
                        <input type="email" name="email" value="{{ old('email', $staff->email) }}" required 
                               class="w-full bg-pink-50 border border-pink-200 rounded-xl px-4 py-3 text-base focus:bg-white focus:border-[#970747] focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-pink-700 uppercase tracking-wider mb-2">Username</label>
                        <input type="text" name="username" value="{{ old('username', $staff->username) }}" required 
                               class="w-full bg-pink-50 border border-pink-200 rounded-xl px-4 py-3 text-base focus:bg-white focus:border-[#970747] focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                    </div>
                </div>
            </section>
            <section class="space-y-6 pt-4">
                <h3 class="text-xs font-black text-pink-600 uppercase tracking-[0.2em] border-b border-pink-50 pb-2">Keamanan Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[13px] font-bold text-pink-700 uppercase tracking-wider mb-2">Password <span class="text-xs text-gray-400">(Kosongkan jika tidak ingin diubah)</span></label>
                        <input type="password" name="password"
                               class="w-full bg-pink-50 border border-pink-200 rounded-xl px-4 py-3 text-base focus:bg-white focus:border-[#970747] focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-pink-700 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                               class="w-full bg-pink-50 border border-pink-200 rounded-xl px-4 py-3 text-base focus:bg-white focus:border-[#970747] focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                    </div>
                </div>
            </section>
        </div>

        <div class="bg-pink-50 px-8 py-5 flex flex-col md:flex-row items-center justify-between gap-3 border-t border-pink-100">
            <p class="text-xs text-pink-500 font-medium italic">Pastikan data sudah benar sebelum menyimpan.</p>
            <button type="submit" 
                    class="bg-[#970747] hover:bg-[#7a0538] text-white font-bold py-2 px-6 rounded-xl shadow-lg shadow-pink-200 transition-all active:scale-95 text-base uppercase tracking-widest">
                Update Admin
            </button>
        </div>
    </form>
@endsection