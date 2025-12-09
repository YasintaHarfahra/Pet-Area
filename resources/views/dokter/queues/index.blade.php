@extends('layouts.dokter_app')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Bagian Header dan Status Cards (Kompak) tetap menggunakan kode revisi terakhir Anda --}}
    {{-- ... (Kode untuk Header dan Status Cards di sini) ... --}}
    <div class="mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Daftar Antrian Pasien 🩺</h1>
        <p class="text-gray-600 text-lg">{{ now()->format('l, d F Y') }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Card: Menunggu (Waiting) --}}
        <button type="button" data-status="waiting" title="Lihat yang menunggu"
                class="status-card bg-white rounded-xl shadow-lg p-4 flex items-center gap-4 text-left focus:outline-none focus:ring-4 focus:ring-yellow-100 transition duration-200 hover:shadow-xl">
            <div class="flex items-center flex-shrink-0">
                <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Menunggu</p>
                </div>
            </div>
            <div class="ml-auto">
                <h3 class="text-3xl font-extrabold text-yellow-600">{{ $queues->where('status', 'waiting')->count() }}</h3>
            </div>
        </button>

        {{-- Card: Sedang Dilayani (In-Progress) --}}
        <button type="button" data-status="in-progress" title="Lihat yang sedang diperiksa"
                class="status-card bg-white rounded-xl shadow-lg p-4 flex items-center gap-4 text-left focus:outline-none focus:ring-4 focus:ring-green-100 transition duration-200 hover:shadow-xl">
            <div class="flex items-center flex-shrink-0">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Sedang Dilayani</p>
                </div>
            </div>
            <div class="ml-auto">
                <h3 class="text-3xl font-extrabold text-green-600">{{ $queues->where('status', 'in-progress')->count() }}</h3>
            </div>
        </button>

        {{-- Card: Selesai Hari Ini (Completed) --}}
        <button type="button" data-status="completed" title="Lihat yang selesai"
                class="status-card bg-white rounded-xl shadow-lg p-4 flex items-center gap-4 text-left focus:outline-none focus:ring-4 focus:ring-blue-100 transition duration-200 hover:shadow-xl">
            <div class="flex items-center flex-shrink-0">
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Selesai Hari Ini</p>
                </div>
            </div>
            <div class="ml-auto">
                <h3 class="text-3xl font-extrabold text-blue-600">{{ $queues->where('status', 'completed')->count() }}</h3>
            </div>
        </button>
    </div>
    
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Antrian</h2>
            

                <div class="w-full sm:w-72">
                    <label for="queueSearch" class="sr-only">Cari antrian</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input id="queueSearch" type="text" placeholder="Cari nama / MRN / keluhan..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 w-full text-sm">
                    </div>
                </div>
            </div>

            <div class="queue-table" id="waiting-table">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Antrian</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">MRN</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keluhan</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($queues->where('status', 'waiting')->sortBy('queue_number') as $queue)
                            <tr class="table-row-hover transition-all duration-200 queue-item" data-search="{{ strtolower($queue->patient->name . ' ' . $queue->patient->medical_record_number . ' ' . ($queue->complaint ?: '')) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-sm font-bold text-white">#{{ $queue->queue_number }}</span>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ $queue->queue_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $queue->patient->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $queue->patient->medical_record_number }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ Str::limit($queue->complaint ?: 'Tidak ada keluhan', 60) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('dokter.queues.show', $queue->id) }}" 
                                           class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors"
                                           title="Mulai Pemeriksaan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center space-y-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Antrian Kosong</h3>
                                            <p class="text-gray-500">Belum ada pasien yang menunggu untuk diperiksa.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="queue-table hidden" id="in-progress-table">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Antrian</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">MRN</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mulai Pemeriksaan</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($queues->where('status', 'in-progress')->sortBy('queue_number') as $queue)
                            <tr class="table-row-hover transition-all duration-200 queue-item" data-search="{{ strtolower($queue->patient->name . ' ' . $queue->patient->medical_record_number) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-sm font-bold text-white">#{{ $queue->queue_number }}</span>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ $queue->queue_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $queue->patient->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $queue->patient->medical_record_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">
                                        {{ $queue->updated_at ? $queue->updated_at->format('H:i') : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($queue->medicalRecord)
                                        <a href="{{ route('dokter.queues.show', $queue->id) }}"
                                           class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                           title="Lanjut Pemeriksaan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center space-y-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Pemeriksaan</h3>
                                            <p class="text-gray-500">Belum ada pasien yang sedang diperiksa.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="queue-table hidden" id="completed-table">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Antrian</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">MRN</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($queues->where('status', 'completed')->sortByDesc('updated_at') as $queue)
                            <tr class="table-row-hover transition-all duration-200 queue-item" data-search="{{ strtolower($queue->patient->name . ' ' . $queue->patient->medical_record_number) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-500 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-sm font-bold text-white">#{{ $queue->queue_number }}</span>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ $queue->queue_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $queue->patient->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $queue->patient->medical_record_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">
                                        {{ $queue->updated_at ? $queue->updated_at->format('H:i') : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($queue->medicalRecord)
                                        <a href="{{ route('dokter.queues.show', $queue->id) }}"
                                           class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                                           title="Lihat Rekam Medis">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center space-y-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Pemeriksaan Selesai</h3>
                                            <p class="text-gray-500">Belum ada pasien yang menyelesaikan pemeriksaan hari ini.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    /* Styling for active tab */
    .tab-button.active-tab {
        color: #2563eb; /* blue-600 */
        border-bottom: 3px solid #3b82f6; /* blue-500 */
        background-color: #eff6ff; /* blue-50 */
    }
</style>
<script>
    // ... (Script JS yang lain tidak berubah, tetap menggunakan fungsi activateTab, updateQueueStatus, dll.) ...

    // Helper: activate a tab by status
    function activateTab(status) {
        const allBtns = document.querySelectorAll('.tab-button');
        allBtns.forEach(btn => {
            btn.classList.remove('active-tab');
            btn.classList.remove('text-blue-600');
            btn.classList.add('text-gray-500');
        });
        document.querySelectorAll('.queue-table').forEach(table => table.classList.add('hidden'));

        const btn = Array.from(allBtns).find(b => b.dataset.status === status);
        if (btn) {
            btn.classList.remove('text-gray-500');
            btn.classList.add('active-tab');
            btn.classList.add('text-blue-600');
        }

        const table = document.getElementById(`${status}-table`);
        if (table) table.classList.remove('hidden');
        applySearchFilter();
    }

    // Tab switching (button click)
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => activateTab(button.dataset.status));
    });

    // Status cards also switch tabs when clicked (improves discoverability)
    document.querySelectorAll('.status-card').forEach(card => {
        card.addEventListener('click', () => {
            const status = card.dataset.status;
            if (status) activateTab(status);
        });
        card.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const status = card.dataset.status;
                if (status) activateTab(status);
            }
        });
        // make card focusable and set role to button
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
    });

    // Client-side search/filter
    const queueSearch = document.getElementById('queueSearch');
    function applySearchFilter() {
        if (!queueSearch) return;
        const query = queueSearch.value.trim().toLowerCase();
        const visibleTable = Array.from(document.querySelectorAll('.queue-table')).find(t => !t.classList.contains('hidden'));
        if (!visibleTable) return;

        const items = visibleTable.querySelectorAll('.queue-item');
        items.forEach(item => {
            const hay = (item.getAttribute('data-search') || '').toLowerCase();
            if (!query || hay.indexOf(query) !== -1) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    }

    if (queueSearch) {
        queueSearch.addEventListener('input', applySearchFilter);
    }

    // Update queue status (keeps original server call behavior)
    window.updateQueueStatus = function(queueId, newStatus) {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
        const url = `/dokter/queues/${queueId}/status`; // Disesuaikan dengan asumsi rute PUT

        // Tambahkan konfirmasi untuk aksi Selesai
        if (newStatus === 'completed' && !confirm('Yakin ingin menyelesaikan pemeriksaan antrian ini? Pasien akan dipindahkan ke daftar Selesai.')) {
            return;
        }

        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => {
            if (!response.ok) return response.text().then(text => { throw new Error(text || response.status); });
            return response.json();
        })
        .then(data => {
            if (data.message) {
                // Untuk UX yang lebih baik, reload halaman setelah berhasil
                window.location.reload(); 
            } else {
                throw new Error('Invalid response from server');
            }
        })
        .catch(error => {
            console.error('Update error:', error);
            alert('Terjadi kesalahan saat mengupdate status antrian: ' + (error.message || error));
        });
    }

    // Initialize: make sure waiting tab is visible by default and search applied
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil status dari URL jika ada (setelah reload dari update)
        const urlParams = new URLSearchParams(window.location.search);
        const initialStatus = urlParams.get('status') || 'waiting';
        activateTab(initialStatus);

        // Tambahkan listener untuk mempertahankan status tab setelah reload
        const reloadFunction = window.location.reload;
        window.location.reload = function() {
            const currentStatus = Array.from(document.querySelectorAll('.tab-button')).find(b => b.classList.contains('active-tab'))?.dataset.status || 'waiting';
            window.location.href = window.location.pathname + `?status=${currentStatus}`;
        }
        
    });

    // Auto refresh every 30 seconds to keep queues up-to-date
    // **NOTE**: Menonaktifkan autorefresh 30s agar tidak mengganggu, cukup reload setelah aksi status
    /*
    setInterval(() => {
        window.location.reload();
    }, 30000);
    */
</script>
@endpush
@endsection