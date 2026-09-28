(function () {
    const THEME_ATTRIBUTE = 'data-pc-theme';
    const BOOTSTRAP_THEME_ATTRIBUTE = 'data-bs-theme';
    const SIDEBAR_ATTRIBUTE = 'data-pc-sidebar-theme';
    const PRESET_ATTRIBUTE = 'data-pc-preset';
    const THEME_DEFAULTS = {
        mode: 'light',
        sidebarAppearance: 'light',
        accentColor: 'preset-1',
    };

    const state = {
        mode: THEME_DEFAULTS.mode,
        sidebarAppearance: THEME_DEFAULTS.sidebarAppearance,
        accentColor: THEME_DEFAULTS.accentColor,
    };

    function normalizeThemeMode(value) {
        if (value === 'dark' || value === 'light' || value === 'default') {
            return value;
        }

        return THEME_DEFAULTS.mode;
    }

    function normalizeSidebarAppearance(value) {
        return value === 'dark' || value === 'light' ? value : THEME_DEFAULTS.sidebarAppearance;
    }

    function normalizeAccentColor(value) {
        return value || THEME_DEFAULTS.accentColor;
    }

    function resolveResolvedMode(mode) {
        const normalizedMode = normalizeThemeMode(mode);

        if (normalizedMode === 'default') {
            return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        return normalizedMode === 'dark' ? 'dark' : 'light';
    }

    function applyTheme() {
        const root = document.documentElement;
        const body = document.body;
        const resolvedMode = resolveResolvedMode(state.mode);

        root.setAttribute(THEME_ATTRIBUTE, resolvedMode);
        root.setAttribute(BOOTSTRAP_THEME_ATTRIBUTE, resolvedMode);
        root.setAttribute(SIDEBAR_ATTRIBUTE, state.sidebarAppearance);
        root.setAttribute(PRESET_ATTRIBUTE, state.accentColor);
        root.style.colorScheme = resolvedMode;

        if (body) {
            body.setAttribute(THEME_ATTRIBUTE, resolvedMode);
            body.setAttribute(BOOTSTRAP_THEME_ATTRIBUTE, resolvedMode);
            body.setAttribute(SIDEBAR_ATTRIBUTE, state.sidebarAppearance);
            body.setAttribute(PRESET_ATTRIBUTE, state.accentColor);
            body.style.colorScheme = resolvedMode;
        }
    }

    function updateState(settings) {
        state.mode = normalizeThemeMode(settings.mode);
        state.sidebarAppearance = normalizeSidebarAppearance(settings.sidebarAppearance);
        state.accentColor = normalizeAccentColor(settings.accentColor);
        applyTheme();
    }

    function setMode(mode) {
        updateState({
            mode,
            sidebarAppearance: state.sidebarAppearance,
            accentColor: state.accentColor,
        });
    }

    function setSidebarAppearance(value) {
        updateState({
            mode: state.mode,
            sidebarAppearance: value,
            accentColor: state.accentColor,
        });
    }

    function setAccentColor(value) {
        updateState({
            mode: state.mode,
            sidebarAppearance: state.sidebarAppearance,
            accentColor: value,
        });
    }

    function init(initialSettings) {
        const root = document.documentElement;
        const fallbackMode = root?.dataset?.pcTheme || THEME_DEFAULTS.mode;
        const fallbackSidebar = root?.dataset?.pcSidebarTheme || THEME_DEFAULTS.sidebarAppearance;
        const fallbackAccent = root?.dataset?.pcPreset || THEME_DEFAULTS.accentColor;

        state.mode = normalizeThemeMode(initialSettings && initialSettings.mode ? initialSettings.mode : fallbackMode);
        state.sidebarAppearance = normalizeSidebarAppearance(initialSettings && initialSettings.sidebarAppearance ? initialSettings.sidebarAppearance : fallbackSidebar);
        state.accentColor = normalizeAccentColor(initialSettings && initialSettings.accentColor ? initialSettings.accentColor : fallbackAccent);
        applyTheme();
    }

    window.ThemeSettings = {
        init,
        setMode,
        setSidebarAppearance,
        setAccentColor,
        getMode: () => state.mode,
        getResolvedMode: () => resolveResolvedMode(state.mode),
        getSidebarAppearance: () => state.sidebarAppearance,
        getAccentColor: () => state.accentColor,
    };

    init({
        mode: document.documentElement?.dataset?.pcTheme || 'light',
        sidebarAppearance: document.documentElement?.dataset?.pcSidebarTheme || 'light',
        accentColor: document.documentElement?.dataset?.pcPreset || 'preset-1',
    });
})();
