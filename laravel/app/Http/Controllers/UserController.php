<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('index');
        }

        return view('pages.login');
    }

    public function showRegistrationForm()
    {
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('index');
        }

        return view('pages.register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $role = Role::where('name', 'User')->first();

        $user = User::create([
            'role_id' => $role->id,
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('login')->with([
            'message' => 'Please verify your email. A verification link has been sent to your email address.',
            'alert-type' => 'info'
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                return back()->with([
                    'message' => 'You need to verify your email first.',
                    'alert-type' => 'error'
                ]);
            }

            return redirect()->intended('/')->with([
                'message' => 'You have successfully logged in.',
                'alert-type' => 'success'
            ]);
        }

        return back()->with([
            'message' => 'Incorrect password.',
            'email' => $credentials['email'],
            'alert-type' => 'error'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index')->with([
            'message' => 'You have been successfully logged out.',
            'alert-type' => 'success'
        ]);
    }
}
