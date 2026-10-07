<?php

namespace Tests\Feature;

use App\Exports\CustomerPhonesExport;
use App\Exports\CustomersExport;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class CustomerExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_customer_export_downloads_xlsx_with_arabic_headings(): void
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
            'name' => 'عبد الرحمن علي',
            'city' => 'الرياض',
            'address' => 'شارع الملك فهد',
            'mobile' => '966500000002',
            'specialization_id' => $specialization->id,
            'status' => 'active',
            'subscription_start_date' => '2026-09-01',
            'subscription_end_date' => '2026-12-31',
        ]);

        $response = $this->get(route('customers.export'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertDownload('customers.xlsx');

        $xlsx = Excel::raw(new CustomersExport, ExcelFormat::XLSX);
        $this->assertStringStartsWith('PK', $xlsx);
        $this->assertNotSame('', $xlsx);
        $this->assertGreaterThan(0, strlen($xlsx));
    }

    public function test_phone_numbers_export_downloads_xlsx_with_phone_column(): void
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

        Customer::create([
            'organization_id' => $organization->id,
            'name' => 'سامي محمد',
            'city' => 'الرياض',
            'address' => 'شارع العليا',
            'mobile' => '966500000012',
            'specialization_id' => $specialization->id,
            'status' => 'active',
            'subscription_start_date' => '2026-09-20',
            'subscription_end_date' => '2026-10-19',
        ]);

        $response = $this->get(route('customers.export.phones'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertDownload('customers-phones.xlsx');

        $xlsx = Excel::raw(new CustomerPhonesExport, ExcelFormat::XLSX);
        $this->assertStringStartsWith('PK', $xlsx);
        $this->assertNotSame('', $xlsx);
        $this->assertGreaterThan(0, strlen($xlsx));
    }
}
