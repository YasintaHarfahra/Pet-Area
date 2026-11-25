{{-- Partial: Tabel antrian admin (untuk AJAX polling) --}}
@forelse($queues as $queue)
    <tr class="table-row-hover transition-all duration-200">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-[#970747] to-purple-600 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-sm font-bold text-white">{{ $queue->queue_number }}</span>
                </div>
                <span class="text-sm font-semibold text-gray-900">#{{ $queue->queue_number }}</span>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-3">
                @if($queue->pet?->foto_hewan_url)
                    <img src="{{ $queue->pet->foto_hewan_url }}" alt="Foto {{ $queue->pet?->nama_hewan ?? 'Hewan' }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                @else
                    <div class="w-10 h-10 rounded-full bg-pink-100 text-[#970747] font-bold flex items-center justify-center">
                        {{ strtoupper(substr($queue->pet?->nama_hewan ?? 'P', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <div class="text-sm font-semibold text-gray-900">{{ $queue->pet?->nama_hewan ?? '-' }}</div>
                    <div class="text-xs text-gray-500">{{ $queue->pet?->owner?->nama ?? '-' }} | {{ $queue->pet?->jenis ?? '-' }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900">{{ Str::limit($queue->complaint, 50) ?: '-' }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @switch($queue->status)
                @case('waiting')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        <span class="w-2 h-2 bg-yellow-600 rounded-full mr-2 mt-1"></span>Menunggu
                    </span>
                    @break
                @case('in-progress')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-600 rounded-full mr-2 mt-1"></span>Sedang Dilayani
                    </span>
                    @break
                @case('completed')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                        <span class="w-2 h-2 bg-gray-600 rounded-full mr-2 mt-1"></span>Selesai
                    </span>
                    @break
                @case('cancelled')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        <span class="w-2 h-2 bg-red-600 rounded-full mr-2 mt-1"></span>Dibatalkan
                    </span>
                    @break
            @endswitch
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="flex items-center justify-center space-x-2">
                <a href="{{ route('admin.queues.show', $queue->id_queue) }}" 
                   class="p-2 text-[#970747] hover:text-[#7a0538] hover:bg-pink-50 rounded-lg transition-colors"
                   title="View Details">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </a>
                @if($queue->status === 'waiting')
                    <form action="{{ route('admin.queues.cancel', $queue->id_queue) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to cancel this queue?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                                title="Cancel Queue">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                @endif
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
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada antrian</h3>
                    <p class="text-gray-500">Daftarkan pasien dari halaman Detail Pemilik.</p>
                </div>
            </div>
        </td>
    </tr>
@endforelse
