<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900 dark:text-white leading-tight">
            {{ __('Ubah Data Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                
                <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nomor Pelanggan -->
                    <div>
                        <x-input-label for="customer_number" value="Nomor Pelanggan" class="font-bold text-gray-900 dark:text-gray-100" />
                        <x-text-input 
                            id="customer_number" 
                            class="block mt-1 w-full text-sm bg-white text-gray-900 border-gray-400 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500" 
                            type="text" 
                            name="customer_number" 
                            :value="old('customer_number', $customer->customer_number)" 
                            required 
                            placeholder="Masukkan 12 digit nomor pelanggan..." 
                            maxlength="12" 
                        />
                        <x-input-error :messages="$errors->get('customer_number')" class="mt-2 text-red-700 dark:text-red-400 font-bold" />
                    </div>

                    <!-- Nama Pelanggan -->
                    <div>
                        <x-input-label for="name" value="Nama Pelanggan" class="font-bold text-gray-900 dark:text-gray-100" />
                        <x-text-input 
                            id="name" 
                            class="block mt-1 w-full text-sm bg-white text-gray-900 border-gray-400 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500" 
                            type="text" 
                            name="name" 
                            :value="old('name', $customer->name)" 
                            required 
                            placeholder="Masukkan nama lengkap pelanggan..." 
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-700 dark:text-red-400 font-bold" />
                    </div>

                    <!-- Golongan Tarif -->
                    <div>
                        <x-input-label for="electricity_tariff_id" value="Golongan Tarif Listrik" class="font-bold text-gray-900 dark:text-gray-100" />
                        <select 
                            id="electricity_tariff_id" 
                            name="electricity_tariff_id" 
                            class="border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full text-sm" 
                            required
                        >
                            <option value="">-- Pilih Golongan Tarif --</option>
                            @foreach ($tariffs as $tariff)
                                <option value="{{ $tariff->id }}" {{ old('electricity_tariff_id', $customer->electricity_tariff_id) == $tariff->id ? 'selected' : '' }}>
                                    {{ $tariff->tariff_code }} - (Daya: {{ number_format($tariff->base_charge) }} VA, Rp {{ number_format($tariff->tariff_per_kwh) }}/kWh)
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('electricity_tariff_id')" class="mt-2 text-red-700 dark:text-red-400 font-bold" />
                    </div>

                    <!-- Alamat -->
                    <div>
                        <x-input-label for="address" value="Alamat Lengkap" class="font-bold text-gray-900 dark:text-gray-100" />
                        <textarea 
                            id="address" 
                            name="address" 
                            rows="4" 
                            class="block mt-1 w-full text-sm bg-white text-gray-900 border-gray-400 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                            required 
                            placeholder="Masukkan alamat lengkap tempat tinggal..."
                        >{{ old('address', $customer->address) }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2 text-red-700 dark:text-red-400 font-bold" />
                    </div>

                    <!-- Tombol Navigasi Aksi -->
                    <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-5">
                        <a href="{{ route('customers.index') }}" class="text-sm text-gray-900 dark:text-gray-100 hover:text-black dark:hover:text-white underline font-bold">
                            Batal
                        </a>
                        <x-primary-button class="bg-indigo-700 hover:bg-indigo-600 text-white font-bold">
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
