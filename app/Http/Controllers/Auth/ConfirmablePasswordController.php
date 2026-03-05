<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ConfirmablePasswordController extends Controller
{
    public function show()
    {
        return view('auth.confirm-password');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! \Illuminate\Support\Facades\Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'password' => 'Mật khẩu không đúng.',
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('products.index', absolute: false));
    }
}
