<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Customer;
use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class BillController extends Controller
{
    /**
     * Menampilkan daftar tagihan listrik dengan fitur pencarian, sortir kolom, dan paginasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): View
    {
        // Mengotorisasi apakah user diperbolehkan melihat daftar tagihan secara umum
        Gate::authorize('viewAny', Bill::class);

        // --- Pengambilan Parameter Query String ---
        $search    = $request->query('search');
        $sort      = $request->query('sort');
        $direction = $request->query('direction', 'asc') === 'desc' ? 'desc' : 'asc';

        // --- Whitelist Validasi Kolom Sort (Proteksi SQL Injection) ---
        // Hanya nama-nama kunci berikut yang diizinkan sebagai input 'sort'.
        // Nilai apapun di luar daftar ini akan diabaikan dan query berjalan tanpa sort khusus.
        $allowedSorts = ['customer', 'period', 'usage', 'total_charge'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = null;
        }

        // --- Pembangunan Query Dasar ---
        // select('bills.*') wajib digunakan saat ada leftJoin agar tidak terjadi
        // tabrakan nama kolom 'id' antara tabel bills dan tabel yang di-join.
        $query = Bill::select('bills.*')
            ->with(['customer.electricityTariff']);

        // --- Implementasi Pengurutan Berbasis Relasi & Formula SQL ---
        if ($sort === 'customer') {
            // Sort berdasarkan nama pelanggan: membutuhkan join ke tabel customers
            $query->leftJoin('customers', 'bills.customer_id', '=', 'customers.id')
                  ->orderBy('customers.name', $direction);

        } elseif ($sort === 'period') {
            // Sort berdasarkan periode: urutkan tahun dulu, kemudian bulan (urutan kalender benar)
            $query->orderBy('billing_year', $direction)
                  ->orderBy('billing_month', $direction);

        } elseif ($sort === 'usage') {
            // Sort langsung berdasarkan kolom electricity_usage di tabel bills
            $query->orderBy('electricity_usage', $direction);

        } elseif ($sort === 'total_charge') {
            // Sort berdasarkan total biaya: karena 'total_charge' adalah virtual accessor PHP,
            // kita harus menggunakan formula SQL mentah yang mereplikasi rumus di model.
            // Membutuhkan join ke customers DAN electricity_tariffs untuk mengakses kolom tarif.
            $query->leftJoin('customers', 'bills.customer_id', '=', 'customers.id')
                  ->leftJoin('electricity_tariffs', 'customers.electricity_tariff_id', '=', 'electricity_tariffs.id')
                  ->orderByRaw(
                      '(electricity_tariffs.base_charge + (bills.electricity_usage * electricity_tariffs.tariff_per_kwh)) ' . $direction
                  );
        }

        // --- Penerapan Filter Pencarian (Dipertahankan dari Logika Sebelumnya) ---
        $query->when($search, function ($q, $search) {
            $q->whereHas('customer', function ($subQ) use ($search) {
                $subQ->where('name', 'like', "%{$search}%")
                     ->orWhere('customer_number', 'like', "%{$search}%");
            });
        });

        // --- Paginasi dengan Pelestarian Seluruh Query String ---
        // withQueryString() memastikan parameter 'search', 'sort', dan 'direction'
        // tidak hilang saat pengguna berpindah halaman di tabel paginasi.
        $bills = $query->paginate(10)->withQueryString();

        // Mengembalikan tampilan dashboard manajemen tagihan
        return view('bills.index', compact('bills', 'sort', 'direction'));
    }

    /**
     * Menampilkan formulir pembuatan tagihan baru.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): View
    {
        // Mengotorisasi apakah user memiliki izin untuk membuat data tagihan baru
        Gate::authorize('create', Bill::class);

        // Mengambil seluruh data pelanggan untuk pilihan drop-down di formulir
        $customers = Customer::all();

        // Mengembalikan tampilan form pembuatan tagihan
        return view('bills.create', compact('customers'));
    }

    /**
     * Menyimpan tagihan listrik baru ke database.
     *
     * @param  \App\Http\Requests\StoreBillRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBillRequest $request): RedirectResponse
    {
        // Mengotorisasi apakah user memiliki izin membuat tagihan baru
        Gate::authorize('create', Bill::class);

        // Menyimpan data tagihan yang telah tervalidasi oleh StoreBillRequest
        Bill::create($request->validated());

        // Mengalihkan kembali ke halaman utama manajemen tagihan dengan pesan sukses
        return redirect()
            ->route('bills.index')
            ->with('success', 'Tagihan berhasil disimpan.');
    }

    /**
     * Menampilkan detail informasi tagihan secara spesifik.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Bill $bill): View
    {
        // Mengotorisasi apakah user diizinkan melihat tagihan spesifik ini
        Gate::authorize('view', $bill);

        // Mengembalikan tampilan detail tagihan
        return view('bills.show', compact('bill'));
    }

    /**
     * Menampilkan formulir pengubahan data tagihan.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Bill $bill): View
    {
        // Mengotorisasi apakah user diizinkan untuk mengedit tagihan spesifik ini
        Gate::authorize('update', $bill);

        // Mengambil semua data pelanggan untuk pilihan di form pengeditan
        $customers = Customer::all();

        // Mengembalikan tampilan form edit tagihan
        return view('bills.edit', compact('bill', 'customers'));
    }

    /**
     * Memperbarui data tagihan listrik di database.
     *
     * @param  \App\Http\Requests\UpdateBillRequest  $request
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBillRequest $request, Bill $bill): RedirectResponse
    {
        // Mengotorisasi secara eksplisit dengan memeriksa policy 'update' untuk model tagihan terkait
        Gate::authorize('update', $bill);

        // Melakukan pembaruan parsial berdasarkan data tervalidasi dari UpdateBillRequest
        $bill->update($request->validated());

        // Mengalihkan kembali ke halaman utama manajemen tagihan dengan pesan sukses
        return redirect()
            ->route('bills.index')
            ->with('success', 'Tagihan berhasil diperbarui.');
    }

    /**
     * Menghapus data tagihan listrik dari database.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Bill $bill): RedirectResponse
    {
        // Mengotorisasi secara eksplisit dengan memeriksa policy 'delete' untuk model tagihan terkait
        Gate::authorize('delete', $bill);

        // Menghapus data tagihan dari database
        $bill->delete();

        // Mengalihkan kembali ke halaman utama manajemen tagihan dengan pesan sukses
        return redirect()
            ->route('bills.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }

    /**
     * Mengubah status tagihan secara cepat (paid <-> unpaid).
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(Bill $bill): RedirectResponse
    {
        Gate::authorize('update', $bill);

        $bill->status = $bill->status === 'paid' ? 'unpaid' : 'paid';
        $bill->save();

        return redirect()
            ->back()
            ->with('success', 'Status tagihan berhasil diperbarui.');
    }
}

