@extends('layouts.dokter_app')

@section('title', 'Ganti Password')

@section('content')
<div class="max-w-lg mx-auto py-8 px-4 font-sans">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-[#970747] mb-1">Ganti Password</h1>
        <p class="text-sm text-slate-500 font-medium">Ubah password akun Anda untuk menjaga keamanan</p>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex items-center gap-3 shadow-sm transition-all">
        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <div class="space-y-6">
        <form action="{{ route('dokter.password.update') }}" method="POST" class="bg-white rounded-2xl shadow-xl p-6 md:p-8 space-y-5 border border-pink-50">
            @csrf
            @method('PUT')

            {{-- Current Password --}}
            <div class="space-y-1">
                <label for="current_password" class="block text-xs font-black text-pink-700 uppercase tracking-widest">
                    Password Lama <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" 
                           name="current_password" 
                           id="current_password"
                           class="w-full bg-slate-50 rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-[#970747] focus:ring-4 focus:ring-pink-50 transition-all outline-none border-2 @error('current_password') border-red-500 @else border-transparent @enderror"
                           placeholder="Masukkan password saat ini"
                           required>
                    <button type="button" onclick="togglePassword('current_password')" 
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#970747] transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                @error('current_password')
                    <p class="text-[11px] font-bold text-red-600 mt-1 uppercase">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div class="space-y-1">
                <label for="password" class="block text-xs font-black text-pink-700 uppercase tracking-widest">
                    Password Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" 
                           name="password" 
                           id="password"
                           class="w-full bg-slate-50 rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-[#970747] focus:ring-4 focus:ring-pink-50 transition-all outline-none border-2 @error('password') border-red-500 @else border-transparent @enderror"
                           placeholder="Minimal 8 karakter"
                           required>
                    <button type="button" onclick="togglePassword('password')" 
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#970747] transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-[11px] font-bold text-red-600 mt-1 uppercase">{{ $message }}</p>
                @else
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Minimal 8 karakter kombinasi</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-1">
                <label for="password_confirmation" class="block text-xs font-black text-pink-700 uppercase tracking-widest">
                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation"
                           class="w-full bg-slate-50 rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-[#970747] focus:ring-4 focus:ring-pink-50 transition-all outline-none border-2 @error('password_confirmation') border-red-500 @else border-transparent @enderror"
                           placeholder="Ulangi password baru"
                           required>
                    <button type="button" onclick="togglePassword('password_confirmation')" 
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#970747] transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-4 flex justify-end">
                <button type="submit" 
                        class="bg-[#970747] hover:bg-[#7a0538] text-white font-bold py-3 px-8 rounded-2xl shadow-xl shadow-pink-100 transition-all active:scale-95 text-xs uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>

        {{-- Security Tips --}}
        <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-6 shadow-sm">
            <h3 class="font-black text-blue-900 text-xs uppercase tracking-widest mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Tips Keamanan
            </h3>
            <ul class="text-[13px] text-blue-800 space-y-2">
                <li class="flex items-start gap-2">
                    <span class="w-1.5 h-1.5 bg-blue-300 rounded-full mt-1.5"></span>
                    Gunakan kombinasi huruf besar, kecil, angka, dan simbol.
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-1.5 h-1.5 bg-blue-300 rounded-full mt-1.5"></span>
                    Hindari menggunakan tanggal lahir atau nama hewan peliharaan.
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-1.5 h-1.5 bg-blue-300 rounded-full mt-1.5"></span>
                    Ganti password Anda secara berkala setiap 3-6 bulan.
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    if(input) {
        input.type = input.type === 'password' ? 'text' : 'password';
    }
}
</script>
@endsection