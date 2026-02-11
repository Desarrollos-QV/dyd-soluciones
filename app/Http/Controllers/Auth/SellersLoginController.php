<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellersLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:sellers')->except('logout');
    }

    public function showLoginForm()
    {
        return view('sellers.auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        
        if (Auth::guard('sellers')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->route('sellers.dashboard');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'Estas credenciales no coinciden con nuestros registros.',
        ]);
    }

    public function logout()
    {
        Auth::guard('sellers')->logout();
        return redirect()->route('sellers.login');
    }
}
