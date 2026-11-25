<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    <title>@yield('title', 'Dashboard Admin Klinik Hewan')</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* ================================================
           PAGE TRANSITION ANIMATIONS
           ================================================ */
        
        /* Initial page load animation */
        @keyframes pageSlideIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pageFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Content area animation on load */
        .page-content {
            animation: pageSlideIn 0.4s ease-out forwards;
        }
        
        /* Navigating state - fade out */
        .navigating .page-content {
            opacity: 0.6;
            transform: scale(0.995);
            transition: all 0.2s ease;
        }
        
        /* Loading overlay */
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
        
        .page-loader.active {
            transform: scaleX(0.7);
        }
        
        .page-loader.complete {
            transform: scaleX(1);
            transition: transform 0.2s ease;
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* View Transitions API (Chrome 111+) */
        @view-transition {
            navigation: auto;
        }
        
        ::view-transition-old(root) {
            animation: 0.25s ease-out both fade-out;
        }
        
        ::view-transition-new(root) {
            animation: 0.25s ease-out both fade-in;
        }
        
        @keyframes fade-out {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* ================================================
           SMOOTH INTERACTIONS
           ================================================ */
        
        /* All clickable elements get smooth transitions */
        a, button, .btn, [role="button"] {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Cards hover effect */
        .card, [class*="rounded-xl"], [class*="rounded-lg"] {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover, .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Table rows smooth hover */
        tr {
            transition: background-color 0.15s ease;
        }
        
        /* Sidebar link hover effect */
        .sidebar-nav-item a {
            position: relative;
            transition: all 0.2s ease;
        }
        
        .sidebar-nav-item a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%) scaleY(0);
            width: 4px;
            height: 60%;
            background: white;
            border-radius: 0 4px 4px 0;
            transition: transform 0.2s ease;
        }
        
        .sidebar-nav-item a:hover::before,
        .sidebar-nav-item.active a::before {
            transform: translateY(-50%) scaleY(1);
        }
        
        /* ================================================
           EXISTING STYLES
           ================================================ */
        
        /* Custom styles for sidebar active state and scrollbar */
        .sidebar-nav-item.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 0.375rem;
        }
        
        /* Custom scrollbar - Deep Magenta Theme */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #7a0538;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
        
        /* Button Primary Styling - DEEP MAGENTA SOLID */
        .btn-primary {
            background-color: #970747;
            color: white;
            font-weight: 600;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0.5rem;
        }
        .btn-primary:hover {
            background-color: #7a0538;
            box-shadow: 0 4px 12px rgba(151, 7, 71, 0.4);
            transform: translateY(-1px);
        }
        .btn-primary:active {
            transform: translateY(0) scale(0.98);
        }
        
        /* Button Secondary - Outlined Magenta */
        .btn-secondary {
            background-color: white;
            border: 2px solid #970747;
            color: #970747;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-secondary:hover {
            background-color: #FDF2F8;
            border-color: #7a0538;
            color: #7a0538;
            transform: translateY(-1px);
        }
        
        /* Button Danger - SOLID RED */
        .btn-danger {
            background-color: #dc2626;
            color: white;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-danger:hover {
            background-color: #b91c1c;
            transform: translateY(-1px);
        }
        
        /* Gradient Text - Deep Magenta */
        .gradient-text {
            background: linear-gradient(135deg, #970747 0%, #7a0538 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Table Styles - Pink Theme */
        .table-header {
            background-color: #FDF2F8;
        }
        .table-row:hover {
            background-color: #FCE7F3;
        }
        
        /* Ripple effect for buttons */
        .ripple {
            position: relative;
            overflow: hidden;
        }
        .ripple::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, rgba(255,255,255,0.3) 10%, transparent 10%);
            background-repeat: no-repeat;
            background-position: 50%;
            transform: scale(10, 10);
            opacity: 0;
            transition: transform 0.4s, opacity 0.8s;
        }
        .ripple:active::after {
            transform: scale(0, 0);
            opacity: 0.3;
            transition: 0s;
        }
        
        /* Pulse animation for badges */
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .animate-pulse-soft {
            animation: pulse-soft 2s ease-in-out infinite;
        }
        
        /* Skeleton loading */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
        }
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body class="bg-[#FDF2F8]">
    {{-- Page Loading Bar --}}
    <div class="page-loader" id="pageLoader"></div>
    <div class="flex min-h-screen">
        {{-- Sidebar - Sticky --}}
        @include('layouts._sidebar_admin')

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col min-h-screen">
            {{-- Topbar --}}
            @include('layouts._topbar_admin')

            {{-- Page Content with Animation --}}
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
            
            // Show loading bar when navigating
            function startLoading() {
                document.body.classList.add('navigating');
                loader.classList.add('active');
            }
            
            // Complete loading animation
            function completeLoading() {
                loader.classList.remove('active');
                loader.classList.add('complete');
                setTimeout(() => {
                    loader.classList.remove('complete');
                    document.body.classList.remove('navigating');
                }, 300);
            }
            
            // Intercept link clicks for smooth navigation
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (!link) return;
                
                // Skip if modifier keys, external links, anchors, or special links
                if (e.ctrlKey || e.metaKey || e.shiftKey) return;
                if (link.target === '_blank') return;
                if (link.href.startsWith('#') || link.href.includes('#')) return;
                if (link.href.startsWith('javascript:')) return;
                if (link.href.startsWith('mailto:') || link.href.startsWith('tel:')) return;
                if (!link.href.startsWith(window.location.origin)) return;
                if (link.hasAttribute('download')) return;
                
                // Check if it's the same page
                if (link.href === window.location.href) return;
                
                // Start loading animation
                startLoading();
            });
            
            // Handle form submissions
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.method.toLowerCase() !== 'get') {
                    startLoading();
                }
            });
            
            // Complete loading when page is about to unload
            window.addEventListener('beforeunload', function() {
                completeLoading();
            });
            
            // Handle browser back/forward
            window.addEventListener('popstate', function() {
                startLoading();
            });
            
            // Initial page load complete
            window.addEventListener('load', function() {
                completeLoading();
            });
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

            function updateTime() {
                const now = new Date();
                const optionsDate = { day: '2-digit', month: 'short', year: 'numeric' };
                const optionsTime = { hour: '2-digit', minute: '2-digit', hour12: false };
                const formattedDate = now.toLocaleDateString('id-ID', optionsDate).replace('.', '');
                const formattedTime = now.toLocaleTimeString('id-ID', optionsTime);
                const dateTimeEl = document.getElementById('current-date-time');
                if (dateTimeEl) {
                    dateTimeEl.textContent = `${formattedDate} | ${formattedTime}`;
                }
            }
            setInterval(updateTime, 1000);
            updateTime();
            
            // SweetAlert for delete confirmations
            document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
                form.onsubmit = null;
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
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
            
            // Add ripple effect to buttons
            document.querySelectorAll('.btn-primary, .btn-secondary, .btn-danger').forEach(btn => {
                btn.classList.add('ripple');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>