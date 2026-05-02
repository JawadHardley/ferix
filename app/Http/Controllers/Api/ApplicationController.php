<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\feriApp;
use App\Models\Certificate;
use App\Models\Invoice;
use App\Models\Company;
use App\Models\User;
use App\Models\chats;
use App\Mail\NewAppMail;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
        try {
            $feriType = $request->feri_type;
            
            if ($feriType === 'continuance') {
                return $this->storeContinuance($request);
            } else {
                return $this->storeRegional($request);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create application',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    private function storeContinuance(Request $request)
    {
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
        $allFields = ['transport_mode', 'importer_phone', 'importer_email', 'importer_address', 'exporter_address', 'importer_details', 'exporter_phone', 'exporter_email', 'cf_agent_contact', 'hs_code', 'package_type', 'cargo_origin', 'cargo_description', 'manifest_no', 'occ_bivac', 'fob_currency', 'incoterm', 'insurance_currency', 'additional_fees_currency', 'additional_fees_value'];
        foreach ($allFields as $field) {
            if (!array_key_exists($field, $validatedData)) {
                $validatedData[$field] = 0;
            }
        }

        // Resolve company name to ID
        $validatedData['transporter_company'] = $this->resolveCompanyId($validatedData['transporter_company']);

        $validatedData['feri_type'] = 'continuance';
        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 1;

        // Handle file uploads
        $documentUploads = $this->handleFileUploads($request);
        $validatedData['documents_upload'] = json_encode($documentUploads);

        $application = feriApp::create($validatedData);
        $this->notifyVendors($application, Auth::user());

        return response()->json([
            'message' => 'Continuance application created successfully',
            'data' => $application
        ], 201);
    }

    private function storeRegional(Request $request)
    {
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
        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 1;

        // Resolve company name to ID
        $validatedData['transporter_company'] = $this->resolveCompanyId($validatedData['transporter_company']);

        // Handle file uploads
        $documentUploads = $this->handleFileUploads($request);
        $validatedData['documents_upload'] = json_encode($documentUploads);

        $application = feriApp::create($validatedData);
        $this->notifyVendors($application, Auth::user());

        return response()->json([
            'message' => 'Regional application created successfully',
            'data' => $application
        ], 201);
    }

    private function handleFileUploads(Request $request)
    {
        $fileFields = ['invoice', 'manifest', 'packing_list', 'customs'];
        $documentUploads = [];

        foreach ($fileFields as $fileField) {
            if ($request->hasFile($fileField)) {
                $file = $request->file($fileField);
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('feri_documents', $filename, 'private');
                $documentUploads[$fileField] = $path;
            }
        }

        return $documentUploads;
    }

    private function resolveCompanyId($companyInput)
    {
        // If it's already numeric, return it
        if (is_numeric($companyInput)) {
            return (int) $companyInput;
        }

        // If it's a string, try to find the company by name
        $company = Company::where('name', $companyInput)->first();
        if ($company) {
            return $company->id;
        }

        // If not found, throw validation error
        throw new \Illuminate\Validation\ValidationException(
            \Illuminate\Support\Facades\Validator::make([], [])->errors(),
            response()->json(['error' => "Company '$companyInput' not found"], 422)
        );
    }

    private function notifyVendors($application, $transporter)
    {
        try {
            $company = Company::where('type', 'vendor')->first();
            if (!$company) return;

            $vendors = User::where('company', $company->id)->where('role', 'vendor')->get();

            if ($vendors->count() > 0) {
                $mainVendor = $vendors->first();
                $ccEmails = $vendors->skip(1)->pluck('email')->filter()->all();

                Mail::to($mainVendor->email)->cc($ccEmails)->queue(new NewAppMail($application, $mainVendor, $transporter));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to notify vendors: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $application = feriApp::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($application);
    }

    public function update(Request $request, $id)
    {
        $application = feriApp::where('user_id', Auth::id())->findOrFail($id);

        if ($application->status != 1) {
            return response()->json([
                'message' => 'Cannot edit application. Status does not allow editing.'
            ], 422);
        }

        $feriType = $application->feri_type;
        
        // Reuse validation rules from store
        if ($feriType === 'continuance') {
            $rules = [
                'company_ref' => 'nullable|string|max:255',
                'po' => 'nullable|string|max:100',
                'validate_feri_cert' => 'nullable|string|max:255',
                'entry_border_drc' => 'required|string|max:255',
                'arrival_date' => 'required|date|after_or_equal:today',
                'final_destination' => 'required|string|max:255',
            ];
        } else {
            $rules = [
                'transport_mode' => 'required|string|max:255',
                'entry_border_drc' => 'required|string|max:255',
                'arrival_date' => 'required|date|after_or_equal:today',
            ];
        }

        $validatedData = $request->validate($rules);
        $application->update($validatedData);

        return response()->json([
            'message' => 'Application updated successfully',
            'data' => $application
        ]);
    }

    public function destroy($id)
    {
        $application = feriApp::where('user_id', Auth::id())->findOrFail($id);
        $application->delete();

        return response()->json(['message' => 'Application deleted successfully']);
    }

    public function getCertificate($id)
    {
        $application = feriApp::where('user_id', Auth::id())->findOrFail($id);
        $certificate = Certificate::where('application_id', $id)->where('type', 'certificate')->latest()->first();

        if (!$certificate) {
            return response()->json(['message' => 'Certificate not found'], 404);
        }

        if ($certificate->file && Storage::disk('private')->exists($certificate->file)) {
            return Storage::disk('private')->download($certificate->file);
        }

        return response()->json(['message' => 'Certificate file not found'], 404);
    }

    public function getInvoice($id)
    {
        $application = feriApp::where('user_id', Auth::id())->findOrFail($id);
        $cert = Certificate::where('application_id', $id)->where('type', 'draft')->latest()->first();

        if (!$cert) {
            return response()->json(['message' => 'Certificate not found'], 404);
        }

        $invoice = Invoice::where('cert_id', $cert->id)->latest()->first();

        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        $app_user = Auth::user();
        $company = Company::where('id', $app_user->company)->first();

        $parts = array_map('trim', explode(',', $company->address ?? ''));

        $pdf = Pdf::loadView('layouts.theinvoice', [
            'invoice' => $invoice,
            'feriapp' => $application,
            'company' => $company,
            'poBox' => $parts[0] ?? null,
            'location1' => $parts[1] ?? null,
            'location2' => $parts[2] ?? null,
        ]);

        return $pdf->download('invoice_' . $id . '.pdf');
    }

    public function getAge($id)
    {
        $application = feriApp::where('user_id', Auth::id())->findOrFail($id);
        $age = Carbon::parse($application->created_at)->diffInDays(Carbon::now());

        return response()->json([
            'application_id' => $id,
            'age_days' => $age,
            'created_at' => $application->created_at,
            'created_at_formatted' => $application->created_at->format('Y-m-d H:i:s')
        ]);
    }
}