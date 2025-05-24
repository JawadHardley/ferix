<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\feriApp;
use App\Models\Company;
use App\Models\chats;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\mainmail;
use App\Mail\CustomVerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VendorAuthController extends Controller
{
    // Show vendor login form
    public function showLoginForm()
    {
        return view('vendor.login');
    }

    // Show profile
    public function showProfile()
    {
        return view('profile');
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
                return redirect()->back()->with('status', 'error')->with('message', 'Your account is not authorized yet.');
            }

            if (Auth::user()->role !== 'vendor') {
                Auth::logout();
                return redirect()->back()->with('status', 'error')->with('message', 'You are not authorized to access the vendor panel.');
            }

            return redirect()->route('vendor.dashboard');
        }

        return redirect()->back()->with('status', 'error')->with('message', 'The provided credentials do not match our records.');
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
            'company' => 0,
            'password' => Hash::make($validated['password']),
            'role' => 'vendor', // Set role explicitly
            'user_auth' => 0, // or 0 if you want to authorize later
        ]);

        // Send email verification notification
        event(new Registered($user));

        // Auth::login($user);

        return redirect()
            ->route('vendor.login')
            ->with([
                'status' => 'success',
                'message' => 'Registered successfully, check your email for verification link.',
            ]);
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

        // Check if email has changed
        $emailChanged = $request->email !== $user->email;
        $user->email = $request->email;

        if ($emailChanged) {
            $user->email_verified_at = null;
            // Optionally send new verification email
            $user->sendEmailVerificationNotification();
        }

        $user->update([
            'name' => $request->name,
            'company' => $request->company,
            'email' => $request->email,
        ]);

        return redirect()
            ->route('vendor.showProfile')
            ->with([
                'status' => 'success',
                'message' => 'Account Updated successfully.',
            ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('active_tab', 'tabs-home-9'); // 👈 set your tab ID here
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
            ->route('vendor.showProfile')
            ->with('success', 'Password changed successfully!')
            ->with([
                'status' => 'success',
                'message' => 'Password Updated successfully.',
            ]);
    }

    // Show lists of applications
    // public function showApps()
    // {
    //     $records = feriApp::simplePaginate(10);

    //     // Add company name to each record
    //     $applicant = User::find(Auth::user()->id)->name ?? 'Unknown Applicant';
    //     $records->getCollection()->transform(function ($record) use ($applicant) {
    //         $record->applicant = $applicant;
    //         return $record;
    //     });

    //     return view('vendor.applications', compact('records'));
    // }

    public function showApps()
    {
        // Fetch all feriApp records with pagination
        $records = feriApp::simplePaginate(10);

        // Add applicant name and company name to each record
        $records->getCollection()->transform(function ($record) {
            // Fetch the user associated with the feriApp
            $user = User::find($record->user_id);

            // Fetch the company name using the user's company ID
            $companyName = null;
            if ($user && $user->company) {
                $company = Company::find($user->company);
                $companyName = $company->name ?? 'No Company Assigned';
            }

            // Add applicant name and company name to the record
            $record->applicantName = $user->name ?? 'Unknown Applicant';
            $record->companyName = $companyName;

            return $record;
        });
        return view('vendor.applications', compact('records'));
    }

    // Show single application
    // public function showApp($id)
    // {
    //     $record = feriApp::where('id', $id)->firstOrFail();

    //     // Add applicant name to the record
    //     $record->applicant = User::find($record->user_id)->name ?? 'Unknown Applicant';

    //     // Fetch the latest draft certificate for the application
    //     $latestDraft = Certificate::where('application_id', $id)->where('type', 'draft')->latest()->first();

    //     // Fetch the latest certificate for the application
    //     $latestCertificate = Certificate::where('application_id', $id)->where('type', 'certificate')->latest()->first();

    //     // Attach draft data to the record if it exists
    //     if ($latestDraft) {
    //         $record->applicationFile = $latestDraft->file ?? null;
    //         $record->type = $latestDraft->type ?? null;
    //     }

    //     // Attach certificate data to the record if it exists
    //     if ($latestCertificate) {
    //         $record->certificateFile = $latestCertificate->file ?? null;
    //     }

    //     return view('vendor.viewapplication', compact('record'));
    // }

    // Show single application
    public function showApp($id)
    {
        // Fetch the specific feriApp record
        $record = feriApp::where('id', $id)->firstOrFail();

        // Fetch the user associated with the feriApp
        $user = User::find($record->user_id);

        // Fetch the company name using the user's company ID
        $companyName = null;
        if ($user && $user->company) {
            $company = Company::find($user->company);
            $companyName = $company->name ?? 'No Company Assigned';
        }

        // Add applicant name and company name to the record
        $record->applicant = $user->name ?? 'Unknown Applicant';
        $record->companyName = $companyName;

        // Fetch the latest draft certificate for the application
        $latestDraft = Certificate::where('application_id', $id)->where('type', 'draft')->latest()->first();

        // Fetch the latest certificate for the application
        $latestCertificate = Certificate::where('application_id', $id)->where('type', 'certificate')->latest()->first();

        // Attach draft data to the record if it exists
        if ($latestDraft) {
            $record->applicationFile = $latestDraft->file ?? null;
            $record->type = $latestDraft->type ?? null;
        }

        // Attach certificate data to the record if it exists
        if ($latestCertificate) {
            $record->certificateFile = $latestCertificate->file ?? null;
        }

        // Fetch all chats related to the application
        $chats = chats::where('application_id', $id)
            ->orderBy('created_at', 'asc') // Order chats by creation time
            ->get()
            ->map(function ($chat) {
                // Format the created_at timestamp
                $now = now();
                if ($chat->created_at->isToday()) {
                    $chat->formatted_date = $chat->created_at->format('H:i'); // e.g., "21:33"
                } elseif ($chat->created_at->diffInDays($now) < 365) {
                    $chat->formatted_date = $chat->created_at->format('j M'); // e.g., "2 May"
                } else {
                    $chat->formatted_date = $chat->created_at->format('j M Y'); // e.g., "2 May 25"
                }
                return $chat;
            });

        // Pass the record and chats to the view
        return view('vendor.viewapplication', compact('record', 'chats'));

        // return view('transporter.viewapplication', compact('record'));
    }

    public function destroyApp($id)
    {
        feriApp::destroy($id);
        return back()->with([
            'status' => 'success',
            'message' => 'Application Deleted successfully!.',
        ]);
    }

    public function process1(Request $request, $id)
    {
        // Check if the current user has the role of 'vendor' or 'admin'
        $user = Auth::user();
        if (!in_array($user->role, ['vendor', 'admin'])) {
            return back()->with([
                'status' => 'error',
                'message' => 'You are not authorized to perform this action.',
            ]);
        }

        // Find the specific feriApp record
        $feriApp = feriApp::findOrFail($id);

        // Update the status to 2
        $feriApp->update(['status' => 2]);

        return back()->with([
            'status' => 'success',
            'message' => 'Application status updated successfully.',
        ]);
    }

    public function process2(Request $request, $id)
    {
        // Check if the current user has the role of 'vendor' or 'admin'
        $user = Auth::user();
        if (!in_array($user->role, ['vendor', 'admin'])) {
            return back()->with([
                'status' => 'error',
                'message' => 'You are not authorized to perform this action.',
            ]);
        }

        // Validate the file (limit to PDF and max size 5MB)
        $request->validate([
            'file' => 'required|mimes:pdf|max:20480', // max:20480 KB = 20 MB
        ]);

        // Find the specific feriApp record
        $feriApp = feriApp::findOrFail($id);

        // Check if the status is 2
        if ($feriApp->status != 2) {
            return back()->with([
                'status' => 'error',
                'message' => 'The application status must be accepted to proceed.',
            ]);
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('certificates', $fileName, 'public');
        }

        // Update the status to 3
        $feriApp->update(['status' => 3]);

        // Add a new entry in the Certificates table
        Certificate::create([
            'user_id' => $user->id,
            'application_id' => $id,
            'type' => 'draft',
            'file' => $filePath, // Save the file path
        ]);

        return back()->with([
            'status' => 'success',
            'message' => 'Application updated successfully.',
        ]);
    }

    public function process3(Request $request, $id)
    {
        // Check if the current user has the role of 'vendor' or 'admin'
        $user = Auth::user();
        if (!in_array($user->role, ['vendor', 'admin'])) {
            return back()->with([
                'status' => 'error',
                'message' => 'You are not authorized to perform this action.',
            ]);
        }

        // Validate the file (limit to PDF and max size 5MB)
        $request->validate([
            'file' => 'required|mimes:pdf|max:20480', // max:20480 KB = 20 MB
        ]);

        // Find the specific feriApp record
        $feriApp = feriApp::findOrFail($id);

        // Check if the status is 2
        if ($feriApp->status != 4) {
            return back()->with([
                'status' => 'error',
                'message' => 'The application status must be accepted to proceed.',
            ]);
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('certificates', $fileName, 'public');
        }

        // Update the status to 3
        $feriApp->update(['status' => 5]);

        // Add a new entry in the Certificates table
        Certificate::create([
            'user_id' => $user->id,
            'application_id' => $id,
            'type' => 'certificate',
            'file' => $filePath, // Save the file path
        ]);

        return back()->with([
            'status' => 'success',
            'message' => 'Application updated successfully.',
        ]);
    }

    public function updatedraft(Request $request, $id)
    {
        // Check if the current user has the role of 'vendor' or 'admin'
        $user = Auth::user();
        if (!in_array($user->role, ['vendor', 'admin'])) {
            return back()->with([
                'status' => 'error',
                'message' => 'You are not authorized to perform this action.',
            ]);
        }
        // dd($request->file);
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:pdf|max:25600', // max:25600 KB = 25 MB
        ]);

        // Find the specific feriApp record
        $feriApp = feriApp::findOrFail($id);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('certificates', $fileName, 'public'); // Save in 'public/drafts' directory
        }

        // Update the draft file in the Certificates table
        $certificate = Certificate::where('application_id', $id)->where('type', 'draft')->latest()->first();

        if ($certificate) {
            // Update the existing draft
            $certificate->update(['file' => $filePath]);
        } else {
            // Create a new draft entry if it doesn't exist
            Certificate::create([
                'user_id' => Auth::id(),
                'application_id' => $id,
                'type' => 'draft',
                'file' => $filePath,
            ]);
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Draft updated successfully.',
        ]);
    }

    public function sendchat(Request $request, $id)
    {
        // Check if the current user has the role of 'transporter' or 'admin'
        $user = Auth::user();
        if (!in_array($user->role, ['vendor', 'admin'])) {
            return back()->with([
                'status' => 'error',
                'message' => 'You are not authorized to perform this action.',
            ]);
        }

        // Validate the request
        $validatedData = $request->validate([
            'message' => 'required|string|max:500', // Ensure the message is not too long
        ]);

        // Sanitize the message
        $validatedData['message'] = htmlspecialchars($validatedData['message'], ENT_QUOTES, 'UTF-8');

        chats::create([
            'user_id' => $user->id, // Current logged-in user ID
            'application_id' => $id, // Application ID from the route parameter
            'read' => 0, // Default to unread
            'message' => $validatedData['message'], // Sanitized message
            'del' => 0, // Default to not deleted
        ]);

        $this->readchat($id);

        return back()->with([
            'status' => 'success',
            'message' => 'Query sent successfully!.',
        ]);
    }

    public function readchat($id)
    {
        $feriApp = feriApp::findOrFail($id);

        // Ensure the authenticated user owns the application or has the proper role
        if (!in_array(Auth::user()->role, ['vendor'])) {
            abort(403, 'Unauthorized action.');
        }
        // Fetch chats where:
        // - application_id matches $id
        // - user_id is not the authenticated user's ID
        // - read is 0
        $unreadChats = chats::where('application_id', $id)->where('user_id', '!=', Auth::id())->where('read', 0)->get();

        // Check if there are any unread chats
        if ($unreadChats->isNotEmpty()) {
            // Update the read field to 1 for all matching chats
            chats::where('application_id', $id)
                ->where('user_id', '!=', Auth::id())
                ->where('read', 0)
                ->update(['read' => 1]);
        }

        return back();
        // return back()->with([
        //     'status' => 'success',
        //     'message' => 'Query marked as read successfully!',
        // ]);
    }

    public function deletechat($id)
    {
        // $feriApp = feriApp::findOrFail($id);s

        // Fetch the specific chat by its ID
        $chat = chats::findOrFail($id);

        // Ensure the authenticated user owns the application or has the proper role
        $feriApp = feriApp::findOrFail($chat->application_id);
        if ($chat->user_id !== Auth::id() && !in_array(Auth::user()->role, ['admin'])) {
            abort(403, 'Unauthorized action.');
        }

        // // Ensure the chat belongs to the authenticated user or is authorized
        // if ($chat->user_id !== Auth::id() && !in_array(Auth::user()->role, ['admin'])) {
        //     abort(403, 'Unauthorized action.');
        // }

        // Mark the chat as deleted
        $chat->update(['del' => 1]);

        return back()->with([
            'status' => 'success',
            'message' => 'Message deleted successfully!',
        ]);
    }
}
