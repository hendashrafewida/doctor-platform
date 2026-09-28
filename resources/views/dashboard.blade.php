@extends('layouts.app')

@section('title', 'الرئيسية')

@push('scripts')
    <script src="{{ asset('assets/js/pages/dashboard-default.js') }}"></script>
@endpush

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">لوحة التحكم</a></li>
                    <li class="breadcrumb-item" aria-current="page">الرئيسية</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">الرئيسية</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 col-sm-6">
        <div class="card statistics-card-1 overflow-hidden">
            <div class="card-body">
                <img src="{{ asset('assets/images/widget/img-status-4.svg') }}" alt="img" class="img-fluid img-bg">
                <h5 class="mb-4">إجمالي المنظمات</h5>
                <div class="d-flex align-items-center mt-3">
                    <h3 class="f-w-300 d-flex align-items-center m-b-0">{{ $totalOrganizations }}</h3>
                    <span class="badge bg-light-success ms-2">{{ $totalOrganizations > 0 ? '36%' : '0%' }}</span>
                </div>
                <p class="text-muted mb-2 text-sm mt-3">عدد المنظمات المسجلة في النظام</p>
                <div class="progress" style="height: 7px">
                    <div class="progress-bar bg-brand-color-3" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6">
        <div class="card statistics-card-1 overflow-hidden">
            <div class="card-body">
                <img src="{{ asset('assets/images/widget/img-status-5.svg') }}" alt="img" class="img-fluid img-bg">
                <h5 class="mb-4">المنظمات النشطة</h5>
                <div class="d-flex align-items-center mt-3">
                    <h3 class="f-w-300 d-flex align-items-center m-b-0">{{ $activeOrganizations }}</h3>
                    <span class="badge bg-light-primary ms-2">{{ $totalOrganizations > 0 ? round(($activeOrganizations / $totalOrganizations) * 100) . '%' : '0%' }}</span>
                </div>
                <p class="text-muted mb-2 text-sm mt-3">المنظمات التي تعمل بنشاط</p>
                <div class="progress" style="height: 7px">
                    <div class="progress-bar bg-brand-color-3" role="progressbar" style="width: {{ $totalOrganizations > 0 ? round(($activeOrganizations / $totalOrganizations) * 100) : 0 }}%" aria-valuenow="{{ $activeOrganizations }}" aria-valuemin="0" aria-valuemax="{{ $totalOrganizations }}"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="card statistics-card-1 overflow-hidden bg-brand-color-3">
            <div class="card-body">
                <img src="{{ asset('assets/images/widget/img-status-6.svg') }}" alt="img" class="img-fluid img-bg">
                <h5 class="mb-4 text-white">المنظمات الموقوفة</h5>
                <div class="d-flex align-items-center mt-3">
                    <h3 class="text-white f-w-300 d-flex align-items-center m-b-0">{{ $suspendedOrganizations }}</h3>
                </div>
                <p class="text-white text-opacity-75 mb-2 text-sm mt-3">المنظمات المعلقة أو الموقوفة</p>
                <div class="progress bg-white bg-opacity-10" style="height: 7px">
                    <div class="progress-bar bg-white" role="progressbar" style="width: {{ $totalOrganizations > 0 ? round(($suspendedOrganizations / $totalOrganizations) * 100) : 0 }}%" aria-valuenow="{{ $suspendedOrganizations }}" aria-valuemin="0" aria-valuemax="{{ $totalOrganizations }}"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6 col-xl-7">
        <div class="card">
            <div class="card-header">
                <h5>توزيع المنظمات</h5>
            </div>
            <div class="card-body">
                <div id="world-map-markers" class="set-map" style="height:365px;"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-5">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <h5>المنظمات النشطة</h5>
                <div class="dropdown">
                    <a class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons-two-tone f-18">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">عرض</a>
                        <a class="dropdown-item" href="#">تعديل</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avtar avtar-s bg-light-primary flex-shrink-0">
                        <i class="ph-duotone ph-money f-20"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="mb-0 text-muted">إجمالي المنظمات</p>
                        <h5 class="mb-0">{{ $totalOrganizations }}</h5>
                    </div>
                </div>
                <div id="earnings-users-chart"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="avtar avtar-s bg-light-warning flex-shrink-0">
                                <i class="ph-duotone ph-lightning f-20"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="mb-0 text-muted">إجمالي المنظمات</p>
                                <h6 class="mb-0">{{ $totalOrganizations }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="avtar avtar-s bg-light-danger flex-shrink-0">
                                <i class="ph-duotone ph-map-pin f-20"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="mb-0 text-muted">المنظمات النشطة</p>
                                <h6 class="mb-0">{{ $activeOrganizations }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card table-card">
            <div class="card-header">
                <h5>المنظمات الحديثة</h5>
            </div>
            <div class="card-body py-2 px-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-sm mb-0">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>العنوان</th>
                                <th>الموبايل</th>
                                <th>التخصص</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrganizations as $organization)
                                <tr>
                                    <td>
                                        <div class="d-inline-block align-middle">
                                            <div class="d-inline-block">
                                                <h6 class="m-b-0">{{ $organization->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td><p class="mb-0 text-sm">{{ $organization->address }}</p></td>
                                    <td><p class="mb-0"><i class="ph-duotone ph-phone"></i> {{ $organization->mobile }}</p></td>
                                    <td><p class="mb-0 text-sm">{{ $organization->specialization?->name }}</p></td>
                                    <td>
                                        @if ($organization->status === 'active')
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-danger">معلق</span>
                                        @endif
                                    </td>
                                    <td><p class="mb-0 text-sm">{{ $organization->created_at->format('Y-m-d H:i') }}</p></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <p>لا توجد منظمات مسجلة حتى الآن</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection