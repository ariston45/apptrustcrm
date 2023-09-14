<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function index()
	{
		return view('layout.app');
	}
	public function homeFunction()
	{
		return view('contents.page_home.home');
	}
}
