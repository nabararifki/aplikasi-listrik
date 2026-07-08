<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'electricity_tariff_id',
        'customer_number',
        'name',
        'address',
    ];

    public function electricityTariff(): BelongsTo
    {
        // Penentuan foreign key 'electricity_tariff_id' dan owner key 'id' secara eksplisit
        // untuk mengaitkan data pelanggan dengan tarif listrik yang sesuai secara tepat.
        return $this->belongsTo(ElectricityTariff::class, 'electricity_tariff_id', 'id');
    }

    public function bills(): HasMany
    {
        // Penentuan foreign key 'customer_id' dan local key 'id' secara eksplisit
        // guna menghubungkan pelanggan dengan semua data tagihan (bills) miliknya.
        return $this->hasMany(Bill::class, 'customer_id', 'id');
    }
}
