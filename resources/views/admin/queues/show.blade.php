@extends('layouts.admin_app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detail Antrian</h1>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Queue Information -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Antrian</h2>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nomor Antrian</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $queue->queue_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Kunjungan</dt>
                            <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($queue->queue_date)->format('d F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @switch($queue->status)
                                    @case('waiting')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Menunggu
                                        </span>
                                        @break
                                    @case('in-progress')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Sedang Dilayani
                                        </span>
                                        @break
                                    @case('completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Selesai
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Dibatalkan
                                        </span>
                                        @break
                                @endswitch
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Patient Information -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pasien</h2>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Pasien</dt>
                            <dd class="mt-1 text-gray-900">{{ $queue->patient->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nomor Rekam Medis</dt>
                            <dd class="mt-1 text-gray-900">{{ $queue->patient->medical_record_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Keluhan</dt>
                            <dd class="mt-1 text-gray-900">{{ $queue->complaint ?: 'Tidak ada keluhan tercatat' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 border-t border-gray-200 pt-6 flex justify-between">
                <a href="{{ route('admin.queues.index') }}" class="text-gray-600 hover:text-gray-900">
                    &larr; Kembali ke Daftar Antrian
                </a>
                
                @if($queue->status === 'waiting')
                    <form action="{{ route('admin.queues.cancel', $queue->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('Yakin ingin membatalkan antrian ini?')">
                            Batalkan Antrian
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection