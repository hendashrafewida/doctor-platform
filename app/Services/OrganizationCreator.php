<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\OrganizationEmployee;
use Illuminate\Support\Facades\DB;

class OrganizationCreator
{
    public function create(array $data): Organization
    {
        return DB::transaction(function () use ($data): Organization {
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
