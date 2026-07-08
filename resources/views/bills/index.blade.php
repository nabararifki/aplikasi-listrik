<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Tagihan Listrik') }}
        </h2>
    </x-slot>

    @php
        $indonesianMonths = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        /**
         * Helper: membangun URL sort dengan menyertakan seluruh query string aktif
         * (termasuk 'search') agar tidak ter-reset saat klik header kolom.
         */
        $sortUrl = function (string $column) {
            $currentSort      = request('sort');
            $currentDirection = request('direction', 'asc');
            $newDirection     = ($currentSort === $column && $currentDirection === 'asc') ? 'desc' : 'asc';

            return route('bills.index', array_merge(
                request()->query(),
                ['sort' => $column, 'direction' => $newDirection]
            ));
        };

        /**
         * Helper: CSS kelas warna teks pada link header kolom.
         * Kolom aktif → indigo, tidak aktif → default gray.
         */
        $sortHeaderClass = function (string $column) use ($sort): string {
            return $sort === $column
                ? 'text-indigo-700 dark:text-indigo-300'
                : 'text-gray-900 dark:text-white';
        };

        /**
         * Helper: merender sepasang ikon SVG panah atas-bawah minimalis.
         *
         * Logika reaktivitas warna per-ikon:
         *  - Kolom TIDAK aktif  → kedua panah redup: text-gray-400 dark:text-gray-500
         *  - Kolom aktif ASC    → panah atas nyala (indigo), panah bawah redup
         *  - Kolom aktif DESC   → panah bawah nyala (indigo), panah atas redup
         *
         * SVG menggunakan dua path stroke-only dengan ukuran w-3 h-3 agar proporsional
         * di dalam konteks header tabel berukuran text-xs uppercase.
         */
        $sortSvg = function (string $column) use ($sort, $direction): string {
            $isActive = ($sort === $column);

            $upClass   = 'text-gray-400 dark:text-gray-500';
            $downClass = 'text-gray-400 dark:text-gray-500';

            if ($isActive) {
                if ($direction === 'asc') {
                    $upClass   = 'text-indigo-600 dark:text-indigo-400';
                    $downClass = 'text-gray-300 dark:text-gray-600';
                } else {
                    $upClass   = 'text-gray-300 dark:text-gray-600';
                    $downClass = 'text-indigo-600 dark:text-indigo-400';
                }
            }

            return '
                <span class="inline-flex flex-col gap-0" aria-hidden="true">
                    <svg class="w-3 h-3 ' . $upClass . '" viewBox="0 0 12 12" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 8l4-4 4 4"/>
                    </svg>
                    <svg class="w-3 h-3 ' . $downClass . '" viewBox="0 0 12 12" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 4l4 4 4-4"/>
                    </svg>
                </span>';
        };
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Alert Sukses Operasi CRUD -->
            @if (session('success'))
                <div class="mb-6 p-4 text-sm text-green-800 border border-green-200 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">

                <!-- ============================================================ -->
                <!-- Header Kontrol: Pencarian & Tambah Data                      -->
                <!-- ============================================================ -->
                <div class="p-6 text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <!-- Form Pencarian — hidden inputs mempertahankan sort & direction -->
                    <form method="GET" action="{{ route('bills.index') }}" class="flex items-center w-full md:w-96 gap-2">
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        @if(request('direction'))
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        @endif

                        <x-text-input
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama / nomor pelanggan..."
                            class="w-full text-sm bg-white text-gray-900 border-gray-400 dark:bg-gray-900 dark:text-white dark:border-gray-600"
                        />
                        <x-primary-button type="submit" class="py-2.5">
                            {{ __('Cari') }}
                        </x-primary-button>
                        @if(request('search'))
                            <a href="{{ route('bills.index') }}" class="text-xs text-gray-800 hover:text-gray-950 dark:text-gray-200 dark:hover:text-white underline whitespace-nowrap">
                                Reset
                            </a>
                        @endif
                    </form>

                    <!-- Tombol Tambah Data Baru -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('bills.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Tagihan Baru
                        </a>
                    </div>
                </div>

                <!-- ============================================================ -->
                <!-- Tabel Data Tagihan (Responsif + Sortable Headers + SVG Ikon) -->
                <!-- ============================================================ -->
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-sm text-left bg-white dark:bg-gray-900">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <tr>

                                {{-- Kolom: Pelanggan (Sortable) --}}
                                <th scope="col" class="px-6 py-4">
                                    <a href="{{ $sortUrl('customer') }}"
                                       title="Urutkan berdasarkan Pelanggan"
                                       class="inline-flex items-center gap-1.5 font-bold hover:underline {{ $sortHeaderClass('customer') }}">
                                        Pelanggan
                                        {!! $sortSvg('customer') !!}
                                    </a>
                                </th>

                                {{-- Kolom: Tahun & Bulan (Sortable) --}}
                                <th scope="col" class="px-6 py-4">
                                    <a href="{{ $sortUrl('period') }}"
                                       title="Urutkan berdasarkan Periode"
                                       class="inline-flex items-center gap-1.5 font-bold hover:underline {{ $sortHeaderClass('period') }}">
                                        Tahun &amp; Bulan
                                        {!! $sortSvg('period') !!}
                                    </a>
                                </th>

                                {{-- Kolom: Penggunaan (Sortable) --}}
                                <th scope="col" class="px-6 py-4 text-right">
                                    <a href="{{ $sortUrl('usage') }}"
                                       title="Urutkan berdasarkan Penggunaan kWh"
                                       class="inline-flex items-center justify-end gap-1.5 w-full font-bold hover:underline {{ $sortHeaderClass('usage') }}">
                                        Penggunaan
                                        {!! $sortSvg('usage') !!}
                                    </a>
                                </th>

                                {{-- Kolom: Tarif / Beban (Tidak sortable) --}}
                                <th scope="col" class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">
                                    Tarif / Beban
                                </th>

                                {{-- Kolom: Total Tagihan (Sortable via formula SQL) --}}
                                <th scope="col" class="px-6 py-4 text-right">
                                    <a href="{{ $sortUrl('total_charge') }}"
                                       title="Urutkan berdasarkan Total Tagihan"
                                       class="inline-flex items-center justify-end gap-1.5 w-full font-bold hover:underline {{ $sortHeaderClass('total_charge') }}">
                                        Total Tagihan
                                        {!! $sortSvg('total_charge') !!}
                                    </a>
                                </th>

                                {{-- Kolom: Status (Tidak sortable) --}}
                                <th scope="col" class="px-6 py-4 text-center font-bold text-gray-900 dark:text-white">
                                    Status
                                </th>

                                {{-- Kolom: Aksi (Tidak sortable) --}}
                                <th scope="col" class="px-6 py-4 text-center font-bold text-gray-900 dark:text-white">
                                    Aksi
                                </th>

                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($bills as $bill)
                                <tr class="bg-white dark:bg-gray-800 even:bg-gray-50/70 dark:even:bg-gray-800/40 hover:bg-indigo-50/50 dark:hover:bg-gray-700/50 transition-colors duration-150">

                                    {{-- Data: Pelanggan --}}
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $bill->customer?->name }}</div>
                                        <div class="text-xs font-mono text-gray-800 dark:text-gray-200 mt-0.5">{{ $bill->customer?->customer_number }}</div>
                                    </td>

                                    {{-- Data: Periode --}}
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $bill->billing_year }} – {{ $indonesianMonths[$bill->billing_month] ?? 'Bulan ' . $bill->billing_month }}
                                    </td>

                                    {{-- Data: Penggunaan (kWh) --}}
                                    <td class="px-6 py-4 text-right font-mono font-medium text-gray-900 dark:text-white">
                                        {{ number_format($bill->electricity_usage, 0, ',', '.') }} kWh
                                    </td>

                                    {{-- Data: Tarif & Biaya Beban --}}
                                    <td class="px-6 py-4 text-right text-xs text-gray-800 dark:text-gray-200">
                                        <div class="font-medium">Beban: Rp {{ number_format($bill->customer?->electricityTariff?->base_charge ?? 0, 0, ',', '.') }}</div>
                                        <div class="font-medium">Tarif: Rp {{ number_format($bill->customer?->electricityTariff?->tariff_per_kwh ?? 0, 0, ',', '.') }}/kWh</div>
                                    </td>

                                    {{-- Data: Total Tagihan --}}
                                    <td class="px-6 py-4 text-right font-bold text-blue-600 dark:text-blue-400">
                                        Rp {{ number_format($bill->total_charge, 0, ',', '.') }}
                                    </td>

                                    {{-- Data: Status --}}
                                    <td class="px-6 py-4 text-center">
                                        @if ($bill->status === 'paid')
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-900 dark:bg-green-900/60 dark:text-green-200 border border-green-200 dark:border-green-700 whitespace-nowrap">
                                                Lunas
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-900 dark:bg-red-900/60 dark:text-red-200 border border-red-200 dark:border-red-700 whitespace-nowrap">
                                                Belum Lunas
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Kolom Aksi: Ikon SVG Heroicons --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-1.5">

                                            {{-- Lihat Detail (Eye Icon) --}}
                                            <a href="{{ route('bills.show', $bill) }}"
                                               title="Lihat Detail"
                                               class="p-1.5 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span class="sr-only">Lihat Detail</span>
                                            </a>

                                            {{-- Ubah Data (Pencil Icon) --}}
                                            <a href="{{ route('bills.edit', $bill) }}"
                                               title="Ubah Data"
                                               class="p-1.5 rounded-md text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 hover:text-yellow-700 dark:hover:text-yellow-300 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span class="sr-only">Ubah Data</span>
                                            </a>

                                            {{-- Hapus Data (Trash Icon) — hanya Admin via direktif can --}}
                                            @can('delete', $bill)
                                                <form action="{{ route('bills.destroy', $bill) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus tagihan ini? Tindakan ini tidak dapat dibatalkan.');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            title="Hapus Data"
                                                            class="p-1.5 rounded-md text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-700 dark:hover:text-red-300 bg-transparent border-0 cursor-pointer transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        <span class="sr-only">Hapus Data</span>
                                                    </button>
                                                </form>
                                            @endcan

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr class="bg-white dark:bg-gray-800">
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-800 dark:text-gray-200">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="font-semibold">Tidak ditemukan data tagihan listrik.</span>
                                            @if(request('search'))
                                                <a href="{{ route('bills.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Tampilkan semua data</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Halaman Paginasi -->
                <div class="p-6 border-t border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100">
                    {{ $bills->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
