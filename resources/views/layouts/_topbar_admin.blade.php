{{-- TOPBAR ADMIN - CLEAN MINIMALIST DESIGN --}}
<header class="h-16 bg-white border-b border-gray-100 px-8 flex items-center justify-between sticky top-0 z-40">
    
    {{-- LEFT: Page Title --}}
    <div class="flex items-center">
        <h1 class="text-xl font-bold text-slate-800">
            @if(request()->routeIs('admin.dashboard'))
                Dashboard
            @elseif(request()->routeIs('admin.registration.*'))
                Pemilik & Hewan
            @elseif(request()->routeIs('admin.queues.*'))
                Antrian Pasien
            @elseif(request()->routeIs('admin.cashier.*'))
                Kasir
            @elseif(request()->routeIs('admin.medicines.*'))
                Stok Obat
            @elseif(request()->routeIs('admin.referrals.*'))
                Surat Rujukan
            @else
                Admin Panel
            @endif
        </h1>
    </div>

    {{-- RIGHT: Info & Status --}}
    <div class="flex items-center gap-6">
        
        {{-- Quick Status Badges --}}
        <div class="hidden lg:flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                Klinik Buka
            </span>
        </div>

        {{-- Date & Time --}}
        <div class="text-right border-l border-gray-200 pl-6">
            <div id="topbar-time" class="text-lg font-bold text-slate-800 tabular-nums">--:--</div>
            <div id="topbar-date" class="text-xs text-slate-500">Loading...</div>
        </div>

        {{-- User Profile --}}
        <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
            <div class="w-9 h-9 bg-[#970747] rounded-full flex items-center justify-center shadow-sm">
                <span class="text-white font-semibold text-sm">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 2)) }}
                </span>
            </div>
            <div class="hidden md:block">
                <div class="text-sm font-semibold text-slate-800">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</div>
                <div class="text-xs text-slate-500">Admin</div>
            </div>
        </div>
    </div>
</header>

<script>
    // Update waktu real-time
    function updateTopbarDateTime() {
        const now = new Date();
        
        // Format waktu HH:MM
        const timeString = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: false 
        });
        
        // Format tanggal: Sabtu, 7 Feb 2026
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const dayName = days[now.getDay()];
        const date = now.getDate();
        const month = months[now.getMonth()];
        const year = now.getFullYear();
        const dateString = `${dayName}, ${date} ${month} ${year}`;
        
        const timeEl = document.getElementById('topbar-time');
        const dateEl = document.getElementById('topbar-date');
        
        if (timeEl) timeEl.textContent = timeString;
        if (dateEl) dateEl.textContent = dateString;
    }

    // Update waktu setiap detik
    updateTopbarDateTime();
    setInterval(updateTopbarDateTime, 1000);
</script>