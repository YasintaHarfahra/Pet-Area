@extends('layouts.admin_app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Antrian Baru</h1>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-6">
        <form action="{{ route('admin.queues.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="patient_id" class="block text-sm font-medium text-gray-700">Pasien</label>
                <select name="patient_id" id="patient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Pilih Pasien</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">
                            {{ $patient->name }} - {{ $patient->medical_record_number }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="queue_date" class="block text-sm font-medium text-gray-700">Tanggal Kunjungan</label>
                <input type="date" name="queue_date" id="queue_date" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       value="{{ date('Y-m-d') }}">
                @error('queue_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="complaint" class="block text-sm font-medium text-gray-700">Keluhan</label>
                <textarea name="complaint" id="complaint" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Tuliskan keluhan pasien..."></textarea>
                @error('complaint')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Tambah Antrian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection