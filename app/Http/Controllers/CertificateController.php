<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\feriApp;
use App\Models\Invoice;
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
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function download($id)
    {
        // Fetch the latest certificate for the given application ID
        $certificate = Certificate::where('application_id', $id)->where('type', 'certificate')->latest()->firstOrFail();

        // Check if the logged-in user is the owner of the feriApp or has the role of 'vendor' or 'admin'
        $feriApp = feriApp::findOrFail($id);
        $user = auth()->user();

        if (!$user || (!in_array($user->role, ['admin', 'vendor']) && !($user->role === 'transporter' && $feriApp->user_id == $user->id))) {
            abort(403, 'Unauthorized access.');
        }
        // dd($certificate);
        // exit();

        // Get the file path from the certificate record
        $filePath = $certificate->file;

        // Check if the file exists in storage
        if (!Storage::disk('private')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Download the file
        // return Storage::disk('private')->download($filePath);
        return response()->download(storage_path('app/private/' . $filePath));
    }

    public function downloaddraft($id)
    {
        // Fetch the latest certificate for the given application ID
        $certificate = Certificate::where('application_id', $id)->where('type', 'draft')->latest()->firstOrFail();

        // Check if the logged-in user is the owner of the feriApp or has the role of 'vendor' or 'admin'
        $feriApp = feriApp::findOrFail($id);
        $user = auth()->user();

        if (!$user || (!in_array($user->role, ['admin', 'vendor']) && !($user->role === 'transporter' && $feriApp->user_id == $user->id))) {
            abort(403, 'Unauthorized access.');
        }
        // dd($certificate);
        // exit();

        // Get the file path from the certificate record
        $filePath = $certificate->file;

        // Check if the file exists in storage
        if (!Storage::disk('private')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Download the file
        // return Storage::disk('private')->download($filePath);
        return response()->download(storage_path('app/private/' . $filePath));
    }

    public function downloadfile($id, $type)
    {
        // Fetch the feriApp record by ID
        $feriApp = feriApp::findOrFail($id);

        // Check if the logged-in user is the owner of the feriApp or has the role of 'vendor' or 'admin'
        $user = auth()->user();

        if (!$user || (!in_array($user->role, ['admin', 'vendor']) && !($user->role === 'transporter' && $feriApp->user_id == $user->id))) {
            abort(403, 'Unauthorized access.');
        }

        // Decode the JSON column to get all file paths
        $documents = json_decode($feriApp->documents_upload ?? '[]', true);

        // Check if the requested type exists
        if (!isset($documents[$type])) {
            abort(404, 'Requested file type not found.');
        }

        $filePath = $documents[$type];

        // Check if the file exists in storage
        if (!Storage::disk('private')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Download the file
        return response()->download(storage_path('app/private/' . $filePath));
    }

    public function downloadinvoice($id)
    {
        // Get the draft certificate for this application
        $cert = Certificate::where('application_id', $id)->where('type', 'draft')->latest()->firstOrFail();

        // Get the invoice related to this certificate
        $invoice = Invoice::where('cert_id', $cert->id)->latest()->firstOrFail();

        // Fetch the related feriApp record
        $feriApp = feriApp::findOrFail($id);
        $user = auth()->user();

        if (!$user || (!in_array($user->role, ['admin', 'vendor']) && !($user->role === 'transporter' && $feriApp->user_id == $user->id))) {
            abort(403, 'Unauthorized access.');
        }

        // Fetch the applicant's name
        $applicantName = User::find($feriApp->user_id)?->name ?? 'N/A';

        // Pass $invoice, $feriApp, and $applicantName to the view
        $pdf = Pdf::loadView('layouts.theinvoice', [
            'invoice' => $invoice,
            'feriapp' => $feriApp,
            'applicantName' => Str::title($applicantName),
        ]);

        return $pdf->download("{$invoice->customer_trip_no}.pdf");
    }

    public function downloadinvoice2($id)
    {
        // Get the draft certificate for this application
        $cert = Certificate::where('id', $id)->where('type', 'draft')->latest()->firstOrFail();

        // Get the invoice related to this certificate
        $invoice = Invoice::where('cert_id', $cert->id)->latest()->firstOrFail();

        // Fetch the related feriApp record
        $feriApp = feriApp::where('id', $cert->application_id)->firstOrFail();
        $user = auth()->user();

        if (!$user || (!in_array($user->role, ['admin', 'vendor']) && !($user->role === 'transporter' && $feriApp->user_id == $user->id))) {
            abort(403, 'Unauthorized access.');
        }
        // Fetch the applicant's name
        $applicantName = User::find($feriApp->user_id)?->name ?? 'N/A';

        // Pass $invoice, $feriApp, and $applicantName to the view
        $pdf = Pdf::loadView('layouts.theinvoice', [
            'invoice' => $invoice,
            'feriapp' => $feriApp,
            'applicantName' => Str::title($applicantName),
        ]);

        return $pdf->download("{$invoice->customer_trip_no}.pdf");
    }

    public function statement_download(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        // Fetch the invoices within the specified date range
        $records = Invoice::whereBetween('invoice_date', [$validatedData['start'], $validatedData['end']])->get();

        if ($records->isEmpty()) {
            return back()->with([
                'status' => 'error',
                'message' => 'no invoice to work with for at that date range',
            ]);
        }

        // Get all certificates referenced by invoices
        $certIds = $records->pluck('cert_id')->unique()->filter();
        $certificates = Certificate::whereIn('id', $certIds)->get()->keyBy('id');

        // Get all application_ids from those certificates
        $applicationIds = $certificates->pluck('application_id')->unique()->filter();
        $applications = feriApp::whereIn('id', $applicationIds)->get()->keyBy('id');

        // Filter invoices: only those whose certificate's application has status = 5
        $records = $records
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
        $records->transform(function ($invoice) {
            $feriQty = (float) ($invoice->feri_quantity ?? 0);
            $feriUnits = (float) ($invoice->feri_units ?? 0);
            $codQty = (float) ($invoice->cod_quantities ?? 0);
            $codUnits = (float) ($invoice->cod_units ?? 0);
            $euroRate = (float) ($invoice->euro_rate ?? 1);
            $transporterQty = (float) ($invoice->transporter_quantity ?? 0);

            // Calculating the amounts
            $feriAmount = $feriQty * $feriUnits;
            $codAmount = $codQty * $codUnits;
            $upTotal = $feriAmount + $codAmount;
            $transporterAmount = $transporterQty * 0.018;
            $grandTotal = $transporterAmount + $upTotal * $euroRate - 5;

            $invoice->amount = $grandTotal;
            return $invoice;
        });

        // Pass $invoice, $feriApp, and $applicantName to the view
        $pdf = Pdf::loadView('layouts.thestatement', [
            'invoice' => $records,
        ]);

        // dd($records);

        return $pdf->download('STATEMENT.pdf');
    }
}
