<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\ElectricityTariff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $officer;
    private ElectricityTariff $tariff;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup base data: admin, officer, and a tariff
        $this->admin = User::factory()->create([
            'access_level' => 1, // Admin
        ]);

        $this->officer = User::factory()->create([
            'access_level' => 2, // Officer
        ]);

        $this->tariff = ElectricityTariff::create([
            'user_id' => $this->admin->id,
            'tariff_code' => 'R1',
            'base_charge' => 10000,
            'tariff_per_kwh' => 1500,
        ]);
    }

    /**
     * Admin (access_level = 1) can create customer.
     */
    public function test_admin_can_create_customer(): void
    {
        $response = $this->actingAs($this->admin)->post(route('customers.store'), [
            'electricity_tariff_id' => $this->tariff->id,
            'customer_number' => 'CUST-001',
            'name' => 'Bara Rifki',
            'address' => 'Sleman, Yogyakarta',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'customer_number' => 'CUST-001',
            'name' => 'Bara Rifki',
        ]);
    }

    /**
     * Admin (access_level = 1) can update customer.
     */
    public function test_admin_can_update_customer(): void
    {
        $customer = Customer::create([
            'electricity_tariff_id' => $this->tariff->id,
            'customer_number' => 'CUST-001',
            'name' => 'Bara Rifki',
            'address' => 'Sleman, Yogyakarta',
        ]);

        $response = $this->actingAs($this->admin)->put(route('customers.update', $customer), [
            'electricity_tariff_id' => $this->tariff->id,
            'customer_number' => 'CUST-001',
            'name' => 'Bara Rifki Updated',
            'address' => 'Updated Address',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Bara Rifki Updated',
            'address' => 'Updated Address',
        ]);
    }

    /**
     * Admin (access_level = 1) can delete customer.
     */
    public function test_admin_can_delete_customer(): void
    {
        $customer = Customer::create([
            'electricity_tariff_id' => $this->tariff->id,
            'customer_number' => 'CUST-001',
            'name' => 'Bara Rifki',
            'address' => 'Sleman, Yogyakarta',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('customers.destroy', $customer));

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    /**
     * Officer (access_level = 2) can create customer.
     */
    public function test_officer_can_create_customer(): void
    {
        $response = $this->actingAs($this->officer)->post(route('customers.store'), [
            'electricity_tariff_id' => $this->tariff->id,
            'customer_number' => 'CUST-002',
            'name' => 'Officer Cust',
            'address' => 'Solo, Central Java',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'customer_number' => 'CUST-002',
            'name' => 'Officer Cust',
        ]);
    }

    /**
     * Officer (access_level = 2) can update customer.
     */
    public function test_officer_can_update_customer(): void
    {
        $customer = Customer::create([
            'electricity_tariff_id' => $this->tariff->id,
            'customer_number' => 'CUST-002',
            'name' => 'Officer Cust',
            'address' => 'Solo, Central Java',
        ]);

        $response = $this->actingAs($this->officer)->put(route('customers.update', $customer), [
            'electricity_tariff_id' => $this->tariff->id,
            'customer_number' => 'CUST-002',
            'name' => 'Officer Cust Updated',
            'address' => 'Updated Address',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Officer Cust Updated',
        ]);
    }

    /**
     * Officer (access_level = 2) cannot delete customer (returns 403).
     */
    public function test_officer_cannot_delete_customer_returns_403(): void
    {
        $customer = Customer::create([
            'electricity_tariff_id' => $this->tariff->id,
            'customer_number' => 'CUST-002',
            'name' => 'Officer Cust',
            'address' => 'Solo, Central Java',
        ]);

        $response = $this->actingAs($this->officer)->delete(route('customers.destroy', $customer));

        $response->assertStatus(403);
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
        ]);
    }

    /**
     * Validate uniqueness of customer_number rejects duplicate payload.
     */
    public function test_customer_number_must_be_unique(): void
    {
        // Create first customer
        Customer::create([
            'electricity_tariff_id' => $this->tariff->id,
            'customer_number' => 'CUST-001',
            'name' => 'First Customer',
            'address' => 'Address 1',
        ]);

        // Try to create second customer with the same customer_number
        $response = $this->actingAs($this->admin)->post(route('customers.store'), [
            'electricity_tariff_id' => $this->tariff->id,
            'customer_number' => 'CUST-001', // duplicate
            'name' => 'Second Customer',
            'address' => 'Address 2',
        ]);

        $response->assertSessionHasErrors('customer_number');
    }

    /**
     * Validate customer_number length limit (max 12 characters).
     */
    public function test_customer_number_cannot_exceed_12_characters(): void
    {
        $response = $this->actingAs($this->admin)->post(route('customers.store'), [
            'electricity_tariff_id' => $this->tariff->id,
            'customer_number' => 'CUST-123456789', // 14 characters, exceeds 12
            'name' => 'Too Long Customer',
            'address' => 'Address Limit',
        ]);

        $response->assertSessionHasErrors('customer_number');
    }
}
