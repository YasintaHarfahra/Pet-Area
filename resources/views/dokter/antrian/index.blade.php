@extends('layouts.dokter_app')

@section('title', 'Antrian Pemeriksaan')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 space-y-6">

    {{-- 1. HEADER STATS --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Antrian Hari Ini</h2>
            <p class="text-sm text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            {{-- Badge Menunggu --}}
            <div class="flex items-center gap-2 px-4 py-2 bg-yellow-50 text-yellow-700 rounded-lg border border-yellow-100">
                <span class="flex h-2 w-2 rounded-full bg-yellow-500"></span>
                <span class="text-sm font-semibold">Menunggu: <span id="count-waiting">{{ $waitingQueues->count() }}</span></span>
            </div>
            
            {{-- Badge Diproses --}}
            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg border border-blue-100">
                <span class="flex h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                <span class="text-sm font-semibold">Diproses: <span id="count-in-progress">{{ $inProgressQueues->count() }}</span></span>
            </div>

            {{-- Badge Selesai --}}
            <div class="flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 rounded-lg border border-green-100">
                <span class="flex h-2 w-2 rounded-full bg-green-500"></span>
                <span class="text-sm font-semibold">Selesai: <span id="count-completed">{{ $completedQueues->count() }}</span></span>
            </div>
        </div>
    </div>

    {{-- 2. GABUNGKAN SEMUA DATA ANTRIAN UNTUK LIST --}}
    @php
        $semuaAntrian = collect([])
            ->concat($inProgressQueues)
            ->concat($waitingQueues);
    @endphp

    {{-- 3. LIST ANTRIAN (FLOATING ROWS) --}}
    <div class="space-y-4" id="queue-list-container">
        @include('dokter.antrian._queue_list', ['waitingQueues' => $waitingQueues, 'inProgressQueues' => $inProgressQueues])
    </div>
</div>

{{-- Auto-refresh indicator --}}
<div id="live-indicator" class="fixed bottom-4 right-4 flex items-center gap-2 bg-white/90 backdrop-blur px-3 py-1.5 rounded-full shadow-md border border-gray-200 text-xs text-gray-500 z-50">
    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
    <span>Live</span>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const POLL_INTERVAL = 10000; // 10 detik
    const pollUrl = @json(route('dokter.api.antrian.poll'));
    let lastHash = '';

    async function pollAntrian() {
        try {
            const res = await fetch(pollUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;
            const data = await res.json();

            // Update badge counts
            const cw = document.getElementById('count-waiting');
            const ci = document.getElementById('count-in-progress');
            const cc = document.getElementById('count-completed');
            if (cw) cw.textContent = data.waiting_count;
            if (ci) ci.textContent = data.in_progress_count;
            if (cc) cc.textContent = data.completed_count;

            // Update sidebar badge
            const sidebarBadge = document.getElementById('sidebar-waiting-badge');
            if (sidebarBadge) {
                sidebarBadge.textContent = data.waiting_count;
                sidebarBadge.style.display = data.waiting_count > 0 ? '' : 'none';
            }

            // Update queue list HTML only if content changed
            const newHash = data.waiting_count + '-' + data.in_progress_count + '-' + data.completed_count;
            if (newHash !== lastHash) {
                lastHash = newHash;
                const container = document.getElementById('queue-list-container');
                if (container && data.html) {
                    container.innerHTML = data.html;
                }
            }
        } catch (e) {
            // Silent fail — will retry next interval
        }
    }

    // Start polling
    setInterval(pollAntrian, POLL_INTERVAL);
    
    // Also poll on page visibility change (when user switches back to tab)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) pollAntrian();
    });
})();
</script>
@endpush