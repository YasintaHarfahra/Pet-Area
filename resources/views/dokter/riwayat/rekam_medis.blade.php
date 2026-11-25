@extends('layouts.dokter_app')

@section('title', 'Riwayat Rekam Medis')

@section('content')

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
                <div>
                    <label class="block text-xs font-bold text-pink-700 mb-1">Kolom</label>
                    <select name="sort_field" class="px-3 py-2 border-2 border-pink-200 rounded-lg text-sm focus:ring-2 focus:ring-pink-300">
                        <option value="visit_date" @if(request('sort_field','visit_date')=='visit_date') selected @endif>Tanggal</option>
                        <option value="id_pet" @if(request('sort_field')=='id_pet') selected @endif>Nama Hewan</option>
                    </select>
                </div>
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

    {{-- Container Tabel (Disini file table.blade.php dipanggil) --}}
    <div id="antrian-container">
        @include('dokter.riwayat.table')
    </div>
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
