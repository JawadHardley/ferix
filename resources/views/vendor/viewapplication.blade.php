@extends('layouts.admin.main')
@section('content')


<x-errorshow />

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col py-2">

                @if ($record->status == 1)
                <span class="badge bg-danger me-1"></span> New Entry
                @elseif ($record->status == 2)
                <span class="badge bg-warning me-1"></span> Process
                @elseif ($record->status == 3)
                <span class="status-dot status-dot-animated status-cyan me-1"></span> Awaiting Approval
                @elseif ($record->status == 4)
                <span class="badge bg-primary me-1"></span> Approved
                @elseif ($record->status == 5)
                <span class="status-dot status-dot-animated status-green me-1"></span> Complete
                @endif

            </div>

            <div class="col text-end">
                <button type="button" class="btn btn-outline-primary position-relative me-3">
                    <i class="fa fa-message"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                </button>


                @if ($record->status == 1)


                <!-- for a processing the application feri -->
                <button class="btn btn-md btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#a{{ $record->id }}">
                    <span class="d-none d-md-inline">Process</span> <i class="fa fa-spinner ms-md-3"></i>
                </button>

                @elseif ($record->status == 2)
                <button class="btn btn-md btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#b{{ $record->id }}">
                    <span class="d-none d-md-inline">Upload Draft</span> <i class="fa fa-upload ms-md-3"></i>
                </button>
                @elseif ($record->status == 3)
                <!-- wait for user to approve or query -->
                @elseif ($record->status == 4)

                <button class="btn btn-md btn-outline-success" data-bs-toggle="modal"
                    data-bs-target="#c{{ $record->id }}">
                    <span class="d-none d-md-inline">Upload Certificate</span> <i class="fa fa-upload ms-md-3"></i>
                </button>
                @elseif ($record->status == 5)
                <!-- we are all done here -->
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

                    @if($record->applicationFile)
                    <div class="nav-item" role="presentation">
                        <a href="#tabs-home-7"
                            class="list-group-item list-group-item-action d-flex align-items-center nav-link"
                            data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            Draft
                        </a>
                    </div>
                    @endif

                    @if($record->certificateFile)
                    <div class="nav-item" role="presentation">
                        <a href="#tabs-home-8"
                            class="list-group-item list-group-item-action d-flex align-items-center nav-link"
                            data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            Certificate
                            <i class="fa fa-award text-dark ms-2 fs-2"></i>
                        </a>
                    </div>
                    @endif

                </div>
                <!-- <h4 class="subheader mt-4">#Leave</h4>
                            <div class="list-group list-group-transparent">
                                <a href="#" class="list-group-item list-group-item-action">Give Feedback</a>
                            </div> -->
            </div>
        </div>
        <div class="col-12 col-md-10 d-flex flex-column tab-content">
            <div class="card-body tab-pane fade active show" id="tabs-home-1" role="tabpanel">

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
                    <div class="col-12 mb-3 col-lg-4">
                        <div class="form-label">Transporter Company</div>
                        <input type="text" name="transporter_company" class="form-control"
                            value="{{ $record->transporter_company }}">
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

                    <div class="col-12 mb-3 col-lg-4">
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
                    <div class="col-12 mb-3 col-lg-6">
                        <div class="form-label">Documents Upload</div>
                        <input type="text" name="documents_upload" class="form-control"
                            value="{{ $record->documents_upload }}">
                    </div>

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

                    <a href="{{ asset('storage/' . $record->applicationFile) }}" target="_blanck"
                        class="text-decoration-none">
                        <div class="card col-12 card-link">
                            <div class="card-body pt-5" style="height: 5rem">
                                Download Feri Document
                            </div>
                            <div class="ribbon bg-danger ribbon-top ribbon-start">
                                <i class="fa fa-award fs-2 px-2"></i>
                            </div>

                        </div>
                    </a>

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

                    <a href="{{ asset('storage/' . $record->certificateFile) }}" target="_blanck"
                        class="text-decoration-none">
                        <div class="card col-12 card-link">
                            <div class="card-body pt-5" style="height: 5rem">
                                Download Feri Certificate
                            </div>
                            <div class="ribbon bg-success ribbon-top ribbon-start">
                                <i class="fa fa-award fs-2 px-2"></i>
                            </div>

                        </div>
                    </a>

                </div>
            </div>
            @endif


        </div>
    </div>


    <!-- modals modals -->
    <!-- modals modals -->
    <!-- modals modals -->


    @if ($record->status == 1)
    <!-- Modal -->
    <form action="{{ route('vendor.process1', ['id' => $record->id]) }}" method="POST" class="d-inline">
        @csrf
        <div class="modal fade" id="a{{ $record->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-primary"></div>
                    <div class="modal-body text-center py-4">
                        <i class="fa fa-spinner text-primary display-2 pb-5"></i>
                        <!-- <h3>Are you sure?</h3> -->
                        <div class="text-secondary">
                            Do you want to proceed with the application ?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <a href="#" class="btn w-100" data-bs-dismiss="modal"> Cancel </a>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-primary w-100" data-bs-dismiss="modal">
                                        Save </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @elseif ($record->status == 2)
    <!-- Modal -->
    <form action="{{ route('vendor.process2', ['id' => $record->id]) }}" method="POST" enctype="multipart/form-data"
        class="d-inline">
        @csrf
        <div class="modal fade" id="b{{ $record->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-primary"></div>
                    <div class="modal-body py-4">
                        <h2 class="text-center mb-3">
                            Upload {{ $record->status == 2 ? 'Draft' : ($record->status == 4 ? 'Certificate' : '') }}
                        </h2>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="type"
                                value="{{ $record->status == 2 ? 'draft' : ($record->status == 4 ? 'certificate' : '') }}"
                                disabled />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                {{ $record->status == 2 ? 'Draft' : ($record->status == 4 ? '' : '') }} Ceritificate
                            </label>
                            <input type="file" class="form-control" name="file" />
                        </div>
                        <!-- <div class="text-secondary">
                        Do you want to proceed with the application ?
                    </div> -->
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <a href="#" class="btn w-100" data-bs-dismiss="modal"> Cancel </a>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-primary w-100" data-bs-dismiss="modal">
                                        Save </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @elseif ($record->status == 4)
    <!-- Modal -->
    <form action="{{ route('vendor.process3', ['id' => $record->id]) }}" method="POST" enctype="multipart/form-data"
        class="d-inline">
        @csrf
        <div class="modal fade" id="c{{ $record->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-success"></div>
                    <div class="modal-body py-4">
                        <h2 class="text-center mb-3">
                            Upload Certificate
                        </h2>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="type"
                                value="{{ $record->status == 2 ? 'draft' : ($record->status == 4 ? 'certificate' : '') }}"
                                disabled />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                {{ $record->status == 2 ? 'Draft' : ($record->status == 4 ? '' : '') }} Ceritificate
                            </label>
                            <input type="file" class="form-control" name="file" />
                        </div>
                        <!-- <div class="text-secondary">
                        Do you want to proceed with the application ?
                    </div> -->
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







    @endsection