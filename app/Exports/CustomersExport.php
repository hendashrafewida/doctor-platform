<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    /**
     * @return Collection<int, array<int, mixed>>
     */
    public function collection(): Collection
    {
        return Customer::query()
            ->leftJoin('organizations', 'customers.organization_id', '=', 'organizations.id')
            ->leftJoin('specializations', 'customers.specialization_id', '=', 'specializations.id')
            ->select([
                'customers.id',
                'customers.name',
                'organizations.name as organization_name',
                'customers.city',
                'customers.address',
                'customers.mobile',
                'specializations.name as specialization_name',
                'customers.status',
            ])
            ->orderBy('customers.id')
            ->get();
    }

    public function headings(): array
    {
        return ['المعرّف', 'الاسم', 'الجهة', 'المدينة', 'العنوان', 'رقم الهاتف', 'التخصص', 'الحالة'];
    }
}
