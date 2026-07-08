<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\ElectricityTariff;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Customer::class);

        $search = $request->query('search');

        $customers = Customer::with('electricityTariff')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('customer_number', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Customer::class);

        $tariffs = ElectricityTariff::all();

        return view('customers.create', compact('tariffs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Customer::class);

        $validated = $request->validate([
            'customer_number' => [
                'required',
                'string',
                'max:12',
                'unique:customers,customer_number',
            ],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'electricity_tariff_id' => [
                'required',
                'exists:electricity_tariffs,id',
            ],
        ]);

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        Gate::authorize('view', $customer);

        $customer->load('electricityTariff');

        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        Gate::authorize('update', $customer);

        $tariffs = ElectricityTariff::all();

        return view('customers.edit', compact('customer', 'tariffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        Gate::authorize('update', $customer);

        $validated = $request->validate([
            'customer_number' => [
                'required',
                'string',
                'max:12',
                Rule::unique('customers', 'customer_number')->ignore($customer->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'electricity_tariff_id' => [
                'required',
                'exists:electricity_tariffs,id',
            ],
        ]);

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        Gate::authorize('delete', $customer);

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
