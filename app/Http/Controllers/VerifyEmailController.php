<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Jobs\sendEmailVerificationJob;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\FuncCall;

class VerifyEmailController extends Controller
{
    public function notice()
    {
        return view('email-verify');
    }
    public function noticeNew()
    {
        return view('email-verify-new');
    }
    public function send(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return view('verify-sended');
    }
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return view('verify');
    }
}