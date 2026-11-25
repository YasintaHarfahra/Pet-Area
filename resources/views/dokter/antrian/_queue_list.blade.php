{{-- Partial: Daftar antrian dokter (untuk AJAX polling) --}}
@php
    $semuaAntrian = collect([])
        ->concat($inProgressQueues)
        ->concat($waitingQueues);
@endphp

@forelse($semuaAntrian as $index => $queue)
    @php
        $isWaiting = $queue->status === 'waiting';
        $isProcessing = $queue->status === 'processing' || $queue->status === 'in-progress';
        $isCompleted = $queue->status === 'completed';
        
        $borderColor = $isWaiting ? 'border-yellow-400' : ($isProcessing ? 'border-blue-500' : 'border-green-500');
        $opacity = $isCompleted ? 'opacity-75 bg-gray-50' : 'bg-white';
    @endphp

    <div class="group relative {{ $opacity }} rounded-xl shadow-sm p-4 border-l-4 {{ $borderColor }} transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-6 w-full md:w-auto">
                <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 bg-gradient-to-br from-pink-50 to-purple-50 rounded-full border border-pink-100 shadow-inner">
                    <span class="text-2xl font-extrabold text-[#970747]">{{ $queue->queue_number }}</span>
                </div>
                @if($queue->pet?->foto_hewan_url)
                    <img src="{{ $queue->pet->foto_hewan_url }}" alt="Foto {{ $queue->pet?->nama_hewan ?? 'Hewan' }}" class="w-14 h-14 rounded-full object-cover border border-gray-200 shadow-sm">
                @else
                    <div class="w-14 h-14 rounded-full bg-pink-100 text-[#970747] font-bold text-lg flex items-center justify-center shadow-sm">
                        {{ strtoupper(substr($queue->pet?->nama_hewan ?? 'P', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="text-lg font-bold text-gray-900">{{ $queue->pet?->nama_hewan ?? 'Tanpa Nama' }}</h3>
                        @if($queue->pet?->jenis)
                            <span class="px-2 py-0.5 text-xs font-semibold bg-gray-100 text-gray-600 rounded-full">
                                {{ $queue->pet->jenis }}
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mb-1">
                        Pemilik: <span class="font-medium text-gray-700">{{ $queue->pet?->owner?->nama ?? '-' }}</span>
                    </p>
                    @if($queue->complaint)
                        <p class="text-sm text-gray-600 italic truncate max-w-md bg-gray-50 p-1.5 rounded border border-gray-100">
                            "{{ Str::limit($queue->complaint, 60) }}"
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if($isWaiting)
                    <span class="px-3 py-1 text-xs font-bold text-yellow-700 bg-yellow-100 rounded-full">Menunggu</span>
                @elseif($isProcessing)
                    <span class="px-3 py-1 text-xs font-bold text-blue-700 bg-blue-100 rounded-full animate-pulse">Sedang Diperiksa</span>
                @elseif($isCompleted)
                    <span class="px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full">Selesai</span>
                @endif

                @if($isWaiting)
                    <form action="{{ route('dokter.antrian.start', $queue->id_queue) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full shadow-md hover:shadow-lg focus:ring-2 focus:ring-pink-400 {{ $loop->first ? 'animate-pulse ring-4 ring-pink-200' : '' }}">
                            Mulai Periksa
                        </button>
                    </form>
                    <form action="{{ route('dokter.antrian.skip', $queue->id_queue) }}" method="POST" onsubmit="return confirm('Yakin ingin melewati antrian ini?')">
                        @csrf
                        <button type="submit" class="p-2.5 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" title="Lewati">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                        </button>
                    </form>
                @elseif($isProcessing)
                    @if($queue->medicalRecord)
                        <a href="{{ route('dokter.riwayat.edit', ['record' => $queue->medicalRecord->id_medical_record]) }}" class="px-6 py-2.5 text-sm font-bold text-amber-700 bg-amber-100 border border-amber-300 rounded-full shadow-sm hover:bg-amber-200 hover:border-amber-400 hover:shadow-md transition-all duration-200">
                            Lanjutkan Pemeriksaan
                        </a>
                    @else
                        <a href="{{ route('dokter.examinations.create', ['queue' => $queue->id_queue, 'pet_id' => $queue->id_pet]) }}" class="px-6 py-2.5 text-sm font-bold text-amber-700 bg-amber-100 border border-amber-300 rounded-full shadow-sm hover:bg-amber-200 hover:border-amber-400 hover:shadow-md transition-all duration-200">
                            Lanjutkan Pemeriksaan
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>

@empty
    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900">Antrian Kosong</h3>
        <p class="text-gray-500">Belum ada pasien, Dok. Silakan istirahat sejenak ☕.</p>
    </div>
@endforelse
