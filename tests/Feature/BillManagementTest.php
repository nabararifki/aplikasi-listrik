<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\ElectricityTariff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private ElectricityTariff $tariff;
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'access_level' => 1,
        ]);

        $this->tariff = ElectricityTariff::create([
            'user_id' => $this->admin->id,
            'tariff_code' => 'R1',
            'base_charge' => 20000,
            'tariff_per_kwh' => 1352,
        ]);

        $this->customer = Customer::create([
            'electricity_tariff_id' => $this->tariff->id,
            'customer_number' => 'CUST-001',
            'name' => 'Bara Rifki',
            'address' => 'Sleman, Yogyakarta',
        ]);
    }

    /**
     * Test the formula for total_charge calculation:
     * total_charge = base_charge + (electricity_usage * tariff_per_kwh).
     */
    public function test_bill_total_charge_formula_calculation(): void
    {
        $bill = Bill::create([
            'customer_id' => $this->customer->id,
            'billing_year' => '2026',
            'billing_month' => 1,
            'electricity_usage' => 250, // kWh
            'status' => 'unpaid',
        ]);

        $expectedTotalCharge = 20000 + (250 * 1352); // 358000
        
        $this->assertEquals($expectedTotalCharge, $bill->total_charge);
    }

    /**
     * Test instant PATCH route (bills.toggle-status) toggles status.
     */
    public function test_toggle_status_route_toggles_bill_status_back_and_forth(): void
    {
        $bill = Bill::create([
            'customer_id' => $this->customer->id,
            'billing_year' => '2026',
            'billing_month' => 1,
            'electricity_usage' => 250,
            'status' => 'unpaid',
        ]);

        // Toggle from unpaid to paid
        $response = $this->actingAs($this->admin)
            ->from(route('bills.index'))
            ->patch(route('bills.toggle-status', $bill));

        $response->assertRedirect(route('bills.index'));
        $this->assertEquals('paid', $bill->fresh()->status);

        // Toggle from paid to unpaid
        $response = $this->actingAs($this->admin)
            ->from(route('bills.index'))
            ->patch(route('bills.toggle-status', $bill));

        $response->assertRedirect(route('bills.index'));
        $this->assertEquals('unpaid', $bill->fresh()->status);
    }

    /**
     * Test public bill search returns accurate grand_total_unpaid.
     */
    public function test_public_bill_search_returns_accurate_grand_total_unpaid(): void
    {
        // Create multiple bills with different statuses
        // Bill 1: unpaid
        Bill::create([
            'customer_id' => $this->customer->id,
            'billing_year' => '2026',
            'billing_month' => 1,
            'electricity_usage' => 100, // 20000 + (100 * 1352) = 155200
            'status' => 'unpaid',
        ]);

        // Bill 2: unpaid
        Bill::create([
            'customer_id' => $this->customer->id,
            'billing_year' => '2026',
            'billing_month' => 2,
            'electricity_usage' => 200, // 20000 + (200 * 1352) = 290400
            'status' => 'unpaid',
        ]);

        // Bill 3: paid (should be excluded from grand_total_unpaid)
        Bill::create([
            'customer_id' => $this->customer->id,
            'billing_year' => '2026',
            'billing_month' => 3,
            'electricity_usage' => 300, // 20000 + (300 * 1352) = 425600
            'status' => 'paid',
        ]);

        $expectedGrandTotalUnpaid = (20000 + 100 * 1352) + (20000 + 200 * 1352); // 155200 + 290400 = 445600

        $response = $this->get(route('public.bills.search', [
            'customer_number' => 'CUST-001'
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('public_bills.result');
        $response->assertViewHas('grand_total_unpaid', $expectedGrandTotalUnpaid);
    }
}
