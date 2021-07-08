<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;
    protected $guard = 'admin';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
    {
        if (auth()->guard('admin')->user()->type == 'transfer_company') {
            return '/dashboard';
        } else if (auth()->guard('admin')->user()->type == 'hotel' ||
            auth()->guard('admin')->user()->type == 'tourism_company') {
            return '/clients/dashboard';
        }
        return '/';
    }

    public function showLoginForm()
    {
        return view('auth.adminLogin');
    }

    public function login(Request $request)
    {
        $remember = request('remember') ? true : false;
        if (auth()->guard('admin')->attempt(['username' => $request->username, 'password' => $request->password, 'deleted_at' => Null], $remember)) {
            return redirect($this->redirectTo());

        }
        return back()->withErrors(['email' => 'Email or password are wrong.']);
    }

    public function logout(Request $request)
    {
        $this->guard('admin')->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('/login');
    }

}
