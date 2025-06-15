<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\feriApp;
use App\Models\Invoice;
use App\Models\Company;
use App\Models\Rate;
use App\Models\chats;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Mail\ChatNotificationMail;
use App\Mail\DraftInvoiceMail;
use App\Mail\CertificateMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\mainmail;
use App\Mail\CustomVerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class VendorAuthController extends Controller
{
    // Show vendor login form
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('vendor.dashboard');
        }
        return view('vendor.login');
    }

    // Show profile
    public function showProfile()
    {
        $company = Company::find(Auth::user()->company); // Get the company by ID

        return view('profile', [
            'company' => $company,
        ]);
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
        $records = Company::all();
        return view('vendor.register', compact('records'));
    }

    // Handle vendor registration
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
        // Fetch all feriApp records
        $records = feriApp::orderBy('created_at', 'desc')->get();

        // Add applicant name and company name to each record
        $records->transform(function ($record) {
            $user = User::find($record->user_id);

            $companyName = null;
            $companyId = $record->transporter_company;
            if ($companyId !== null && $companyId !== '') {
                $company = Company::find((int) $companyId);
                $companyName = $company->name;
            } else {
                $companyName = 'No Company Assigned';
            }

            // Debug output
            // Log::info("Record ID: {$record->id}, transporter_company: {$companyId}, companyName: {$companyName}");

            $record->applicantName = $user->name ?? 'Unknown Applicant';
            $record->companyName = $companyName;

            return $record;
        });
        // dd($records);

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

        return view('vendor.applications', compact('records', 'chats'));
    }

    public function showAppsCompleted()
    {
        // Fetch all feriApp records
        $records = feriApp::where('status', '=', 5)->orderBy('created_at', 'desc')->get();

        // Add applicant name and company name to each record
        $records->transform(function ($record) {
            $user = User::find($record->user_id);

            $companyName = null;
            $companyId = $record->transporter_company;
            if ($companyId !== null && $companyId !== '') {
                $company = Company::find((int) $companyId);
                $companyName = $company->name;
            } else {
                $companyName = 'No Company Assigned';
            }

            // Debug output
            // Log::info("Record ID: {$record->id}, transporter_company: {$companyId}, companyName: {$companyName}");

            $record->applicantName = $user->name ?? 'Unknown Applicant';
            $record->companyName = $companyName;

            return $record;
        });
        // dd($records);

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

        return view('vendor.completedapps', compact('records', 'chats'));
    }

    public function showAppsRejected()
    {
        // Fetch all feriApp records
        $records = feriApp::where('status', '=', 6)->orderBy('created_at', 'desc')->get();

        // Add applicant name and company name to each record
        $records->transform(function ($record) {
            $user = User::find($record->user_id);

            $companyName = null;
            $companyId = $record->transporter_company;
            if ($companyId !== null && $companyId !== '') {
                $company = Company::find((int) $companyId);
                $companyName = $company->name;
            } else {
                $companyName = 'No Company Assigned';
            }

            // Debug output
            // Log::info("Record ID: {$record->id}, transporter_company: {$companyId}, companyName: {$companyName}");

            $record->applicantName = $user->name ?? 'Unknown Applicant';
            $record->companyName = $companyName;

            return $record;
        });
        // dd($records);

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

        return view('vendor.rejectedapps', compact('records', 'chats'));
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
        //if user has not verified email
        if (!Auth::user()->email_verified_at) {
            return view('auth.verify-email');
        }

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
            $record->type = $latestCertificate->type ?? null;
        }

        // Fetch the invoice related to the latest certificate
        $invoice = null;
        if ($latestCertificate && $record->status >= 4) {
            $invoice = Invoice::where('cert_id', $latestDraft->id)->first();
        } elseif ($latestDraft) {
            $invoice = Invoice::where('cert_id', $latestDraft->id)->first();
        }

        // dd($invoice);

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

        $rates = (object) [
            'tz' => Rate::where('currency', 'USD to TZS')->first(),
            'eur' => Rate::where('currency', 'EUR to USD')->first(),
        ];

        $companies = Company::all(); // Fetch all companies for the dropdown

        // dd($rates->tz->amount);

        // Pass the record and chats to the view
        return view('vendor.viewapplication', compact('record', 'chats', 'invoice', 'rates', 'companies'));

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
        $validatedData = $request->validate([
            'file' => 'required|mimes:pdf|max:20480', // max:20480 KB = 20 MB

            'feri_quantity' => 'required|integer',
            'feri_units' => 'required|string',

            'cod_quantities' => 'required|integer',
            'cod_units' => 'required|string',

            'euro_rate' => 'required|numeric',

            'transporter_quantity' => 'required|integer',

            'customer_ref' => 'required|string',
            'customer_po' => 'required|string',
            'customer_trip_no' => 'required|string',
            // 'application_invoice_no' => 'required|string',
            'tz_rate' => 'required|numeric',
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
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'application_id' => $id,
            'type' => 'draft',
            'file' => $filePath, // Save the file path
        ]);

        // Add a new entry in the Invoices table
        Invoice::create([
            'cert_id' => $certificate->id, // Use the ID of the newly created certificate
            'invoice_date' => today(),
            'feri_quantity' => $validatedData['feri_quantity'], // Map quantity to feri_quantity
            'feri_units' => $validatedData['feri_units'], // You can set a default value or validate it if needed
            'cod_quantities' => $validatedData['cod_quantities'], // Default value for cod_quantities
            'cod_units' => $validatedData['cod_units'], // Default value for cod_units
            'euro_rate' => $validatedData['euro_rate'], // Default value for euro_rate
            'tz_rate' => $validatedData['tz_rate'], // Default value for tz_rate
            'transporter_quantity' => $validatedData['transporter_quantity'], // Map additional_values to transporter_quantity
            'customer_ref' => $validatedData['customer_ref'],
            'customer_po' => $validatedData['customer_po'],
            'customer_trip_no' => $validatedData['customer_trip_no'],
            // 'application_invoice_no' => $validatedData['application_invoice_no'],
        ]);

        // Find the recipient (transporter)
        $recipient = User::find($feriApp->user_id);

        //store file path in the certificate
        $certificatePath = $filePath;

        // Sender is the current user
        $sender = $user;

        // Get the invoice just created (if you want to pass it)
        $invoice = Invoice::where('cert_id', $certificate->id)->first();

        // File path for the draft certificate
        $certificatePath = $filePath; // This is set above when you store the file

        // Fetch the applicant's name
        $applicantName = $recipient->name;

        // Pass $invoice, $feriApp, and $applicantName to the view
        $pdf = Pdf::loadView('layouts.theinvoice', [
            'invoice' => $invoice,
            'feriapp' => $feriApp,
            'applicantName' => Str::title($applicantName),
        ])->output();

        if ($recipient && $recipient->email && $certificatePath) {
            Mail::to($recipient->email)->send(new DraftInvoiceMail($invoice, $feriApp, $recipient, $certificatePath, $sender, $pdf));
        }

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
            'certificate_no' => 'required|string|max:255',
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
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'application_id' => $id,
            'type' => 'certificate',
            'file' => $filePath, // Save the file path
        ]);

        // Find the recipient (transporter)
        $recipient = User::find($feriApp->user_id);

        //store file path in the certificate
        $certificatePath = $filePath;

        // Sender is the current user
        $sender = $user;

        // Get the invoice just created (if you want to pass it)
        $draft1 = Certificate::where('application_id', $feriApp->id)->where('type', 'draft')->first();
        $invoice = Invoice::where('cert_id', $draft1->id)->first();

        // Update certificate_no if present in request
        if ($invoice && $request->has('certificate_no')) {
            $invoice->certificate_no = $request->certificate_no;
            $invoice->save();
        }

        // dd($invoice);

        // Fetch the applicant's name
        $applicantName = $recipient->name;

        // Pass $invoice, $feriApp, and $applicantName to the view
        $pdf = Pdf::loadView('layouts.theinvoice', [
            'invoice' => $invoice,
            'feriapp' => $feriApp,
            'applicantName' => Str::title($applicantName),
        ])->output();

        // dd($recipient, $recipient->email, $certificatePath);

        if ($recipient && $recipient->email && $certificatePath) {
            Mail::to($recipient->email)->send(new CertificateMail($invoice, $feriApp, $recipient, $certificatePath, $sender, $pdf));
        }

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

        // Validate the uploaded file
        $validatedData = $request->validate([
            'file' => 'mimes:pdf|max:25600', // max:25600 KB = 25 MB
            'feri_quantity' => 'required|integer',
            'feri_units' => 'required|string',
            'cod_quantities' => 'required|integer',
            'cod_units' => 'required|string',
            'euro_rate' => 'required|numeric',
            'transporter_quantity' => 'required|integer',
            'customer_ref' => 'required|string',
            'customer_po' => 'required|string',
            'customer_trip_no' => 'required|string',
            'tz_rate' => 'required|numeric',
            'certificate_no' => 'nullable|string',
        ]);

        // Find the specific feriApp record
        $feriApp = feriApp::findOrFail($id);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('certificates', $fileName, 'public'); // Save in 'public/certificates' directory

            // Update the draft file in the Certificates table
            $certificate = Certificate::where('application_id', $id)->where('type', 'draft')->latest()->first();

            if ($certificate) {
                $certificate->update(['file' => $filePath]);
            } else {
                $certificate = Certificate::create([
                    'user_id' => Auth::id(),
                    'application_id' => $id,
                    'type' => 'draft',
                    'file' => $filePath,
                ]);
            }
        } else {
            // Fetch the existing certificate if no file is uploaded
            $certificate = Certificate::where('application_id', $id)->where('type', 'draft')->latest()->first();
            $filePath = $certificate ? $certificate->file : null;
        }

        if ($certificate) {
            // Update or create the invoice data
            $invoice = Invoice::where('cert_id', $certificate->id)->first();

            if ($invoice) {
                $invoice->update([
                    'feri_quantity' => $validatedData['feri_quantity'],
                    'feri_units' => $validatedData['feri_units'],
                    'cod_quantities' => $validatedData['cod_quantities'],
                    'cod_units' => $validatedData['cod_units'],
                    'euro_rate' => $validatedData['euro_rate'],
                    'transporter_quantity' => $validatedData['transporter_quantity'],
                    'customer_ref' => $validatedData['customer_ref'],
                    'customer_po' => $validatedData['customer_po'],
                    'customer_trip_no' => $validatedData['customer_trip_no'],
                    'tz_rate' => $validatedData['tz_rate'],
                    'certificate_no' => $validatedData['certificate_no'] ?? null,
                ]);
            } else {
                $invoice = Invoice::create([
                    'cert_id' => $certificate->id,
                    'invoice_date' => today(),
                    'feri_quantity' => $validatedData['feri_quantity'],
                    'feri_units' => $validatedData['feri_units'],
                    'cod_quantities' => $validatedData['cod_quantities'],
                    'cod_units' => $validatedData['cod_units'],
                    'euro_rate' => $validatedData['euro_rate'],
                    'transporter_quantity' => $validatedData['transporter_quantity'],
                    'customer_ref' => $validatedData['customer_ref'],
                    'customer_po' => $validatedData['customer_po'],
                    'customer_trip_no' => $validatedData['customer_trip_no'],
                    'tz_rate' => $validatedData['tz_rate'],
                    'certificate_no' => $validatedData['certificate_no'] ?? null,
                ]);
            }

            // Prepare for email
            $recipient = User::find($feriApp->user_id);
            $sender = $user;
            $applicantName = $recipient ? $recipient->name : 'Applicant';

            // Generate PDF for the invoice
            $pdf = Pdf::loadView('layouts.theinvoice', [
                'invoice' => $invoice,
                'feriapp' => $feriApp,
                'applicantName' => Str::title($applicantName),
            ])->output();

            // Send email if recipient and file exist
            if ($recipient && $recipient->email && $filePath) {
                Mail::to($recipient->email)->send(new DraftInvoiceMail($invoice, $feriApp, $recipient, $filePath, $sender, $pdf));
            }
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Draft and invoice updated successfully, and email sent.',
        ]);
    }

    public function sendchat(Request $request, $id)
    {
        // Check if the current user has the role of 'vendor' or 'admin'
        $user = Auth::user();
        if (!in_array($user->role, ['vendor', 'admin'])) {
            return back()->with([
                'status' => 'error',
                'message' => 'You are not authorized to perform this action.',
            ]);
        }

        // If feriApp status is 6, set it back to 3
        $feriApp = feriApp::findOrFail($id);
        if ($feriApp->status == 6) {
            $feriApp->status = 3;
            $feriApp->save();
        }

        // Validate the request
        $validatedData = $request->validate([
            'message' => 'required|string|max:500', // Ensure the message is not too long
        ]);

        // Sanitize the message
        $validatedData['message'] = htmlspecialchars($validatedData['message'], ENT_QUOTES, 'UTF-8');

        // Create the chat and get the chat object
        $chat = chats::create([
            'user_id' => $user->id, // Current logged-in user ID
            'application_id' => $id, // Application ID from the route parameter
            'read' => 0, // Default to unread
            'message' => $validatedData['message'], // Sanitized message
            'del' => 0, // Default to not deleted
        ]);

        // Find the recipient (transporter) - assuming transporter_company is user_id
        $recipient = User::find($feriApp->user_id);

        // Send the email if recipient exists and has an email
        if ($recipient && $recipient->email) {
            Mail::to($recipient->email)->send(new ChatNotificationMail($chat, $feriApp, $user, $recipient));
        }

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

    public function showdashboard()
    {
        $feris = feriApp::all();
        $companies = Company::where('type', 'transporter')->get();

        // If no data found, set $feris to 0
        if ($feris->isEmpty()) {
            $feris = 0;
        }
        return view('vendor.dashboard', compact('feris', 'companies'));
    }

    public function sampcalculator()
    {
        // Fetch all records from the Company table
        $records = Company::all();

        // Fetch the rates you want (e.g., tz and eur)
        $rates = (object) [
            'tz' => Rate::where('currency', 'USD to TZS')->first(),
            'eur' => Rate::where('currency', 'EUR to USD')->first(),
        ];

        // Pass both $records and $rates to the view
        return view('vendor.calculator', compact('records', 'rates'));
    }

    public function rates()
    {
        //if user has not verified email
        if (!Auth::user()->email_verified_at) {
            return view('auth.verify-email');
        }

        // Fetch all records from the Company table
        $records = Rate::all();

        // Pass the records to the view
        return view('vendor.rates', compact('records'));
    }

    public function rateupdate(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate(
            [
                'amount' => [
                    'required',
                    'numeric',
                    'regex:/^\d{1,6}(\.\d{1,4})?$/', // up to 6 digits before and 4 after decimal
                    'between:0,999999.9999', // match DECIMAL(10,4) max
                ],
            ],
            [
                'amount.regex' => 'Rate must not have more than 4 decimal places and must be less than 1,000,000.',
                'amount.between' => 'Rate must be between 0 and 999999.9999.',
            ],
        );

        // Find the specific rate record
        $rate = Rate::findOrFail($id);

        // Update the rate record with validated data
        $rate->update($validatedData);

        return back()->with([
            'status' => 'success',
            'message' => 'Rate updated successfully.',
        ]);
    }

    public function fetchChatMessages($id)
    {
        $chats = chats::where('application_id', $id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($chat) {
                $now = now();
                if ($chat->created_at->isToday()) {
                    $chat->formatted_date = $chat->created_at->format('H:i');
                } elseif ($chat->created_at->diffInDays($now) < 365) {
                    $chat->formatted_date = $chat->created_at->format('j M');
                } else {
                    $chat->formatted_date = $chat->created_at->format('j M Y');
                }
                return $chat;
            });
        return view('components.chat_messages', compact('chats'))->render();
    }

    public function showinvoices()
    {
        if (!Auth::user()->email_verified_at) {
            return view('auth.verify-email');
        }

        // Get all invoices
        $records = Invoice::all();

        // Get all certificates referenced by invoices
        $certIds = $records->pluck('cert_id')->unique()->filter();
        $certificates = Certificate::whereIn('id', $certIds)->get()->keyBy('id');

        // Get all application_ids from those certificates
        $applicationIds = $certificates->pluck('application_id')->unique()->filter();
        $applications = feriApp::whereIn('id', $applicationIds)->get()->keyBy('id');

        // Filter invoices: only those whose certificate's application has status = 5
        $approvedRecords = $records
            ->filter(function ($invoice) use ($certificates, $applications) {
                $cert = $certificates->get($invoice->cert_id);
                if (!$cert) {
                    return false;
                }
                $app = $applications->get($cert->application_id);
                return $app && $app->status == 5;
            })
            ->values();

        // Add grandTotal to each record
        $approvedRecords->transform(function ($invoice) {
            $feriQty = (float) ($invoice->feri_quantity ?? 0);
            $feriUnits = (float) ($invoice->feri_units ?? 0);
            $codQty = (float) ($invoice->cod_quantities ?? 0);
            $codUnits = (float) ($invoice->cod_units ?? 0);
            $euroRate = (float) ($invoice->euro_rate ?? 1);
            $transporterQty = (float) ($invoice->transporter_quantity ?? 0);

            $feriAmount = $feriQty * $feriUnits;
            $codAmount = $codQty * $codUnits;
            $upTotal = $feriAmount + $codAmount;
            $transporterAmount = $transporterQty * 0.018;
            $grandTotal = $transporterAmount + $upTotal * $euroRate - 5;

            $invoice->grandTotal = $grandTotal;
            return $invoice;
        });

        return view('vendor.invoices', ['records' => $approvedRecords]);
    }

    public function showstatementgen()
    {
        return view('vendor.stateform');
    }
}
