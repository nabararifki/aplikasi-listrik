<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Tagihan Listrik') }}
        </h2>
    </x-slot>

    @php
        $indonesianMonths = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    @endphp

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Sukses Operasi -->
            @if (session('success'))
                <div class="mb-6 p-4 text-sm text-green-900 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-300 dark:border-green-800" role="alert">
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Detail Penggunaan Listrik</h3>
                    <span class="text-sm text-gray-800 dark:text-gray-200">ID Tagihan: #{{ $bill->id }}</span>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Nama Pelanggan</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $bill->customer?->name }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Nomor Pelanggan</span>
                            <span class="text-sm font-mono font-bold text-blue-600 dark:text-blue-400">{{ $bill->customer?->customer_number }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Periode Tagihan</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $indonesianMonths[$bill->billing_month] ?? $bill->billing_month }} {{ $bill->billing_year }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Alamat Rumah</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $bill->customer?->address }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Golongan Tarif</span>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">
                                {{ $bill->customer?->electricityTariff?->tariff_code }} - {{ number_format($bill->customer?->electricityTariff?->power ?? 0, 0, ',', '.') }} VA
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Jumlah Pemakaian Listrik</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($bill->electricity_usage, 0, ',', '.') }} kWh</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Status Pembayaran</span>
                            <div class="flex items-center gap-3 mt-1">
                                @if ($bill->status === 'paid')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-900 dark:bg-green-900/60 dark:text-green-200 border border-green-200 dark:border-green-700">
                                        Lunas
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-900 dark:bg-red-900/60 dark:text-red-200 border border-red-200 dark:border-red-700">
                                        Belum Lunas
                                    </span>
                                @endif

                                <form action="{{ route('bills.toggle-status', $bill) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-indigo-700 hover:bg-indigo-600 active:bg-indigo-800 text-xs font-bold text-white rounded-md transition duration-150 shadow-sm">
                                        Ubah Status Cepat
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div>
                            <!-- Kolom kanan kosong atau tambahan info -->
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Biaya Beban (Base Charge)</span>
                            <span class="text-sm text-gray-900 dark:text-white font-semibold">
                                Rp {{ number_format($bill->customer?->electricityTariff?->base_charge ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Tarif per kWh</span>
                            <span class="text-sm text-gray-900 dark:text-white font-semibold">
                                Rp {{ number_format($bill->customer?->electricityTariff?->tariff_per_kwh ?? 0, 0, ',', '.') }}/kWh
                            </span>
                        </div>
                    </div>

                    <div class="p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800 rounded-lg flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-bold uppercase text-blue-600 dark:text-blue-400">Total Biaya Tagihan</span>
                            <span class="text-xs text-gray-800 dark:text-gray-200">Rumus: Biaya Beban + (Pemakaian * Tarif per kWh)</span>
                        </div>
                        <span class="text-xl font-black text-blue-700 dark:text-blue-300">
                            Rp {{ number_format($bill->total_charge, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Tombol Navigasi -->
                <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-5 mt-6">
                    <a href="{{ route('bills.index') }}" class="inline-flex items-center text-sm font-medium text-gray-800 dark:text-gray-200 hover:text-gray-950 dark:hover:text-white transition">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar
                    </a>
                    
                    <a href="{{ route('bills.edit', $bill) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Ubah Tagihan
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
