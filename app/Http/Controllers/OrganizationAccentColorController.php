<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrganizationAccentColorController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'theme_mode' => ['sometimes', 'required', 'string', Rule::in(['light', 'dark', 'default'])],
            'sidebar_theme' => ['sometimes', 'required', 'string', Rule::in(['light', 'dark'])],
            'accent_color' => ['sometimes', 'required', 'string', Rule::in(array_keys(config('accent.presets')))],
        ]);

        $user = $request->user('web') ?? $request->user('organization');
        abort_unless($user, 401);
        $user->update($validated);

        return response()->json($validated);
    }
}