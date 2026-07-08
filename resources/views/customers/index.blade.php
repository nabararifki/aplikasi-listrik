<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900 dark:text-white leading-tight">
            {{ __('Manajemen Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Alert Sukses Operasi CRUD -->
            @if (session('success'))
                <div class="mb-6 p-4 text-sm text-green-900 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-300 dark:border-green-800" role="alert">
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">

                <!-- Header Kontrol: Pencarian & Tambah Data -->
                <div class="p-6 text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <!-- Form Pencarian -->
                    <form method="GET" action="{{ route('customers.index') }}" class="flex items-center w-full md:w-96 gap-2">
                        <x-text-input
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama / nomor pelanggan..."
                            class="w-full text-sm bg-white text-gray-900 border-gray-400 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <x-primary-button type="submit" class="py-2.5 bg-indigo-700 hover:bg-indigo-600 text-white font-bold">
                            {{ __('Cari') }}
                        </x-primary-button>
                        @if(request('search'))
                            <a href="{{ route('customers.index') }}" class="text-xs text-gray-900 hover:text-black dark:text-gray-100 dark:hover:text-white underline font-bold whitespace-nowrap">
                                Reset
                            </a>
                        @endif
                    </form>

                    <!-- Tombol Tambah Data Baru -->
                    @can('create', App\Models\Customer::class)
                    <div class="flex-shrink-0">
                        <a href="{{ route('customers.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-700 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Pelanggan Baru
                        </a>
                    </div>
                    @endcan
                </div>

                <!-- Tabel Data Pelanggan -->
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-sm text-left bg-white dark:bg-gray-900">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    Nomor Pelanggan
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    Nama Pelanggan
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    Alamat
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    Golongan Tarif
                                </th>
                                <th scope="col" class="px-6 py-4 text-center font-bold text-gray-900 dark:text-white">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($customers as $index => $customer)
                                <tr class="bg-white dark:bg-gray-800 even:bg-gray-50/70 dark:even:bg-gray-800/40 hover:bg-indigo-50/50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    {{-- Data: No --}}
                                    <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">
                                        {{ $customers->firstItem() + $index }}
                                    </td>

                                    {{-- Data: Nomor Pelanggan --}}
                                    <td class="px-6 py-4 font-mono font-bold text-indigo-700 dark:text-indigo-300">
                                        {{ $customer->customer_number }}
                                    </td>

                                    {{-- Data: Nama Pelanggan --}}
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                        {{ $customer->name }}
                                    </td>

                                    {{-- Data: Alamat --}}
                                    <td class="px-6 py-4 text-gray-800 dark:text-gray-200 max-w-xs truncate">
                                        {{ $customer->address }}
                                    </td>

                                    {{-- Data: Golongan Tarif --}}
                                    <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">
                                        @if($customer->electricityTariff)
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-900 dark:bg-blue-900/60 dark:text-blue-200 border border-blue-200 dark:border-blue-700">
                                                {{ $customer->electricityTariff->tariff_code }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    {{-- Data: Aksi --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="inline-flex items-center gap-3">
                                            {{-- Detail (Show) --}}
                                            <a href="{{ route('customers.show', $customer) }}" class="text-blue-700 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Lihat Detail">
                                                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            {{-- Ubah (Edit) --}}
                                            @can('update', $customer)
                                            <a href="{{ route('customers.edit', $customer) }}" class="text-yellow-700 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="Ubah Data">
                                                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            @endcan

                                            {{-- Hapus (Delete) --}}
                                            @can('delete', $customer)
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan {{ $customer->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-700 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 bg-transparent border-0 p-0 cursor-pointer" title="Hapus Data">
                                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="font-bold">Tidak ada data pelanggan ditemukan.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    {{ $customers->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
