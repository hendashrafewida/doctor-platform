@extends('layouts.app')

@section('title', 'حسابي')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item" aria-current="page">حسابي</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">حسابي</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar avatar-xl mb-3">
                    <img src="{{ auth()->user()->avatar ?? asset('assets/images/user/avatar-2.jpg') }}" class="img-fluid rounded-circle" alt="Profile Picture">
                </div>
                <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5>البيانات الشخصية</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('account.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="text" name="mobile" value="{{ old('mobile', $user->mobile ?? '') }}" class="form-control @error('mobile') is-invalid @enderror">
                            @error('mobile')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>تغيير كلمة المرور</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('account.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">كلمة المرور الحالية</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning">تغيير كلمة المرور</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
