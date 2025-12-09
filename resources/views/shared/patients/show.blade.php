@extends(Auth::user()->role === 'admin' ? 'layouts.admin_app' : 'layouts.dokter_app')

@section('title', 'Detail Pasien')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm uppercase tracking-wide text-blue-500 font-semibold">Informasi Pasien</p>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $patient->name }}</h1>
            <p class="text-gray-500">Nomor Rekam Medis: <span class="font-semibold text-gray-900">{{ $patient->medical_record_number ?? '-' }}</span></p>
        </div>
        <div class="flex items-center space-x-3">
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.patients.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">← Kembali</a>
                <a href="{{ route('admin.patients.edit', $patient->id) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold rounded-lg">Edit</a>
            @else
                <a href="{{ route('dokter.patients.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">← Kembali</a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Identitas Pasien</h2>
            <span class="text-sm text-gray-500">
                Terakhir diperbarui: {{ $patient->updated_at ? $patient->updated_at->diffForHumans() : '-' }}
            </span>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
            <div class="lg:col-span-2 space-y-4">
                <div class="border border-gray-100 rounded-xl p-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Data Hewan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                        <div>
                            <p class="text-gray-500">Nama Hewan</p>
                            <p class="font-semibold text-gray-900">{{ $patient->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Nomor Rekam Medis</p>
                            <p class="font-semibold text-gray-900">{{ $patient->medical_record_number ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Jenis Hewan</p>
                            <p class="font-semibold text-gray-900">{{ $patient->species ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Ras</p>
                            <p class="font-semibold text-gray-900">{{ $patient->breed ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Jenis Kelamin</p>
                            <p class="font-semibold text-gray-900">{{ $patient->gender ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Berat Badan</p>
                            <p class="font-semibold text-gray-900">{{ $patient->weight ? $patient->weight . ' kg' : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Tanggal Lahir</p>
                            <p class="font-semibold text-gray-900">
                                @if($patient->date_of_birth)
                                    {{ \Carbon\Carbon::parse($patient->date_of_birth)->translatedFormat('d M Y') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Umur</p>
                            <p class="font-semibold text-gray-900">
                                @if($patient->date_of_birth)
                                    @php
                                        $dob = \Carbon\Carbon::parse($patient->date_of_birth);
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
            </div>
            <div class="border border-gray-100 rounded-xl p-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Data Pemilik</h3>
                <dl class="space-y-3 text-sm text-gray-700">
                    <div>
                        <dt class="text-gray-500">Nama Pemilik</dt>
                        <dd class="font-semibold text-gray-900">{{ $patient->owner_name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">No. HP</dt>
                        <dd class="font-semibold text-gray-900">{{ $patient->owner_phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Alamat</dt>
                        <dd class="font-semibold text-gray-900">{{ $patient->owner_address ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Email</dt>
                        <dd class="font-semibold text-gray-900">{{ $patient->owner_email ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Riwayat Pemeriksaan</h2>
                    <p class="text-sm text-gray-500">Rekam medis pasien berdasarkan tanggal kehadiran.</p>
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
                    @forelse($patient->medicalRecords as $record)
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