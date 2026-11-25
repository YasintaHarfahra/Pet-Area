<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Pet Area Clinic</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-[#970747] to-[#7a0538]">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl p-8 flex flex-col items-center">
            
            <div class="mb-6">
                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-[#970747] to-purple-600 rounded-2xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Lupa Password?</h1>
                <p class="text-gray-500 text-sm leading-relaxed px-2">Masukkan email dokter Anda untuk menerima link pemulihan kata sandi.</p>
            </div>

            <form action="{{ route('forgot.password.send') }}" method="POST" class="w-full flex flex-col gap-5">
                @csrf
                
                <div class="flex flex-col gap-2">
                    <label for="email" class="text-sm font-semibold text-gray-700 ml-1">
                        Email Dokter
                    </label>
                    <input type="email" name="email" id="email" 
                        class="w-full px-4 py-3 border-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#970747]/20 transition-all
                        {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200 focus:border-[#970747]' }}" 
                        placeholder="contoh@example.test" required autofocus />
                </div>

                <button type="submit" 
                    class="w-full py-3 bg-[#970747] hover:bg-[#7a0538] text-white font-bold rounded-xl shadow-md transition-all active:scale-[0.98]">
                    Kirim Link Reset
                </button>
            </form>

            @if(session('status'))
                <div class="mt-6 w-full p-3 bg-emerald-50 text-emerald-700 rounded-lg text-center text-xs font-bold border border-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mt-6 w-full p-3 bg-rose-50 text-rose-700 rounded-lg text-center text-xs font-bold border border-rose-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="mt-8">
                <a href="{{ route('login') }}" class="group flex items-center text-sm font-bold text-[#970747] hover:text-[#7a0538] transition-colors">
                    <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>