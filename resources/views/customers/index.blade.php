@extends('layouts.app')

@section('title', 'قائمة العملاء')

@section('content')
<style>
body:has(.customer-shell) { background: #111a24; color: #d8e0eb; }
body:has(.customer-shell) .pc-sidebar { display: none; }
body:has(.customer-shell) .pc-container { margin-right: 0; margin-left: 0; padding-top: 82px; }
body:has(.customer-shell) .pc-footer { background: #111a24; color: #a7b2c2; }
.customer-shell { direction: rtl; max-width: 1320px; margin: 0 auto; }
.customer-breadcrumb { color: #9ba8ba; font-size: .78rem; margin: 0 0 24px; }
.customer-breadcrumb strong { color: #f2f5f9; font-size: 1.3rem; margin-left: 18px; }
.customer-card { background: #19242f; border: 1px solid #2b3b4c; border-radius: 8px; overflow: hidden; }
.customer-card__head { display: flex; align-items: center; justify-content: space-between; padding: 20px 18px; border-bottom: 1px solid #2b3b4c; }
.customer-card__head h5 { color: #e5eaf1; margin: 0; font-size: .95rem; }
.customer-actions { display: flex; gap: 4px; direction: ltr; }
.customer-actions .btn { border: 1px solid #7b46d4; color: #f5f1ff; background: #6837bd; font-size: .78rem; }
.customer-actions .btn-outline { background: transparent; border-color: #6c88a6; }
.customer-filters { display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px; padding: 18px; align-items: end; }
.customer-filters label { display: block; color: #bdc7d5; font-size: .75rem; margin-bottom: 7px; }
.customer-filters .form-control, .customer-filters .form-select { background: #273646; color: #dce5ef; border-color: #34495e; height: 36px; font-size: .78rem; }
.customer-filters .form-control::placeholder { color: #a4b0be; }
.customer-filters .btn { height: 36px; min-width: 205px; background: #7040cf; border-color: #7040cf; color: white; font-size: .78rem; }
.customer-table { margin: 0 18px 16px; color: #dce4ee; vertical-align: middle; }
.customer-table thead th { background: #30445b; color: #e0e6ee; border: 0; font-size: .75rem; white-space: nowrap; }
.customer-table tbody td { border-color: #2b3b4c; color: #c7d0dc; font-size: .76rem; padding: 13px 10px; }
.customer-table tbody tr:hover { background: #202f3e; }
.customer-empty { text-align: center; color: #9daaba; padding: 18px !important; }
.customer-meta { color: #a7b2c2; font-size: .75rem; padding: 0 18px 18px; }
.customer-pagination { padding: 0 18px 18px; }
@media (max-width: 768px) { .customer-filters { grid-template-columns: 1fr; } .customer-actions { flex-wrap: wrap; } .customer-table { min-width: 850px; } }
</style>
<div class="customer-shell">
	<div class="customer-breadcrumb"><strong>قائمة العملاء</strong> الرئيسية <span class="mx-2">&lsaquo;</span> قائمة العملاء</div>
	@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
	<section class="customer-card">
		<div class="customer-card__head"><h5>إدارة العملاء</h5><div class="customer-actions"><a class="btn btn-sm" href="{{ route('customers.create') }}">＋ إضافة عميل</a><a class="btn btn-sm btn-outline" href="{{ route('customers.export') }}">⇩ تصدير الأرقام</a><a class="btn btn-sm btn-outline" href="{{ route('customers.export') }}">⇩ تصدير البيانات</a></div></div>
		<form class="customer-filters" method="get" action="{{ route('customers.index') }}"><div><label for="customer-search">البحث</label><input id="customer-search" class="form-control" name="search" value="{{ request('search') }}" placeholder="ابحث باسم أو رقم الهاتف"></div><div><label for="customer-status">الحالة</label><select id="customer-status" class="form-select" name="status"><option value="">الكل</option><option value="active" @selected(request('status') === 'active')>نشط</option><option value="suspended" @selected(request('status') === 'suspended')>معلق</option></select></div><button class="btn" type="submit">بحث</button></form>
		<div class="table-responsive"><table class="table customer-table mb-0"><thead><tr><th>الاسم</th><th>المدينة</th><th>الموبايل</th><th>التخصص</th><th>الحالة</th><th>التاريخ</th><th>الإجراءات</th></tr></thead><tbody>@forelse ($customers as $customer)<tr><td>{{ $customer->name }}</td><td>{{ $customer->city }}</td><td>{{ $customer->mobile }}</td><td>{{ $customer->specialization?->name }}</td><td>{{ $customer->status === 'active' ? 'نشط' : 'معلق' }}</td><td>{{ $customer->created_at?->format('Y-m-d') }}</td><td class="text-nowrap"><a href="{{ route('customers.edit', $customer) }}">تعديل</a> <form class="d-inline" method="post" action="{{ route('customers.destroy', $customer) }}">@csrf @method('DELETE')<button class="btn btn-link text-danger p-0" type="submit">حذف</button></form></td></tr>@empty<tr><td class="customer-empty" colspan="7">لا توجد عملاء مسجلة حتى الآن</td></tr>@endforelse</tbody></table></div>
		<div class="customer-meta">عرض {{ $customers->firstItem() ?? 0 }} إلى {{ $customers->lastItem() ?? 0 }} من {{ $customers->total() }} عميل</div><div class="customer-pagination">{{ $customers->links() }}</div>
	</section>
</div>
@endsection