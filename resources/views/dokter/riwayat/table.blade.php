<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    @if($medicalRecords->isEmpty())
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada riwayat</h3>
            <p class="text-gray-500">Riwayat pemeriksaan pasien akan muncul di sini.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. RM</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Pemeriksaan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Hewan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pemilik</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($medicalRecords as $record)
                    <tr class="hover:bg-pink-50/50 transition-colors duration-200">
                        {{-- 1. No RM --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-[#970747]">{{ $record->pet->no_rm ?? '-' }}</span>
                        </td>

                        {{-- 2. Tgl Pemeriksaan --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-bold">{{ $record->visit_date ? \Carbon\Carbon::parse($record->visit_date)->format('d/m/Y') : '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $record->visit_date ? \Carbon\Carbon::parse($record->visit_date)->format('H:i') : '-' }} WIB</div>
                        </td>

                        {{-- 3. Nama Hewan --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                @if($record->pet?->foto_hewan_url)
                                    <img src="{{ $record->pet->foto_hewan_url }}" alt="Foto {{ $record->pet->nama_hewan ?? 'Hewan' }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-pink-100 text-pink-600 font-bold flex items-center justify-center">
                                        {{ strtoupper(substr($record->pet->nama_hewan ?? 'P', 0, 1)) }}
                                    </div>
                                @endif
                                <span class="text-sm font-medium text-gray-800">{{ $record->pet->nama_hewan ?? '-' }}</span>
                            </div>
                        </td>

                        {{-- 4. Nama Pemilik --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700">{{ $record->pet->owner->nama_lengkap ?? $record->pet->owner->nama ?? '-' }}</span>
                        </td>

                        {{-- 5. Aksi --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Detail (Ikon Mata) --}}
                                <a href="{{ route('dokter.riwayat.record', $record->id_medical_record) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 bg-sky-500 hover:bg-blue-600 text-white rounded-lg transition shadow-sm" 
                                   title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Logika Edit: Cek apakah sudah lewat 24 jam --}}
                                @php 
                                    $isEditable = $record->created_at->diffInHours(now()) < 24; 
                                @endphp

                                @if($isEditable)
                                    {{-- Tombol Edit (Ikon Pensil) --}}
                                    <a href="{{ route('dokter.riwayat.edit', $record->id_medical_record) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition shadow-sm" 
                                       title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                @else
                                    {{-- Tombol Terkunci (Ikon Gembok) --}}
                                    <button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed" 
                                            title="Terkunci (Sudah > 24 Jam)" 
                                            disabled>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </button>
                                @endif

                                {{-- Tombol Hapus (Ikon Silang) --}}
                                <form action="{{ route('dokter.riwayat.destroy', $record->id_medical_record) }}" 
                                      method="POST" 
                                      class="inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus rekam medis ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg transition shadow-sm" 
                                            title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>