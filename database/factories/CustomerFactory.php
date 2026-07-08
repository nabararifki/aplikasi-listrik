<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\ElectricityTariff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tariff = ElectricityTariff::inRandomOrder()->first();

        $indonesianNames = [
            'Budi Santoso', 'Siti Aminah', 'Joko Purwanto', 'Dewi Lestari', 'Agus Setiawan',
            'Rina Wijaya', 'Ahmad Hidayat', 'Sri Wahyuni', 'Hendra Wijaya', 'Mega Utami',
            'Dian Pratama', 'Eko Susilo', 'Fitriani', 'Aditya Nugraha', 'Wulan Dari',
            'Rahmat Hidayat', 'Siti Nurhaliza', 'Rudi Hermawan', 'Indah Permatasari'
        ];

        $indonesianAddresses = [
            'Jl. Merdeka No. 12, Jakarta', 'Jl. Mawar No. 45, Bandung', 'Jl. Sudirman No. 88, Surabaya',
            'Jl. Diponegoro No. 3, Yogyakarta', 'Jl. Gajah Mada No. 21, Semarang', 'Jl. Pemuda No. 7, Medan',
            'Jl. Ahmad Yani No. 56, Makassar', 'Jl. Kartini No. 15, Solo', 'Jl. Pahlawan No. 9, Malang',
            'Jl. Gatot Subroto No. 34, Denpasar'
        ];

        return [
            'electricity_tariff_id' => $tariff ? $tariff->id : ElectricityTariff::factory(),
            'customer_number' => 'CUST-' . $this->faker->unique()->numberBetween(100, 999),
            'name' => $this->faker->randomElement($indonesianNames),
            'address' => $this->faker->randomElement($indonesianAddresses),
        ];
    }
}
