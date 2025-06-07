@extends('layouts.userlayout')
@section('content')

@php
$unreadChats = $chats->filter(function ($chat) {
return $chat->user_id !== Auth::id() && $chat->read === 0;
});


@endphp




<x-errorshow />

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col py-2">

                @if ($record->status == 5)
                <div class="ribbon ribbon-top ribbon-end bg-success">
                    <i class="fa fa-certificate fs-3"></i>
                </div>
                @endif

                @if ($record->status == 1)
                <span class="badge bg-danger me-1"></span> Pending
                @elseif ($record->status == 2)
                <span class="badge bg-warning me-1"></span> Pending
                @elseif ($record->status == 3)
                <span class="status-dot status-dot-animated status-cyan me-1"></span> Draft Approval
                @elseif ($record->status == 4)
                <span class="badge bg-primary me-1"></span> In progress
                @elseif ($record->status == 5)
                <span class="status-dot status-dot-animated status-green me-1"></span> Complete
                @elseif ($record->status == 6)
                <span class="status-dot status-dot-animated status-danger me-1"></span> Rejected
                @endif

            </div>
            <div class="col text-end">

                <a href="#" class="text-decoration-none position-relative me-3" data-bs-toggle="modal"
                    data-bs-target="#chat">
                    <i class="fa fa-comment-dots"></i>

                    @if ($unreadChats->isNotEmpty())
                    <span class="badge bg-red mb-2"></span>
                    @endif
                </a>

                @if(is_numeric($record->po))
                <span class="btn badge bg-teal-lt text-teal-lt-fg me-3 my-2" data-bs-toggle="modal"
                    data-bs-target="#poedit">{{ $record->po }}</span>
                @else
                <span class=" btn badge bg-red-lt text-red-lt-fg me-3 my-2" data-bs-toggle="modal"
                    data-bs-target="#poedit">Add PO</span>
                @endif



                @if ($record->status == 1)

                <!-- do nothing as we are waiting -->

                @elseif ($record->status == 2)

                <!-- do nothing as we are waiting -->

                @elseif ($record->status == 3)
                <button class="btn btn-md btn-outline-success me-2" data-bs-toggle="modal"
                    data-bs-target="#a{{ $record->id }}">
                    <span class="d-none d-md-inline">Approve</span> <i class="fa fa-circle-check ms-md-3"></i>
                </button>

                <button class="btn btn-md btn-outline-danger" data-bs-toggle="modal"
                    data-bs-target="#ax{{ $record->id }}">
                    <span class="d-none d-md-inline">Reject</span> <i class="fa fa-circle-xmark ms-md-3"></i>
                </button>
                @elseif ($record->status == 4)
                <!-- 4 -->
                @elseif ($record->status == 5)
                <!-- <button class="btn btn-outline-primary">
                    <i class="fa fa-award fs-3"></i>
                </button> -->
                <div class="ms-5 d-inline"></div>
                @endif

            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="row g-0">
        <div class="col-12 col-md-2 border-end">
            <div class="card-body">
                <h4 class="subheader"></h4>
                <div class="list-group list-group-transparent nav nav-tabs card-header-tabs" data-bs-toggle="tabs"
                    role="tablist">

                    @if($record->applicationFile && !$record->certificateFile)
                    <div class="nav-item border border-dark border-opacity-50" role="presentation">
                        <a href="#tabs-home-7"
                            class="list-group-item list-group-item-action d-flex align-items-center nav-link"
                            data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            Draft
                            <i class="fa fa-circle-down ms-2"></i>
                        </a>
                    </div>
                    @endif

                    @if($record->certificateFile)
                    <div class="nav-item border border-dark border-opacity-50" role="presentation">
                        <a href="#tabs-home-8" class="list-group-item list-group-item-action nav-link"
                            data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            Certificate
                            <i class="fa fa-circle-down text-end ms-2"></i>

                        </a>
                    </div>
                    @endif

                    <div class="nav-item" role="presentation">
                        <a href="#tabs-home-1"
                            class="list-group-item list-group-item-action d-flex align-items-center nav-link active"
                            data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            Transport & Cargo Details
                        </a>
                    </div>

                    <div class="nav-item" role="presentation">
                        <a href="#tabs-home-2"
                            class="list-group-item list-group-item-action d-flex align-items-center nav-link"
                            data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            Import Details
                        </a>
                    </div>

                    <div class="nav-item" role="presentation">
                        <a href="#tabs-home-3"
                            class="list-group-item list-group-item-action d-flex align-items-center nav-link"
                            data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            Export Details
                        </a>
                    </div>

                    <div class="nav-item" role="presentation">
                        <a href="#tabs-home-4"
                            class="list-group-item list-group-item-action d-flex align-items-center nav-link"
                            data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            Cargo Description
                        </a>
                    </div>

                    <div class="nav-item" role="presentation">
                        <a href="#tabs-home-5"
                            class="list-group-item list-group-item-action d-flex align-items-center nav-link"
                            data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            Expedition
                        </a>
                    </div>

                    <div class="nav-item" role="presentation">
                        <a href="#tabs-home-6"
                            class="list-group-item list-group-item-action d-flex align-items-center nav-link"
                            data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            Value
                        </a>
                    </div>



                </div>
                <!-- <h4 class="subheader mt-4">#Leave</h4>
                            <div class="list-group list-group-transparent">
                                <a href="#" class="list-group-item list-group-item-action">Give Feedback</a>
                            </div> -->
            </div>
        </div>
        <div class="col-12 col-md-10 d-flex flex-column tab-content">
            <div class="card-body tab-pane fade active show" id="tabs-home-1" role="tabpanel">
                <form action="{{ route('transporter.editApp', ['id' => $record->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- <h2 class="mb-4">#</h2> -->
                    <h3 class="card-title mb-5">Transport & Cargo Details</h3>

                    <div class="row g-3">

                        <div class="col-12 mb-3 col-lg-4">
                            <div class="form-label">Applicant</div>
                            <input type="text" name="user_id" class="form-control" value="{{ $record->applicant }}"
                                disabled>
                        </div>
                        <div class="col-12 mb-3 col-lg-4">
                            <div class="form-label">Transport Mode</div>
                            <input type="text" name="transport_mode" class="form-control"
                                value="{{ $record->transport_mode }}">
                        </div>
                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label">Transporter Company</label>
                            @if($record->status < 2) <select class="form-select" name="transporter_company">
                                <option value="">-- select --</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ old('transporter_company', $record->transporter_company ?? '') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                                @endforeach
                                </select>
                                @else
                                <input type="text" class="form-control"
                                    value="{{ $companies->where('id', $record->transporter_company)->first()->name ?? 'N/A' }}"
                                    disabled>
                                <input type="hidden" name="transporter_company"
                                    value="{{ $record->transporter_company }}">
                                @endif
                        </div>
                        <div class="col-12 mb-3 col-lg-12">
                            <div class="form-label">Entry Border DRC</div>
                            <input type="text" name="entry_border_drc" class="form-control"
                                value="{{ $record->entry_border_drc }}">
                        </div>
                        <div class="col-12 mb-3 col-lg-4">
                            <div class="form-label">Truck Details</div>
                            <input type="text" name="truck_details" class="form-control"
                                value="{{ $record->truck_details }}">
                        </div>
                        <div class="col-12 mb-3 col-lg-4">
                            <div class="form-label">Arrival Station</div>
                            <input type="text" name="arrival_station" class="form-control"
                                value="{{ $record->arrival_station }}">
                        </div>
                        <div class="col-12 mb-3 col-lg-4">
                            <div class="form-label">Final Destination</div>
                            <input type="text" name="final_destination" class="form-control"
                                value="{{ $record->final_destination }}">
                        </div>

                    </div>
            </div>

            <div class="card-body tab-pane fade" id="tabs-home-2" role="tabpanel">

                <!-- <h2 class="mb-4">#</h2> -->
                <h3 class="card-title mb-5">Import Details</h3>

                <div class="row g-3">

                    <div class="col-12 mb-3 col-lg-12">
                        <div class="form-label">Importer Name</div>
                        <input type="text" name="importer_name" class="form-control"
                            value="{{ $record->importer_name }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-6">
                        <div class="form-label">Importer Phone</div>
                        <input type="text" name="importer_phone" class="form-control"
                            value="{{ $record->importer_phone }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-6">
                        <div class="form-label">Importer Email</div>
                        <input type="text" name="importer_email" class="form-control"
                            value="{{ $record->importer_email }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-12">
                        <div class="form-label">Fix Number</div>
                        <input type="text" name="fix_number" class="form-control" value="{{ $record->fix_number }}">
                    </div>

                </div>

            </div>

            <div class="card-body tab-pane fade" id="tabs-home-3" role="tabpanel">

                <!-- <h2 class="mb-4">#</h2> -->
                <h3 class="card-title mb-5">Exporter Details</h3>

                <div class="row g-3">

                    <div class="col-12 mb-3 col-lg-4">
                        <div class="form-label">Exporter Name</div>
                        <input type="text" name="exporter_name" class="form-control"
                            value="{{ $record->exporter_name }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-4">
                        <div class="form-label">Exporter Phone</div>
                        <input type="text" name="exporter_phone" class="form-control"
                            value="{{ $record->exporter_phone }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-4">
                        <div class="form-label">Exporter Email</div>
                        <input type="text" name="exporter_email" class="form-control"
                            value="{{ $record->exporter_email }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-12">
                        <div class="form-label">CF Agent</div>
                        <input type="text" name="cf_agent" class="form-control" value="{{ $record->cf_agent }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-12">
                        <div class="form-label">CF Agent Contact</div>
                        <input type="text" name="cf_agent_contact" class="form-control"
                            value="{{ $record->cf_agent_contact }}">
                    </div>

                </div>

            </div>

            <div class="card-body tab-pane fade" id="tabs-home-4" role="tabpanel">

                <!-- <h2 class="mb-4">#</h2> -->
                <h3 class="card-title mb-5">Cargo Description </h3>

                <div class="row g-3">

                    <div class="col-12 mb-3 col-lg-12">
                        <div class="form-label">Cargo Description</div>
                        <input type="text" name="cargo_description" class="form-control"
                            value="{{ $record->cargo_description }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-6">
                        <div class="form-label">HS Code</div>
                        <input type="text" name="hs_code" class="form-control" value="{{ $record->hs_code }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-6">
                        <div class="form-label">Package Type</div>
                        <input type="text" name="package_type" class="form-control" value="{{ $record->package_type }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-12">
                        <div class="form-label">Quantity</div>
                        <input type="text" name="quantity" class="form-control" value="{{ $record->quantity }}">
                    </div>

                </div>

            </div>

            <div class="card-body tab-pane fade" id="tabs-home-5" role="tabpanel">

                <!-- <h2 class="mb-4">#</h2> -->
                <h3 class="card-title mb-5">Expedition</h3>

                <div class="row g-3">

                    <div class="col-12 mb-3 col-lg-2">
                        <div class="form-label">PO Number</div>
                        <input type="text" name="po" class="form-control" value="{{ $record->po }}">
                    </div>

                    <div class="col-12 mb-3 col-lg-2">
                        <div class="form-label">Company Ref</div>
                        <input type="text" name="company_ref" class="form-control" value="{{ $record->company_ref }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-4">
                        <div class="form-label">Cargo Origin</div>
                        <input type="text" name="cargo_origin" class="form-control" value="{{ $record->cargo_origin }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-4">
                        <div class="form-label">Customs Decl No</div>
                        <input type="text" name="customs_decl_no" class="form-control"
                            value="{{ $record->customs_decl_no }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-6">
                        <div class="form-label">Manifest No</div>
                        <input type="text" name="manifest_no" class="form-control" value="{{ $record->manifest_no }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-6">
                        <div class="form-label">OCC Bivac</div>
                        <input type="text" name="occ_bivac" class="form-control" value="{{ $record->occ_bivac }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-12">
                        <div class="form-label">Instructions</div>
                        <input type="text" name="instructions" class="form-control" value="{{ $record->instructions }}">
                    </div>

                </div>

            </div>

            <div class="card-body tab-pane fade" id="tabs-home-6" role="tabpanel">

                <!-- <h2 class="mb-4">#</h2> -->
                <h3 class="card-title mb-5">Values</h3>

                <div class="row g-3">

                    <div class="col-12 mb-3 col-lg-3">
                        <div class="form-label">FOB Currency</div>
                        <input type="text" name="fob_currency" class="form-control" value="{{ $record->fob_currency }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-3">
                        <div class="form-label">FOB Value</div>
                        <input type="text" name="fob_value" class="form-control" value="{{ $record->fob_value }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-3">
                        <div class="form-label">Incoterm</div>
                        <input type="text" name="incoterm" class="form-control" value="{{ $record->incoterm }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-3">
                        <div class="form-label">Freight Currency</div>
                        <input type="text" name="freight_currency" class="form-control"
                            value="{{ $record->freight_currency }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-3">
                        <div class="form-label">Freight Value</div>
                        <input type="text" name="freight_value" class="form-control"
                            value="{{ $record->freight_value }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-3">
                        <div class="form-label">Insurance Currency</div>
                        <input type="text" name="insurance_currency" class="form-control"
                            value="{{ $record->insurance_currency }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-3">
                        <div class="form-label">Insurance Value</div>
                        <input type="text" name="insurance_value" class="form-control"
                            value="{{ $record->insurance_value }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-3">
                        <div class="form-label">Additional Fees Currency</div>
                        <input type="text" name="additional_fees_currency" class="form-control"
                            value="{{ $record->additional_fees_currency }}">
                    </div>
                    <div class="col-12 mb-3 col-lg-6">
                        <div class="form-label">Additional Fees Value</div>
                        <input type="text" name="additional_fees_value" class="form-control"
                            value="{{ $record->additional_fees_value }}">
                    </div>
                    <div
                        class="col-12 mb-3 col-lg-{{ $record->status == 1 ? '3' : ($record->status != 1 ? '6' : '') }}">
                        <div class="form-label">Document</div>
                        <a href="{{ route('file.downloadfile', ['id' => $record->id]) }}" download>
                            <div class="card py-1">
                                <div class="card-body p-1">
                                    Download
                                    File
                                </div>
                                <div class="ribbon ribbon-top">
                                    <i class="fa fa-file"></i>
                                </div>
                        </a>
                    </div>
                </div>

                @if($record->status == 1)

                <div class="col-12 mb-3 col-lg-3">
                    <div class="form-label">Edit Document</div>
                    <input type="file" name="documents_upload" class="form-control">
                </div>

                @endif

                <!-- <div class="col-12 mb-3 col-lg-3">
                            <div class="form-label">Created At</div>
                            <input type="text" name="created_at" class="form-control" value="{{ $record->created_at }}">
                        </div> -->


            </div>

        </div>


        @if($record->applicationFile)
        <div class="card-body tab-pane fade" id="tabs-home-7" role="tabpanel">
            <!-- <h2 class="mb-4">#</h2> -->
            <h3 class="card-title mb-5">Draft</h3>

            <div class="row g-3">

                <div class="col-12 mb-3 col-lg-12">
                    <div class="form-label">Document</div>
                    <input type="text" name="#" class="form-control" value="{{ $record->type }}" disabled />
                </div>

                <a href="{{ route('certificate.downloaddraft', ['id' => $record->id]) }}" class="text-decoration-none"
                    download>
                    <div class="card col-12 card-link">
                        <div class="card-body pt-5" style="height: 5rem">
                            Download Feri Document
                        </div>
                        <div class="ribbon bg-danger ribbon-top ribbon-start">
                            <i class="fa fa-certificate fs-2 px-2"></i>
                        </div>

                    </div>
                </a>

                @if ($invoice)
                <a href="{{ route('invoices.downloadinvoice', ['id' => $record->id]) }}" target="_blanck"
                    class="text-decoration-none">
                    <div class="card col-12 card-link">
                        <div class="card-body pt-5" style="height: 5rem">
                            Download Draft Invoice
                        </div>
                        <div class="ribbon bg-danger ribbon-top ribbon-start">
                            <i class="fa fa-dollar-sign fs-2 px-2"></i>
                        </div>

                    </div>
                </a>
                @endif

            </div>
        </div>
        @endif

        @if($record->certificateFile)
        <div class="card-body tab-pane fade" id="tabs-home-8" role="tabpanel">
            <!-- <h2 class="mb-4">#</h2> -->
            <h3 class="card-title mb-5">Certificate</h3>

            <div class="row g-3">

                <div class="col-12 mb-3 col-lg-12">
                    <div class="form-label">Document</div>
                    <input type="text" name="#" class="form-control" value="{{ $record->type }}" disabled />
                </div>

                <a href="{{ route('certificate.download', ['id' => $record->id]) }}" class="text-decoration-none"
                    download>
                    <div class="card col-12 card-link">
                        <div class="card-body pt-5" style="height: 5rem">
                            Download Feri Certificate
                        </div>
                        <div class="ribbon bg-success ribbon-top ribbon-start">
                            <i class="fa fa-certificate fs-2 px-2"></i>
                        </div>

                    </div>
                </a>
                @if ($invoice)
                <a href="{{ route('invoices.downloadinvoice', ['id' => $record->id]) }}" target="_blank"
                    class="text-decoration-none">
                    <div class="card col-12 card-link">
                        <div class="card-body pt-5" style="height: 5rem">
                            Download Feri Invoice
                        </div>
                        <div class="ribbon bg-success ribbon-top ribbon-start">
                            <i class="fa fa-dollar-sign fs-2 px-2"></i>
                        </div>
                    </div>
                </a>
                @endif

            </div>
        </div>
        @endif

    </div>
    @if ($record->status == 1)
    <div class="row">
        <div class="col py-3 pt-5 text-end">
            <a href="{{ route(Auth::user()->role . '' . '.showApps') }}" class="btn btn-outline-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Edit</button>
        </div>
    </div>
    @endif
    </form>
