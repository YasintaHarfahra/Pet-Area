@extends('layouts.admin_app')

@section('title', 'Stok Obat')
@section('header_title', 'Stok Obat')

@section('content')
    <div class="max-w-6xl mx-auto px-4 pb-8">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Manajemen Stok Obat</h1>
                <p class="text-sm text-slate-500">Kelola persediaan obat di klinik</p>
            </div>
            <a href="{{ route('admin.medicines.create') }}" 
               class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium text-sm transition-colors">
                + Tambah Obat
            </a>
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 border border-slate-200">
                <p class="text-2xl font-bold text-slate-800">{{ $medicines->total() }}</p>
                <p class="text-xs text-slate-500">Total Obat</p>
            </div>
            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                <p class="text-2xl font-bold text-emerald-600">{{ $medicines->where('stok', '>', 10)->count() }}</p>
                <p class="text-xs text-emerald-600">Stok Cukup</p>
            </div>
            <div class="bg-amber-50 rounded-xl p-4 border border-amber-200">
                <p class="text-2xl font-bold text-amber-600">{{ $medicines->where('stok', '<=', 10)->where('stok', '>', 0)->count() }}</p>
                <p class="text-xs text-amber-600">Stok Rendah</p>
            </div>
            <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                <p class="text-2xl font-bold text-red-600">{{ $medicines->where('stok', '<=', 0)->count() }}</p>
                <p class="text-xs text-red-600">Stok Habis</p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4 text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                ✕ {{ session('error') }}
            </div>
        @endif

        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('admin.medicines.index') }}" class="flex gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari nama obat..." 
                class="flex-1 px-4 py-2 rounded-lg border border-slate-200 text-sm focus:border-[#970747] focus:ring-1 focus:ring-[#970747]">
            <select name="stock" class="px-3 py-2 rounded-lg border border-slate-200 text-sm bg-white">
                <option value="">Semua Stok</option>
                <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Stok Rendah (≤10)</option>
                <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Stok Habis</option>
            </select>
            <select name="status" class="px-3 py-2 rounded-lg border border-slate-200 text-sm bg-white">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-aktif</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-medium transition-colors">
                Filter
            </button>
        </form>

        {{-- Medicine List --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Nama Obat</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Dosis</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Satuan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">
                            <a href="{{ route('admin.medicines.index', array_merge(request()->query(), ['sort' => 'stok', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="inline-flex items-center gap-1 hover:text-[#970747] transition">
                                Stok
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Harga</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($medicines as $medicine)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-medium text-slate-800">{{ $medicine->nama_obat }}</p>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-slate-600">{{ $medicine->dosis ?? '-' }}</td>
                            <td class="px-4 py-3 text-center text-sm text-slate-600">{{ $medicine->satuan }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($medicine->stok <= 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold bg-red-100 text-red-700 rounded-full">
                                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                        HABIS
                                    </span>
                                @elseif($medicine->stok <= 10)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold bg-amber-100 text-amber-700 rounded-full">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                        {{ $medicine->stok }} - Menipis
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold bg-emerald-100 text-emerald-700 rounded-full">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                        {{ $medicine->stok }} - Aman
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-medium text-slate-800">
                                {{ $medicine->formatted_harga }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($medicine->is_active)
                                    <span class="px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full">Aktif</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-600 rounded-full">Non-aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.medicines.edit', $medicine) }}" 
                                       class="p-1.5 text-slate-400 hover:text-[#970747] transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    
                                    {{-- Quick Add Stock Button --}}
                                    <button data-id="{{ $medicine->id_medicine }}" data-nama="{{ addslashes($medicine->nama_obat) }}" data-stok="{{ $medicine->stok }}" 
                                            onclick="openAddStockModal(parseInt(this.dataset.id), this.dataset.nama, parseInt(this.dataset.stok))" 
                                            class="p-1.5 text-slate-400 hover:text-emerald-600 transition-colors" title="Tambah Stok">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </button>
                                    
                                    <form action="{{ route('admin.medicines.destroy', $medicine) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin hapus obat {{ $medicine->nama_obat }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                Belum ada data obat
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $medicines->links() }}
        </div>
    </div>

    {{-- Modal Add Stock --}}
    <div id="addStockModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Tambah Stok Obat</h3>
            <form id="addStockForm" method="POST">
                @csrf
                <p class="text-sm text-slate-600 mb-2">Obat: <strong id="modalMedicineName"></strong></p>
                <p class="text-sm text-slate-600 mb-4">Stok Saat Ini: <strong id="modalCurrentStock"></strong></p>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Tambahan</label>
                    <input type="number" name="tambah_stok" min="1" required
                           class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-[#970747] focus:ring-1 focus:ring-[#970747]">
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeAddStockModal()" 
                            class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors">
                        Tambah Stok
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddStockModal(medicineId, medicineName, currentStock) {
            document.getElementById('addStockForm').action = `/admin/obat/${medicineId}/add-stock`;
            document.getElementById('modalMedicineName').textContent = medicineName;
            document.getElementById('modalCurrentStock').textContent = currentStock;
            document.getElementById('addStockModal').classList.remove('hidden');
            document.getElementById('addStockModal').classList.add('flex');
        }
        
        function closeAddStockModal() {
            document.getElementById('addStockModal').classList.add('hidden');
            document.getElementById('addStockModal').classList.remove('flex');
        }
        
        // Close modal on outside click
        document.getElementById('addStockModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddStockModal();
            }
        });
    </script>
<script>
document.addEventListener('DOMContentLoaded',function(){let t;document.querySelectorAll('form[method="GET"] input[type="text"]').forEach(function(i){i.addEventListener('input',function(){clearTimeout(t);t=setTimeout(function(){i.closest('form').submit()},400)})});document.querySelectorAll('form[method="GET"] select,form[method="GET"] input[type="date"]').forEach(function(el){el.addEventListener('change',function(){el.closest('form').submit()})})});
</script>
@endsection
