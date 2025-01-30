<?php

namespace App\Http\Controllers\Front\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthenticationController extends Controller
{
    //
    public function index(){

        $user = Auth::user();
        return view('front.auth.two-factor-auth',compact('user'));
    }
}
