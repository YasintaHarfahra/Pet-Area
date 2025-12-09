<aside class="w-72 bg-white border-r border-gray-100 flex flex-col custom-scrollbar shadow-sm">
    <div class="flex items-center mb-8 px-6 py-6">
        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl mr-3">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900">Pet Area</h2>
            <p class="text-sm text-gray-500">Clinic Management</p>
        </div>
    </div>

    <nav class="flex-1 px-4 space-y-6">
        <div>
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-3">Main Menu</h3>
            <ul class="space-y-1">
                <li class="sidebar-nav-item">
                    <a href="{{ route('dashboard.admin') }}" class="flex items-center px-3 py-3 hover:bg-blue-50 rounded-xl transition-all duration-200 group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 mr-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        <span class="text-gray-700 group-hover:text-blue-900 font-medium transition-colors">Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-3">Patient Management</h3>
            <ul class="space-y-1">
                <li class="sidebar-nav-item">
                    <a href="{{ route('admin.queues.index') }}" class="flex items-center px-3 py-3 hover:bg-blue-50 rounded-xl transition-all duration-200 group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 mr-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-gray-700 group-hover:text-blue-900 font-medium transition-colors">Queue Management</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('admin.patients.index') }}" class="flex items-center px-3 py-3 hover:bg-blue-50 rounded-xl transition-all duration-200 group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 mr-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-gray-700 group-hover:text-blue-900 font-medium transition-colors">Patients</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('admin.medical_records.index') }}" class="flex items-center px-3 py-3 hover:bg-blue-50 rounded-xl transition-all duration-200 group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 mr-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-gray-700 group-hover:text-blue-900 font-medium transition-colors">Medical Records</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('admin.visits.index') }}" class="flex items-center px-3 py-3 hover:bg-blue-50 rounded-xl transition-all duration-200 group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 mr-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gray-700 group-hover:text-blue-900 font-medium transition-colors">Visit History</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-3">Finance</h3>
            <ul class="space-y-1">
                <li class="sidebar-nav-item">
                    <a href="{{ route('admin.transactions.index') }}" class="flex items-center px-3 py-3 hover:bg-blue-50 rounded-xl transition-all duration-200 group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 mr-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-gray-700 group-hover:text-blue-900 font-medium transition-colors">Transactions</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>