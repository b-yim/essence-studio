<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        return view('user.account', ['orders' => $request->user()->orders()->latest()->take(5)->get()]);
    }
}
