<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $data['id'] = auth()->user()->id;
        $data['name'] = auth()->user()->name;
        $data['email'] = auth()->user()->email;
        return view('profile.main', compact('data'));
    }
    public function nameUpdate(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string']]);
        User::where('id', auth()->user()->id)->update(['name' => $data['name']]);
        return back();
    }
    public function emailUpdate(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email:filter', 'unique:users,email,' . $request->user_id],
            'user_id' => ['required', 'exists:users,id']
        ]);
        if ($data['email'] == auth()->user()->email) {
            return back();
        }
        User::where('id', auth()->user()->id)->update([
            'email' => $data['email'],
            'email_verified_at' => null
        ]);
        return redirect()->route('verification.notice.new');
    }
    public function passwordUpdate(Request $request)
    {
        $data = $request->validate([
            'old_password' => ['required', 'current_password:web'],
            'password' => ['required', 'confirmed']
        ]);
        if ($data['old_password'] == $data['password']) {
            return back();
        }
        User::where('id', auth()->user()->id)->update([
            'password' => Hash::make($data['password']),
        ]);
        Auth::logoutOtherDevices(Hash::make($data['password']));
        return back();
    }
}