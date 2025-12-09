<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pet Area Clinic')</title>
    {{-- @vite('resources/css/app.css') --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .sidebar-nav-item.active > a { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        .sidebar-nav-item.active > a svg {
            color: white !important;
        }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        .table-row-hover:hover {
            background-color: #f8fafc;
            transform: scale(1.01);
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .status-inactive {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="flex min-h-screen bg-gray-50">
    @include('layouts._sidebar_admin')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('layouts._topbar_admin')
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/20 fade-in">
            @yield('content')
        </main>
    </div>
    <script>
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
                const dateTimeElement = document.getElementById('current-date-time');
                if (dateTimeElement) {
                    dateTimeElement.textContent = `${formattedDate} | ${formattedTime}`;
                }
            }
            setInterval(updateTime, 1000);
            updateTime();
            
            // Add smooth transitions to all interactive elements
            document.querySelectorAll('button, a, .card-hover').forEach(el => {
                el.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });
        });
    </script>
</body>
</html>