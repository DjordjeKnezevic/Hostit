<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if ($request->has('redirect_to')) {
            session(['url.intended' => $request->redirect_to]);
        }

        return view('pages.login');
    }


    public function showRegistrationForm()
    {
        return view('pages.register');
    }

    public function register(Request $request)
    {
        $messages = [
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters.',
            'password.regex' => 'The password must contain at least one uppercase letter and one number.',
        ];

        $rules = [
            'name' => 'required|max:255|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'min:6',
                'regex:/^(?=.*[A-Z])(?=.*[0-9]).+$/',
            ],
        ];

        $validatedData = $request->validate($rules, $messages);

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

            // Redirect the user to their intended destination (or to the default home page if none is found).
            return redirect()->intended('/profile')->with([
                'message' => 'You have successfully logged in.',
                'alert-type' => 'success'
            ]);
        }

        // If authentication fails, return back to the login form with input and error message.
        return back()->withInput($request->only('email', 'remember'))
            ->withErrors([
                'password' => 'The provided credentials do not match our records.',
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
