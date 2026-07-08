<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBillRequest extends FormRequest
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
            // customer_id wajib diisi dan harus terdaftar di kolom id pada tabel customers.
            'customer_id' => 'required|exists:customers,id',
            
            // billing_year wajib diisi, bertipe data string, dan bertipe string sepanjang tepat 4 karakter (tahun).
            'billing_year' => 'required|string|size:4',
            
            // billing_month wajib diisi, bertipe data integer, dan bernilai di rentang 1 s.d 12 (representasi bulan).
            'billing_month' => 'required|integer|between:1,12',
            
            // electricity_usage wajib diisi, bertipe data integer, dengan nilai penggunaan listrik minimal 0 kWh.
            'electricity_usage' => 'required|integer|min:0',

            // status wajib diisi, bertipe data string, bernilai 'paid' atau 'unpaid'.
            'status' => 'required|string|in:paid,unpaid',
        ];
    }
}

