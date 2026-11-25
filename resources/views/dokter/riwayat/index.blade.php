@extends('layouts.dokter_app')

@section('title', 'Riwayat Rekam Medis')


@section('content')
    @if(request()->get('mode') == 'pasien')
        <div class="p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Riwayat Pasien</h1>
                <p class="text-gray-600">Cari dan lihat riwayat medis hewan (read-only)</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 flex items-center justify-between">
                <form action="{{ route('dokter.riwayat.index', ['mode' => 'pasien']) }}" method="GET" class="flex-1">
                    <div class="flex gap-4">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ $search ?? '' }}"
                                   placeholder="Cari nama hewan, nama pemilik, atau nomor telepon..."
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                        </div>
                        <input type="hidden" name="mode" value="pasien">
                        <button type="submit" 
                                class="px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white rounded-lg font-medium transition">
                            Filter
                        </button>
                    </div>
                </form>
            </div>
            {{-- Hasil pencarian --}}
            @if(isset($pets) && $pets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($pets as $pet)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex items-start gap-4">
                            @if($pet->foto_hewan_url)
                            <img src="{{ $pet->foto_hewan_url }}" alt="Foto {{ $pet->nama_hewan }}" class="w-14 h-14 rounded-full object-cover border border-gray-200 flex-shrink-0">
                            @else
                            <div class="w-14 h-14 bg-pink-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-xl font-bold text-pink-600">{{ strtoupper(substr($pet->nama_hewan, 0, 1)) }}</span>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-800 truncate">{{ $pet->nama_hewan }}</h3>
                                <p class="text-sm text-gray-500">{{ $pet->jenis }} - {{ $pet->ras ?? '-' }}</p>
                                <div class="mt-2 flex items-center text-xs text-gray-400">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $pet->owner?->nama ?? '-' }}
                                </div>
                                @if($pet->owner?->no_hp)
                                <div class="flex items-center text-xs text-gray-400 mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $pet->owner->no_hp }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t flex items-center justify-between">
                            <div class="text-xs text-gray-500">
                                {{ $pet->medical_records_count ?? 0 }} rekam medis
                            </div>
                            <a href="{{ route('dokter.riwayat.show', $pet->id_pet) }}" 
                               class="inline-flex items-center gap-1 text-pink-600 hover:text-pink-800 font-medium text-sm">
                                Lihat Riwayat
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @elseif(isset($search))
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Tidak Ditemukan</h3>
                    <p class="text-gray-500">Tidak ada hewan yang cocok dengan pencarian Anda.</p>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Cari Riwayat Pasien</h3>
                    <p class="text-gray-500 max-w-md mx-auto">
                        Masukkan nama hewan, nama pemilik, atau nomor telepon untuk mencari riwayat medis pasien.
                    </p>
                </div>
            @endif
        </div>
    @else
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-[#970747] mb-1">Riwayat Rekam Medis</h2>
            <p class="text-gray-500 mb-4">Daftar pemeriksaan pasien yang telah selesai.</p>
            <div class="bg-pink-50 border border-pink-200 rounded-xl p-4 mb-2 shadow-sm">
                <form id="filterForm" class="flex flex-wrap gap-4 items-end" method="GET" action="">
                    <div>
                        <label class="block text-xs font-bold text-pink-700 mb-1">Nama Hewan</label>
                        <input type="text" name="nama_hewan" value="{{ request('nama_hewan') }}" class="px-3 py-2 border-2 border-pink-200 rounded-lg text-sm focus:ring-2 focus:ring-pink-300" placeholder="Cari hewan...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-pink-700 mb-1">Nama Pemilik</label>
                        <input type="text" name="nama_pemilik" value="{{ request('nama_pemilik') }}" class="px-3 py-2 border-2 border-pink-200 rounded-lg text-sm focus:ring-2 focus:ring-pink-300" placeholder="Cari pemilik...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-pink-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="px-3 py-2 border-2 border-pink-200 rounded-lg text-sm focus:ring-2 focus:ring-pink-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-pink-700 mb-1">Urutkan</label>
                        <select name="sort" class="px-3 py-2 border-2 border-pink-200 rounded-lg text-sm focus:ring-2 focus:ring-pink-300">
                            <option value="desc" @if(request('sort','desc')=='desc') selected @endif>Terbaru</option>
                            <option value="asc" @if(request('sort')=='asc') selected @endif>Terlama</option>
                        </select>
                    </div>
                    <!-- Kolom sort dihapus sesuai permintaan -->
                    <button type="submit" class="px-4 py-2 bg-[#970747] text-white rounded-lg text-sm font-bold shadow hover:bg-pink-700">Filter</button>
                    <a href="{{ route('dokter.riwayat.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold shadow hover:bg-gray-300">Reset</a>
                </form>
            </div>
        </div>
        {{-- Pesan Sukses/Error --}}
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        <div id="antrian-container">
            @include('dokter.riwayat.table')
        </div>
    @endif
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Auto Refresh setiap 10 detik (Jangan terlalu cepat agar tidak mengganggu saat mau klik tombol)
        setInterval(function() {
            let urlParams = new URLSearchParams(window.location.search);
            let page = urlParams.get('page');
            
            // Cuma refresh kalau di halaman 1
            if (!page || page == 1) {
                $.ajax({
                    url: "{{ route('dokter.riwayat.index') }}",
                    type: "GET",
                    success: function(data) {
                        $('#antrian-container').html($(data).find('#antrian-container').html());
                    }
                });
            }
        }, 10000);
    });
</script>
<script>
document.addEventListener('DOMContentLoaded',function(){let t;document.querySelectorAll('form[method="GET"] input[type="text"]').forEach(function(i){i.addEventListener('input',function(){clearTimeout(t);t=setTimeout(function(){i.closest('form').submit()},400)})});document.querySelectorAll('form[method="GET"] select,form[method="GET"] input[type="date"]').forEach(function(el){el.addEventListener('change',function(){el.closest('form').submit()})})});
</script>
@endpush
