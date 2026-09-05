<form method="post" action="{{ $formAction }}" class="card card-body">
    @csrf
    @if ($formMethod !== 'POST') @method($formMethod) @endif
    @if ($errors->any())<div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">المنظمة</label><select name="organization_id" class="form-select" required><option value="">اختر المنظمة</option>@foreach ($organizations as $organization)<option value="{{ $organization->id }}" @selected(old('organization_id', $customer?->organization_id) == $organization->id)>{{ $organization->name }}</option>@endforeach</select></div>
        <div class="col-md-6"><label class="form-label">الاسم</label><input name="name" class="form-control" value="{{ old('name', $customer?->name) }}" required></div>
        <div class="col-md-6"><label class="form-label">المدينة</label><select name="city" class="form-select" required>@foreach (config('cities.الغربية', []) as $city)<option value="{{ $city }}" @selected(old('city', $customer?->city) === $city)>{{ $city }}</option>@endforeach</select></div>
        <div class="col-md-6"><label class="form-label">رقم الهاتف</label><input name="mobile" class="form-control" value="{{ old('mobile', $customer?->mobile) }}" required></div>
        <div class="col-md-6"><label class="form-label">العنوان</label><input name="address" class="form-control" value="{{ old('address', $customer?->address) }}" required></div>
        <div class="col-md-6"><label class="form-label">التخصص</label><select name="specialization_id" class="form-select" required>@foreach ($specializations as $specialization)<option value="{{ $specialization->id }}" @selected(old('specialization_id', $customer?->specialization_id) == $specialization->id)>{{ $specialization->name }}</option>@endforeach</select></div>
        <div class="col-md-6"><label class="form-label">الحالة</label><select name="status" class="form-select"><option value="active" @selected(old('status', $customer?->status ?? 'active') === 'active')>نشط</option><option value="suspended" @selected(old('status', $customer?->status) === 'suspended')>معلق</option></select></div>
    </div>
    <div class="mt-3"><button class="btn btn-primary" type="submit">حفظ</button> <a class="btn btn-light" href="{{ route('customers.index') }}">إلغاء</a></div>
</form>