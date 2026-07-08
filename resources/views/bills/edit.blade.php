<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ubah Tagihan Listrik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                
                <form method="POST" action="{{ route('bills.update', $bill) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Pilihan Pelanggan -->
                    <div>
                        <x-input-label for="customer_id" value="Pilih Pelanggan" />
                        <select id="customer_id" name="customer_id" class="border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-sm" required>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $bill->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->customer_number }} - {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                    </div>

                    <!-- Tahun Tagihan -->
                    <div>
                        <x-input-label for="billing_year" value="Tahun Tagihan" />
                        <x-text-input id="billing_year" class="block mt-1 w-full text-sm bg-white text-gray-900 border-gray-400 dark:bg-gray-900 dark:text-white dark:border-gray-600" type="text" name="billing_year" :value="old('billing_year', $bill->billing_year)" required placeholder="Contoh: 2026" maxlength="4" />
                        <x-input-error :messages="$errors->get('billing_year')" class="mt-2" />
                    </div>

                    <!-- Bulan Tagihan -->
                    <div>
                        <x-input-label for="billing_month" value="Bulan Tagihan" />
                        <select id="billing_month" name="billing_month" class="border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-sm" required>
                            @php
                                $months = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            @endphp
                            @foreach ($months as $num => $name)
                                <option value="{{ $num }}" {{ old('billing_month', $bill->billing_month) == $num ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('billing_month')" class="mt-2" />
                    </div>

                    <!-- Jumlah Pemakaian Listrik -->
                    <div>
                        <x-input-label for="electricity_usage" value="Jumlah Pemakaian Listrik (kWh)" />
                        <x-text-input id="electricity_usage" class="block mt-1 w-full text-sm bg-white text-gray-900 border-gray-400 dark:bg-gray-900 dark:text-white dark:border-gray-600" type="number" name="electricity_usage" :value="old('electricity_usage', $bill->electricity_usage)" required placeholder="Masukkan jumlah pemakaian (misal: 150)" min="0" />
                        <x-input-error :messages="$errors->get('electricity_usage')" class="mt-2" />
                    </div>

                    <!-- Status Penagihan -->
                    <div>
                        <x-input-label for="status" value="Status Penagihan" class="font-bold text-gray-900 dark:text-gray-100" />
                        <select id="status" name="status" class="border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-sm" required>
                            <option value="unpaid" {{ old('status', $bill->status) == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="paid" {{ old('status', $bill->status) == 'paid' ? 'selected' : '' }}>Lunas</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2 text-red-700 dark:text-red-400 font-bold" />
                    </div>

                    <!-- Tombol Navigasi Aksi -->
                    <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-5">
                        <a href="{{ route('bills.index') }}" class="text-sm text-gray-800 dark:text-gray-200 hover:text-gray-950 dark:hover:text-white underline">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Perbarui Tagihan') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
