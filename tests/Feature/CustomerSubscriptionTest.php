<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_be_created_with_subscription_dates(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $organization = Organization::create([
            'name' => 'مستشفى النجاح',
            'address' => 'الرياض',
            'mobile' => '966500000001',
            'password' => bcrypt('secret'),
            'status' => 'active',
            'specialization' => 'طبية',
        ]);
        $specialization = Specialization::create(['name' => 'طب أسنان']);

        $response = $this->post('/customers', [
            'organization_id' => $organization->id,
            'name' => 'عبد الرحمن علي',
            'city' => 'الرياض',
            'address' => 'شارع الملك فهد',
            'mobile' => '966500000002',
            'specialization_id' => $specialization->id,
            'status' => 'active',
            'subscription_start_date_year' => '2026',
            'subscription_start_date_month' => '9',
            'subscription_start_date_day' => '1',
            'subscription_end_date_year' => '2026',
            'subscription_end_date_month' => '12',
            'subscription_end_date_day' => '31',
        ]);

        $response->assertRedirect('/customers');

        $customer = Customer::query()->first();
        $this->assertNotNull($customer);
        $this->assertSame('2026-09-01', $customer->subscription_start_date->format('Y-m-d'));
        $this->assertSame('2026-12-31', $customer->subscription_end_date->format('Y-m-d'));
    }

    public function test_customer_can_be_updated_with_subscription_dates(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $organization = Organization::create([
            'name' => 'مستشفى النجاح',
            'address' => 'الرياض',
            'mobile' => '966500000011',
            'password' => bcrypt('secret'),
            'status' => 'active',
            'specialization' => 'طبية',
        ]);
        $specialization = Specialization::create(['name' => 'طب أسنان']);

        $customer = Customer::create([
            'organization_id' => $organization->id,
            'name' => 'سامي محمد',
            'city' => 'الرياض',
            'address' => 'شارع العليا',
            'mobile' => '966500000012',
            'specialization_id' => $specialization->id,
            'status' => 'active',
            'subscription_start_date' => '2025-09-19',
            'subscription_end_date' => '2025-10-18',
        ]);

        $response = $this->put('/customers/' . $customer->id, [
            'organization_id' => $organization->id,
            'name' => 'سامي محمد',
            'city' => 'الرياض',
            'address' => 'شارع العليا',
            'mobile' => '966500000012',
            'specialization_id' => $specialization->id,
            'status' => 'active',
            'subscription_start_date_year' => '2025',
            'subscription_start_date_month' => '9',
            'subscription_start_date_day' => '20',
            'subscription_end_date_year' => '2025',
            'subscription_end_date_month' => '10',
            'subscription_end_date_day' => '19',
        ]);

        $response->assertRedirect('/customers');
        $customer->refresh();
        $this->assertSame('2025-09-20', $customer->subscription_start_date->format('Y-m-d'));
        $this->assertSame('2025-10-19', $customer->subscription_end_date->format('Y-m-d'));
    }
}
