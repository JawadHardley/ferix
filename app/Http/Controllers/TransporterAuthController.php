<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Http\Request;

class TransporterAuthController extends Controller
{
    // Show transporter login form
    public function showLoginForm()
    {
        return view('transporter.login');
    }

    // Handle transporter login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if user is authorized
            if (Auth::user()->user_auth !== 1) {
                Auth::logout();
                return redirect()->back()->with('status', 'error')->with('message', 'Your account is not authorized yet.');
            }

            if (Auth::user()->role !== 'transporter') {
                Auth::logout();
                return redirect()->back()->with('status', 'error')->with('message', 'You are not authorized to access the transporter panel.');
            }

            return redirect()->route('transporter.dashboard');
        }

        return redirect()->back()->with('status', 'error')->with('message', 'The provided credentials do not match our records.');
    }

    // Show transporter registration form
    public function showRegisterForm()
    {
        return view('transporter.register');
    }

    // Handle transporter registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'company' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company' => $validated['company'],
            'password' => Hash::make($validated['password']),
            'role' => 'transporter', // Set role explicitly
            'user_auth' => 0, // or 0 if you want to authorize later
        ]);

        // Auth::login($user);

        return redirect()
            ->route('transporter.login')
            ->with([
                'status' => 'success',
                'message' => 'Account registered successfully.',
            ]);
    }
}
