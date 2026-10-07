<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerPhonesExport implements FromCollection, WithHeadings
{
    /**
     * @return Collection<int, array<int, mixed>>
     */
    public function collection(): Collection
    {
        return Customer::query()
            ->select(['id', 'name', 'mobile'])
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return ['المعرّف', 'الاسم', 'رقم الهاتف'];
    }
}
