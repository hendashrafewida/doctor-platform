<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\Specialization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('customers.index', [
            'customers' => Customer::with(['organization', 'specialization'])->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('customers.create', [
            'organizations' => Organization::query()->orderBy('name')->get(),
            'specializations' => Specialization::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Customer::create($this->validated($request));

        return redirect()->route('customers.index')->with('success', 'تم إنشاء العميل بنجاح.');
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', [
            'customer' => $customer,
            'organizations' => Organization::query()->orderBy('name')->get(),
            'specializations' => Specialization::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update($this->validated($request));

        return redirect()->route('customers.index')->with('success', 'تم تحديث العميل بنجاح.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'تم حذف العميل بنجاح.');
    }

    public function export(): Response
    {
        $customers = Customer::with(['organization', 'specialization'])->orderBy('id')->get();
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['ID', 'Name', 'Organization', 'City', 'Address', 'Mobile', 'Specialization', 'Status']);

        foreach ($customers as $customer) {
            fputcsv($handle, [
                $customer->id,
                $customer->name,
                $customer->organization?->name,
                $customer->city,
                $customer->address,
                $customer->mobile,
                $customer->specialization?->name,
                $customer->status,
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="customers.csv"',
        ]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'organization_id' => ['required', 'exists:organizations,id'],
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:255', 'unique:customers,mobile,' . optional($request->route('customer'))->id],
            'specialization_id' => ['required', 'exists:specializations,id'],
            'status' => ['required', 'in:active,suspended'],
        ]);
    }
}