<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    function login() {
        return view('login');
    }

    function authentication(Request $request) {
       $credentials = $request->validate([
        'email' => ['required','email'],
        'password' => ['required']
       ]); 

       if (Auth::attempt($credentials)){
        $request->session()->regenerate();

        return redirect()->intended('/admin/barang/data-barang');
       }

       Session::flash('status','failed!');
       Session::flash('massage','login failed');

       return redirect('/login');
       
    }
    function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();   
        $request->session()->regenerateToken();   
        return redirect('/login');
    }
}
