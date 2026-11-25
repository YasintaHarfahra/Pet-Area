{{-- SIDEBAR ADMIN - DEEP MAGENTA THEME (COMPACT) --}}
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
            <p class="text-xs text-pink-200 font-medium">Admin Portal</p>
        </div>
    </div>

    {{-- Navigation Menu - Compact --}}
    <nav class="flex-1 px-3 py-3">
        <ul class="space-y-1">
            {{-- Dashboard --}}
            <li class="sidebar-nav-item">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.dashboard') 
                      ? 'bg-[#7a0538] text-white border-l-4 border-white' 
                      : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            {{-- Pemilik & Hewan --}}
            <li class="sidebar-nav-item">
                <a href="{{ route('admin.registration.owners.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.registration.*') 
                      ? 'bg-[#7a0538] text-white border-l-4 border-white' 
                      : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="font-medium">Pemilik & Hewan</span>
                </a>
            </li>

            {{-- Antrian Pasien --}}
            <li class="sidebar-nav-item">
                <a href="{{ route('admin.queues.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.queues.*') 
                      ? 'bg-[#7a0538] text-white border-l-4 border-white' 
                      : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span class="font-medium">Antrian Pasien</span>
                </a>
            </li>

            {{-- Kasir --}}
            <li class="sidebar-nav-item">
                <a href="{{ route('admin.cashier.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.cashier.*') 
                      ? 'bg-[#7a0538] text-white border-l-4 border-white' 
                      : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-medium">Kasir</span>
                </a>
            </li>

            {{-- Stok Obat --}}
            <li class="sidebar-nav-item">
                <a href="{{ route('admin.medicines.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.medicines.*') 
                      ? 'bg-[#7a0538] text-white border-l-4 border-white' 
                      : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    <span class="font-medium">Stok Obat</span>
                </a>
            </li>

            {{-- Surat Rujukan --}}
            <li class="sidebar-nav-item">
                <a href="{{ route('admin.referrals.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.referrals.*') 
                      ? 'bg-[#7a0538] text-white border-l-4 border-white' 
                      : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium">Surat Rujukan</span>
                </a>
            </li>
        </ul>
    </nav>

    {{-- User Info --}}
    <div class="p-3 border-t border-white/20">
        <div class="flex items-center gap-3 p-3 bg-[#7a0538] rounded-xl">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-pink-200">Administrator</p>
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