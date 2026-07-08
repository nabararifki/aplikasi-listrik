<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white">Cek Tagihan Listrik</h2>
        <p class="text-sm text-gray-800 dark:text-gray-200 mt-2">
            Masukkan Nomor Pelanggan Anda untuk memeriksa riwayat penggunaan dan detail tagihan bulanan secara real-time.
        </p>
    </div>

    <!-- Alert Error ketika nomor pelanggan tidak ditemukan -->
    @if (session('error'))
        <div class="mb-5 p-4 text-sm text-red-800 border border-red-200 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
            <div class="flex items-center">
                <svg class="flex-shrink-0 inline w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 3 0v6a1.5 1.5 0 0 1-3 0V4Zm1.5 10.25a1.25 1.25 0 1 1-2.5 0 1.25 1.25 0 0 1 2.5 0Z"/>
                </svg>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <form method="GET" action="{{ route('public.bills.search') }}" class="space-y-4">
        <div>
            <x-input-label for="customer_number" value="Nomor Pelanggan" />
            <x-text-input id="customer_number" class="block mt-1 w-full bg-white text-gray-900 border-gray-400 dark:bg-gray-900 dark:text-white dark:border-gray-600" type="text" name="customer_number" :value="old('customer_number')" required autofocus placeholder="Masukkan 12 digit nomor pelanggan" maxlength="12" />
            <x-input-error :messages="$errors->get('customer_number')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-3 pt-2">
            <x-primary-button class="w-full justify-center py-2.5 dark:bg-gray-900">
                {{ __('Cek Riwayat Tagihan') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
