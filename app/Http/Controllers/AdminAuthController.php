<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\mainmail;
use App\Mail\CustomVerifyEmail;

class AdminAuthController extends Controller
{
    // Show admin login form
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Show profile
    public function showProfile()
    {
        // Mail::to(Auth::user()->email)->send(new mainmail(Auth::user()));
        return view('profile');
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

            // Check if user has verified their email
            if (!Auth::user()->hasVerifiedEmail()) {
                // If email is not verified, send a new verification link
                Auth::user()->sendEmailVerificationNotification();

                // Log the user out and redirect with a message
                Auth::logout();
                return redirect()
                    ->back()
                    ->with([
                        'status' => 'error',
                        'message' => 'Your email is not verified. Please check your inbox for the verification link.',
                    ]);
            }

            // Check if user is authorized
            if (Auth::user()->user_auth !== 1) {
                Auth::logout();
                return redirect()
                    ->back()
                    ->with([
                        'status' => 'error',
                        'message' => 'Your account is not authorized yet.',
                    ]);
            }

            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                return redirect()
                    ->back()
                    ->with([
                        'status' => 'error',
                        'message' => 'You are not authorized to access the Admin panel.',
                    ]);
            }

            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->with('status', 'error')->with('message', 'The provided credentials do not match our records.');
    }

    // After email verification
    public function verify(Request $request)
    {
        // Verify the user's email
        $user = User::findOrFail($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('admin.dashboard')->with('message', 'Your email has already been verified.');
        }

        // Mark the email as verified
        $user->markEmailAsVerified();

        // Fire the verified event
        event(new Verified($user));

        return redirect()->route('admin.dashboard')->with('message', 'Your email has been verified. Welcome!');
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

        // Send email verification notification
        event(new Registered($user));

        // Auth::login($user);

        return redirect()
            ->route('admin.login')
            ->with([
                'status' => 'success',
                'message' => 'Registered successfully, check your email for verification link.',
            ]);
    }

    // verify email
    public function verifyNotice()
    {
        return view('auth.verify-email');
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('admin.dashboard');
    }

    public function verifyHandler(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    }

    // update user profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required|string',
            // add other fields as necessary
        ]);

        // Check if password confirmation is valid
        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()
                ->back()
                ->withErrors(['password' => 'The password is incorrect.'])
                ->withInput()
                ->with([
                    'status' => 'error',
                    'message' => 'The password is incorrect.',
                ]);
        }

        $user->update([
            'name' => $request->name,
            'company' => $request->company,
            'email' => $request->email,
        ]);

        return redirect()
            ->route('admin.showProfile')
            ->with([
                'status' => 'success',
                'message' => 'Account Updated successfully.',
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

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.showProfile')->withErrors($validator)->withInput(); // 👈 set your tab ID here
        }

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Your current password is incorrect'])
                ->withInput();
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()
            ->route('admin.showProfile')
            ->with('success', 'Password changed successfully!')
            ->with([
                'status' => 'success',
                'message' => 'Password Updated successfully.',
            ]);
    }
}
