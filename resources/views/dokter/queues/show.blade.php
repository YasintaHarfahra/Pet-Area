@extends('layouts.dokter_app')

@section('title', 'Detail Antrian & Rekam Medis')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm uppercase tracking-wide text-blue-500 font-semibold">Mulai Pemeriksaan</p>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $queue->patient->name }}</h1>
            <p class="text-gray-500">No. Rekam Medis: <span class="font-semibold text-gray-900">{{ $queue->patient->medical_record_number ?? '-' }}</span></p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                @if($queue->status === 'in-progress')
                    bg-green-100 text-green-700
                @elseif($queue->status === 'completed')
                    bg-gray-100 text-gray-700
                @else
                    bg-yellow-100 text-yellow-700
                @endif">
                <span class="w-2 h-2 rounded-full mr-2
                    @if($queue->status === 'in-progress')
                        bg-green-500
                    @elseif($queue->status === 'completed')
                        bg-gray-500
                    @else
                        bg-yellow-500
                    @endif"></span>
                Status: {{ ucfirst(str_replace('-', ' ', $queue->status)) }}
            </span>
            <a href="{{ route('dokter.queues.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">← Kembali ke Antrian</a>
        </div>
    </div>

    {{-- Section: Identitas Pasien & Pemilik --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Identitas Pasien</h2>
            <div class="text-sm text-gray-500">
                Tanggal Kunjungan: <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($queue->queue_date)->translatedFormat('d M Y') }}</span>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
            <div class="lg:col-span-2 space-y-4">
                <div class="border border-gray-100 rounded-xl p-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Data Hewan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                        <div>
                            <p class="text-gray-500">Nama Hewan</p>
                            <p class="font-semibold text-gray-900">{{ $queue->patient->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Nomor Rekam Medis</p>
                            <p class="font-semibold text-gray-900">{{ $queue->patient->medical_record_number ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Jenis Hewan</p>
                            <p class="font-semibold text-gray-900">{{ $queue->patient->species ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Ras</p>
                            <p class="font-semibold text-gray-900">{{ $queue->patient->breed ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Jenis Kelamin</p>
                            <p class="font-semibold text-gray-900">{{ $queue->patient->gender ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Berat Badan</p>
                            <p class="font-semibold text-gray-900">{{ $queue->patient->weight ? $queue->patient->weight . ' kg' : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Tanggal Lahir</p>
                            <p class="font-semibold text-gray-900">
                                @if($queue->patient->date_of_birth)
                                    {{ \Carbon\Carbon::parse($queue->patient->date_of_birth)->translatedFormat('d M Y') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Umur</p>
                            <p class="font-semibold text-gray-900">
                                @if($queue->patient->date_of_birth)
                                    @php
                                        $dob = \Carbon\Carbon::parse($queue->patient->date_of_birth);
                                        $diff = $dob->diff(\Carbon\Carbon::now());
                                    @endphp
                                    {{ $diff->y }} tahun {{ $diff->m }} bulan
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @if($queue->patient->medical_history)
                    <div class="border border-gray-100 rounded-xl p-4 bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Riwayat Medis Umum</h3>
                        <p class="text-sm text-gray-700">{{ $queue->patient->medical_history }}</p>
                    </div>
                @endif
            </div>
            <div class="border border-gray-100 rounded-xl p-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Data Pemilik</h3>
                <dl class="space-y-3 text-sm text-gray-700">
                    <div>
                        <dt class="text-gray-500">Nama Pemilik</dt>
                        <dd class="font-semibold text-gray-900">{{ $queue->patient->owner_name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">No. HP</dt>
                        <dd class="font-semibold text-gray-900">{{ $queue->patient->owner_phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Alamat</dt>
                        <dd class="font-semibold text-gray-900">{{ $queue->patient->owner_address ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Email</dt>
                        <dd class="font-semibold text-gray-900">{{ $queue->patient->owner_email ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    {{-- Section: Riwayat Pemeriksaan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-wrap items-start justify-between gap-4">
            <div class="text-left">
                <h2 class="text-lg font-semibold text-gray-900">Riwayat Pemeriksaan</h2>
                <p class="text-sm text-gray-500">Daftar pemeriksaan sebelumnya untuk referensi diagnosa.</p>
            </div>
            <div class="flex items-center gap-4 ml-auto">
                <div class="text-sm text-gray-500 text-right">
                    Tanggal pemeriksaan hari ini:<br>
                    <span class="font-semibold text-gray-900">{{ now()->translatedFormat('d M Y') }}</span>
                </div>
                <a href="{{ route('dokter.medical_records.create', ['patient_id' => $queue->patient_id, 'queue_id' => $queue->id]) }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Pemeriksaan
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokter</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Diagnosa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tindakan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Resep Obat</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($medicalRecords as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $record->visit_date ? \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $record->doctor->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ \Illuminate\Support\Str::limit($record->diagnosis ?? '-', 60) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ \Illuminate\Support\Str::limit($record->treatment ?? '-', 60) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ \Illuminate\Support\Str::limit($record->prescription ?? '-', 60) }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                <a href="{{ route('medical_records.show', $record->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                Belum ada rekam medis untuk pasien ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

