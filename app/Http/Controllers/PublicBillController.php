<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class PublicBillController extends Controller
{
    /**
     * Menampilkan halaman pencarian tagihan publik.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        // Mengembalikan tampilan form pencarian awal tagihan listrik untuk umum.
        return view('public_bills.index');
    }

    /**
     * Melakukan pencarian tagihan berdasarkan nomor pelanggan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function search(Request $request): RedirectResponse|View
    {
        // 1. Mengambil nomor pelanggan dari input request
        $customerNumber = $request->input('customer_number');

        // 2. Mencari data pelanggan beserta relasi tarif listrik dan tagihan
        $customer = Customer::with(['bills', 'electricityTariff'])
            ->where('customer_number', $customerNumber)
            ->first();

        // 3. Jika data pelanggan tidak ditemukan, kembalikan ke halaman sebelumnya dengan pesan error
        if (!$customer) {
            return redirect()
                ->route('public.bills.index')
                ->withInput()
                ->with('error', 'Nomor pelanggan tidak ditemukan.');
        }

        // Hitung total akumulasi biaya khusus untuk seluruh tagihan yang berstatus 'unpaid'
        $grand_total_unpaid = $customer->bills->where('status', 'unpaid')->sum('total_charge');

        // 4. Jika ditemukan, tampilkan halaman hasil pencarian dengan membawa data pelanggan
        return view('public_bills.result', compact('customer', 'grand_total_unpaid'));
    }
}

