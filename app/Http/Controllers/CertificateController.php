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
        if ($feriApp->user_id !== auth()->id() && !in_array(auth()->user()->role, ['vendor', 'admin'])) {
            abort(403, 'Unauthorized access.');
        }
        // dd($certificate);
        // exit();

        // Get the file path from the certificate record
        $filePath = $certificate->file;

        // Check if the file exists in storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Download the file
        // return Storage::disk('public')->download($filePath);
        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function downloaddraft($id)
    {
        // Fetch the latest certificate for the given application ID
        $certificate = Certificate::where('application_id', $id)->where('type', 'draft')->latest()->firstOrFail();

        // Check if the logged-in user is the owner of the feriApp or has the role of 'vendor' or 'admin'
        $feriApp = feriApp::findOrFail($id);
        if ($feriApp->user_id !== auth()->id() && !in_array(auth()->user()->role, ['vendor', 'admin'])) {
            abort(403, 'Unauthorized access.');
        }
        // dd($certificate);
        // exit();

        // Get the file path from the certificate record
        $filePath = $certificate->file;

        // Check if the file exists in storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Download the file
        // return Storage::disk('public')->download($filePath);
        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function downloadfile($id, $type)
    {
        // Fetch the feriApp record by ID
        $feriApp = feriApp::findOrFail($id);

        // Check if the logged-in user is the owner of the feriApp or has the role of 'vendor' or 'admin'
        if ($feriApp->user_id !== auth()->id() && !in_array(auth()->user()->role, ['vendor', 'admin'])) {
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
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Download the file
        return response()->download(storage_path('app/public/' . $filePath));
    }

    // public function downloadinvoice($id)
    // {
    //      // Fetch the feriApp record by ID
    //     //  $feriApp = Invoice::findOrFail($id);
    //      $cert = Certificate::where('application_id', $id)->where('type', 'draft')->latest()->firstOrFail();
    //      $invoice = Invoice::where('cert_id', $cert->id)->latest()->firstOrFail();
    //     // dd($invoice);
    //     $pdf = Pdf::loadView('layouts.theinvoice', ['invoice' => $invoice]);
    //     // dd($invoice->id);
    //     return $pdf->download("{$invoice->customer_trip_no}.pdf");
    // }

    public function downloadinvoice($id)
    {
        // Get the draft certificate for this application
        $cert = Certificate::where('application_id', $id)->where('type', 'draft')->latest()->firstOrFail();

        // Get the invoice related to this certificate
        $invoice = Invoice::where('cert_id', $cert->id)->latest()->firstOrFail();

        // Fetch the related feriApp record
        $feriApp = feriApp::findOrFail($id);

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

        return $pdf->download('ALM_STATEMENT.pdf');
    }
}
