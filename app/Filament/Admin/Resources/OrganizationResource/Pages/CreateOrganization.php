<?php

namespace App\Filament\Admin\Resources\OrganizationResource\Pages;

use App\Filament\Admin\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\OrganizationEmployee;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateOrganization extends CreateRecord
{
    protected static string $resource = OrganizationResource::class;

    /**
     * Handle the record creation with automatic employee creation in a transaction.
     */
    protected function handleRecordCreation(array $data): Organization
    {
        return DB::transaction(function () use ($data) {
            $organization = Organization::create($data);

            OrganizationEmployee::create([
                'organization_id' => $organization->id,
                'name' => 'admin',
                'password' => $data['password'],
                'role' => 'admin',
            ]);

            return $organization;
        });
    }
}
