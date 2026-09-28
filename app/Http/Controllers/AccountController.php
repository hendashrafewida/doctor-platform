<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccountPasswordRequest;
use App\Http\Requests\UpdateAccountProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function show(): View
    {
        return view('account.profile', [
            'user' => auth()->user(),
        ]);
    }

    public function update(UpdateAccountProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ]);

        $user->save();

        return back()->with('status', 'تم تحديث البيانات الشخصية بنجاح.');
    }

    public function updatePassword(UpdateAccountPasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'تم تغيير كلمة المرور بنجاح.');
    }
}
