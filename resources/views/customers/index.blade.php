@extends('layouts.app')

@section('title', 'العملاء')

@section('content')
<div class="page-header"><h2>قائمة العملاء</h2></div>
@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card"><div class="card-header d-flex justify-content-between align-items-center"><h5>العملاء</h5><div><a class="btn btn-primary" href="{{ route('customers.create') }}">إضافة عميل</a> <a class="btn btn-secondary" href="{{ route('customers.export') }}">تصدير</a></div></div>
<div class="card-body"><div class="table-responsive"><table class="table"><thead><tr><th>الاسم</th><th>المنظمة</th><th>المدينة</th><th>الهاتف</th><th>التخصص</th><th>الحالة</th><th></th></tr></thead><tbody>
@forelse ($customers as $customer)<tr><td>{{ $customer->name }}</td><td>{{ $customer->organization?->name }}</td><td>{{ $customer->city }}</td><td>{{ $customer->mobile }}</td><td>{{ $customer->specialization?->name }}</td><td>{{ $customer->status }}</td><td class="text-nowrap"><a href="{{ route('customers.edit', $customer) }}">تعديل</a><form class="d-inline" method="post" action="{{ route('customers.destroy', $customer) }}">@csrf @method('DELETE')<button class="btn btn-link text-danger" type="submit">حذف</button></form></td></tr>@empty<tr><td colspan="7">لا يوجد عملاء.</td></tr>@endforelse
</tbody></table></div>{{ $customers->links() }}</div></div>
@endsection