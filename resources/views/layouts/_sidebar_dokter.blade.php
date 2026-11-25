{{-- SIDEBAR DOKTER - REVISI TOTAL (SESUAI BUSINESS PROCESS) --}}
<aside class="w-64 bg-[#970747] text-white flex flex-col flex-shrink-0 sticky top-0 h-screen">
    {{-- Logo & Brand --}}
    <div class="flex items-center px-5 py-4 border-b border-white/20">
        <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl mr-3 shadow-lg backdrop-blur-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </div>
        <div>
            <h2 class="text-base font-bold text-white">Pet Area</h2>
            <p class="text-xs text-pink-200 font-medium">Doctor Portal</p>
        </div>
    </div>

    {{-- Navigation Menu --}}
    <nav class="flex-1 px-3 py-3">
        <ul class="space-y-1">
            {{-- A. Dashboard --}}
            <li class="sidebar-nav-item">
                <a href="{{ route('dokter.dashboard') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dokter.dashboard') ? 'bg-[#7a0538] text-white border-l-4 border-white' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            {{-- B. Antrian Pemeriksaan (WORKSPACE UTAMA) --}}
            @php
                $waitingCount = cache()->remember('sidebar_waiting_count', 30, function() {
                    return \App\Models\Queue::whereDate('queue_date', today())
                        ->where('status', 'waiting')
                        ->count();
                });
            @endphp
            <li class="sidebar-nav-item">
                <a href="{{ route('dokter.antrian.index') }}" 
                   class="flex items-center justify-between px-4 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dokter.antrian.*') || request()->routeIs('dokter.examinations.create') ? 'bg-[#7a0538] text-white border-l-4 border-white' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span class="font-medium">Antrian Periksa</span>
                    </div>
                    @if($waitingCount > 0)
                    <span id="sidebar-waiting-badge" class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">
                        {{ $waitingCount }}
                    </span>
                    @else
                    <span id="sidebar-waiting-badge" class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full animate-pulse" style="display:none">
                        0
                    </span>
                    @endif
                </a>
            </li>

            {{-- C. Rekam Medis (semua riwayat rekam medis) --}}
            <li class="sidebar-nav-item">
                <a href="{{ route('dokter.riwayat.index') }}"
                   class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 {{ (request()->routeIs('dokter.riwayat.index') || request()->routeIs('dokter.riwayat.edit') || request()->routeIs('dokter.riwayat.update')) && request()->get('mode') != 'pasien' ? 'bg-[#7a0538] text-white border-l-4 border-white' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium">Rekam Medis</span>
                </a>
            </li>

            {{-- C2. Riwayat Pasien (per pasien) --}}
            <li class="sidebar-nav-item">
                <a href="{{ route('dokter.riwayat.index', ['mode' => 'pasien']) }}"
                   class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 {{ (request()->routeIs('dokter.riwayat.show') || request()->get('mode') == 'pasien') ? 'bg-[#7a0538] text-white border-l-4 border-white' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="font-medium">Riwayat Pasien</span>
                </a>
            </li>

            {{-- D. Laporan Medis --}}
            <li class="sidebar-nav-item">
                <a href="{{ route('dokter.laporan.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dokter.laporan.*') ? 'bg-[#7a0538] text-white border-l-4 border-white' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium">Laporan Medis</span>
                </a>
            </li>

            {{-- Divider --}}
            <li class="my-3 border-t border-white/20"></li>

            {{-- E. Manajemen Akun --}}
            <li class="sidebar-nav-item">
                <a href="{{ route('dokter.staff.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dokter.staff.*') ? 'bg-[#7a0538] text-white border-l-4 border-white' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="font-medium">Kelola Admin</span>
                </a>
            </li>

            <li class="sidebar-nav-item">
                <a href="{{ route('dokter.password.edit') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dokter.password.*') ? 'bg-[#7a0538] text-white border-l-4 border-white' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <span class="font-medium">Ganti Password</span>
                </a>
            </li>
        </ul>
    </nav>

    {{-- User Info --}}
    <div class="p-3 border-t border-white/20">
        <div class="flex items-center gap-3 p-3 bg-[#7a0538] rounded-xl">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(Auth::user()->name ?? 'D', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Dokter' }}</p>
                <p class="text-xs text-pink-200">Dokter Hewan</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="p-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors" 
                        title="Logout">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>