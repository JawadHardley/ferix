@extends('layouts.admin.main')
@section('content')

@php
$tile1 = asset('images/designer.svg');
$tile2 = asset('images/team.svg');

$completed = 0;
$pending = 0;
$draft = 0;
$waiting = 0;
$rejected = 0;
$today = 0;
$total = 0;

if(!empty($feris)) {
foreach ($feris as $feri) {
if ($feri->status == 5) {
$completed++;
} elseif ($feri->status == 4) {
$waiting++;
}elseif ($feri->status == 3) {
$draft++;
}elseif ($feri->status == 1) {
$pending++;
}elseif ($feri->status == 6) {
$rejected++;
}

if ($feri->created_at->isToday()) {
$today++;
}
$total++;

}
}

$transporter = null;
// Build a company_name => count array
$companyNameCounts = [];
if(!empty($feris)) {
foreach ($feris as $feri) {
// Get the company name (assuming you have a relation or can fetch it)
$company = $companies->firstWhere('id', (int)$feri->transporter_company);
$companyName = $company ? $company->name : 'No Company Assigned';
if (!isset($companyNameCounts[$companyName])) {
$companyNameCounts[$companyName] = 0;
}
$companyNameCounts[$companyName]++;
}
}

if ($total != 0) {
$rate = ($completed / $total) * 100;
$rate = number_format($rate, 0);
} else {
$rate = 0;
}

if($rate <= 50) { $bg="warning" ; } else { $bg="success" ; } @endphp <div class="row fade-slide-in">
    <div class="col-12 col-md-4 col-lg-3 mb-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-teal text-white avatar">
                            <i class="fa fa-circle-check"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">{{ $waiting }}</div>
                        <div class="text-secondary">Waiting Certificate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 col-lg-3 mb-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-primary text-white avatar">
                            <i class="fa fa-clock-rotate-left"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">{{ $pending }}</div>
                        <div class="text-secondary">Pending Entries</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 col-lg-3 mb-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-warning text-white avatar">
                            <i class="fa fa-hourglass-end"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">{{ $draft }}</div>
                        <div class="text-secondary">Waiting Approval</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 col-lg-3 mb-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-danger text-white avatar">
                            <i class="fa fa-circle-xmark"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">{{ $rejected }}</div>
                        <div class="text-secondary">Rejected Applications</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="row g-3 align-items-stretch fade-slide-in">
        <div class="col-sm-12 col-lg-6 d-flex">
            <div class="card flex-fill">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-12 col-sm d-flex flex-column">
                            <h3 class="h2">Welcome back, {{ Auth::user()->name }}</h3>
                            <p class="text-muted">Start your day with Stats</p>
                            <div class="row g-5 mt-auto">
                                <div class="col-auto">
                                    <div class="subheader">Today's Applications</div>
                                    <div class="d-flex align-items-baseline">
                                        <div class="h3 me-2">{{ $today }}</div>
                                    </div>
                                </div>
                                <!-- <div class="col-auto">
                                <div class="subheader">Rate</div>
                                <div class="d-flex align-items-baseline">
                                    <div class="h3 me-2">78.4%</div>
                                    <div class="me-auto">
                                    </div>
                                </div>
                            </div> -->
                            </div>
                        </div>
                        <div class="col-12 col-sm-auto d-flex justify-content-center p-5">
                            <img src="{{ $tile1 }}" alt="stats illustrations" class="img-fluid"
                                style="width: 200px; height: 200px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Application Traffic</h3>
                </div>
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th colspan="2">Entries</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $company)
                        @php
                        $count = 0;
                        if (!empty($feris)) {
                        foreach ($feris as $feri) {
                        if($company->id == $feri->transporter_company) {
                        $count++;
                        }
                        }
                        }

                        @endphp
                        <tr>
                            <td>{{ $company->name }}</td>
                            <td>{{ $count }}</td>
                            <td class="w-50">
                                <div class="progress progress-xs">
                                    <div class="progress-bar bg-primary" style="width: {{ $count }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>





    @endsection