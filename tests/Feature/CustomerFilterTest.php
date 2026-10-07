<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_and_status_filters_are_combined_and_persisted(): void
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

        Customer::create([
            'organization_id' => $organization->id,
            'name' => 'Mahmoud Ali',
            'city' => 'الرياض',
            'address' => 'شارع الملك',
            'mobile' => '966500000010',
            'specialization_id' => $specialization->id,
            'status' => 'active',
        ]);

        Customer::create([
            'organization_id' => $organization->id,
            'name' => 'Ali Hassan',
            'city' => 'جدة',
            'address' => 'شارع الأمير',
            'mobile' => '966500000020',
            'specialization_id' => $specialization->id,
            'status' => 'suspended',
        ]);

        $response = $this->get('/customers?search=mahmoud&status=active');

        $response->assertOk();
        $response->assertSeeInOrder(['Mahmoud Ali', '966500000010']);
        $response->assertDontSee('Ali Hassan');
        $response->assertSee('mahmoud', false);
        $response->assertSee('active', false);
    }

    public function test_search_filter_bar_has_the_expected_controls_and_state(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/customers?search=mahmoud&status=suspended');

        $response->assertOk();
        $response->assertSee('ti ti-search', false);
        $response->assertSee('name="search"', false);
        $response->assertSee('name="status"', false);
        $response->assertSee('value="mahmoud"', false);
        $response->assertSee('value="suspended"', false);
        $response->assertSee('aria-label="مسح البحث"', false);
    }

    public function test_search_matches_partial_phone_number_case_insensitively(): void
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

        Customer::create([
            'organization_id' => $organization->id,
            'name' => 'محمد عبدالله',
            'city' => 'الرياض',
            'address' => 'شارع الملك',
            'mobile' => '966500000099',
            'specialization_id' => $specialization->id,
            'status' => 'active',
        ]);

        $response = $this->get('/customers?search=0000099');

        $response->assertOk();
        $response->assertSee('محمد عبدالله');
        $response->assertDontSee('لا توجد عملاء مسجلة');
    }
}
