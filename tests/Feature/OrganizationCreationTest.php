<?php

namespace Tests\Feature;

use App\Filament\Admin\Resources\OrganizationResource\Pages\CreateOrganization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrganizationCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_organization_creates_default_admin_employee(): void
    {
        $page = new CreateOrganization();
        $method = new \ReflectionMethod($page, 'handleRecordCreation');
        $method->setAccessible(true);

        $organization = $method->invoke($page, [
            'name' => 'مستشفى النجاح',
            'city' => 'القاهرة',
            'address' => 'عنوان التجربة',
            'mobile' => '01012345678',
            'specialization' => 'جلدية',
            'status' => 'active',
            'password' => 'Str0ngPass!123',
        ]);

        $employee = $organization->employees()->first();

        $this->assertNotNull($employee);
        $this->assertSame('admin', $employee->name);
        $this->assertSame('admin', $employee->role);
        $this->assertTrue(Hash::check('Str0ngPass!123', $employee->password));
        $this->assertDatabaseHas('organizations', [
            'name' => 'مستشفى النجاح',
            'city' => 'القاهرة',
        ]);
        $this->assertDatabaseHas('organization_employees', [
            'organization_id' => $organization->id,
            'name' => 'admin',
            'role' => 'admin',
        ]);
    }

    public function test_application_locale_and_direction_are_arabic_rtl(): void
    {
        $this->assertSame('ar', config('app.locale'));
        $this->assertSame('ar', app()->getLocale());

        $html = view('layouts.app')->render();

        $this->assertStringContainsString('lang="ar"', $html);
        $this->assertStringContainsString('dir="rtl"', $html);
    }
}
