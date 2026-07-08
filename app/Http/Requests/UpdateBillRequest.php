<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBillRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        // Mengembalikan true karena otorisasi operasional dikendalikan secara
        // tersentralisasi melalui middleware dan Model Policy.
        return true;
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // customer_id opsional (sometimes) tetapi jika ada wajib terdaftar di kolom id pada tabel customers.
            'customer_id' => 'sometimes|required|exists:customers,id',
            
            // billing_year opsional (sometimes) tetapi jika ada wajib berupa string sepanjang tepat 4 karakter (tahun).
            'billing_year' => 'sometimes|required|string|size:4',
            
            // billing_month opsional (sometimes) tetapi jika ada wajib berupa integer di rentang 1 s.d 12 (bulan).
            'billing_month' => 'sometimes|required|integer|between:1,12',
            
            // electricity_usage opsional (sometimes) tetapi jika ada wajib berupa integer dengan nilai minimal 0 kWh.
            'electricity_usage' => 'sometimes|required|integer|min:0',

            // status opsional (sometimes) tetapi jika ada wajib berupa string bernilai 'paid' atau 'unpaid'.
            'status' => 'sometimes|required|string|in:paid,unpaid',
        ];
    }
}

