@props(['row' => null, 'actions' => []])

@php
    $items = collect($actions)->filter(function ($action) {
        if (!is_array($action)) {
            return false;
        }

        $can = $action['can'] ?? null;
        if (empty($can)) {
            return true;
        }

        $user = auth()->user();
        if (!$user) {
            return false;
        }

        try {
            if (is_array($can)) {
                return $user->canAny($can);
            }

            return $user->can($can);
        } catch (\Throwable $exception) {
            return true;
        }
    })->values()->all();
@endphp

<div class="row-actions position-relative d-inline-block">
    <button
        type="button"
        class="btn btn-sm row-actions__trigger"
        data-bs-toggle="dropdown"
        data-bs-auto-close="true"
        aria-expanded="false"
        aria-label="خيارات"
    >
        <i class="ti ti-dots-vertical"></i>
        <span class="d-none d-sm-inline">خيارات</span>
    </button>

    <div class="dropdown-menu dropdown-menu-end row-actions__menu" role="menu" aria-label="خيارات الصف">
        @foreach ($items as $action)
            @php
                $route = $action['route'] ?? null;
                $label = $action['label'] ?? 'إجراء';
                $icon = $action['icon'] ?? 'ti ti-circle';
                $variant = $action['variant'] ?? 'default';
                $method = strtoupper((string) ($action['method'] ?? 'GET'));
                $confirmDelete = !empty($action['confirm']);
                $danger = $variant === 'danger';
                $itemClass = 'dropdown-item row-actions__item' . ($danger ? ' row-actions__item--danger' : '');
            @endphp

            @if (($method === 'DELETE' || ($action['type'] ?? null) === 'delete') && $route)
                <form method="post" action="{{ $route }}" class="d-block">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="{{ $itemClass }}"
                        @if ($confirmDelete)
                            onclick="return confirm('هل أنت متأكد من حذف هذا العنصر؟')"
                        @endif
                    >
                        <i class="{{ $icon }}"></i>
                        <span>{{ $label }}</span>
                    </button>
                </form>
            @elseif ($route)
                <a
                    href="{{ $route }}"
                    class="{{ $itemClass }}"
                    @if ($confirmDelete)
                        onclick="return confirm('هل أنت متأكد؟')"
                    @endif
                >
                    <i class="{{ $icon }}"></i>
                    <span>{{ $label }}</span>
                </a>
            @endif
        @endforeach
    </div>
</div>

<style>
    .row-actions__trigger {
        background: var(--bs-primary);
        border: 1px solid var(--bs-primary);
        color: var(--bs-white, #fff);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        border-radius: 10px;
        min-width: 88px;
        box-shadow: 0 10px 24px rgba(var(--bs-primary-rgb), 0.22);
        transition: filter 0.2s ease, transform 0.2s ease;
    }

    .row-actions__trigger:hover,
    .row-actions__trigger:focus,
    .row-actions__trigger:active {
        background: var(--bs-primary);
        border-color: var(--bs-primary);
        color: var(--bs-white, #fff);
        filter: brightness(0.96);
        transform: translateY(-1px);
    }

    .row-actions__menu {
        min-width: 180px;
        padding: .5rem;
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        box-shadow: var(--pc-card-box-shadow);
        border-radius: 12px;
        margin-top: .45rem;
        inset-inline-end: 0;
        left: auto;
        right: 0;
    }

    .row-actions__item {
        display: flex;
        align-items: center;
        gap: .6rem;
        width: 100%;
        padding: .7rem .8rem;
        border: 0;
        border-radius: 10px;
        background: transparent;
        color: var(--bs-body-color);
        text-align: start;
    }

    .row-actions__item:hover,
    .row-actions__item:focus,
    .row-actions__item:active {
        background: rgba(var(--bs-primary-rgb), 0.08);
        color: var(--bs-body-color);
    }

    .row-actions__item i {
        font-size: 1rem;
        width: 1rem;
        text-align: center;
    }

    .row-actions__item--danger {
        color: var(--bs-danger);
    }

    .row-actions__item--danger:hover,
    .row-actions__item--danger:focus,
    .row-actions__item--danger:active {
        background: rgba(var(--bs-danger-rgb), 0.08);
        color: var(--bs-danger);
    }

    @media (max-width: 576px) {
        .row-actions__trigger {
            min-width: 72px;
        }

        .row-actions__menu {
            min-width: 170px;
        }
    }
</style>