</div>
</div>


@if ($record->status == 3)
<!-- Modal Rejection -->
<form action="{{ route('transporter.sendchat', ['id' => $record->id]) }}" method="POST">
    @csrf
    <div class="modal fade" id="ax{{ $record->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <i class="fa fa-circle-xmark text-danger display-2 pb-5"></i>
                    <!-- <h3>Are you sure?</h3> -->
                    <div class="text-secondary my-3">
                        Tell us the rejection reason

                    </div>
                    <div class="input-group input-group-flat">
                        <input type="text" name="message" class="form-control" autocomplete="off"
                            placeholder="Type reason">
                        <input type="hidden" name="rejection" value="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a href="#" class="btn w-100" data-bs-dismiss="modal"> Cancel </a>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-danger w-100" data-bs-dismiss="modal">
                                    Reject </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal -->
<form action="{{ route('transporter.process3', ['id' => $record->id]) }}" method="POST" class="d-inline">
    @csrf
    <div class="modal fade" id="a{{ $record->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-success"></div>
                <div class="modal-body text-center py-4">
                    <i class="fa fa-circle-check text-success display-2 pb-5"></i>
                    <!-- <h3>Are you sure?</h3> -->
                    <div class="text-secondary">
                        Do you want to approve this draft ?
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a href="#" class="btn w-100" data-bs-dismiss="modal"> Cancel </a>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-success w-100" data-bs-dismiss="modal">
                                    Save </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endif



