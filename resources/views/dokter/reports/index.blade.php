@extends('layouts.dokter_app')

@section('title', 'Laporan Medis - Dokter')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Laporan & Ekspor Data</h1>
            <p class="text-gray-500 text-sm">Unduh data rekam medis dan pasien dengan mudah.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <form action="{{ route('dokter.reports.export') }}" method="POST" id="exportForm" class="p-6">
                @csrf
                
                <!-- Hidden Inputs -->
                <input type="hidden" name="type" id="selectedType" value="medical_records">
                <input type="hidden" name="range" id="selectedRange" value="1_month">
                <input type="hidden" name="format" id="selectedFormat" value="excel">

                <!-- Step 1: Jenis Laporan -->
                <div class="mb-6">
                    <div class="flex items-center mb-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-2 text-sm">1</div>
                        <label class="text-base font-bold text-gray-800">Pilih Jenis Laporan</label>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Option: Medical Records -->
                        <div class="relative group cursor-pointer" onclick="selectType('medical_records')">
                            <div id="card_medical_records" class="type-card h-full p-4 rounded-xl border-2 border-blue-500 bg-blue-50 transition-all duration-200 hover:shadow-md flex flex-row items-center text-left">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">Rekam Medis</h3>
                                    <p class="text-xs text-gray-500">Diagnosis, tindakan, & resep.</p>
                                </div>
                                <div class="check-icon absolute top-1/2 right-4 transform -translate-y-1/2 text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Option: Patient Data -->
                        <div class="relative group cursor-pointer" onclick="selectType('patients')">
                            <div id="card_patients" class="type-card h-full p-4 rounded-xl border-2 border-transparent bg-gray-50 hover:bg-gray-100 transition-all duration-200 hover:shadow-md flex flex-row items-center text-left">
                                <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">Data Pasien</h3>
                                    <p class="text-xs text-gray-500">Info pemilik & hewan.</p>
                                </div>
                                <div class="check-icon absolute top-1/2 right-4 transform -translate-y-1/2 text-purple-600 hidden">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Rentang Waktu -->
                <div class="mb-6">
                    <div class="flex items-center mb-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-2 text-sm">2</div>
                        <label class="text-base font-bold text-gray-800">Rentang Waktu</label>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-3">
                        <button type="button" onclick="selectRange('1_month')" id="btn_range_1_month" class="range-btn active w-full py-2 px-3 rounded-lg border-2 border-blue-500 bg-blue-50 text-blue-700 font-bold transition-all duration-200 hover:shadow-sm text-xs md:text-sm">
                            1 Bulan
                        </button>
                        <button type="button" onclick="selectRange('6_months')" id="btn_range_6_months" class="range-btn w-full py-2 px-3 rounded-lg border-2 border-transparent bg-gray-50 text-gray-600 font-medium transition-all duration-200 hover:bg-gray-100 hover:shadow-sm text-xs md:text-sm">
                            6 Bulan
                        </button>
                        <button type="button" onclick="selectRange('all')" id="btn_range_all" class="range-btn w-full py-2 px-3 rounded-lg border-2 border-transparent bg-gray-50 text-gray-600 font-medium transition-all duration-200 hover:bg-gray-100 hover:shadow-sm text-xs md:text-sm">
                            Semua
                        </button>
                    </div>
                </div>

                <!-- Step 3: Format File -->
                <div class="mb-8">
                    <div class="flex items-center mb-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-2 text-sm">3</div>
                        <label class="text-base font-bold text-gray-800">Format File</label>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <!-- Excel -->
                        <button type="button" onclick="selectFormat('excel')" id="btn_excel" class="format-btn active flex flex-row items-center justify-center p-3 rounded-lg border-2 border-green-500 bg-green-50 text-green-700 transition-all duration-200 hover:shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="font-bold text-xs md:text-sm">Excel</span>
                        </button>
                        <!-- PDF -->
                        <button type="button" onclick="selectFormat('pdf')" id="btn_pdf" class="format-btn flex flex-row items-center justify-center p-3 rounded-lg border-2 border-transparent bg-gray-50 text-gray-500 hover:bg-gray-100 transition-all duration-200 hover:shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <span class="font-bold text-xs md:text-sm">PDF</span>
                        </button>
                        <!-- CSV -->
                        <button type="button" onclick="selectFormat('csv')" id="btn_csv" class="format-btn flex flex-row items-center justify-center p-3 rounded-lg border-2 border-transparent bg-gray-50 text-gray-500 hover:bg-gray-100 transition-all duration-200 hover:shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="font-bold text-xs md:text-sm">CSV</span>
                        </button>
                    </div>
                </div>

                <!-- Action Button -->
                <button type="submit" id="downloadBtn" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center text-base">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Unduh Laporan Excel</span>
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function selectType(type) {
            document.getElementById('selectedType').value = type;

            document.querySelectorAll('.type-card').forEach(el => {
                el.classList.remove('border-blue-500', 'bg-blue-50');
                el.classList.add('border-transparent', 'bg-gray-50');
                el.querySelector('.check-icon').classList.add('hidden');
            });

            const activeCard = document.getElementById('card_' + type);
            activeCard.classList.remove('border-transparent', 'bg-gray-50');
            activeCard.classList.add('border-blue-500', 'bg-blue-50');
            activeCard.querySelector('.check-icon').classList.remove('hidden');

            updateButtonText();
        }

        function selectRange(range) {
            document.getElementById('selectedRange').value = range;

            document.querySelectorAll('.range-btn').forEach(el => {
                el.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-700', 'font-bold');
                el.classList.add('border-transparent', 'bg-gray-50', 'text-gray-600', 'font-medium');
            });

            const activeBtn = document.getElementById('btn_range_' + range);
            activeBtn.classList.remove('border-transparent', 'bg-gray-50', 'text-gray-600', 'font-medium');
            activeBtn.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-700', 'font-bold');
        }

        function selectFormat(format) {
            document.getElementById('selectedFormat').value = format;

            document.querySelectorAll('.format-btn').forEach(el => {
                el.classList.remove('border-green-500', 'bg-green-50', 'text-green-700', 'border-red-500', 'bg-red-50', 'text-red-700', 'border-gray-500', 'bg-gray-100', 'text-gray-700');
                el.classList.add('border-transparent', 'bg-gray-50', 'text-gray-500');
            });

            const activeBtn = document.getElementById('btn_' + format);
            activeBtn.classList.remove('border-transparent', 'bg-gray-50', 'text-gray-500');
            
            if(format === 'excel') {
                activeBtn.classList.add('border-green-500', 'bg-green-50', 'text-green-700');
            } else if(format === 'pdf') {
                activeBtn.classList.add('border-red-500', 'bg-red-50', 'text-red-700');
            } else {
                activeBtn.classList.add('border-gray-500', 'bg-gray-100', 'text-gray-700');
            }

            updateButtonText();
        }

        function updateButtonText() {
            const format = document.getElementById('selectedFormat').value;
            const formatName = format === 'excel' ? 'Excel' : (format === 'pdf' ? 'PDF' : 'CSV');
            const btnText = document.querySelector('#downloadBtn span');
            btnText.textContent = `Unduh Laporan ${formatName}`;
        }
    </script>
    @endpush
@endsection
