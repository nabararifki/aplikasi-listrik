<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'billing_year',
        'billing_month',
        'electricity_usage',
        'status',
    ];

    /**
     * Atribut virtual yang ditambahkan ke representasi JSON/Array model.
     *
     * @var array
     */
    protected $appends = [
        'total_charge',
    ];

    /**
     * Relasi Belongs-To ke model Customer.
     */
    public function customer(): BelongsTo
    {
        // Penentuan foreign key 'customer_id' dan owner key 'id' secara eksplisit
        // dilakukan untuk memastikan pemetaan data tagihan ke pelanggan yang tepat.
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Aksesor virtual untuk menghitung total biaya tagihan (total_charge).
     */
    protected function totalCharge(): Attribute
    {
        // Penggunaan komponen Attribute::make(get: ...) mendefinisikan sebuah getter (accessor) 
        // pintar untuk menghitung 'total_charge' secara dinamis sewaktu-waktu atribut diakses.
        // Rumus kalkulasi: base_charge + (electricity_usage * tariff_per_kwh).
        return Attribute::make(
            get: function () {
                // Memanggil relasi customer dan lanjut ke electricityTariff yang terkait
                $tariff = $this->customer?->electricityTariff;
                
                if (!$tariff) {
                    return 0;
                }

                return $tariff->base_charge + ($this->electricity_usage * $tariff->tariff_per_kwh);
            }
        );
    }
}
