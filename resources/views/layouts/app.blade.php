@php
    $organization = auth('organization')->user()?->fresh();
    $admin = auth('web')->user()?->fresh();
    $settingsUser = $admin ?? $organization;
    $accentPreset = $settingsUser?->accent_color ?? 'preset-1';
    $themeMode = $settingsUser?->theme_mode ?? 'light';
    $sidebarTheme = $settingsUser?->sidebar_theme ?? 'light';
    $sidebarCaption = $settingsUser?->sidebar_caption ?? true;
    $isSuperAdmin = $settingsUser?->is_super_admin ?? false;
    $accentPresets = config('accent.presets');
    $accentPreset = array_key_exists($accentPreset, $accentPresets) ? $accentPreset : 'preset-1';
    $sidebarCaptionValue = filter_var($sidebarCaption, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-pc-theme="{{ $themeMode }}" data-bs-theme="{{ $themeMode }}" data-pc-preset="{{ $accentPreset }}" data-pc-sidebar-theme="{{ $sidebarTheme }}" data-pc-sidebar-caption="{{ $sidebarCaptionValue }}">
<head>
    <title>@yield('title', 'الرئيسية') | لوحة التحكم</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="لوحة تحكم الإدارة">
    <script>
        (function () {
            const resolveMode = (value) => {
                if (value === 'default') {
                    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }

                return value === 'dark' ? 'dark' : 'light';
            };

            const mode = resolveMode(@json($themeMode));
            document.documentElement.setAttribute('data-pc-theme', mode);
            document.documentElement.setAttribute('data-bs-theme', mode);
            document.documentElement.setAttribute('data-pc-sidebar-theme', @json($sidebarTheme));
            document.documentElement.setAttribute('data-pc-preset', @json($accentPreset));
            document.documentElement.setAttribute('data-pc-sidebar-caption', @json($sidebarCaptionValue));
            document.documentElement.style.colorScheme = mode;
        })();
    </script>
    <script src="{{ asset('assets/js/theme-settings.js') }}?v={{ filemtime(public_path('assets/js/theme-settings.js')) }}"></script>
    <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/jsvectormap.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}?v={{ filemtime(public_path('assets/css/style-rtl.css')) }}">
    <style>
        * {
            font-family: 'Cairo', 'Public Sans', sans-serif;
        }
    </style>
</head>
<body data-pc-preset="{{ $accentPreset }}" data-pc-sidebar-theme="{{ $sidebarTheme }}" data-pc-sidebar-caption="{{ $sidebarCaptionValue }}" data-pc-direction="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-pc-theme="{{ $themeMode }}" data-bs-theme="{{ $themeMode }}">
<div class="loader-bg"><div class="loader-track"><div class="loader-fill"></div></div></div>

