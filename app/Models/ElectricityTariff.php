<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectricityTariff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tariff_code',
        'base_charge',
        'tariff_per_kwh',
    ];

    public function user(): BelongsTo
    {
        // Penentuan foreign key 'user_id' dan owner key 'id' secara eksplisit untuk mencegah
        // kesalahan pemetaan jika terjadi ketidaksesuaian nama model dengan penamaan tabel fisik.
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customers(): HasMany
    {
        // Penentuan foreign key 'electricity_tariff_id' dan local key 'id' secara eksplisit
        // agar relasi satu-ke-banyak terpeta dengan benar pada tabel database customers.
        return $this->hasMany(Customer::class, 'electricity_tariff_id', 'id');
    }
}
