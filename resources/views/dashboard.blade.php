<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- GRID INFOGRAFIS: 3 Kartu Statistik Agregat Real-Time         --}}
            {{-- ============================================================ --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Kartu 1: Total Pelanggan --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6 flex items-start gap-4">
                        {{-- Ikon --}}
                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/40">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        {{-- Konten --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200 mb-1">
                                Total Pelanggan
                            </p>
                            <p class="text-3xl font-black text-gray-900 dark:text-white leading-none">
                                {{ number_format($total_customers, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-800 dark:text-gray-300 mt-1.5 font-medium">
                                Pelanggan terdaftar aktif
                            </p>
                        </div>
                    </div>
                    <div class="h-1 bg-blue-500 dark:bg-blue-400"></div>
                </div>

                {{-- Kartu 2: Total Penggunaan Listrik --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6 flex items-start gap-4">
                        {{-- Ikon --}}
                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-yellow-100 dark:bg-yellow-900/40">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        {{-- Konten --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200 mb-1">
                                Total Penggunaan
                            </p>
                            <p class="text-3xl font-black text-gray-900 dark:text-white leading-none">
                                {{ number_format($total_usage_kwh, 0, ',', '.') }}
                                <span class="text-base font-semibold text-gray-800 dark:text-gray-300 ml-1">kWh</span>
                            </p>
                            <p class="text-xs text-gray-800 dark:text-gray-300 mt-1.5 font-medium">
                                Akumulasi seluruh pemakaian listrik
                            </p>
                        </div>
                    </div>
                    <div class="h-1 bg-yellow-500 dark:bg-yellow-400"></div>
                </div>

                {{-- Kartu 3: Total Record Tagihan --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6 flex items-start gap-4">
                        {{-- Ikon --}}
                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/40">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        {{-- Konten --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200 mb-1">
                                Total Record Tagihan
                            </p>
                            <p class="text-3xl font-black text-gray-900 dark:text-white leading-none">
                                {{ number_format($total_bills_count, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-800 dark:text-gray-300 mt-1.5 font-medium">
                                Berkas tagihan terproses di sistem
                            </p>
                        </div>
                    </div>
                    <div class="h-1 bg-green-500 dark:bg-green-400"></div>
                </div>

            </div>{{-- /grid --}}

            {{-- ============================================================ --}}
            {{-- QUICK ACCESS: Akses Cepat ke Modul Manajemen                 --}}
            {{-- ============================================================ --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wide">
                        Akses Cepat
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('bills.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Kelola Tagihan Listrik
                        </a>
                        <a href="{{ route('bills.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Tagihan Baru
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