<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('dashboard') }}" class="b-brand text-primary">
                <img src="{{ asset('assets/images/logo-dark.svg') }}" alt="logo image" class="logo-lg">
                <span class="badge bg-brand-color-2 rounded-pill ms-2 theme-version">v1.0</span>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item pc-caption"><label>الملاحة</label></li>
                <li class="pc-item">
                    <a href="{{ route('dashboard') }}" class="pc-link">
                        <span class="pc-micon"><i class="ph-duotone ph-gauge"></i></span>
                        <span class="pc-mtext">لوحة التحكم</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('customers.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ph-duotone ph-users"></i></span>
                        <span class="pc-mtext">قائمة العملاء</span>
                    </a>
                </li>
            </ul>
            <div class="card nav-action-card bg-brand-color-4">
                <div class="card-body" style="background-image: url('{{ asset('assets/images/layout/nav-card-bg.svg') }}')">
                    <h5 class="text-dark">مركز المساعدة</h5>
                    <p class="text-dark text-opacity-75">يرجى التواصل معنا لمزيد من الأسئلة والاستفسارات.</p>
                    <a href="https://phoenixcoded.support-hub.io/" class="btn btn-primary" target="_blank">الذهاب إلى مركز المساعدة</a>
                </div>
            </div>
        </div>
        <div class="card pc-user-card">
            <div class="card-body"><div class="d-flex align-items-center">
                <div class="flex-shrink-0"><img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="صورة المستخدم" class="user-avtar wid-45 rounded-circle"></div>
                <div class="flex-grow-1 me-3"><h6 class="mb-0">جون سميث</h6><small>مسؤول</small></div>
            </div></div>
        </div>
    </div>
</nav>

<header class="pc-header">
    <div class="header-wrapper">
        <div class="pc-header-controls">
            <ul class="list-unstyled">
                <li class="pc-h-item pc-sidebar-collapse"><a href="#" class="pc-head-link ms-0" id="sidebar-hide"><i class="ti ti-menu-2"></i></a></li>
                <li class="pc-h-item pc-sidebar-popup"><a href="#" class="pc-head-link ms-0" id="mobile-collapse"><i class="ti ti-menu-2"></i></a></li>
                <li class="dropdown pc-h-item d-inline-flex d-md-none"><a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#" aria-expanded="false"><i class="ti ti-search"></i></a><div class="dropdown-menu pc-h-dropdown drp-search"><form class="px-3"><div class="mb-0 d-flex align-items-center"><input type="search" class="form-control border-0 shadow-none" placeholder="ابحث..."><button class="btn btn-light-secondary btn-search">بحث</button></div></form></div></li>
                <li class="pc-h-item d-none d-md-inline-flex"><form class="form-search"><i class="ti ti-search icon-search"></i><input type="search" class="form-control" placeholder="ابحث..."><button class="btn btn-search" style="padding: 0"><kbd>ctrl+k</kbd></button></form></li>
                <li class="pc-h-item"><a class="pc-head-link pct-c-btn" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_pc_layout"><i class="ti ti-settings"></i></a></li>
                <li class="dropdown pc-h-item header-user-profile"><a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" aria-expanded="false"><img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="صورة المستخدم" class="user-avtar"></a><div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown"><div class="dropdown-header"><h5 class="m-0">الملف الشخصي</h5></div><div class="dropdown-body"><a href="{{ route('account.show') }}" class="dropdown-item"><i class="ph-duotone ph-user"></i><span>حسابي</span></a><a href="#" class="dropdown-item" role="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_pc_layout"><i class="ph-duotone ph-gear"></i><span>الإعدادات</span></a><form action="{{ route('logout') }}" method="POST" class="d-block m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-start border-0 bg-transparent w-100"><i class="ph-duotone ph-power"></i><span>تسجيل الخروج</span></button>
                        </form></div></div></li>
            </ul>
        </div>
    </div>
</header>

<main class="pc-container"><div class="pc-content">@yield('content')</div></main>

<footer class="pc-footer"><div class="footer-wrapper container-fluid"><div class="row"><div class="col-sm-6 my-1"><p class="m-0">تم بناؤه بـ &#9829; من قبل فريق <a href="https://themeforest.net/user/phoenixcoded" target="_blank">Phoenixcoded</a></p></div><div class="col-sm-6 ms-auto my-1"><ul class="list-inline footer-link mb-0 justify-content-sm-end d-flex"><li class="list-inline-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li><li class="list-inline-item"><a href="https://pcoded.gitbook.io/light-able/" target="_blank">التوثيق</a></li><li class="list-inline-item"><a href="https://phoenixcoded.support-hub.io/" target="_blank">الدعم</a></li></ul></div></div></div></footer>

<div class="offcanvas border-0 pct-offcanvas offcanvas-start" tabindex="-1" id="offcanvas_pc_layout">
    <div class="offcanvas-header justify-content-between"><h5 class="offcanvas-title">الإعدادات</h5><button type="button" class="btn btn-icon btn-link-danger" data-bs-dismiss="offcanvas" aria-label="إغلاق"><i class="ti ti-x"></i></button></div>
    <div class="pct-body customizer-body"><div class="offcanvas-body py-0"><ul class="list-group list-group-flush">
        <li class="list-group-item"><h6 class="mb-1">وضع المظهر</h6><p class="text-muted text-sm">اختر فاتح أو داكن أو افتراضي</p><div class="row theme-color theme-layout"><div class="col-4"><button class="preset-btn btn {{ $themeMode === 'light' ? 'active' : '' }}" data-setting="theme_mode" data-value="light"><span class="btn-label">فاتح</span></button></div><div class="col-4"><button class="preset-btn btn {{ $themeMode === 'dark' ? 'active' : '' }}" data-setting="theme_mode" data-value="dark"><span class="btn-label">داكن</span></button></div><div class="col-4"><button class="preset-btn btn {{ $themeMode === 'default' ? 'active' : '' }}" data-setting="theme_mode" data-value="default"><span class="btn-label">افتراضي</span></button></div></div></li>
        <li class="list-group-item"><h6 class="mb-1">مظهر الشريط الجانبي</h6><p class="text-muted text-sm">اختر مظهر الشريط الجانبي</p><div class="row theme-color theme-sidebar-color"><div class="col-6"><button class="preset-btn btn {{ $sidebarTheme === 'dark' ? 'active' : '' }}" data-setting="sidebar_theme" data-value="dark"><span class="btn-label">داكن</span></button></div><div class="col-6"><button class="preset-btn btn {{ $sidebarTheme === 'light' ? 'active' : '' }}" data-setting="sidebar_theme" data-value="light"><span class="btn-label">فاتح</span></button></div></div></li>
        <li class="list-group-item"><h6 class="mb-1">لون التمييز</h6><p class="text-muted text-sm">اختر لون المظهر الأساسي الخاص بك</p><div class="theme-color preset-color">
            @foreach ($accentPresets as $preset => $hex)
                <a href="#" class="{{ $accentPreset === $preset ? 'active' : '' }}" data-value="{{ $preset }}" style="background-color: {{ $hex }}" aria-label="{{ $preset }}"><i class="ti ti-check"></i></a>
            @endforeach
        </div></li>
        @if ($isSuperAdmin)
            <li class="list-group-item">
                <div class="d-grid">
                    <button class="btn btn-danger" id="apply-theme-to-all" type="button">تطبيق على كل الحسابات</button>
                </div>
            </li>
        @endif
        <li class="list-group-item"><div class="d-grid"><button class="btn btn-light-danger" id="layoutreset">إعادة تعيين المخطط</button></div></li>
    </ul></div></div>
</div>

<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jsvectormap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/world.js') }}"></script>
<script src="{{ asset('assets/js/plugins/world-merc.js') }}"></script>
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
<script src="{{ asset('assets/js/pcoded.js') }}?v={{ filemtime(public_path('assets/js/pcoded.js')) }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script>
    (() => {
        const serverAccentPreset = @json($accentPreset);
        const accentStorageKey = 'light-able-accent-preset';
        const settingsSaveUrl = @json(route('settings.theme.update'));
        const applyAllSettingsUrl = @json(route('settings.theme.apply-all'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        ThemeSettings.setAccentColor(serverAccentPreset);

        function lockThemeAttributes() {
            const activeMode = ThemeSettings.getMode();
            const activeSidebar = ThemeSettings.getSidebarAppearance();
            const activeAccent = ThemeSettings.getAccentColor();
            const activeCaption = String(document.body.dataset.pcSidebarCaption === 'true');

            document.body.setAttribute('data-pc-theme', activeMode);
            document.body.setAttribute('data-pc-sidebar-theme', activeSidebar);
            document.body.setAttribute('data-pc-preset', activeAccent);
            document.body.setAttribute('data-pc-sidebar-caption', activeCaption);
            document.documentElement.setAttribute('data-pc-theme', activeMode);
            document.documentElement.setAttribute('data-pc-sidebar-theme', activeSidebar);
            document.documentElement.setAttribute('data-pc-preset', activeAccent);
            document.documentElement.setAttribute('data-pc-sidebar-caption', activeCaption);
        }

        async function saveSetting(setting) {
            const response = await fetch(settingsSaveUrl, {
                method: 'PUT',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(setting),
            });

            if (!response.ok) {
                throw new Error(`Settings could not be saved (${response.status})`);
            }

            lockThemeAttributes();

            try {
                window.localStorage.setItem('doctor-theme-settings', JSON.stringify({
                    mode: ThemeSettings.getMode(),
                    sidebarAppearance: ThemeSettings.getSidebarAppearance(),
                    accentColor: ThemeSettings.getAccentColor(),
                    sidebarCaption: document.body.dataset.pcSidebarCaption === 'true',
                }));
            } catch (error) {
                // ignore storage issues; DB remains the source of truth.
            }
        }

        const applyThemeToAllBtn = document.getElementById('apply-theme-to-all');
        if (applyThemeToAllBtn) {
            applyThemeToAllBtn.addEventListener('click', async () => {
                const confirmed = window.confirm('هل تريد تطبيق هذه الإعدادات على جميع الحسابات؟ سيتم استبدال تفضيلات كل المستخدمين الحاليين.');
                if (!confirmed) {
                    return;
                }

                const payload = {
                    theme_mode: ThemeSettings.getMode(),
                    sidebar_theme: ThemeSettings.getSidebarAppearance(),
                    accent_color: ThemeSettings.getAccentColor(),
                    sidebar_caption: document.body.dataset.pcSidebarCaption === 'true',
                };

                try {
                    const response = await fetch(applyAllSettingsUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(payload),
                    });

                    if (!response.ok) {
                        throw new Error('Forbidden');
                    }

                    const result = await response.json();
                    window.alert(result.message || 'تم تطبيق الإعدادات على جميع الحسابات بنجاح.');
                } catch (error) {
                    console.error(error);
                    window.alert('غير مسموح لك بتنفيذ هذا الإجراء.');
                }
            });
        }

        document.querySelectorAll('.preset-color > a').forEach((swatch) => {
            swatch.addEventListener('click', (event) => {
                event.preventDefault();
                const preset = swatch.dataset.value;
                ThemeSettings.setAccentColor(preset);
                saveSetting({ accent_color: preset }).catch((error) => {
                    console.error(error);
                });
            });
        });

        function applyImmediateControlState(setting, value) {
            if (setting === 'theme_mode') {
                ThemeSettings.setMode(value);

                const resolvedMode = ThemeSettings.getResolvedMode();
                document.body.setAttribute('data-pc-theme', resolvedMode);
                document.body.setAttribute('data-bs-theme', resolvedMode);
                document.documentElement.setAttribute('data-pc-theme', resolvedMode);
                document.documentElement.setAttribute('data-bs-theme', resolvedMode);
                document.body.style.colorScheme = resolvedMode;
                document.documentElement.style.colorScheme = resolvedMode;
            }

            if (setting === 'sidebar_theme') {
                ThemeSettings.setSidebarAppearance(value);
                document.body.setAttribute('data-pc-sidebar-theme', value);
                document.documentElement.setAttribute('data-pc-sidebar-theme', value);
            }

            document.querySelectorAll('[data-setting="' + setting + '"]').forEach((button) => {
                const isActive = button.dataset.value === value;
                button.classList.toggle('active', isActive);
                button.setAttribute('aria-pressed', String(isActive));
            });
        }

        document.querySelectorAll('[data-setting]').forEach((control) => {
            control.addEventListener('click', async () => {
                const setting = control.dataset.setting;
                const value = control.dataset.value;

                if (!['theme_mode', 'sidebar_theme'].includes(setting)) {
                    return;
                }

                applyImmediateControlState(setting, value);

                try {
                    await saveSetting({ [setting]: value });
                } catch (error) {
                    console.error(error);
                }
            });
        });

        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change(@json(app()->getLocale() === 'ar' ? 'true' : 'false'));
    })();
</script>
</body>
</html>