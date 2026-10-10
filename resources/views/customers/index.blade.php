@extends('layouts.app')

@section('title', 'قائمة العملاء')

@section('content')
<style>
.customer-shell { direction: rtl; max-width: 1320px; margin: 0 auto; }
.customer-breadcrumb { color: var(--bs-secondary-color); font-size: .78rem; margin: 0 0 24px; }
.customer-breadcrumb strong { color: var(--bs-heading-color); font-size: 1.3rem; margin-left: 18px; }
.customer-card { background: var(--bs-card-bg); border: 1px solid var(--bs-border-color); border-radius: 8px; overflow: hidden; }
.customer-card__head { display: flex; align-items: center; justify-content: space-between; padding: 20px 18px; border-bottom: 1px solid var(--bs-border-color); }
.customer-card__head h5 { color: var(--bs-heading-color); margin: 0; font-size: .95rem; }
.customer-actions { display: flex; gap: 4px; direction: ltr; }
.customer-actions .btn { border: 1px solid var(--bs-primary); color: var(--bs-white, #fff); background: var(--bs-primary); font-size: .78rem; }
.customer-actions .btn-outline { background: transparent; border-color: var(--bs-border-color); color: var(--bs-body-color); }
.customer-filters { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto; gap: 10px; padding: 18px; align-items: end; }
.customer-filters label { display: block; color: var(--bs-secondary-color); font-size: .75rem; margin-bottom: 7px; }
.customer-filters .form-field { position: relative; }
.customer-filters .form-control, .customer-filters .form-select { width: 100%; min-height: 42px; padding: 0 38px 0 12px; background: var(--bs-body-bg); color: var(--bs-body-color); border: 1px solid var(--bs-border-color); border-radius: 8px; box-shadow: none; font-size: .78rem; transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease; }
.customer-filters .form-control::placeholder { color: var(--bs-secondary-color); }
.customer-filters .form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M3 6l5 5 5-5'/%3E%3C/svg%3E"); background-position: left 12px center; background-repeat: no-repeat; background-size: 14px; padding-left: 12px; padding-right: 36px; }
.customer-filters .form-field .ti { position: absolute; right: 12px; top: 50%; z-index: 2; color: var(--bs-secondary-color); font-size: .82rem; transform: translateY(-50%); pointer-events: none; }
.customer-filters .form-control:focus, .customer-filters .form-select:focus { border-color: var(--bs-primary); box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--bs-primary) 20%, transparent); outline: 0; }
.customer-filters .form-control:focus + .clear-filters { opacity: 1; }
.customer-filters .clear-filters { position: absolute; left: 10px; top: 50%; z-index: 3; color: var(--bs-secondary-color); transform: translateY(-50%); font-size: 1rem; line-height: 1; opacity: .7; }
.customer-filters .clear-filters:hover { color: var(--bs-primary); text-decoration: none; }
.customer-filters .btn { height: 42px; min-width: 205px; background: var(--bs-primary); border-color: var(--bs-primary); color: var(--bs-white, #fff); font-size: .78rem; }
.customer-table { margin: 0 18px 16px; color: var(--bs-body-color); vertical-align: middle; }
.customer-table thead th { background: var(--bs-tertiary-bg); color: var(--bs-heading-color); border: 0; font-size: .75rem; white-space: nowrap; }
.customer-table tbody td { border-color: var(--bs-border-color); color: var(--bs-body-color); font-size: .76rem; padding: 13px 10px; }
.customer-table tbody tr:hover { background: var(--pc-active-background); }
.customer-table .badge.bg-warning { color: #000 !important; }
.customer-empty { text-align: center; color: var(--bs-secondary-color); padding: 18px !important; }
.customer-meta { color: var(--bs-secondary-color); font-size: .75rem; padding: 0 18px 18px; }
.customer-pagination { padding: 0 18px 18px; }
@media (max-width: 768px) { .customer-filters { grid-template-columns: 1fr; } .customer-actions { flex-wrap: wrap; } .customer-table { min-width: 850px; } }
</style>
<div class="customer-shell">
	<div class="customer-breadcrumb"><strong>قائمة العملاء</strong> الرئيسية <span class="mx-2">&lsaquo;</span> قائمة العملاء</div>
	@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
	<section class="customer-card">
		<div class="customer-card__head"><h5>إدارة العملاء</h5><div class="customer-actions"><a class="btn btn-sm" href="{{ route('customers.create') }}">＋ إضافة عميل</a><a class="btn btn-sm btn-outline" href="{{ route('customers.export.phones') }}">⇩ تصدير الأرقام</a><a class="btn btn-sm btn-outline" href="{{ route('customers.export') }}">⇩ تصدير البيانات</a></div></div>
		<form class="customer-filters" method="get" action="{{ route('customers.index') }}"><div class="form-field"><label for="customer-search">البحث</label><i class="ti ti-search icon-search" aria-hidden="true"></i><input id="customer-search" class="form-control" name="search" value="{{ request('search') }}" placeholder="ابحث باسم أو رقم الهاتف">@if (request('search'))<a class="clear-filters" href="{{ route('customers.index') }}" aria-label="مسح البحث">×</a>@endif</div><div><label for="customer-status">الحالة</label><select id="customer-status" class="form-select" name="status"><option value="">الكل</option><option value="active" @selected(request('status') === 'active')>نشط</option><option value="suspended" @selected(request('status') === 'suspended')>معلق</option></select></div><button class="btn" type="submit">بحث</button></form>
		<div class="table-responsive"><table class="table customer-table mb-0"><thead><tr><th>الاسم</th><th>المدينة</th><th>الموبايل</th><th>التخصص</th><th>الحالة</th><th>بداية الاشتراك</th><th>نهاية الاشتراك</th><th>باقي</th><th>الإجراءات</th></tr></thead><tbody>@forelse ($customers as $customer)
        @php
            $remainingDays = $customer->remaining_days;
            $remainingLabel = $remainingDays === null
                ? 'غير محدد'
                : ($remainingDays < 0
                    ? 'منتهي'
                    : ($remainingDays . ' يوم متبقي'));
            $remainingBadgeClass = $remainingDays === null ? 'bg-secondary' : ($remainingDays < 0 ? 'bg-danger' : ($remainingDays <= 30 ? 'bg-warning text-dark' : 'bg-success'));
        @endphp
        <tr><td>{{ $customer->name }}</td><td>{{ $customer->city }}</td><td>{{ $customer->mobile }}</td><td>{{ $customer->specialization?->name }}</td><td>{{ $customer->status === 'active' ? 'نشط' : 'معلق' }}</td><td>{{ $customer->subscription_start_date?->format('Y-m-d') ?? '—' }}</td><td>{{ $customer->subscription_end_date?->format('Y-m-d') ?? '—' }}</td><td><span class="badge {{ $remainingBadgeClass }}">{{ $remainingLabel }}</span></td><td class="text-nowrap">
    <x-row-actions :row="$customer" :actions="[
        ['route' => route('customers.edit', $customer), 'label' => 'تعديل', 'icon' => 'ti ti-pencil'],
        ['route' => route('customers.destroy', $customer), 'label' => 'حذف', 'icon' => 'ti ti-trash', 'method' => 'DELETE', 'variant' => 'danger', 'confirm' => true],
    ]" />
</td></tr>@empty<tr><td class="customer-empty" colspan="9">لا توجد عملاء مسجلة حتى الآن</td></tr>@endforelse</tbody></table></div>
		<div class="customer-meta">عرض {{ $customers->firstItem() ?? 0 }} إلى {{ $customers->lastItem() ?? 0 }} من {{ $customers->total() }} عميل</div><div class="customer-pagination">{{ $customers->links() }}</div>
	</section>
</div>
@endsection