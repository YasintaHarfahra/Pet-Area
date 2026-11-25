@extends('layouts.admin_app')

@section('title', 'Surat Rujukan')
@section('header_title', 'Surat Rujukan')

@section('content')
    <div class="max-w-6xl mx-auto px-4 pb-8">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Daftar Surat Rujukan</h1>
                <p class="text-sm text-slate-500">Kelola dan cetak surat rujukan dari dokter</p>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 border border-slate-200">
                <p class="text-2xl font-bold text-slate-800">{{ $referrals->total() }}</p>
                <p class="text-xs text-slate-500">Total Rujukan</p>
            </div>
            <div class="bg-amber-50 rounded-xl p-4 border border-amber-200">
                <p class="text-2xl font-bold text-amber-600">{{ $referrals->where('status_cetak', 'pending')->count() }}</p>
                <p class="text-xs text-amber-600">Belum Dicetak</p>
            </div>
            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                <p class="text-2xl font-bold text-emerald-600">{{ $referrals->where('status_cetak', 'printed')->count() }}</p>
                <p class="text-xs text-emerald-600">Sudah Dicetak</p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4 text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('admin.referrals.index') }}" class="flex gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari nomor rujukan, nama hewan, pemilik..." 
                class="flex-1 px-4 py-2 rounded-lg border border-slate-200 text-sm focus:border-[#970747] focus:ring-1 focus:ring-[#970747]">
            <select name="status" class="px-3 py-2 rounded-lg border border-slate-200 text-sm bg-white">
                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Belum Dicetak</option>
                <option value="printed" {{ request('status') === 'printed' ? 'selected' : '' }}>Sudah Dicetak</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-medium transition-colors">
                Filter
            </button>
        </form>

        {{-- Referral List --}}
        <div class="space-y-3">
            @forelse ($referrals as $referral)
                <div class="bg-white rounded-xl border {{ $referral->status_cetak === 'pending' ? 'border-amber-200' : 'border-slate-200' }} p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        {{-- Left: Info --}}
                        <div class="flex items-center gap-4">
                            {{-- Icon --}}
                            <div class="w-12 h-12 rounded-full {{ $referral->status_cetak === 'pending' ? 'bg-amber-100' : 'bg-slate-100' }} flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 {{ $referral->status_cetak === 'pending' ? 'text-amber-600' : 'text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>

                            {{-- Info --}}
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-slate-800">{{ $referral->nomor_rujukan }}</h3>
                                    @if($referral->status_cetak === 'pending')
                                        <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded">Pending</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-700 rounded">Dicetak</span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-600">
                                    {{ $referral->medicalRecord?->pet?->nama_hewan ?? $referral->animalMedicalRecord?->animal?->nama_hewan ?? '-' }}
                                    <span class="text-slate-400">•</span>
                                    {{ $referral->medicalRecord?->pet?->owner?->nama ?? $referral->animalMedicalRecord?->animal?->owner?->nama ?? '-' }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Tujuan: {{ $referral->tujuan_rujukan ?? '-' }} •
                                    {{ $referral->tanggal_rujukan ? $referral->tanggal_rujukan->format('d M Y') : '-' }} •
                                    {{ $referral->doctor?->name ?? $referral->medicalRecord?->doctor?->name ?? '-' }}
                                </p>
                            </div>
                        </div>

                        {{-- Right: Actions --}}
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.referrals.show', $referral) }}" 
                               class="px-3 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                                Detail
                            </a>
                            <a href="{{ route('admin.referrals.print', $referral) }}" 
                               target="_blank"
                               class="px-4 py-2 text-sm font-medium {{ $referral->status_cetak === 'pending' ? 'bg-[#970747] hover:bg-[#7a0538] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }} rounded-lg transition-colors">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Cetak
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-slate-500">Belum ada surat rujukan</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $referrals->links() }}
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded',function(){let t;document.querySelectorAll('form[method="GET"] input[type="text"]').forEach(function(i){i.addEventListener('input',function(){clearTimeout(t);t=setTimeout(function(){i.closest('form').submit()},400)})});document.querySelectorAll('form[method="GET"] select,form[method="GET"] input[type="date"]').forEach(function(el){el.addEventListener('change',function(){el.closest('form').submit()})})});
</script>
@endsection
