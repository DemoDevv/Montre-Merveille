<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('accueil.index');
        }

        return view("user/register");
    }

    public function doRegister(LoginRequest $request)
    {
        $credentials = $request->validate([
            "firstname" => ["required"],
            "lastname" => ["required"],
            "email" => ["required", "email", "unique:users,email"],
            "password" => ["required", "confirmed"],
        ]);

        User::create([
            "firstname" => $credentials["firstname"],
            "lastname" => $credentials["lastname"],
            "email" => $credentials["email"],
            "password" => Hash::make($credentials["password"]),
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
        }

        return redirect()->intended(route("accueil.index"));
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('accueil.index');
        }
        return view('user/login');
    }

    public function doLogin(LoginRequest $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('accueil.index'));
        }

        return back();
    }

    public function doLogout()
    {
        Auth::logout();

        return redirect()->route('accueil.index');
    }

    public function profile()
    {
        $user = Auth::user();

        $cart = $user->cart();

        return view("user/profile", ['user' => $user, 'quantityItems' => $cart->quantityItems]);
    }

    public function update(LoginRequest $request)
    {
        $credentials = $request->validate([
            "firstname" => ["required"],
            "lastname" => ["required"],
            "password" => ["required", "confirmed"],
        ]);

        $user = Auth::user();

        $user->update([
            "firstname" => $credentials["firstname"],
            "lastname" => $credentials["lastname"],
            "password" => Hash::make($credentials["password"]),
        ]);

        return redirect()->route('user.profile');
    }

    public function delete()
    {
        $user = Auth::user();

        User::destroy($user->id);
        Auth::logout();

        return redirect()->route('accueil.index');
    }
}
