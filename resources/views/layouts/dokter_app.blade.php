<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>@yield('title', 'Pet Area Clinic - Doctor')</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        /* ================================================
           PAGE TRANSITION ANIMATIONS
           ================================================ */
        
        @keyframes pageSlideIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .page-content {
            animation: pageSlideIn 0.4s ease-out forwards;
        }
        
        .navigating .page-content {
            opacity: 0.6;
            transform: scale(0.995);
            transition: all 0.2s ease;
        }
        
        /* Loading bar */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            height: 3px;
            background: linear-gradient(90deg, #970747, #ec4899, #970747);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite linear;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }
        
        .page-loader.active { transform: scaleX(0.7); }
        .page-loader.complete { transform: scaleX(1); transition: transform 0.2s ease; }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* View Transitions API */
        @view-transition { navigation: auto; }
        ::view-transition-old(root) { animation: 0.25s ease-out both fade-out; }
        ::view-transition-new(root) { animation: 0.25s ease-out both fade-in; }
        @keyframes fade-out { from { opacity: 1; } to { opacity: 0; } }
        @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
        
        /* All clickable elements smooth */
        a, button, .btn, [role="button"] {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* ================================================
           EXISTING STYLES
           ================================================ */
        
        .sidebar-nav-item.active > a { 
            background: #7a0538;
            color: white;
            border-left: 4px solid white;
            box-shadow: 0 4px 12px rgba(151, 7, 71, 0.3);
        }
        .sidebar-nav-item.active > a svg {
            color: white !important;
        }
        
        /* Custom scrollbar - Deep Magenta Theme */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #7a0538; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.5); }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(151, 7, 71, 0.15);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #970747 0%, #7a0538 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-primary {
            background: #970747;
            color: white;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
        }
        
        .btn-primary:hover {
            background: #7a0538;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(151, 7, 71, 0.4);
        }

        .btn-secondary {
            background: white;
            border: 2px solid #970747;
            color: #970747;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
        }
        
        .btn-secondary:hover {
            background: #FDF2F8;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(151, 7, 71, 0.2);
        }
        
        .table-row-hover:hover {
            background-color: #FDF2F8;
            transform: scale(1.005);
            transition: all 0.2s ease;
        }

        /* Queue Status Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.025em;
        }
        
        .status-waiting {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: white;
        }
        
        .status-in-progress {
            background: linear-gradient(135deg, #970747 0%, #7a0538 100%);
            color: white;
        }
        
        .status-completed {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
        }

        .status-cancelled {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: white;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Navigation Styles */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: #374151;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .nav-link:hover {
            background-color: #FDF2F8;
            color: #111827;
        }

        .nav-link.active {
            background-color: #FDF2F8;
            color: #970747;
            font-weight: 500;
        }

        .nav-link svg {
            width: 1.25rem;
            height: 1.25rem;
            color: #9CA3AF;
            transition: color 0.2s;
        }

        .nav-link:hover svg {
            color: #970747;
        }

        .nav-link.active svg {
            color: #970747;
        }

        /* Section Headers */
        .nav-section-header {
            padding: 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* Shadows and Elevation */
        aside {
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #7a0538;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 9999px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
    </style>
</head>
<body class="bg-[#FDF2F8]" style="font-family: 'Inter', 'Segoe UI', 'Arial', 'sans-serif'; font-size: 15px;">
    {{-- Page Loading Bar --}}
    <div class="page-loader" id="pageLoader"></div>
    
    <div class="flex min-h-screen">
        {{-- Sidebar - Sticky --}}
        @include('layouts._sidebar_dokter')

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col min-h-screen">
            {{-- Topbar ala Admin --}}
            <header class="h-16 bg-white border-b border-gray-100 px-8 flex items-center justify-between sticky top-0 z-40">
                {{-- LEFT: Page Title --}}
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-slate-800">
                        @if(request()->routeIs('dokter.dashboard'))
                            Dashboard
                        @elseif(request()->routeIs('rekam-medis.*'))
                            Rekam Medis
                        @elseif(request()->routeIs('riwayat.*'))
                            Riwayat Pasien
                        @elseif(request()->routeIs('laporan.*'))
                            Laporan Medis
                        @elseif(request()->routeIs('admin.*'))
                            Kelola Admin
                        @else
                            Dokter Panel
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
                        <div class="w-9 h-9 bg-gradient-to-br from-[#970747] to-purple-600 rounded-full flex items-center justify-center shadow-sm">
                            <span class="text-white font-semibold text-sm">
                                {{ strtoupper(substr(Auth::guard('dokter')->user()->name ?? 'D', 0, 2)) }}
                            </span>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-sm font-semibold text-slate-800">{{ Auth::guard('dokter')->user()->name ?? 'Dokter' }}</div>
                            <div class="text-xs text-slate-500">Dokter</div>
                        </div>
                    </div>
                </div>
            </header>
            <script>
                // Update waktu real-time
                function updateTopbarDateTime() {
                    const now = new Date();
                    const pad = n => n.toString().padStart(2, '0');
                    document.getElementById('topbar-time').textContent = pad(now.getHours()) + ':' + pad(now.getMinutes());
                    document.getElementById('topbar-date').textContent = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                }
                setInterval(updateTopbarDateTime, 1000);
                updateTopbarDateTime();
            </script>

            {{-- Main Content --}}
            <main class="flex-1 p-6 bg-[#FDF2F8] page-content">
                @yield('content')
            </main>
        </div>
    </div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ================================================
    // PAGE TRANSITION & SMOOTH NAVIGATION
    // ================================================
    (function() {
        const loader = document.getElementById('pageLoader');
        
        function startLoading() {
            document.body.classList.add('navigating');
            loader.classList.add('active');
        }
        
        function completeLoading() {
            loader.classList.remove('active');
            loader.classList.add('complete');
            setTimeout(() => {
                loader.classList.remove('complete');
                document.body.classList.remove('navigating');
            }, 300);
        }
        
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (!link) return;
            if (e.ctrlKey || e.metaKey || e.shiftKey) return;
            if (link.target === '_blank') return;
            if (link.href.startsWith('#') || link.href.includes('#')) return;
            if (link.href.startsWith('javascript:')) return;
            if (link.href.startsWith('mailto:') || link.href.startsWith('tel:')) return;
            if (!link.href.startsWith(window.location.origin)) return;
            if (link.hasAttribute('download')) return;
            if (link.href === window.location.href) return;
            
            startLoading();
        });
        
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.method.toLowerCase() !== 'get') {
                startLoading();
            }
        });
        
        window.addEventListener('beforeunload', function() { completeLoading(); });
        window.addEventListener('popstate', function() { startLoading(); });
        window.addEventListener('load', function() { completeLoading(); });
    })();

    // ================================================
    // EXISTING FUNCTIONALITY
    // ================================================
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.sidebar-nav-item a').forEach(link => {
                const linkPath = link.getAttribute('href').replace(/\/$/, '');
                const currentPathClean = currentPath.replace(/\/$/, '');
                if (linkPath === currentPathClean || (linkPath !== '/' && currentPathClean.startsWith(linkPath + '/'))) {
                    link.closest('.sidebar-nav-item').classList.add('active');
                }
            });
            
            // Add smooth transitions to all interactive elements
            document.querySelectorAll('button, a, .card-hover').forEach(el => {
                el.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });

            // User Menu Dropdown Logic
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');
            const userMenuContainer = document.getElementById('user-menu-container');
            
            if (userMenuButton && userMenuDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenuDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', function(e) {
                    if (userMenuContainer && !userMenuContainer.contains(e.target)) {
                        userMenuDropdown.classList.add('hidden');
                    }
                });
            }
            
            // Global SweetAlert for delete forms
            document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
                form.onsubmit = null;
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: 'Apakah Anda yakin ingin menghapus data ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    {{-- Global: Auto-update sidebar badge antrian (semua halaman dokter) --}}
    <script>
    (function() {
        const SIDEBAR_POLL = 15000; // 15 detik
        const pollUrl = @json(route('dokter.api.antrian.poll'));

        async function updateSidebarBadge() {
            try {
                const res = await fetch(pollUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) return;
                const data = await res.json();
                const badge = document.getElementById('sidebar-waiting-badge');
                if (badge) {
                    badge.textContent = data.waiting_count;
                    badge.style.display = data.waiting_count > 0 ? '' : 'none';
                }
            } catch(e) {}
        }

        // Hanya jalankan global poll jika BUKAN di halaman antrian (halaman antrian punya poll sendiri)
        if (!window.location.pathname.includes('/antrian')) {
            setInterval(updateSidebarBadge, SIDEBAR_POLL);
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) updateSidebarBadge();
            });
        }
    })();
    </script>

    @stack('scripts')
</body>
</html>