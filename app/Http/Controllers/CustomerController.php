<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\Specialization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CustomerController extends Controller
{
    private const PER_PAGE = 15;
    private const EXPORT_HEADERS = ['ID', 'Name', 'Organization', 'City', 'Address', 'Mobile', 'Specialization', 'Status'];

    public function index(Request $request): View
    {
        $customers = $this->filteredCustomerQuery($request)
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create', $this->customerFormOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        Customer::create($this->validated($request));

        return $this->redirectToCustomersIndex('تم إنشاء العميل بنجاح.');
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', [
            'customer' => $customer,
            ...$this->customerFormOptions(),
        ]);
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update($this->validated($request));

        return $this->redirectToCustomersIndex('تم تحديث العميل بنجاح.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return $this->redirectToCustomersIndex('تم حذف العميل بنجاح.');
    }

    public function export(): Response
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, self::EXPORT_HEADERS);

        foreach ($this->customerExportQuery()->get() as $customer) {
            fputcsv($handle, $this->exportRow($customer));
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="customers.csv"',
        ]);
    }

    private function filteredCustomerQuery(Request $request): Builder
    {
        $query = $this->baseCustomerQuery();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search): void {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        return $query;
    }

    private function baseCustomerQuery(): Builder
    {
        return Customer::query()->with(['organization', 'specialization'])->latest();
    }

    private function customerExportQuery(): Builder
    {
        return $this->baseCustomerQuery()->orderBy('id');
    }

    private function customerFormOptions(): array
    {
        return [
            'organizations' => Organization::query()->orderBy('name')->get(),
            'specializations' => Specialization::query()->orderBy('name')->get(),
        ];
    }

    private function exportRow(Customer $customer): array
    {
        return [
            $customer->id,
            $customer->name,
            $customer->organization?->name,
            $customer->city,
            $customer->address,
            $customer->mobile,
            $customer->specialization?->name,
            $customer->status,
        ];
    }

    private function redirectToCustomersIndex(string $message): RedirectResponse
    {
        return redirect()->route('customers.index')->with('success', $message);
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate($this->validationRules($request));

        $startDate = $this->resolveDateInput($request, 'subscription_start_date');
        $endDate = $this->resolveDateInput($request, 'subscription_end_date');

        if ($startDate) {
            $validated['subscription_start_date'] = $startDate;
        }

        if ($endDate) {
            $validated['subscription_end_date'] = $endDate;
        }

        if (($validated['subscription_start_date'] ?? null) && ($validated['subscription_end_date'] ?? null)) {
            if (Carbon::parse($validated['subscription_end_date'])->lt(Carbon::parse($validated['subscription_start_date']))) {
                abort(422, 'تاريخ نهاية الاشتراك لا يمكن أن يكون قبل تاريخ بداية الاشتراك.');
            }
        }

        if (! array_key_exists('subscription_start_date', $validated) && $request->route('customer')?->subscription_start_date) {
            $validated['subscription_start_date'] = $request->route('customer')->subscription_start_date->toDateString();
        }

        if (! array_key_exists('subscription_end_date', $validated) && $request->route('customer')?->subscription_end_date) {
            $validated['subscription_end_date'] = $request->route('customer')->subscription_end_date->toDateString();
        }

        return $validated;
    }

    private function validationRules(Request $request): array
    {
        $customerId = optional($request->route('customer'))->id;

        return [
            'organization_id' => ['required', 'exists:organizations,id'],
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:255', 'unique:customers,mobile,' . $customerId],
            'specialization_id' => ['required', 'exists:specializations,id'],
            'status' => ['required', 'in:active,suspended'],
            'subscription_start_date' => ['nullable', 'date'],
            'subscription_end_date' => ['nullable', 'date'],
        ];
    }

    private function resolveDateInput(Request $request, string $field): ?string
    {
        $year = $request->input($field . '_year');
        $month = $request->input($field . '_month');
        $day = $request->input($field . '_day');

        if ($year === null && $month === null && $day === null) {
            return $request->filled($field) ? $request->input($field) : null;
        }

        if ($year === null || $month === null || $day === null) {
            return $request->filled($field) ? $request->input($field) : null;
        }

        $candidate = sprintf('%s-%02d-%02d', $year, (int) $month, (int) $day);

        return checkdate((int) $month, (int) $day, (int) $year) ? $candidate : null;
    }
}