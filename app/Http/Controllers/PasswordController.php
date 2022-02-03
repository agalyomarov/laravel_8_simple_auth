<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function request()
    {
        return view('forgot-password');
    }

    public function email(Request $request)
    {
        $request->validate(['email' => ['required', 'exists:users,email']]);

        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function reset(Request $request)
    {
        return view('reset-password', ['token' => $request->token]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email:filter', 'exists:users,email'],
            'password' => ['required', 'confirmed'],
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}