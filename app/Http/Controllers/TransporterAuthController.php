<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\feriApp;
use App\Models\Invoice;
use App\Models\chats;
use App\Models\Company;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\mainmail;
use App\Mail\CustomVerifyEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TransporterAuthController extends Controller
{
    // Show transporter login form
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('transporter.dashboard');
        }
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
        $records = Company::all();
        return view('transporter.register', compact('records'));
    }

    // Handle transporter registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'company' => ['required', 'integer', 'exists:companies,id'],
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

        // Send email verification notification
        event(new Registered($user));

        // Auth::login($user);

        return redirect()
            ->route('transporter.login')
            ->with([
                'status' => 'success',
                'message' => 'Registered successfully, check your email for verification link.',
            ]);
    }

    // Show profile
    public function showProfile()
    {
        $company = Company::find(Auth::user()->company); // Get the company by ID

        return view('transporter.userprofile', [
            'company' => $company,
        ]);
        // return view('transporter.userprofile');
    }

    // update user profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
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
            'email' => $request->email,
        ]);

        return redirect()
            ->route('transporter.showProfile')
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
            return redirect()->route('transporter.showProfile')->withErrors($validator)->withInput(); // 👈 set your tab ID here
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
            ->route('transporter.showProfile')
            ->with('success', 'Password changed successfully!')
            ->with([
                'status' => 'success',
                'message' => 'Password Updated successfully.',
            ]);
    }

    // Show feri application form
    public function applyferi()
    {
        // Fetch all records from the Company table
        $records = Company::all();

        // Pass the records to the view
        return view('transporter.applyferi', compact('records'));
    }

    // Store method to save cargo entry
    public function feriApp(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'transport_mode' => 'required|string|max:255',
            'transporter_company' => 'required|string|max:255',
            'feri_type' => 'required|string|max:255',
            'entry_border_drc' => 'required|string|max:255',
            'truck_details' => 'required|string|max:255',
            'arrival_station' => 'required|string|max:255',
            'final_destination' => 'required|string|max:255',
            'importer_name' => 'required|string|max:255',
            'importer_phone' => 'required|string|max:20',
            'importer_email' => 'nullable|email|max:255',
            'importer_address' => 'nullable|string|max:255',
            'exporter_address' => 'nullable|string|max:255',
            'importer_details' => 'nullable|string|max:255',
            'fix_number' => 'nullable|string|max:255',
            'exporter_name' => 'required|string|max:255',
            'exporter_phone' => 'required|string|max:20',
            'exporter_email' => 'nullable|email|max:255',
            'cf_agent' => 'required|string|max:255',
            'cf_agent_contact' => 'required|string|max:255',
            'cargo_description' => 'required|string|max:255',
            'hs_code' => 'required|string|max:100',
            'package_type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'company_ref' => 'nullable|string|max:255',
            'cargo_origin' => 'nullable|string|max:255',
            'customs_decl_no' => 'nullable|string|max:255',
            'manifest_no' => 'nullable|string|max:255',
            'occ_bivac' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            // Extra values
            'fob_currency' => 'nullable|string|max:50',
            'fob_value' => 'nullable|numeric|max:100',
            'po' => 'nullable|string|max:100',
            'incoterm' => 'nullable|string|max:50',
            'freight_currency' => 'nullable|string|max:50',
            'freight_value' => 'nullable|numeric|max:100',
            'insurance_currency' => 'nullable|string|max:50',
            'insurance_value' => 'nullable|numeric|max:100',
            'additional_fees_currency' => 'nullable|string|max:50',
            'additional_fees_value' => 'nullable|numeric|max:100',
            'documents_upload' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480', // max 20MB
        ]);

        // Add user_id to the validated data
        try {
            // Add user_id to the validated data
            $validatedData['user_id'] = Auth::id();
            $validatedData['status'] = 1;
            if ($validatedData['feri_type'] == 'continuance') {
                $validatedData['feri_type'] = 'continuance';
            } else {
                $validatedData['feri_type'] = 'regional';
            }

            // Handle file upload
            if ($request->hasFile('documents_upload')) {
                $file = $request->file('documents_upload');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('feri_documents', $filename, 'public'); // stores in storage/app/public/feri_documents
                $validatedData['documents_upload'] = $path;
            }

            // dd($validatedData);
            // Create cargo entry
            // dd($col = feriApp::create($validatedData));
            $r = feriApp::create($validatedData);
            // dd($r);
            // Redirect or respond as needed
            return redirect()
                ->back()
                ->with([
                    'status' => 'success',
                    'message' => 'Feri application sent successfully!',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to submit application. ' . $e->getMessage()])
                ->withInput();
        }
    }

    // Show lists of applications
    public function showApps()
    {
        $records = feriApp::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Add applicant name, draft, and certificate file to each record
        $records->transform(function ($record) {
            // Add applicant name
            $record->applicant = User::find($record->user_id)->name ?? 'Unknown Applicant';

            // Fetch the latest certificate for the application
            $latestCertificate = Certificate::where('application_id', $record->id)->where('type', 'certificate')->latest()->first();

            // Fetch the latest draft for the application
            $latestDraft = Certificate::where('application_id', $record->id)->where('type', 'draft')->latest()->first();

            // Attach certificate data to the record if it exists
            $record->certificateFile = $latestCertificate->file ?? null;

            // Attach draft data to the record if it exists
            $record->draftFile = $latestDraft->file ?? null;

            return $record;
        });

        // Fetch all chats related to the feriApps being displayed
        $feriAppIds = $records->pluck('id'); // Get all feriApp IDs
        $chats = chats::whereIn('application_id', $feriAppIds)
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

        return view('transporter.applications', compact('records', 'chats'));
    }

    // Show single application
    public function showApp($id)
    {
        // $record = feriApp::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        // Join feriApp with companies to get the company name
        $record = feriApp::leftJoin('companies', 'feriapp.transporter_company', '=', 'companies.id')->where('feriapp.id', $id)->where('feriapp.user_id', Auth::id())->select('feriapp.*', 'companies.name as company_name')->firstOrFail();

        // Add company name to the record
        $record->applicant = User::find(Auth::user()->id)->name ?? 'Unknown Applicant';

        // / Fetch the latest draft certificate for the application
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
            $record->type = $latestCertificate->type ?? null;
        }

        // Fetch the invoice related to the latest certificate
        $invoice = null;
        if ($latestCertificate && $record->status >= 4) {
            $invoice = Invoice::where('cert_id', $latestDraft->id)->first();
        } elseif ($latestDraft) {
            $invoice = Invoice::where('cert_id', $latestDraft->id)->first();
        }

        $companies = Company::all(); // Fetch all companies for the dropdown

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
        return view('transporter.viewapplication', compact('record', 'chats', 'invoice', 'companies'));

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

    // update user profile
    public function editApp(Request $request, $id)
    {
        $user = Auth::user();

        // Find the specific feriApp record
        $feriApp = feriApp::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Check if the status is not 1
        if ($feriApp->status != 1 || $user->id != $feriApp->user_id) {
            return back()->with([
                'status' => 'error',
                'message' => 'You cannot edit this application because its status does not allow editing.',
            ]);
        }

        // Validate incoming data
        $validatedData = $request->validate([
            'transport_mode' => 'required|string|max:255',
            'transporter_company' => 'required|integer|max:255',
            'entry_border_drc' => 'required|string|max:255',
            'truck_details' => 'required|string|max:255',
            'arrival_station' => 'required|string|max:255',
            'final_destination' => 'required|string|max:255',
            'importer_name' => 'required|string|max:255',
            'importer_phone' => 'required|string|max:20',
            'importer_email' => 'nullable|email|max:255',
            'importer_address' => 'nullable|string|max:255',
            'exporter_address' => 'nullable|string|max:255',
            'importer_details' => 'nullable|string|max:255',
            'fix_number' => 'nullable|string|max:255',
            'exporter_name' => 'required|string|max:255',
            'exporter_phone' => 'required|string|max:20',
            'exporter_email' => 'nullable|email|max:255',
            'cf_agent' => 'required|string|max:255',
            'cf_agent_contact' => 'required|string|max:255',
            'cargo_description' => 'required|string',
            'hs_code' => 'required|string|max:100',
            'package_type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'company_ref' => 'nullable|string|max:255',
            'cargo_origin' => 'nullable|string|max:255',
            'customs_decl_no' => 'nullable|string|max:255',
            'manifest_no' => 'nullable|string|max:255',
            'occ_bivac' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            // Extra values
            'fob_currency' => 'nullable|string|max:50',
            'po' => 'nullable|string|max:100',
            'fob_value' => 'nullable|string|max:100',
            'incoterm' => 'nullable|string|max:50',
            'freight_currency' => 'nullable|string|max:50',
            'freight_value' => 'nullable|string|max:100',
            'insurance_currency' => 'nullable|string|max:50',
            'insurance_value' => 'nullable|string|max:100',
            'additional_fees_currency' => 'nullable|string|max:50',
            'additional_fees_value' => 'nullable|string|max:100',
            'documents_upload' => 'nullable|file|mimes:pdf|max:20480', // max 20MB
        ]);

        // Handle file upload if necessary
        if ($request->hasFile('documents_upload')) {
            $file = $request->file('documents_upload');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('feri_documents', $filename, 'public');
            $validatedData['documents_upload'] = $path;
        }

        // Update the feriApp record with validated data
        $feriApp->update($validatedData);

        return back()->with([
            'status' => 'success',
            'message' => 'Application Updated successfully.',
        ]);
    }

    public function process3(Request $request, $id)
    {
        // Check if the current user has the role of 'vendor' or 'admin'
        $user = Auth::user();
        if (!in_array($user->role, ['transporter', 'admin'])) {
            return back()->with([
                'status' => 'error',
                'message' => 'You are not authorized to perform this action.',
            ]);
        }

        // Find the specific feriApp record
        $feriApp = feriApp::findOrFail($id);

        // Update the status to 2
        $feriApp->update(['status' => 4]);

        return back()->with([
            'status' => 'success',
            'message' => 'Application status updated successfully.',
        ]);
    }

    public function sendchat(Request $request, $id)
    {
        // Check if the current user has the role of 'transporter' or 'admin'
        $user = Auth::user();
        if (!in_array($user->role, ['transporter', 'admin'])) {
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
        if ($feriApp->user_id !== Auth::id() && !in_array(Auth::user()->role, ['admin'])) {
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
        if ($feriApp->user_id !== Auth::id() && !in_array(Auth::user()->role, ['admin'])) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure the chat belongs to the authenticated user or is authorized
        if ($chat->user_id !== Auth::id() && !in_array(Auth::user()->role, ['admin'])) {
            abort(403, 'Unauthorized action.');
        }

        // Mark the chat as deleted
        $chat->update(['del' => 1]);

        return back()->with([
            'status' => 'success',
            'message' => 'Message deleted successfully!',
        ]);
    }

    public function editpo(Request $request, $id)
    {
        // Ensure the current user is a transporter
        $user = Auth::user();

        if ($user->role !== 'transporter') {
            abort(403, 'Unauthorized action');
        }

        // Find the feriApp record and check ownership
        $feriApp = feriApp::findOrFail($id);

        if ($feriApp->user_id !== $user->id) {
            abort(403, 'Unauthoriszed action.');
        }

        // Validate the input
        $validated = $request->validate([
            'po' => 'required|string|max:255',
        ]);

        // Update the po field
        $feriApp->po = $validated['po'];
        $feriApp->save();

        return back()->with([
            'status' => 'success',
            'message' => 'PO updated successfully.',
        ]);
    }

    public function showdashboard()
    {
        $feris = feriApp::where('user_id', Auth::id())->get();

        // If no data found, set $feris to 0
        if ($feris->isEmpty()) {
            $feris = 0;
        }
        return view('transporter.dashboard', compact('feris'));
    }

    public function sampcalculator()
    {
        // Fetch all records from the Company table
        $records = Company::all();

        // Pass the records to the view
        return view('transporter.calculator', compact('records'));
    }
}
