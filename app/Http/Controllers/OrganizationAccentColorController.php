<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrganizationAccentColorController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        return $this->updateTheme($request);
    }

    public function updateTheme(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'theme_mode' => ['sometimes', 'required', 'string', Rule::in(['light', 'dark', 'default'])],
            'sidebar_theme' => ['sometimes', 'required', 'string', Rule::in(['light', 'dark'])],
            'accent_color' => ['sometimes', 'required', 'string', Rule::in(array_keys(config('accent.presets')))],
            'sidebar_caption' => ['sometimes', 'required', 'boolean'],
        ]);

        $user = $request->user('web')?->fresh() ?? $request->user('organization')?->fresh();
        abort_unless($user, 401);

        $user->forceFill($validated);
        $user->save();

        return response()->json([
            'theme_mode' => $user->theme_mode,
            'sidebar_theme' => $user->sidebar_theme,
            'accent_color' => $user->accent_color,
            'sidebar_caption' => (bool) $user->sidebar_caption,
        ]);
    }

    public function applyAll(Request $request): JsonResponse
    {
        $user = $request->user('web')?->fresh() ?? $request->user('organization')?->fresh();
        abort_unless($user && $user->isSuperAdmin(), 403, 'Forbidden');

        $validated = $request->validate([
            'theme_mode' => ['required', 'string', Rule::in(['light', 'dark', 'default'])],
            'sidebar_theme' => ['required', 'string', Rule::in(['light', 'dark'])],
            'accent_color' => ['required', 'string', Rule::in(array_keys(config('accent.presets')))],
            'sidebar_caption' => ['required', 'boolean'],
        ]);

        User::query()->update([
            'theme_mode' => $validated['theme_mode'],
            'sidebar_theme' => $validated['sidebar_theme'],
            'accent_color' => $validated['accent_color'],
            'sidebar_caption' => (bool) $validated['sidebar_caption'],
        ]);

        return response()->json([
            'message' => 'تم تطبيق الإعدادات على جميع الحسابات بنجاح.',
            'updated' => User::query()->count(),
        ]);
    }
}