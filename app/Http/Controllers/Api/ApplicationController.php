<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\feriApp;
use App\Models\Certificate;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Company;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = feriApp::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($applications);
    }

    public function store(Request $request)
    {
        // Similar validation as in TransporterAuthController@feriApp
        // For brevity, using a simplified version
        $validatedData = $request->validate([
            'feri_type' => 'required|in:regional,continuance',
            'transporter_company' => 'required|string|max:255',
            'entry_border_drc' => 'required|string|max:255',
            'truck_details' => 'required|string|max:255',
            'arrival_station' => 'required|string|max:255',
            'arrival_date' => 'required|date|after_or_equal:today',
            'final_destination' => 'required|string|max:255',
            'importer_name' => 'required|string|max:255',
            'exporter_name' => 'required|string|max:255',
            'cf_agent' => 'required|string|max:255',
            'cargo_description' => 'required|string|max:255',
            'hs_code' => 'required|string|max:100',
            'package_type' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:1',
            'weight' => 'required|numeric|min:1',
            'volume' => 'required|string|max:255',
            // Add other fields as needed
        ]);

        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 1;

        // Handle file uploads
        $fileFields = ['invoice', 'manifest', 'packing_list', 'customs'];
        $documentUploads = [];
        foreach ($fileFields as $fileField) {
            if ($request->hasFile($fileField)) {
                $file = $request->file($fileField);
                $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('feri_documents', $filename, 'private');
                $documentUploads[$fileField] = $path;
            }
        }
        $validatedData['documents_upload'] = json_encode($documentUploads);

        $application = feriApp::create($validatedData);

        return response()->json($application, 201);
    }

    public function show($id)
    {
        $application = feriApp::where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json($application);
    }

    public function update(Request $request, $id)
    {
        $application = feriApp::where('user_id', Auth::id())
            ->findOrFail($id);

        // Add update logic if needed
        // For now, return not implemented
        return response()->json(['message' => 'Update not implemented'], 501);
    }

    public function destroy($id)
    {
        $application = feriApp::where('user_id', Auth::id())
            ->findOrFail($id);

        $application->delete();

        return response()->json(['message' => 'Application deleted']);
    }

    public function getCertificate($id)
    {
        $application = feriApp::where('user_id', Auth::id())
            ->findOrFail($id);

        $certificate = Certificate::where('application_id', $id)->where('type', 'certificate')->latest()->first();

        if (!$certificate) {
            return response()->json(['message' => 'Certificate not found'], 404);
        }

        // Assuming certificate has a file path
        if ($certificate->file && Storage::disk('private')->exists($certificate->file)) {
            return Storage::disk('private')->download($certificate->file);
        }

        return response()->json(['message' => 'Certificate file not found'], 404);
    }

    public function getInvoice($id)
    {
        $application = feriApp::where('user_id', Auth::id())
            ->findOrFail($id);

        // Get the draft certificate for this application
        $cert = Certificate::where('application_id', $id)->where('type', 'draft')->latest()->first();

        if (!$cert) {
            return response()->json(['message' => 'Certificate not found'], 404);
        }

        // Get the invoice related to this certificate
        $invoice = Invoice::where('cert_id', $cert->id)->latest()->first();

        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        // Fetch the applicant's name
        $applicantName = Auth::user()->name;

        // Get user with app
        $app_user = Auth::user();

        // Get company details
        $company = Company::where('id', $app_user->company)->first();

        $parts = array_map('trim', explode(',', $company->address ?? ''));

        $poBox     = $parts[0] ?? null;
        $location1 = $parts[1] ?? null;
        $location2 = $parts[2] ?? null;

        // Generate PDF
        $pdf = Pdf::loadView('layouts.theinvoice', [
            'invoice' => $invoice,
            'feriapp' => $application,
            'company' => $company,
            'poBox' => $poBox,
            'location1' => $location1,
            'location2' => $location2,
        ]);

        return $pdf->download('invoice_' . $id . '.pdf');
    }

    public function getAge($id)
    {
        $application = feriApp::where('user_id', Auth::id())
            ->findOrFail($id);

        $age = Carbon::parse($application->created_at)->diffInDays(Carbon::now());

        return response()->json([
            'application_id' => $id,
            'age_days' => $age,
            'created_at' => $application->created_at,
        ]);
    }
}