<!-- Modal -->
<div class="modal fade" id="chat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-3" id="exampleModalLabel">Queries</h1>
                <span class="fs-5 ms-auto">
                    <a href="{{ route('transporter.readchat', ['id' => $record->id]) }}">mark as read</a>
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body scrollable" style="height: 300px; overflow-y: auto;">
                        <div class="chat">
                            <div class="chat-bubbles">
                                <form action="{{ route('transporter.sendchat', ['id' => $record->id]) }}" method="POST">
                                    @csrf

                                    @foreach($chats as $chat)

                                    @if($chat->user_id == Auth::user()->id)
                                    <div class="chat-item mb-3">
                                        <div class="row align-items-end justify-content-end">
                                            <div class="col col-lg-10">
                                                <div class="chat-bubble chat-bubble-me">
                                                    @if($chat->del == 0)
                                                    <div class="chat-bubble-title">
                                                        <div class="row">
                                                            <div class="col chat-bubble-author">{{ Auth::user()->name }}
                                                            </div>
                                                            <div class="col-auto chat-bubble-date fs-4">
                                                                {{ $chat->formatted_date }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="chat-bubble-body">
                                                        <p>{{ $chat->message }}</p>
                                                    </div>
                                                    <span class="fs-5">
                                                        <a
                                                            href="{{ route('transporter.deletechat', ['id' => $chat->id]) }}">delete</a>
                                                    </span>
                                                    @else
                                                    <div class="row">
                                                        <div class="col">
                                                            <p>
                                                                <i class="fa fa-ban"></i>
                                                                Deleted message
                                                            </p>
                                                            <span class="fs-5">{{ $chat->formatted_date }}</span>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                <span class="avatar avatar-1">
                                                    <i class="fa fa-user p-auto"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="chat-item mb-3">
                                        <div class="row align-items-end">
                                            <div class="col-auto">
                                                <span class="avatar avatar-1">
                                                    <i class="fa fa-user-shield  p-auto"></i>
                                                </span>
                                            </div>
                                            <div class="col col-lg-10">
                                                <div class="chat-bubble">
                                                    @if($chat->del == 0)
                                                    <div class="chat-bubble-title">
                                                        <div class="row">
                                                            <div class="col chat-bubble-author">Vendor</div>
                                                            <div class="col-auto chat-bubble-date">
                                                                {{ $chat->formatted_date }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="chat-bubble-body">
                                                        <p>{{ $chat->message }}</p>
                                                    </div>
                                                    @else
                                                    <div class="row">
                                                        <div class="col">
                                                            <p>
                                                                <i class="fa fa-ban"></i>
                                                                Deleted message
                                                            </p>
                                                            <span class="fs-5">{{ $chat->formatted_date }}</span>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer px-4 pb-4">
                <div class="input-group input-group-flat">
                    <input type="text" name="message" class="form-control" autocomplete="off"
                        placeholder="Type message">
                    <span class="input-group-text">
                        <button type="submit" class="btn border-0">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </span>
                </div>
                </form>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">
                    send <i class="fa fa-paper-plane ms-2"></i>
                </button>
            </div> -->
        </div>
    </div>
</div>

<!-- this below is modal for editing and adding PO number -->
<!-- this below is modal for editing and adding PO number -->
<!-- this below is modal for editing and adding PO number -->

<!-- Modal -->
<div class="modal fade" id="poedit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">PO Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <form action="{{ route('transporter.editpo', ['id' => $record->id]) }}" method="POST">
                        @csrf
                        <div class="col-12 mb-3 col-lg-12">
                            <label for="validationServer01" class="form-label">PO Numbers</label>
                            <input type="text" name="po"
                                class="form-control is-{{ is_numeric($record->po) ? '' : 'in' }}valid"
                                id="validationServer01" value="{{ $record->po }}" required>
                            <div class="{{ is_numeric($record->po) ? '' : 'in' }}valid-feedback">
                                {{ is_numeric($record->po) ? 'Correct Format' : 'Change PO' }}
                            </div>
                        </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Edit</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let shouldScrollChat = false;

    // When the chat button is clicked, set the flag
    document.querySelectorAll('[data-bs-target="#chat"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            shouldScrollChat = true;
        });
    });

    // When the modal is shown, scroll if the flag is set
    var chatModal = document.getElementById('chat');
    if (chatModal) {
        chatModal.addEventListener('shown.bs.modal', function() {
            if (shouldScrollChat) {
                var chatBody = chatModal.querySelector('.card-body.scrollable');
                if (chatBody) {
                    chatBody.scrollTo({
                        top: chatBody.scrollHeight,
                        behavior: 'smooth'
                    });
                }
                shouldScrollChat = false;
            }
        });
    }
});
</script>


@endsection