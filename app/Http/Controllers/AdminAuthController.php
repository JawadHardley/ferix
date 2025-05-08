<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    // Show admin login form
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Handle admin login
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

            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                return redirect()->back()->with('status', 'error')->with('message', 'You are not authorized to access the Admin panel.');
            }

            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->with('status', 'error')->with('message', 'The provided credentials do not match our records.');
    }

    // Show admin registration form
    public function showRegisterForm()
    {
        return view('admin.register');
    }

    // Show lists of admins
    public function showAdmins()
    {
        $records = User::where('role', 'admin')->simplePaginate(10);
        return view('admin.admins', compact('records'));
    }

    // Show lists of all users
    public function showUsers()
    {
        $records = User::simplepaginate(10);

        return view('admin.users', compact('records'));
    }

    // Handle admin registration
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
            'role' => 'admin', // Set role explicitly
            'user_auth' => 0, // or 0 if you want to authorize later
        ]);

        // Auth::login($user);

        return redirect()
            ->route('admin.login')
            ->with([
                'status' => 'success',
                'message' => 'Admin registered successfully.',
            ]);
    }

    public function deleteUser($id)
    {
        // Find the invoice by ID
        $record = User::findOrFail($id);

        // Delete the invoice
        $record->delete();

        // Redirect back with a success message
        return redirect()
            ->back()
            ->with([
                'status' => 'success',
                'message' => 'User Deleted successfully.',
            ]);
    }

    public function toggleAuth($id)
    {
        $user = User::findOrFail($id);

        // Only allow if the authenticated user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Prevent admin from changing their own status (optional safety)
        if (Auth::id() == $user->id) {
            return back()->with([
                'status' => 'error',
                'message' => 'You cannot change your own access.',
            ]);
        }

        $user->user_auth = $user->user_auth ? 0 : 1;
        $user->save();

        return back()->with([
            'status' => 'success',
            'message' => 'User access updated successfully.',
        ]);
    }
}
