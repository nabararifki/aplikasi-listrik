<x-guest-layout>
    @php
        $indonesianMonths = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    @endphp

    <div class="mb-6">
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4 mb-5">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Hasil Pencarian Tagihan</h2>
            <span class="px-2.5 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                Aktif
            </span>
        </div>
        
        <!-- Kartu Rincian Identitas Pelanggan -->
        <div class="p-5 bg-gray-50 border border-gray-200 rounded-xl dark:bg-gray-800/50 dark:border-gray-700 space-y-4 mb-6">
            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Informasi Pelanggan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <span class="block font-medium text-gray-800 dark:text-gray-200">Nama Pelanggan</span>
                    <span class="text-base font-bold text-gray-900 dark:text-white">{{ $customer->name }}</span>
                </div>
                <div>
                    <span class="block font-medium text-gray-800 dark:text-gray-200">Nomor Pelanggan</span>
                    <span class="text-base font-mono font-bold text-blue-600 dark:text-blue-400">{{ $customer->customer_number }}</span>
                </div>
                <div class="md:col-span-2">
                    <span class="block font-medium text-gray-800 dark:text-gray-200">Alamat Rumah</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $customer->address }}</span>
                </div>
                <div>
                    <span class="block font-medium text-gray-800 dark:text-gray-200">Golongan Tarif & Daya</span>
                    <span class="text-gray-900 dark:text-white font-semibold">
                        {{ $customer->electricityTariff?->tariff_code }} - {{ number_format($customer->electricityTariff?->power, 0, ',', '.') }} VA
                    </span>
                </div>
                <div>
                    <span class="block font-medium text-gray-800 dark:text-gray-200">Biaya Beban (Base Charge)</span>
                    <span class="text-gray-900 dark:text-white font-bold">
                        Rp {{ number_format($customer->electricityTariff?->base_charge, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Banner Status Tunggakan -->
        @if($grand_total_unpaid > 0)
            <div class="p-5 mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl text-red-900 dark:text-red-200 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-red-700 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <span class="block text-xs font-bold uppercase tracking-wider text-red-800 dark:text-red-300">Total Tunggakan Belum Lunas</span>
                        <span class="text-xl font-black text-red-950 dark:text-red-100">
                            Rp {{ number_format($grand_total_unpaid, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        @else
            <div class="p-5 mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl text-green-900 dark:text-green-200 flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 text-green-700 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-green-800 dark:text-green-300">Status Tagihan</span>
                    <span class="text-sm font-bold text-green-950 dark:text-green-100">
                        Semua tagihan telah lunas. Terima kasih atas tertib pembayaran Anda.
                    </span>
                </div>
            </div>
        @endif

        <!-- Tabel Riwayat Penggunaan & Tagihan -->
        <h3 class="text-md font-bold text-gray-800 dark:text-gray-200 mb-3 uppercase tracking-wider">Riwayat Tagihan Listrik</h3>
        
        <div class="relative overflow-x-auto shadow border border-gray-200 dark:border-gray-700 rounded-xl mb-6 bg-white dark:bg-gray-900">
            <table class="w-full text-sm text-left text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-900">
                <thead class="text-xs text-gray-900 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-3.5">Tahun</th>
                        <th scope="col" class="px-5 py-3.5">Bulan</th>
                        <th scope="col" class="px-5 py-3.5 text-right">Pemakaian (kWh)</th>
                        <th scope="col" class="px-5 py-3.5 text-right">Total Tagihan</th>
                        <th scope="col" class="px-5 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($customer->bills as $bill)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition">
                            <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">{{ $bill->billing_year }}</td>
                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $indonesianMonths[$bill->billing_month] ?? 'Bulan ' . $bill->billing_month }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-medium text-gray-900 dark:text-white">{{ number_format($bill->electricity_usage, 0, ',', '.') }} kWh</td>
                            <td class="px-5 py-4 text-right font-bold text-blue-600 dark:text-blue-400">
                                Rp {{ number_format($bill->total_charge, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if ($bill->status === 'paid')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-150 text-green-950 dark:bg-green-900/60 dark:text-green-200 border border-green-300 dark:border-green-800 whitespace-nowrap">
                                        Lunas
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-150 text-red-950 dark:bg-red-900/60 dark:text-red-200 border border-red-300 dark:border-red-800 whitespace-nowrap">
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white dark:bg-gray-800">
                            <td colspan="5" class="px-5 py-8 text-center text-gray-800 dark:text-gray-200">
                                Belum ada riwayat tagihan listrik untuk pelanggan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tombol Kembali -->
        <div class="flex justify-center pt-2">
            <a href="{{ route('public.bills.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-lg font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</x-guest-layout>
