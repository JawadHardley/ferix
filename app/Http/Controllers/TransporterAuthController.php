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
use App\Models\Rate;
use App\Models\FormTemplate;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\mainmail;
use App\Mail\NewAppMail;
use App\Mail\Approval;
use App\Mail\Rejection;
// use App\Mail\CustomVerifyEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CustomVerifyEmail;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            if (Auth::user()->user_auth != 1) {
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
        // $records = Company::all();
        $records = Company::where('type', 'transporter')->orderBy('name', 'asc')->get();
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
        Notification::route('mail', $user->email)->notify(new CustomVerifyEmail());

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
    public function applyferi(Request $request)
    {
        //if user has not verified email
        if (!Auth::user()->email_verified_at) {
            return view('auth.verify-email');
        }
        
        $template = null;
        $type ='';
        $formData = [];

        if ($request->filled('template')) {

            $template = FormTemplate::where('company_id', Auth::user()->company)
                ->where('id', $request->template)
                ->first();

                // dd($template->type);

            if (!$template) {
                abort(404);
            }

            $formData = $template->form_data;
            $type = $template->type;
        }

        // Fetch all records from the Company table
        // $records = Company::where('type', 'transporter')->get();
        $records = Company::where('type', 'transporter')->orderBy('name', 'asc')->get();
        
        if ($type == 'regional') {
            // Pass the records to the view
            return view('transporter.applyferi', compact('records', 'template', 'formData'));
        } elseif ($type == 'continuance') {
            return view('transporter.continueferi', compact('records', 'template', 'formData'));
        } else {
            return view('transporter.applyferi', compact('records', 'template', 'formData'));
        }
    }

        // Show feri application form
    public function manualtemplate()
    {
        //if user has not verified email
        if (!Auth::user()->email_verified_at) {
            return view('auth.verify-email');
        }

        // Fetch all records from the Company table
        // $records = Company::where('type', 'transporter')->get();
        $records = Company::where('type', 'transporter')->orderBy('name', 'asc')->get();

        // Pass the records to the view
        return view('transporter.manualtemplate', compact('records'));
    }

     // Show feri application form
    public function listtemplate()
    {
        //if user has not verified email
        if (!Auth::user()->email_verified_at) {
            return view('auth.verify-email');
        }

        // Fetch all records from the Company table
        // $records = Company::where('type', 'transporter')->get();
        $records = FormTemplate::with('user')->where('company_id', Auth::user()->company)->orderBy('created_at', 'asc')->get();

        // Pass the records to the view
        return view('transporter.listtemplate', compact('records'));
    }

    // Show feri application form
    public function continueferi()
    {
        //if user has not verified email
        if (!Auth::user()->email_verified_at) {
            return view('auth.verify-email');
        }

        // Fetch all records from the Company table
        $records = Company::where('type', 'transporter')->get();

        // Pass the records to the view
        return view('transporter.continueferi', compact('records'));
    }

    // Store method to save cargo entry
    public function feriApp(Request $request)
    {
        // dd($request);
        // List of fields for continuance
        $continuanceFields = ['company_ref', 'po', 'validate_feri_cert', 'entry_border_drc', 'arrival_date', 'final_destination', 'customs_decl_no', 'arrival_station', 'truck_details', 'transporter_company', 'weight', 'quantity', 'volume', 'importer_name', 'cf_agent', 'exporter_name', 'freight_currency', 'freight_value', 'fob_value', 'insurance_value', 'instructions'];

        // File fields to handle
        $fileFields = ['invoice', 'manifest', 'packing_list', 'customs'];
        // dd($request->feri_type);
        if ($request->feri_type === 'continuance') {
            // Validate only the required fields for continuance
            $validatedData = $request->validate([
                'company_ref' => 'nullable|string|max:255',
                'po' => 'nullable|string|max:100',
                'validate_feri_cert' => 'nullable|string|max:255',
                'entry_border_drc' => 'required|string|max:255',
                'arrival_date' => 'required|date|after_or_equal:today',
                'final_destination' => 'required|string|max:255',
                'customs_decl_no' => 'nullable|string|max:255',
                'arrival_station' => 'required|string|max:255',
                'truck_details' => 'required|string|max:255',
                'transporter_company' => 'required|string|max:255',
                'weight' => 'required|numeric|min:1',
                'quantity' => 'required|numeric|max:9999999999',
                'volume' => 'required|string|max:255',
                'importer_name' => 'required|string|max:255',
                'cf_agent' => 'required|string|max:255',
                'exporter_name' => 'required|string|max:255',
                'freight_currency' => 'nullable|string|max:50',
                'freight_value' => 'nullable|numeric|max:9999999999',
                'fob_value' => 'nullable|numeric|max:9999999999',
                'insurance_value' => 'nullable|numeric|max:9999999999',
                'instructions' => 'nullable|string',
                'invoice' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
                'packing_list' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
                'manifest' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
                'customs' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
            ]);

            // Set all other fields to 0

            $allFields = ['transport_mode', 'importer_phone', 'importer_email', 'importer_address', 'exporter_address', 'importer_details', 'exporter_phone', 'exporter_email', 'cf_agent_contact', 'hs_code', 'package_type', 'quantity', 'cargo_origin', 'cargo_description', 'manifest_no', 'occ_bivac', 'fob_currency', 'incoterm', 'insurance_currency', 'additional_fees_currency', 'additional_fees_value'];
            foreach ($allFields as $field) {
                if (!array_key_exists($field, $validatedData)) {
                    $validatedData[$field] = 0;
                }
            }

            $validatedData['feri_type'] = 'continuance';
        } else {
            // Original validation for regional
            $validatedData = $request->validate([
                'transport_mode' => 'required|string|max:255',
                'transporter_company' => 'required|string|max:255',
                //company from companies table
                // 'transporter_company' => 'nullable|exists:companies,id',
                // 'company_input' => 'required_without:transporter_company|string|max:255',
                // end of company validation
                'feri_type' => 'required|string|max:255',
                'entry_border_drc' => 'required|string|max:255',
                'truck_details' => 'required|string|max:255',
                'arrival_station' => 'required|string|max:255',
                'arrival_date' => 'required|date|after_or_equal:today',
                'final_destination' => 'required|string|max:255',
                'importer_name' => 'required|string|max:255',
                'importer_phone' => 'required|string|max:20',
                'importer_email' => 'nullable|string|max:255',
                'importer_address' => 'nullable|string|max:255',
                'exporter_address' => 'nullable|string|max:255',
                'importer_details' => 'nullable|string|max:255',
                'fix_number' => 'nullable|string|max:255',
                'exporter_name' => 'required|string|max:255',
                'exporter_phone' => 'required|string|max:20',
                'exporter_email' => 'nullable|string|max:255',
                'cf_agent' => 'required|string|max:255',
                'cf_agent_contact' => 'required|string|max:255',
                'cargo_description' => 'required|string|max:255',
                'hs_code' => 'required|string|max:100',
                'package_type' => 'required|string|max:255',
                'quantity' => 'required|numeric|min:1',
                'weight' => 'required|numeric|min:1',
                'volume' => 'required|string|max:255',
                'company_ref' => 'nullable|string|max:255',
                'cargo_origin' => 'nullable|string|max:255',
                'customs_decl_no' => 'nullable|string|max:255',
                'manifest_no' => 'nullable|string|max:255',
                'occ_bivac' => 'nullable|string|max:255',
                'instructions' => 'nullable|string',
                // Extra values
                'fob_currency' => 'nullable|string|max:50',
                'fob_value' => 'nullable|numeric|max:9999999999',
                'po' => 'nullable|string|max:100',
                'incoterm' => 'nullable|string|max:50',
                'freight_currency' => 'nullable|string|max:50',
                'freight_value' => 'nullable|numeric|max:9999999999',
                'insurance_currency' => 'nullable|string|max:50',
                'insurance_value' => 'nullable|numeric|max:9999999999',
                'additional_fees_currency' => 'nullable|string|max:50',
                'additional_fees_value' => 'nullable|numeric|max:9999999999',
                'invoice' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
                'packing_list' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
                'manifest' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
                'customs' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
            ]);
            $validatedData['feri_type'] = 'regional';
        }

        // Add user_id and status
        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 1;
        

        // Handle file uploads for all types
        $documentUploads = [];
        foreach ($fileFields as $fileField) {
            if ($request->hasFile($fileField)) {
                $file = $request->file($fileField);
                
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('feri_documents', $filename, 'private');
                $documentUploads[$fileField] = $path;
            }
        }

        $validatedData['documents_upload'] = json_encode($documentUploads);
                // dd($validatedData['documents_upload']);

        // Remove file fields so they are not inserted as columns
        unset($validatedData['invoice'], $validatedData['manifest'], $validatedData['packing_list'], $validatedData['customs']);


        try {
            // // ----- Company selection / creation logic -----
            // $companyId = $request->transporter_company;

            // if ($companyId) {
            //     // User selected existing company
            //     $company = Company::find($companyId);
            // } else {
            //     // User typed a new company name
            //     $company = Company::firstOrCreate([
            //         'name' => trim($request->company_input),
            //         'type' => 'transporter',
            //     ]);
            // }

            // // Inject transporter company into validated data
            // $validatedData['transporter_company'] = $company?->id;
            // unset($validatedData['company_input']);
            // dd($request->hasFile($fileField));
            $r = feriApp::create($validatedData);
            $transporter = Auth::user();
            $company = Company::where('type', 'vendor')->first();
            $vendors = User::where('company', $company->id)->where('role', 'vendor')->get();

            if ($vendors->count() > 0) {
                $mainVendor = $vendors->first(); // Primary recipient
                $ccEmails = $vendors->skip(1)->pluck('email')->filter()->all(); // Remove nulls

                // Mail::to($mainVendor->email)->cc($ccEmails)->send(new NewAppMail($r, $mainVendor, $transporter));
                Mail::to($mainVendor->email)->cc($ccEmails)->queue(new NewAppMail($r, $mainVendor, $transporter));
            }

            return redirect('transporter/applications')
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

            // --- NEW LOGIC: Fetch feri_cert_no from Invoice via latest draft certificate ---
            $record->feri_cert_no = null; // Initialize to null

            if ($latestDraft) {
                // Find the invoice associated with this draft certificate
                // This assumes 'cert_id' in the 'invoices' table refers to the 'id' of the 'certificates' table.
                $invoice = Invoice::where('cert_id', $latestDraft->id)->first();

                if ($invoice) {
                    $record->feri_cert_no = $invoice->certificate_no;
                }
            }
            // --- END NEW LOGIC ---

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

    public function showAppsRejected()
    {
        $records = feriApp::where('user_id', Auth::user()->id)
            ->where('status', '=', 6)
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

        return view('transporter.rejectedapps', compact('records', 'chats'));
    }

    public function showAppsCompleted()
    {
        $records = feriApp::where('user_id', Auth::user()->id)
            ->where('status', '=', 5) // Fetch only completed applications (status 4 and above)
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

        return view('transporter.completedapps', compact('records', 'chats'));
    }

    // Show single application
    public function showApp($id)
    {
        //if user has not verified email
        if (!Auth::user()->email_verified_at) {
            return view('auth.verify-email');
        }

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

        $companies = Company::where('type', 'transporter')->get(); // Fetch only transporter companies for the dropdown

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
        // dd($request);
        $user = Auth::user();

        // Find the specific feriApp record
        $feriApp = feriApp::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Only allow editing if status is 1
        if ($feriApp->status != 1 || $user->id != $feriApp->user_id) {
            return back()->with([
                'status' => 'error',
                'message' => 'You cannot edit this application because its status does not allow editing.',
            ]);
        }

        // File fields to handle
        $fileFields = ['invoice', 'manifest', 'packing_list', 'customs'];

        // Determine type and validate accordingly
        if ($feriApp->feri_type === 'continuance') {
            $validatedData = $request->validate([
                'company_ref' => 'nullable|string|max:255',
                'po' => 'nullable|string|max:100',
                'validate_feri_cert' => 'nullable|string|max:255',
                'entry_border_drc' => 'required|string|max:255',
                'arrival_date' => 'required|date|after_or_equal:today',
                'final_destination' => 'required|string|max:255',
                'customs_decl_no' => 'nullable|string|max:255',
                'arrival_station' => 'required|string|max:255',
                'truck_details' => 'required|string|max:255',
                'transporter_company' => 'required|string|max:255',
                'weight' => 'required|numeric|min:1',
                'quantity' => 'required|numeric|min:1',
                'volume' => 'required|string|max:255',
                'importer_name' => 'required|string|max:255',
                'cf_agent' => 'required|string|max:255',
                'exporter_name' => 'required|string|max:255',
                'freight_currency' => 'nullable|string|max:50',
                'freight_value' => 'nullable|numeric|max:9999999999',
                'fob_value' => 'nullable|numeric|max:9999999999',
                'insurance_value' => 'nullable|numeric|max:9999999999',
                'instructions' => 'nullable|string',
                'invoice' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
                'packing_list' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
                'manifest' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
            ]);

            // Set all other fields to 0
            $allFields = ['transport_mode', 'feri_type', 'importer_phone', 'importer_email', 'importer_address', 'exporter_address', 'importer_details', 'exporter_phone', 'exporter_email', 'cf_agent_contact', 'hs_code', 'package_type', 'cargo_origin', 'cargo_description', 'manifest_no', 'occ_bivac', 'fob_currency', 'incoterm', 'insurance_currency', 'additional_fees_currency', 'additional_fees_value'];
            foreach ($allFields as $field) {
                if (!array_key_exists($field, $validatedData)) {
                    $validatedData[$field] = 0;
                }
            }
            $validatedData['feri_type'] = 'continuance';
        } else {
            // Validation for regional
            $validatedData = $request->validate([
                'transport_mode' => 'required|string|max:255',
                'transporter_company' => 'required|string|max:255',
                'feri_type' => 'required|string|max:255',
                'entry_border_drc' => 'required|string|max:255',
                'truck_details' => 'required|string|max:255',
                'arrival_station' => 'required|string|max:255',
                'arrival_date' => 'required|date|after_or_equal:today',
                'final_destination' => 'required|string|max:255',
                'importer_name' => 'required|string|max:255',
                'importer_phone' => 'required|string|max:20',
                'importer_email' => 'nullable|string|max:255',
                'importer_address' => 'nullable|string|max:255',
                'exporter_address' => 'nullable|string|max:255',
                'importer_details' => 'nullable|string|max:255',
                'fix_number' => 'nullable|string|max:255',
                'exporter_name' => 'required|string|max:255',
                'exporter_phone' => 'required|string|max:20',
                'exporter_email' => 'nullable|string|max:255',
                'cf_agent' => 'required|string|max:255',
                'cf_agent_contact' => 'required|string|max:255',
                'cargo_description' => 'required|string|max:255',
                'hs_code' => 'required|string|max:100',
                'package_type' => 'required|string|max:255',
                'quantity' => 'required|numeric|min:1',
                'weight' => 'required|numeric|min:1',
                'volume' => 'required|string|max:255',
                'company_ref' => 'nullable|string|max:255',
                'cargo_origin' => 'nullable|string|max:255',
                'customs_decl_no' => 'nullable|string|max:255',
                'manifest_no' => 'nullable|string|max:255',
                'occ_bivac' => 'nullable|string|max:255',
                'instructions' => 'nullable|string',
                // Extra values
                'fob_currency' => 'nullable|string|max:50',
                'fob_value' => 'nullable|numeric|max:9999999999',
                'po' => 'nullable|string|max:100',
                'incoterm' => 'nullable|string|max:50',
                'freight_currency' => 'nullable|string|max:50',
                'freight_value' => 'nullable|numeric|max:9999999999',
                'insurance_currency' => 'nullable|string|max:50',
                'insurance_value' => 'nullable|numeric|max:9999999999',
                'additional_fees_currency' => 'nullable|string|max:50',
                'additional_fees_value' => 'nullable|numeric|max:9999999999',
                'invoice' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
                'packing_list' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
                'manifest' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
            ]);
            $validatedData['feri_type'] = 'regional';
        }

        // Handle file uploads and update JSON
        $documentUploads = json_decode($feriApp->documents_upload ?? '{}', true);

        foreach ($fileFields as $fileField) {
            if ($request->hasFile($fileField)) {
                // Delete previous file if exists
                if (!empty($documentUploads[$fileField]) && Storage::disk('private')->exists($documentUploads[$fileField])) {
                    Storage::disk('private')->delete($documentUploads[$fileField]);
                }
                $file = $request->file($fileField);
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('feri_documents', $filename, 'private');
                $documentUploads[$fileField] = $path;
            }
        }
        $validatedData['documents_upload'] = json_encode($documentUploads);

        // Remove file fields so they are not inserted as columns
        unset($validatedData['invoice'], $validatedData['manifest'], $validatedData['packing_list']);

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

        // ===================================
        // ===================================

        $r = $feriApp;
        $transporter = Auth::user();
        $company = Company::where('type', 'vendor')->first();
        $vendors = User::where('company', $company->id)->where('role', 'vendor')->get();

        if ($vendors->count() > 0) {
            $mainVendor = $vendors->first(); // Primary recipient
            $ccEmails = $vendors->skip(1)->pluck('email')->filter()->all(); // Remove nulls

            Mail::to($mainVendor->email)->cc($ccEmails)->queue(new Approval($r, $mainVendor, $transporter));
        }

        // ===================================
        // ===================================

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

        $reason = chats::create([
            'user_id' => $user->id, // Current logged-in user ID
            'application_id' => $id, // Application ID from the route parameter
            'read' => 0, // Default to unread
            'message' => $validatedData['message'], // Sanitized message
            'del' => 0, // Default to not deleted
        ]);

        // Check for rejection logic
        if ($request->has('rejection') && $request->input('rejection') == 1) {
            $feriApp = feriApp::findOrFail($id);
            if ($feriApp->status >= 3) {
                $feriApp->status = 6;
                $feriApp->save();
            }

            // ================
            // ================

            $r = $feriApp;
            $reason = $reason->message;
            $transporter = Auth::user();
            $company = Company::where('type', 'vendor')->first();
            $vendors = User::where('company', $company->id)->where('role', 'vendor')->get();
            // dd($r);

            if ($vendors->count() > 0) {
                $mainVendor = $vendors->first(); // Primary recipient
                $ccEmails = $vendors->skip(1)->pluck('email')->filter()->all(); // Remove nulls

                Mail::to($mainVendor->email)->cc($ccEmails)->queue(new Rejection($r, $mainVendor, $transporter, $reason));
            }

            // ================
            // ================
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
        if ($feriApp->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin'])) {
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
        if ($feriApp->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin'])) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure the chat belongs to the authenticated user or is authorized
        if ($chat->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin'])) {
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

        if ($feriApp->user_id != $user->id) {
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
        $records = FormTemplate::with('user')->where('company_id', Auth::user()->company)->orderBy('created_at', 'asc')->get();

        // If no data found, set $feris to 0
        if ($feris->isEmpty()) {
            $feris = 0;
        }
        return view('transporter.dashboard', compact('feris', 'records'));
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
        return view('transporter.calculator', compact('records', 'rates'));
    }

    public function importFeriApps(Request $request)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        if (!Auth::user()->email_verified_at) {
            return view('auth.verify-email');
        }

        // Validate all file fields
        $validatedData = $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:20480',
            'attachments.*' => 'nullable|file|max:20480',
        ]);

        // $rows = Excel::toArray([], $request->file('excel_file'))[0];
        // // Skip the header row
        // $rows = array_slice($rows, 1);

        // $attachments = $request->file('attachments', []);
        // dd($rows);

        // if (count($attachments) !== count($rows)) {
        //     return back()->withErrors(['attachments' => 'Each Excel row must have a matching file.']);
        // }

        $rows = Excel::toArray([], $request->file('excel_file'))[0];

        // Skip the header row
        $rows = array_slice($rows, 1);

        // Filter out completely empty rows
        $rows = array_filter($rows, function ($row) {
            // Remove the row if all values are null, empty strings, or whitespace
            return array_filter($row, function ($value) {
                return trim($value) !== '';
            });
        });

        // Re-index the array to maintain consistent indexing
        $rows = array_values($rows);

        $attachments = $request->file('attachments', []);

        if (count($attachments) !== count($rows)) {
            return back()->withErrors(['attachments' => 'Each Excel row must have a matching file.']);
        }

        $documentUploads = [];

        foreach ($attachments as $index => $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('feri_documents', $filename, 'private');
            $documentUploads[$index] = $path;
        }

        // Handle file uploads (same for all imported rows)
        // $documentUploads = [];
        // foreach ($fileFields as $fileField) {
        //     if ($request->hasFile($fileField)) {
        //         $file = $request->file($fileField);
        //         $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        //         $path = $file->storeAs('feri_documents', $filename, 'private');
        //         $documentUploads[$fileField] = $path;
        //     }
        // }

        $rows = Excel::toArray([], $request->file('excel_file'))[0];

        if (empty($rows) || count($rows) < 2) {
            return back()->withErrors(['excel_file' => 'Excel file is empty or missing data.']);
        }

        $headers = array_map('trim', $rows[0]);

        // Normalize and map user-friendly headers to DB column names
        $headerMap = [
            'weight (gw)' => 'weight',
            'volume (net)' => 'volume',
        ];

        // Convert headers to lowercase for safer matching
        foreach ($headers as &$header) {
            $lower = strtolower($header);
            if (isset($headerMap[$lower])) {
                $header = $headerMap[$lower];
            }
        }
        unset($header);

        $imported = 0;
        $errors = [];

        foreach (array_slice($rows, 1) as $rowIndex => $row) {
            // Stop if first column is empty
            if (empty($row[0])) {
                break;
            }

            //each row separate file
            $rowSpecificFile = $documentUploads[$rowIndex];

            $data = array_combine($headers, $row);

            // --- Forgiving conversion for common fields ---
            $intFields = ['weight', 'quantity'];
            $floatFields = ['freight_value', 'fob_value', 'insurance_value', 'additional_fees_value', 'volume'];
            $dateFields = ['arrival_date'];
            $stringFields = ['company_ref', 'po', 'validate_feri_cert', 'entry_border_drc', 'final_destination', 'arrival_station', 'truck_details', 'importer_name', 'cf_agent', 'exporter_name', 'freight_currency', 'instructions', 'manifest_no', 'occ_bivac', 'fob_currency', 'incoterm', 'insurance_currency', 'additional_fees_currency', 'importer_phone', 'importer_email', 'importer_address', 'exporter_address', 'importer_details', 'exporter_phone', 'exporter_email', 'cf_agent_contact', 'hs_code', 'package_type', 'cargo_origin', 'cargo_description'];

            foreach ($intFields as $field) {
                if (isset($data[$field]) && $data[$field] !== '') {
                    if (is_numeric($data[$field])) {
                        $data[$field] = intval($data[$field]);
                    }
                }
            }
            foreach ($floatFields as $field) {
                if (isset($data[$field]) && $data[$field] !== '') {
                    if (is_numeric($data[$field])) {
                        $data[$field] = floatval($data[$field]);
                    }
                }
            }

            foreach ($dateFields as $field) {
                if (isset($data[$field]) && !empty($data[$field])) {
                    // If it's a number, treat as Excel serial
                    if (is_numeric($data[$field])) {
                        $data[$field] = ExcelDate::excelToDateTimeObject($data[$field])->format('Y-m-d');
                    } else {
                        // Try strtotime first
                        $timestamp = strtotime($data[$field]);
                        if ($timestamp !== false) {
                            $data[$field] = date('Y-m-d', $timestamp);
                        } else {
                            // Try Carbon with common formats
                            $formats = [
                                'd-m-Y', // 31-12-2025
                                'd/m/Y', // 31/12/2025
                                'm/d/Y', // 12/31/2025 (common in US)
                                'Y-m-d', // 2025-12-31 (ISO format)
                                'd.m.Y', // 31.12.2025 (used in parts of Europe)
                                'Y/m/d', // 2025/12/31
                                'n/j/Y', // 12/3/2025
                                'j/n/Y', // 3/12/2025
                                'm-d-Y', // 12-31-2025
                                'd-m-y', // 31-12-25 (2-digit year)
                            ];
                            $parsed = false;
                            foreach ($formats as $format) {
                                try {
                                    $carbon = Carbon::createFromFormat($format, $data[$field]);
                                    $data[$field] = $carbon->format('Y-m-d');
                                    $parsed = true;
                                    break;
                                } catch (\Exception $e) {
                                    // Try next format
                                }
                            }
                            if (!$parsed) {
                                // Leave as is, validation will catch it
                            }
                        }
                    }
                }
            }

            foreach ($stringFields as $field) {
                if (isset($data[$field])) {
                    $data[$field] = trim((string) $data[$field]);
                }
            }

            // Convert transporter_company name to ID
            if (!empty($data['transporter_company'])) {
                $company = Company::where('name', trim($data['transporter_company']))->first();
                if ($company) {
                    $data['transporter_company'] = $company->id;
                } else {
                    $errors[] = 'Row ' . ($rowIndex + 2) . ': Transporter company "' . $data['transporter_company'] . '" not in System companies list.';
                    continue;
                }
            }

            // Add user_id, status, and documents_upload
            $data['user_id'] = Auth::id();
            $data['status'] = 1;
            // $data['documents_upload'] = json_encode($rowSpecificFile);
            $data['documents_upload'] = json_encode(['customs' => $rowSpecificFile]);

            // Determine type and validation rules (same as feriApp)
            $type = strtolower(trim($data['feri_type'] ?? ($data['type'] ?? '')));
            if ($type === 'continuance') {
                $rules = [
                    'company_ref' => 'nullable|string|max:255',
                    'po' => 'nullable|string|max:100',
                    'validate_feri_cert' => 'nullable|string|max:255',
                    'entry_border_drc' => 'required|string|max:255',
                    'arrival_date' => 'required|date|after_or_equal:today',
                    'final_destination' => 'required|string|max:255',
                    'customs_decl_no' => 'nullable|string|max:255',
                    'arrival_station' => 'required|string|max:255',
                    'truck_details' => 'required|string|max:255',
                    'transporter_company' => 'required|integer|exists:companies,id',
                    'weight' => 'required|numeric|min:1',
                    'quantity' => 'required|numeric|max:9999999999',
                    'volume' => 'required|numeric|max:9999999999',
                    'importer_name' => 'required|string|max:255',
                    'cf_agent' => 'required|string|max:255',
                    'exporter_name' => 'required|string|max:255',
                    'freight_currency' => 'nullable|string|max:50',
                    'freight_value' => 'nullable|numeric|max:9999999999',
                    'fob_value' => 'nullable|numeric|max:9999999999',
                    'insurance_value' => 'nullable|numeric|max:9999999999',
                    'instructions' => 'nullable|string',
                ];
                // Set all other fields to 0
                $allFields = ['transport_mode', 'importer_phone', 'importer_email', 'importer_address', 'exporter_address', 'importer_details', 'exporter_phone', 'exporter_email', 'cf_agent_contact', 'hs_code', 'package_type', 'cargo_origin', 'cargo_description', 'manifest_no', 'occ_bivac', 'fob_currency', 'incoterm', 'insurance_currency', 'additional_fees_currency', 'additional_fees_value'];
                foreach ($allFields as $field) {
                    if (!isset($data[$field])) {
                        $data[$field] = 0;
                    }
                }
                $data['feri_type'] = 'continuance';
            } elseif ($type === 'regional') {
                $rules = [
                    'transport_mode' => 'required|string|max:255',
                    'transporter_company' => 'required|integer|exists:companies,id',
                    'feri_type' => 'required|string|max:255',
                    'entry_border_drc' => 'required|string|max:255',
                    'truck_details' => 'required|string|max:255',
                    'arrival_station' => 'required|string|max:255',
                    'arrival_date' => 'required|date|after_or_equal:today',
                    'final_destination' => 'required|string|max:255',
                    'importer_name' => 'required|string|max:255',
                    'importer_phone' => 'required|string|max:20',
                    'importer_email' => 'nullable|string|max:255',
                    'importer_address' => 'nullable|string|max:255',
                    'exporter_address' => 'nullable|string|max:255',
                    'importer_details' => 'nullable|string|max:255',
                    'fix_number' => 'nullable|string|max:255',
                    'exporter_name' => 'required|string|max:255',
                    'exporter_phone' => 'required|string|max:20',
                    'exporter_email' => 'nullable|string|max:255',
                    'cf_agent' => 'required|string|max:255',
                    'cf_agent_contact' => 'required|string|max:255',
                    'cargo_description' => 'required|string|max:255',
                    'hs_code' => 'required|string|max:100',
                    'package_type' => 'required|string|max:255',
                    'weight' => 'required|numeric|min:1',
                    'quantity' => 'required|numeric|max:9999999999',
                    'volume' => 'required|numeric|max:9999999999',
                    'company_ref' => 'nullable|string|max:255',
                    'cargo_origin' => 'nullable|string|max:255',
                    'customs_decl_no' => 'nullable|string|max:255',
                    'manifest_no' => 'nullable|string|max:255',
                    'occ_bivac' => 'nullable|string|max:255',
                    'instructions' => 'nullable|string',
                    'fob_currency' => 'nullable|string|max:50',
                    'fob_value' => 'nullable|numeric|max:9999999999',
                    'po' => 'nullable|string|max:100',
                    'incoterm' => 'nullable|string|max:50',
                    'freight_currency' => 'nullable|string|max:50',
                    'freight_value' => 'nullable|numeric|max:9999999999',
                    'insurance_currency' => 'nullable|string|max:50',
                    'insurance_value' => 'nullable|numeric|max:9999999999',
                    'additional_fees_currency' => 'nullable|string|max:50',
                    'additional_fees_value' => 'nullable|numeric|max:9999999999',
                ];
                $data['feri_type'] = 'regional';
            } else {
                $errors[] = 'Row ' . ($rowIndex + 2) . ": Invalid or missing feri_type (must be 'continuance' or 'regional').";
                continue;
            }

            // Validate row
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $errors[] = 'Row ' . ($rowIndex + 2) . ': ' . implode(', ', $validator->errors()->all());
                continue;
            }

            // Insert into DB
            try {
                $r = feriApp::create($data);
                $imported++;
                $transporter = Auth::user();
                $company = Company::where('type', 'vendor')->first();

                $vendors = User::where('company', $company->id)->where('role', 'vendor')->get();

                if ($vendors->count() > 0) {
                    $mainVendor = $vendors->first(); // Primary recipient
                    $ccEmails = $vendors->skip(1)->pluck('email')->filter()->all(); // Remove nulls

                    Mail::to($mainVendor->email)->cc($ccEmails)->queue(new NewAppMail($r, $mainVendor, $transporter));
                }
            } catch (\Exception $e) {
                $errors[] = 'Row ' . ($rowIndex + 2) . ': Failed to insert. ' . $e->getMessage();
            }
        }

        if ($imported > 0) {
            $msg = "$imported FERI application(s) imported successfully.";
            if ($errors) {
                $msg .= ' Some rows had errors.';
            }
            return back()->with([
                'status' => 'success',
                'message' => $msg,
                'errors' => $errors,
            ]);
        } else {
            return back()->withErrors(['excel_file' => 'No valid rows imported. Errors: ' . implode(' | ', $errors)]);
        }
    }

    public function importapply()
    {
        //if user has not verified email
        if (!Auth::user()->email_verified_at) {
            return view('auth.verify-email');
        }

        // Fetch all records from the Company table
        $records = Company::all();

        // Pass the records to the view
        return view('transporter.importapply', compact('records'));
    }

    public function downloadFeriExcelTemplate()
    {
        // Fetch dropdown options from DB
        $apptyper = ['regional', 'continuance'];
        $transportModes = ['Road', 'Air', 'Maritime', 'Rail'];
        $companies = Company::where('type', 'transporter')->pluck('name')->toArray();
        $entryBorders = ['Kasumbalesa', 'Mokambo', 'Sakania'];
        $finalDestinations = ['Likasi DRC', 'Lubumbashi DRC', 'Kolwezi DRC', 'Tenke DRC', 'Kisanfu DRC', 'Lualaba DRC', 'Pumpi DRC'];
        $cfAgents = ['AGL', 'CARGO CONGO', 'CONNEX', 'African Logistics', 'Afritac', 'Amicongo', 'OLA', 'Aristote', 'Bollore', 'Brasimba', 'Brasimba S.A', 'COSMOS', 'Chemaf', 'Comexas Afrique', 'Comexas', 'DCG', 'Evele & Co', 'Gecotrans', 'Global Logistics', 'Malabar', 'Polytra', 'Spedag', 'Tradecorp', 'Trade Service'];
        $currencies = ['USD', 'EUR', 'TZS', 'ZAR', 'AOA'];
        $incoterms = ['CFR', 'CIF', 'CIP', 'CPT', 'DAF', 'DAP', 'DAT', 'DDP', 'DDU', 'DEQ', 'DES', 'DPU', 'EXW', 'FAS', 'FCA', 'FOB'];

        // Define headers (must match your import logic)
        $headers = ['feri_type', 'transport_mode', 'transporter_company', 'entry_border_drc', 'truck_details', 'quantity', 'weight (GW)', 'volume (Net)', 'final_destination', 'validate_feri_cert', 'arrival_station', 'arrival_date', 'importer_name', 'importer_phone', 'importer_email', 'importer_address', 'importer_details', 'fix_number', 'exporter_name', 'exporter_phone', 'exporter_email', 'exporter_address', 'cf_agent', 'cf_agent_contact', 'cargo_description', 'hs_code', 'package_type', 'company_ref', 'po', 'cargo_origin', 'customs_decl_no', 'manifest_no', 'occ_bivac', 'instructions', 'fob_currency', 'fob_value', 'incoterm', 'freight_currency', 'freight_value', 'insurance_currency', 'insurance_value', 'additional_fees_currency', 'additional_fees_value'];

        // Sample row (edit as needed)
        $sampleRow = [
            $apptyper[0], // feri_type
            $transportModes[0], // transport_mode
            $companies[0] ?? '', // transporter_company
            $entryBorders[0], // entry_border_drc
            'XXX XXX', // truck_details
            30, // quantity
            30000, // weight
            30.000, // volume
            $finalDestinations[0], // final_destination
            'XXXX', // validate_feri_cert
            'Lubumbashi', // arrival_station
            now()->addDays(7)->format('m-d-Y'),
            // arrival_date,
            'Importer Name', // importer_name
            '0650000000', // importer_phone
            'importer@email.com', // importer_email
            'Importer Address', // importer_address
            'Importer Details', // importer_details
            'N/A', // fix_number
            'Exporter Name', // exporter_name
            '0650000000', // exporter_phone
            'exporter@email.com', // exporter_email
            'Exporter Address', // exporter_address
            $cfAgents[0], // cf_agent
            '0650000000', // cf_agent_contact
            'Sulphur', // cargo_description
            '23500000', // hs_code
            'Bags', // package_type
            'XXXXX', // company_ref
            'TBS', // po
            'USA', // cargo_origin
            'XXXXX', // customs_decl_no
            'XXXXX', // manifest_no
            'XXXXXX', // occ_bivac
            'No Additional Comments', // instructions
            $currencies[0], // fob_currency
            0, // fob_value
            $incoterms[0], // incoterm
            $currencies[0], // freight_currency
            0, // freight_value
            $currencies[0], // insurance_currency
            0, // insurance_value
            $currencies[0], // additional_fees_currency
            0, // additional_fees_value
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        foreach ($headers as $col => $header) {
            $sheet->setCellValueByColumnAndRow($col + 1, 1, $header);
        }

        // Set sample data
        foreach ($sampleRow as $col => $value) {
            $sheet->setCellValueByColumnAndRow($col + 1, 2, $value);
        }

        // Add dropdowns for select columns
        $dropdowns = [
            'A' => $apptyper, // feri_type
            'B' => $transportModes, // transport_mode
            'C' => $companies, // transporter_company
            'D' => $entryBorders, // entry_border_drc
            'I' => $finalDestinations, // final_destination
            'W' => $cfAgents, // cf_agent
            'AI' => $currencies, // fob_currency
            'AK' => $incoterms, // incoterm
            'AL' => $currencies, // freight_currency
            'AN' => $currencies, // insurance_currency
            'AP' => $currencies, // additional_fees_currency
        ];

        foreach ($dropdowns as $colLetter => $options) {
            if (empty($options)) {
                continue;
            }
            $validation = $sheet->getCell($colLetter . '2')->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('"' . implode(',', $options) . '"');
            // Optionally, apply to more rows if you want
        }

        // Set arrival_date column to date format
        // $sheet->getStyle('G2')->getNumberFormat()->setFormatCode('yyyy-mm-dd');

        // Download as response
        $writer = new Xlsx($spreadsheet);
        $filename = 'feri_import_template.xlsx';

        return new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment;filename="feri_import_template.xlsx"',
                'Cache-Control' => 'max-age=0',
            ],
        );
    }

    /*
    |--------------------------------------------------------------------------
    |  PUBLIC METHODS
    |--------------------------------------------------------------------------
    */

    public function storetamplate(Request $request)
    {
        $type = $request->type; // regional or continuance

        $rules = $this->getRulesByType($type);

        $validated = $request->validate($rules);

        // Save actual FERI application here...
        // Feri::create($validated);

        return back()->with('success', 'Application submitted successfully.');
    }


    public function saveTemplate(Request $request)
    {
        $type = $request->type;
        // dd($request);

        // Get original strict rules
        $rules = $this->getRulesByType($type);

        // Remove required dynamically
        $templateRules = $this->removeRequired($rules);

        $validated = $request->validate($templateRules);

        FormTemplate::create([
            'user_id'   => Auth::user()->id,
            'company_id'   => Auth::user()->company,
            'name'      => $request->template_name,
            'type'      => $type,
            'form_data' => $validated
        ]);

        // return back()->with('success', 'Template saved successfully.');
        return redirect()->route('transporter.listtemplate')->with([
            'message' => 'Template Saved successfully.',
            'status' => 'success'
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    |  RULE ENGINE
    |--------------------------------------------------------------------------
    */

    private function getRulesByType(string $type): array
    {
        if ($type === 'continuance') {
            return [
                'type' => 'nullable|string|max:255',
                'company_ref' => 'nullable|string|max:255',
                'po' => 'nullable|string|max:100',
                'validate_feri_cert' => 'nullable|string|max:255',
                'entry_border_drc' => 'required|string|max:255',
                'arrival_date' => 'required|date|after_or_equal:today',
                'final_destination' => 'required|string|max:255',
                'customs_decl_no' => 'nullable|string|max:255',
                'arrival_station' => 'required|string|max:255',
                'truck_details' => 'required|string|max:255',
                'transporter_company' => 'required|integer|exists:companies,id',
                'weight' => 'required|numeric|min:1',
                'quantity' => 'required|numeric|max:9999999999',
                'volume' => 'required|numeric|max:9999999999',
                'importer_name' => 'required|string|max:255',
                'cf_agent' => 'required|string|max:255',
                'exporter_name' => 'required|string|max:255',
                'freight_currency' => 'nullable|string|max:50',
                'freight_value' => 'nullable|numeric|max:9999999999',
                'fob_value' => 'nullable|numeric|max:9999999999',
                'insurance_value' => 'nullable|numeric|max:9999999999',
                'instructions' => 'nullable|string',
            ];
        }

        if ($type === 'regional') {
            return [
                'type' => 'nullable|string|max:255',
                'transport_mode' => 'required|string|max:255',
                'transporter_company' => 'required|integer|exists:companies,id',
                'feri_type' => 'required|string|max:255',
                'entry_border_drc' => 'required|string|max:255',
                'truck_details' => 'required|string|max:255',
                'arrival_station' => 'required|string|max:255',
                'arrival_date' => 'required|date|after_or_equal:today',
                'final_destination' => 'required|string|max:255',
                'importer_name' => 'required|string|max:255',
                'importer_phone' => 'required|string|max:20',
                'importer_email' => 'nullable|string|max:255',
                'importer_address' => 'nullable|string|max:255',
                'exporter_address' => 'nullable|string|max:255',
                'importer_details' => 'nullable|string|max:255',
                'fix_number' => 'nullable|string|max:255',
                'exporter_name' => 'required|string|max:255',
                'exporter_phone' => 'required|string|max:20',
                'exporter_email' => 'nullable|string|max:255',
                'cf_agent' => 'required|string|max:255',
                'cf_agent_contact' => 'required|string|max:255',
                'cargo_description' => 'required|string|max:255',
                'hs_code' => 'required|string|max:100',
                'package_type' => 'required|string|max:255',
                'weight' => 'required|numeric|min:1',
                'quantity' => 'required|numeric|max:9999999999',
                'volume' => 'required|numeric|max:9999999999',
                'company_ref' => 'nullable|string|max:255',
                'cargo_origin' => 'nullable|string|max:255',
                'customs_decl_no' => 'nullable|string|max:255',
                'manifest_no' => 'nullable|string|max:255',
                'occ_bivac' => 'nullable|string|max:255',
                'instructions' => 'nullable|string',
                'fob_currency' => 'nullable|string|max:50',
                'fob_value' => 'nullable|numeric|max:9999999999',
                'po' => 'nullable|string|max:100',
                'incoterm' => 'nullable|string|max:50',
                'freight_currency' => 'nullable|string|max:50',
                'freight_value' => 'nullable|numeric|max:9999999999',
                'insurance_currency' => 'nullable|string|max:50',
                'insurance_value' => 'nullable|numeric|max:9999999999',
                'additional_fees_currency' => 'nullable|string|max:50',
                'additional_fees_value' => 'nullable|numeric|max:9999999999',
            ];
        }

        return [];
    }


    private function removeRequired(array $rules): array
    {
        foreach ($rules as $field => $rule) {

            $rules[$field] = collect(explode('|', $rule))
                ->reject(fn ($r) => $r === 'required')
                ->prepend('nullable')
                ->unique()
                ->implode('|');
        }

        return $rules;
    }

    public function edittemplate($id)
    {

        $template = FormTemplate::where('company_id', Auth::user()->company)
            ->findOrFail($id);
        // dd($template->form_data);

        $formData = $template->form_data;

        $records = Company::where('type', 'transporter')->orderBy('name')->get();

        return view('transporter.edittemplate', compact('template', 'formData', 'records'));
    }

    public function updateTemplate(Request $request, $id)
    {
        if(Auth::user()->id !== FormTemplate::findOrFail($id)->user_id){
            abort(403, 'Unauthorized action.');
        }

        // dd($request);
        $template = FormTemplate::findOrFail($id);

        $type = $request->type;
        // dd($type);

        $rules = $this->getRulesByType($type);

        // Relax rules for template editing
        $templateRules = $this->removeRequired($rules);

        $validated = $request->validate($templateRules);

        $template->update([
            'name' => $request->template_name,
            'type' => $type,
            'form_data' => $validated
        ]);

        return redirect()->back()->with([
            'message' => 'Template updated successfully.',
            'status' => 'success'
            ]);
    }

    public function destroyTemplate($id)
    {
        
        if(Auth::user()->id !== FormTemplate::findOrFail($id)->user_id){
            abort(403, 'Unauthorized action.');
        }

        FormTemplate::destroy($id);
        return redirect('transporter/template/list')->with([
            'status' => 'success',
            'message' => 'Template Deleted successfully!.',
        ]);
    }


}
