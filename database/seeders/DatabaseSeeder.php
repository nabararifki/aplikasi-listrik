<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ElectricityTariff;
use App\Models\Customer;
use App\Models\Bill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Data Statis)
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'access_level' => 1, // Admin
        ]);

        $officer = User::create([
            'username' => 'officer',
            'email' => 'officer@example.com',
            'password' => Hash::make('password'),
            'access_level' => 2, // Officer
        ]);

        // 2. Seed Electricity Tariffs (Data Statis)
        $tariffR1M = ElectricityTariff::create([
            'user_id' => $admin->id,
            'tariff_code' => 'R1M',
            'base_charge' => 20000,
            'tariff_per_kwh' => 1352,
        ]);

        $tariffR1 = ElectricityTariff::create([
            'user_id' => $admin->id,
            'tariff_code' => 'R1',
            'base_charge' => 35000,
            'tariff_per_kwh' => 1444,
        ]);

        $tariffR2 = ElectricityTariff::create([
            'user_id' => $admin->id,
            'tariff_code' => 'R2',
            'base_charge' => 50000,
            'tariff_per_kwh' => 1699,
        ]);

        // 3. Seed Customer Khusus (Bara Rifki - CUST-001)
        $specialCustomer = Customer::create([
            'electricity_tariff_id' => $tariffR1->id,
            'customer_number' => 'CUST-001',
            'name' => 'Bara Rifki',
            'address' => 'Komp. Godean Residence Blok B-9, Sleman',
        ]);

        // 3 rekam jejak tagihan berturut-turut untuk Bara Rifki (Januari, Februari, Maret 2026)
        Bill::create([
            'customer_id' => $specialCustomer->id,
            'billing_year' => '2026',
            'billing_month' => 1, // Januari
            'electricity_usage' => 250, // kWh
            'status' => 'paid',
        ]);

        Bill::create([
            'customer_id' => $specialCustomer->id,
            'billing_year' => '2026',
            'billing_month' => 2, // Februari
            'electricity_usage' => 280, // kWh
            'status' => 'unpaid',
        ]);

        Bill::create([
            'customer_id' => $specialCustomer->id,
            'billing_year' => '2026',
            'billing_month' => 3, // Maret
            'electricity_usage' => 310, // kWh
            'status' => 'unpaid',
        ]);

        // 4. Seed Data Pelanggan Tambahan & Tagihan Menggunakan Factory
        // Membuat 12 customer tambahan
        $additionalCustomers = Customer::factory()->count(12)->create();

        // Untuk setiap customer tambahan, buat 1-2 tagihan secara acak
        foreach ($additionalCustomers as $customer) {
            $months = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])->random(rand(1, 2));
            foreach ($months as $month) {
                Bill::create([
                    'customer_id' => $customer->id,
                    'billing_year' => '2026',
                    'billing_month' => $month,
                    'electricity_usage' => rand(80, 500),
                    'status' => collect(['paid', 'unpaid'])->random(),
                ]);
            }
        }
    }
}
