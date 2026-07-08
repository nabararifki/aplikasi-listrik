<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900 dark:text-white leading-tight">
            {{ __('Detail Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Card 1: Informasi Pelanggan -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Profil Pelanggan</h3>
                    <span class="text-sm font-mono font-bold text-indigo-700 dark:text-indigo-300">No Pelanggan: {{ $customer->customer_number }}</span>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Nama Pelanggan</span>
                            <span class="text-base font-bold text-gray-900 dark:text-white">{{ $customer->name }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Tanggal Pendaftaran</span>
                            <span class="text-base font-semibold text-gray-900 dark:text-white">{{ $customer->created_at->format('d F Y, H:i') }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Golongan Tarif</span>
                            <span class="text-base font-bold text-gray-900 dark:text-white">
                                @if($customer->electricityTariff)
                                    {{ $customer->electricityTariff->tariff_code }}
                                @else
                                    <span class="text-gray-400 font-normal">-</span>
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Alamat Rumah</span>
                            <span class="text-sm text-gray-900 dark:text-white leading-relaxed">{{ $customer->address }}</span>
                        </div>
                    </div>

                    @if($customer->electricityTariff)
                    <div class="p-4 bg-gray-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <span class="block text-xs font-bold uppercase text-gray-800 dark:text-gray-200 mb-2">Detail Tarif Listrik Terkait</span>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-xs text-gray-800 dark:text-gray-200">Biaya Beban (Base Charge):</span>
                                <div class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($customer->electricityTariff->base_charge, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-800 dark:text-gray-200">Tarif per kWh:</span>
                                <div class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($customer->electricityTariff->tariff_per_kwh, 0, ',', '.') }}/kWh</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Tombol Navigasi -->
                <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-5 mt-6">
                    <a href="{{ route('customers.index') }}" class="inline-flex items-center text-sm font-bold text-gray-900 dark:text-gray-100 hover:text-black dark:hover:text-white transition underline">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar
                    </a>
                    
                    @can('update', $customer)
                    <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Ubah Pelanggan
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Card 2: Riwayat Tagihan Pelanggan -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat Tagihan Listrik</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left bg-white dark:bg-gray-900">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-bold text-gray-900 dark:text-white">ID Tagihan</th>
                                <th scope="col" class="px-4 py-3 font-bold text-gray-900 dark:text-white">Tahun &amp; Bulan</th>
                                <th scope="col" class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">Pemakaian (kWh)</th>
                                <th scope="col" class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">Total Tagihan</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold text-gray-900 dark:text-white">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $indonesianMonths = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            @endphp
                            @forelse($customer->bills as $bill)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-indigo-50/50 dark:hover:bg-gray-700/50 transition duration-150">
                                    <td class="px-4 py-3 font-mono font-bold text-gray-900 dark:text-white">#{{ $bill->id }}</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">
                                        {{ $indonesianMonths[$bill->billing_month] ?? $bill->billing_month }} {{ $bill->billing_year }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-900 dark:text-white font-bold">{{ number_format($bill->electricity_usage) }} kWh</td>
                                    <td class="px-4 py-3 text-right text-indigo-700 dark:text-indigo-300 font-bold">
                                        Rp {{ number_format($bill->total_charge, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('bills.show', $bill) }}" class="text-blue-700 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-bold" title="Lihat Tagihan">
                                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada riwayat tagihan listrik untuk pelanggan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
