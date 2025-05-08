<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Http\Request;


class VendorAuthController extends Controller
{
     // Show vendor login form
     public function showLoginForm()
     {
         return view('vendor.login');
     }
 
     // Handle vendor login
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
 
             if (Auth::user()->role !== 'vendor') {
                 Auth::logout();
                 return redirect()->back()
                ->with('status', 'error')
                ->with('message', 'You are not authorized to access the vendor panel.');
             }
 
             return redirect()->route('vendor.dashboard');
         }
 
         return redirect()->back()
        ->with('status', 'error')
        ->with('message', 'The provided credentials do not match our records.');
     }
 
     // Show vendor registration form
     public function showRegisterForm()
     {
         return view('vendor.register');
     }
 
     // Handle vendor registration
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
             'role' => 'vendor', // Set role explicitly
             'user_auth' => 0, // or 0 if you want to authorize later
         ]);
 
         // Auth::login($user);
 
         return redirect()->route('vendor.login')->with([
             'status' => 'success',
             'message' => 'Account registered successfully.',
         ]);
     }
}