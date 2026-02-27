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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        $sanitizedCompanyRef = Str::slug($feriApp->company_ref); // Converts spaces and special chars to hyphens
        $customWords = 'Feri_Certificate';
        $extension = 'pdf';
        $newFileName = "{$customWords}_{$sanitizedCompanyRef}.{$extension}";
        // dd($newFileName);

        // Download the file
        // return Storage::disk('private')->download($filePath);
        return response()->download(storage_path('app/private/' . $filePath), $newFileName);
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

        //Get user with app
        $app_user = User::where('id', $feriApp->user_id)->firstOrFail();

        //Get company details
        $company = Company::where('id', $app_user->company)->firstOrFail();

        $parts = array_map('trim', explode(',', $company->address));

        $poBox     = $parts[0] ?? null;
        $location1 = $parts[1] ?? null;
        $location2 = $parts[2] ?? null;

        // Pass $invoice, $feriApp, and $applicantName to the view
        $pdf = Pdf::loadView('layouts.theinvoice', [
            'invoice' => $invoice,
            'feriapp' => $feriApp,
            'company' => $company,
            'poBox' => $poBox,
            'location1' => $location1,
            'location2' => $location2,
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
            'date' => 'required|string|max:255',
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
        $records->transform(function ($invoice) use ($certificates) {
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

            // $invoice->amount = number_format($grandTotal, 2, '.', ',');
            // $invoice->amount = $grandTotal;

            if ($invoice->invoice_date > '2025-09-09') {
                $invoice->amount = $grandTotal;
            } else {
                $invoice->amount = number_format($grandTotal, 2, '.', ',');
                // $invoice->amount = $grandTotal;
                // dd($invoice->invoice_date);
            }

            // Attach application id as 'appid'
            $cert = $certificates->get($invoice->cert_id);
            $invoice->appid = $cert ? $cert->application_id : null;

            // Attach application PO number as 'po'
            $applications2 = feriApp::where('id', $invoice->appid)->get();
            if (isset($applications2)) {
                $app = $applications2[0]->po;
                $invoice->po = $app;
            }

            return $invoice;
        });

        //attach month name
        $records->month = $request->date;

        // Pass $invoice, $feriApp, and $applicantName to the view
        $pdf = Pdf::loadView('layouts.thestatement', [
            'invoice' => $records,
        ]);

        // dd($records);

        return $pdf->download('STATEMENT.pdf');
    }

    public function getStatusText($statusCode)
    {
        switch ($statusCode) {
            case 1:
                return 'Pending';
            case 2:
                return 'Pending'; // Assuming status 2 is also 'Pending' based on your blade
            case 3:
                return 'Draft Approval';
            case 4:
                return 'In progress';
            case 5:
                return 'Complete';
            case 6:
                return 'Rejected';
            default:
                return 'Unknown';
        }
    }

    // public function exportApplications() {}

    public function exportApplications()
    {
        // 1. Fetch the data
        // Eager load the 'user' relationship to get the applicant's name efficiently.
        $applications = feriApp::with('user')->get();

        // 2. Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 3. Define and set headers
        $headers = ['ID', 'Reference', 'Applicant', 'Date', 'PO', 'Type', 'Customs No', 'Feri Cert No', 'Status'];
        $sheet->fromArray($headers, null, 'A1'); // Write headers starting from A1

        // Optional: Apply some basic styling to headers
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FF000000'], // Black color
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFDDDDDD'], // Light grey background
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($headerStyle);

        // 4. Populate the spreadsheet with data
        $row = 2; // Start data from row 2, after headers
        foreach ($applications as $app) {
            // Fetch the latest draft for the application
            $latestDraft = Certificate::where('application_id', $app->id)->where('type', 'draft')->latest()->first();
            $cert_no = null;

            if ($latestDraft) {
                // Find the invoice associated with this draft certificate
                // This assumes 'cert_id' in the 'invoices' table refers to the 'id' of the 'certificates' table.
                $invoice = Invoice::where('cert_id', $latestDraft->id)->first();

                if ($invoice) {
                    $cert_no = $invoice->certificate_no;
                }
            }
            // --- END NEW LOGIC ---

            $sheet->setCellValue('A' . $row, $app->id);
            $sheet->setCellValue('B' . $row, $app->company_ref);
            $sheet->setCellValue('C' . $row, $app->user->name ?? 'N/A'); // Applicant name
            $sheet->setCellValue('D' . $row, $app->created_at->format('j F Y'));
            $sheet->setCellValue('E' . $row, is_numeric($app->po) ? $app->po : 'TBS');
            $sheet->setCellValue('F' . $row, ucfirst($app->feri_type));
            $sheet->setCellValue('G' . $row, ucfirst($app->customs_decl_no));
            $sheet->setCellValue('H' . $row, $cert_no);
            $sheet->setCellValue('I' . $row, $this->getStatusText($app->status)); // Using the helper function
            $row++;
        }

        // Optional: Auto-size columns for better readability
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 5. Prepare the response for download
        $fileName = 'Feri_Status_' . now()->format('Ymd_His') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        // Use StreamedResponse for large files to prevent memory issues
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function exportInvoices()
    {
        // 1. Get all invoices
        $records = Invoice::all();

        // 2. Get all certificates referenced by invoices
        $certIds = $records->pluck('cert_id')->unique()->filter();
        $certificates = Certificate::whereIn('id', $certIds)->get()->keyBy('id');

        // 3. Get all application_ids from those certificates
        $applicationIds = $certificates->pluck('application_id')->unique()->filter();
        $applications = feriApp::whereIn('id', $applicationIds)->with('user')->get()->keyBy('id');

        // 4. Filter invoices: only those whose certificate's application has status = 5
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

        // 5. Transform for export
        $exportData = $approvedRecords->map(function ($invoice) use ($certificates, $applications) {
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
            $grandTotal_r = number_format($grandTotal, 2, '.', ',');
            $grandTotal_r = number_format($grandTotal_r * $invoice->tz_rate, 2, '.', ',');

            $cert = $certificates->get($invoice->cert_id);
            $appid = $cert ? $cert->application_id : null;
            $app = $appid ? $applications->get($appid) : null;

            return [
                'Invoice' => 'PRES-2025-P' . $invoice->id,
                'Company Ref' => $cert ? $invoice->customer_ref : '',
                'Customer Trip No' => $invoice->customer_trip_no,
                'Date' => $invoice->invoice_date,
                'PO' => $app ? $app->po : '',
                'Cert No' => $invoice->certificate_no,
                'Amount' => $grandTotal_r,
            ];
        });

        // 6. Export to Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = array_keys($exportData->first() ?? []);
        $sheet->fromArray($headers, null, 'A1');

        // Set data
        $sheet->fromArray($exportData->toArray(), null, 'A2');

        // Auto-size columns
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Feri_Invoices_' . now()->format('Ymd_His') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
