<div class="card table-card">
    <div class="card-header d-flex align-items-center justify-content-between py-3">
        <h5 class="mb-0">المنظمات الحديثة</h5>
        <x-filament-actions::modals />
        <button
            type="button"
            class="btn btn-primary btn-sm"
            wire:click="mountAction('createOrganization')"
        >
            <i class="ti ti-plus"></i>
            منظمة جديدة
        </button>
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
                    @forelse ($organizations as $organization)
                        <tr wire:key="organization-{{ $organization->id }}">
                            <td><h6 class="m-b-0">{{ $organization->name }}</h6></td>
                            <td><p class="mb-0 text-sm">{{ $organization->address }}</p></td>
                            <td><p class="mb-0"><i class="ph-duotone ph-phone"></i> {{ $organization->mobile }}</p></td>
                            <td><p class="mb-0 text-sm">{{ $organization->specialization }}</p></td>
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
