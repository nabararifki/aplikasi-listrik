<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Menggunakan Authenticatable agar model User dapat berintegrasi secara penuh dengan fitur keamanan & otentikasi Laravel (seperti Guard, Session, dll)
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'access_level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * Penggunaan metode casts() menggantikan properti primitif $casts merupakan standar modern Laravel 12.
     * Metode ini memberikan fleksibilitas lebih karena pengembang dapat memanggil metode pembantu (helper)
     * atau logika pemrograman dinamis lainnya saat mendefinisikan tipe data casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's name (fallback to username).
     */
    protected function name(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => $this->username,
        );
    }

    /**
     * Relasi One-to-Many ke model ElectricityTariff.
     */
    public function electricityTariffs(): HasMany
    {
        // Penentuan parameter foreign key 'user_id' dan local key 'id' secara eksplisit dilakukan
        // untuk memastikan pemetaan kolom yang tepat tanpa bergantung pada asusmi/konvensi penamaan Laravel.
        return $this->hasMany(ElectricityTariff::class, 'user_id', 'id');
    }
}
