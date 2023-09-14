<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function index()
	{
		if ($user = Auth::user()) {
			if ($user->level == 'admin') {
				return redirect()->intended('home');
			} elseif ($user->level == 'editor') {
				return redirect()->intended('home');
			}
		}
		return view('auth.login');
	}

	public function proses_login(Request $request)
	{
		request()->validate([
			'username' => 'required',
			'password' => 'required',
		]);
		$kredensil = $request->only('username','password');
		if (Auth::attempt($kredensil)) {
			$user = Auth::user();
			if ($user->level == 'ADM') {
				return redirect()->intended('home');
			} elseif ($user->level == 'editor') {
				return redirect()->intended('home');
			}
			return redirect()->intended('/');
		}
		return redirect('login')
		->withInput()
		->withErrors(['login_gagal' => 'These credentials do not match our records.']);
	}

	public function logout(Request $request)
	{
		$request->session()->flush();
		Auth::logout();
		return Redirect('login');
	}
}
