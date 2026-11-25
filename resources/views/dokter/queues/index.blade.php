@extends('layouts.dokter_app')

@section('content')

<div class="container mx-auto px-4 py-6">
    <div class="mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Daftar Antrian Pasien 🩺</h1>
        <p class="text-gray-600 text-lg">{{ now()->format('l, d F Y') }}</p>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Antrian Hari Ini</h2>
            <div class="w-full sm:w-72 mb-4">
                <label for="queueSearch" class="sr-only">Cari antrian</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input id="queueSearch" type="text" placeholder="Cari nama / MRN / keluhan..." 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#970747] w-full text-sm">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Antrian</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">MRN</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keluhan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($queues->sortBy('queue_number') as $queue)
                        <tr class="queue-item" data-search="{{ strtolower($queue->patient->name . ' ' . $queue->patient->medical_record_number . ' ' . ($queue->complaint ?: '')) }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900">#{{ $queue->queue_number }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900">{{ $queue->patient->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $queue->patient->medical_record_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900">{{ Str::limit($queue->complaint ?: 'Tidak ada keluhan', 60) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($queue->status === 'waiting')
                                    <span class="inline-block px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Menunggu</span>
                                @elseif($queue->status === 'in-progress')
                                    <span class="inline-block px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Sedang Diperiksa</span>
                                @elseif($queue->status === 'completed')
                                    <span class="inline-block px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($queue->status === 'waiting')
                                    <a href="{{ route('dokter.queues.show', $queue->id_queue) }}" class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors text-sm font-medium" title="Mulai Pemeriksaan">
                                        Mulai Pemeriksaan
                                    </a>
                                @elseif($queue->status === 'in-progress')
                                    <a href="{{ route('dokter.medical_records.create', ['patient_id' => $queue->id_pet, 'queue_id' => $queue->id_queue, 'resume' => 'true']) }}" class="px-4 py-2 text-white bg-amber-500 hover:bg-amber-600 rounded-lg transition-colors text-sm font-medium" title="Lanjutkan Pemeriksaan">
                                        Lanjutkan Pemeriksaan
                                    </a>
                                @elseif($queue->status === 'completed' && $queue->medicalRecord)
                                    <a href="{{ route('dokter.queues.show', $queue->id_queue) }}" class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors text-sm font-medium" title="Lihat Rekam Medis">
                                        Lihat Rekam Medis
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
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
    </div>
</div>

@push('scripts')
<script>
    // Simple client-side search
    const queueSearch = document.getElementById('queueSearch');
    function applySearchFilter() {
        if (!queueSearch) return;
        const query = queueSearch.value.trim().toLowerCase();
        const items = document.querySelectorAll('.queue-item');
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
</script>
@endpush
@endsection