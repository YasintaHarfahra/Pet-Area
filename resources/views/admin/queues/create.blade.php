@extends('layouts.admin_app')

@section('title', 'Tambah Antrian Baru')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.queues.index', ['date' => request()->input('date', now()->format('Y-m-d'))]) }}" class="text-gray-500 hover:text-gray-700 text-sm mb-2 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Antrian
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Daftarkan Pasien ke Antrian</h1>
        <p class="text-gray-500">Tambahkan hewan ke antrian pemeriksaan</p>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-gray-800">Form Pendaftaran Antrian</h2>
        </div>
        
        <form action="{{ route('admin.queues.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            {{-- Pilih Hewan --}}
            <div>
                <label for="pet_search" class="block text-sm font-semibold text-gray-700 mb-2">
                    Pilih Hewan <span class="text-red-500">*</span>
                </label>
                
                @if(isset($selectedPet))
                    {{-- Jika sudah ada pet yang dipilih dari parameter --}}
                    <div class="p-4 bg-pink-50 border border-pink-200 rounded-lg">
                        <div class="flex items-center gap-4">
                            @if($selectedPet->foto_hewan_url)
                                <img src="{{ $selectedPet->foto_hewan_url }}" alt="Foto {{ $selectedPet->nama_hewan }}" class="w-14 h-14 rounded-full object-cover border border-pink-200">
                            @else
                                <div class="w-14 h-14 bg-[#970747] rounded-full flex items-center justify-center text-2xl">
                                    @if($selectedPet->jenis == 'Kucing')
                                        🐱
                                    @elseif($selectedPet->jenis == 'Anjing')
                                        🐕
                                    @else
                                        🐾
                                    @endif
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-gray-800 text-lg">{{ $selectedPet->nama_hewan }}</p>
                                <p class="text-sm text-gray-600">{{ $selectedPet->jenis }} {{ $selectedPet->ras ? '- '.$selectedPet->ras : '' }}</p>
                                <p class="text-sm text-gray-500">Pemilik: {{ $selectedPet->owner->nama }} ({{ $selectedPet->owner->no_hp }})</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="pet_id" value="{{ $selectedPet->id_pet }}">
                @else
                    {{-- Search pet --}}
                    <div class="relative">
                        <input type="text" 
                               id="pet_search" 
                               placeholder="Ketik nama hewan atau nama pemilik..."
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#970747] focus:border-[#970747] transition"
                               autocomplete="off">
                        <div id="search_results" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-64 overflow-y-auto"></div>
                    </div>
                    <input type="hidden" name="pet_id" id="pet_id" value="{{ old('pet_id') }}">
                    
                    {{-- Selected Pet Display --}}
                    <div id="selected_pet" class="mt-3 hidden">
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div id="pet_icon" class="w-14 h-14 bg-green-600 rounded-full flex items-center justify-center text-2xl">🐾</div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-lg" id="pet_nama"></p>
                                        <p class="text-sm text-gray-600" id="pet_jenis"></p>
                                        <p class="text-sm text-gray-500" id="pet_owner"></p>
                                    </div>
                                </div>
                                <button type="button" id="clear_pet" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                
                @error('pet_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal Kunjungan --}}
            <div>
                <label for="queue_date" class="block text-sm font-semibold text-gray-700 mb-2">
                    Tanggal Kunjungan <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       name="queue_date" 
                       id="queue_date" 
                       value="{{ old('queue_date', $defaultDate ?? date('Y-m-d')) }}"
                       min="{{ date('Y-m-d') }}"
                       class="w-full md:w-1/3 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#970747] focus:border-[#970747] transition"
                       required>
                @error('queue_date')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Keluhan --}}
            <div>
                <label for="complaint" class="block text-sm font-semibold text-gray-700 mb-2">
                    Keluhan / Alasan Kunjungan
                </label>
                <textarea name="complaint" 
                          id="complaint" 
                          rows="4"
                          placeholder="Tuliskan keluhan atau alasan kunjungan..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#970747] focus:border-[#970747] transition resize-none">{{ old('complaint') }}</textarea>
                @error('complaint')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
                <button type="submit" 
                        class="px-6 py-3 bg-[#970747] hover:bg-[#7a0538] text-white font-semibold rounded-lg transition inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Daftarkan ke Antrian
                </button>
                <a href="{{ route('admin.queues.index', ['date' => request()->input('date', now()->format('Y-m-d'))]) }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('pet_search');
    if (!searchInput) return; // Exit if search input doesn't exist (pre-selected pet)
    
    const searchResults = document.getElementById('search_results');
    const petIdInput = document.getElementById('pet_id');
    const selectedPet = document.getElementById('selected_pet');
    const clearBtn = document.getElementById('clear_pet');
    
    let searchTimeout;
    
    // Search pets
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }
        
        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`{{ route('admin.queues.api.pets.search') }}?q=${encodeURIComponent(query)}`);
                const pets = await response.json();
                
                if (pets.length > 0) {
                    searchResults.innerHTML = pets.map(pet => `
                        <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0 pet-result"
                             data-id="${pet.id}"
                             data-nama="${pet.nama_hewan}"
                             data-jenis="${pet.jenis}"
                             data-ras="${pet.ras || ''}"
                             data-owner="${pet.owner_nama}"
                             data-hp="${pet.owner_hp}"
                             data-foto="${pet.foto_hewan_url || ''}">
                            <div class="flex items-center gap-3">
                                ${pet.foto_hewan_url
                                    ? `<img src="${pet.foto_hewan_url}" alt="${pet.nama_hewan}" class="w-10 h-10 rounded-full object-cover border border-pink-100">`
                                    : `<div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center">${pet.jenis === 'Kucing' ? '🐱' : pet.jenis === 'Anjing' ? '🐕' : '🐾'}</div>`}
                                <div>
                                    <p class="font-semibold text-gray-800">${pet.nama_hewan}</p>
                                    <p class="text-sm text-gray-500">${pet.jenis}${pet.ras ? ' - ' + pet.ras : ''} • ${pet.owner_nama}</p>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    searchResults.classList.remove('hidden');
                    
                    // Add click handlers
                    document.querySelectorAll('.pet-result').forEach(el => {
                        el.addEventListener('click', () => selectPet(el.dataset));
                    });
                } else {
                    searchResults.innerHTML = `
                        <div class="p-4 text-center text-gray-500">
                            <p>Tidak ditemukan hewan dengan kata kunci "${query}"</p>
                            <a href="{{ route('admin.registration.owners.create') }}" class="text-[#970747] hover:underline text-sm mt-2 block">
                                + Daftarkan pemilik & hewan baru
                            </a>
                        </div>
                    `;
                    searchResults.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Search error:', error);
            }
        }, 300);
    });
    
    // Select pet
    function selectPet(data) {
        petIdInput.value = data.id;
        document.getElementById('pet_nama').textContent = data.nama;
        document.getElementById('pet_jenis').textContent = `${data.jenis}${data.ras ? ' - ' + data.ras : ''}`;
        document.getElementById('pet_owner').textContent = `Pemilik: ${data.owner} (${data.hp})`;
        if (data.foto) {
            document.getElementById('pet_icon').innerHTML = `<img src="${data.foto}" alt="${data.nama}" class="w-14 h-14 rounded-full object-cover border border-green-200">`;
        } else {
            document.getElementById('pet_icon').innerHTML = data.jenis === 'Kucing' ? '🐱' : data.jenis === 'Anjing' ? '🐕' : '🐾';
        }
        
        selectedPet.classList.remove('hidden');
        searchResults.classList.add('hidden');
        searchInput.value = '';
        searchInput.classList.add('hidden');
    }
    
    // Clear selection
    clearBtn.addEventListener('click', function() {
        petIdInput.value = '';
        selectedPet.classList.add('hidden');
        searchInput.classList.remove('hidden');
        searchInput.focus();
    });
    
    // Hide results on outside click
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });
});
</script>
@endpush
@endsection