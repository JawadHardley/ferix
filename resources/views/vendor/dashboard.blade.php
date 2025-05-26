@extends('layouts.admin.main')
@section('content')


@php
$tile1 = asset('images/designer.svg');
$tile2 = asset('images/team.svg');
@endphp


<div class="row g-3 align-items-stretch">
    <div class="col-sm-12 col-lg-6 y d-flex">
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
                                    <div class="h3 me-2">6,782</div>
                                </div>
                                <span class="visually-hidden">75% Complete</span>
                            </div>
                            <div class="col-auto">
                                <div class="subheader">Rate</div>
                                <div class="d-flex align-items-baseline">
                                    <div class="h3 me-2">78.4%</div>
                                    <div class="me-auto">
                                        <!-- icon here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-auto d-flex justify-content-center p-5">
                        <img src="{{ $tile2 }}" alt="stats illustrations" class="img-fluid"
                            style="width: 200px; height: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-6 x d-flex">
        <div class="card flex-fill">
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
                    <tr>
                        <td>Alistair Group</td>
                        <td>3,550</td>
                        <td class="w-50">
                            <!-- something -->
                        </td>
                    </tr>
                    <tr>
                        <td>Mowara Group</td>
                        <td>450</td>
                        <td class="w-50">
                            <!-- something -->
                        </td>
                    </tr>
                    <tr>
                        <td>Orion Beta</td>
                        <td>250</td>
                        <td class="w-50">
                            <!-- something -->
                        </td>
                    </tr>
                    <tr>
                        <td>Bollore Group</td>
                        <td>150</td>
                        <td class="w-50">
                            <!-- something -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="row mt-5">
    <div class="col-12 col-md-4 col-lg-3 mb-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-success text-white avatar">
                            <i class="fa fa-dollar-sign"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">32 Invoices</div>
                        <div class="text-secondary">10 waiting Approval</div>
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
                            <i class="fa fa-database"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">230 Entries</div>
                        <div class="text-secondary">100 To process</div>
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
                            <i class="fa fa-clock"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">31 Pending</div>
                        <div class="text-secondary">9 Urgent List</div>
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
                            <i class="fa fa-clock"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">21 On Progress</div>
                        <div class="text-secondary">19 In the run</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection