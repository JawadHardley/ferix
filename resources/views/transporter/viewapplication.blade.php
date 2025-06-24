@extends('layouts.userlayout')
@section('content')

    @php
        $documents = json_decode($record->documents_upload ?? '[]', true);

        $unreadChats = $chats->filter(function ($chat) {
            return $chat->user_id != Auth::id() && $chat->read == 0;
        });

    @endphp




    <x-errorshow />

    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-2 py-2">

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
                <div class="col py-2 text-muted">
                    <i class="fa fa-earth-americas me-1"></i> {{ Str::title($record->feri_type) }} Feri
                </div>
                <div class="col text-end">

                    <a href="#" class="text-decoration-none position-relative me-3" data-bs-toggle="modal"
                        data-bs-target="#chat">
                        <i class="fa fa-comment-dots"></i>

                        @if ($unreadChats->isNotEmpty())
                            <span class="badge bg-red mb-2"></span>
                        @endif
                    </a>

                    @if (is_numeric($record->po))
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
    @if ($record->feri_type == 'regional')
        <div class="card">
            <div class="row g-0">
                <div class="col-12 col-md-2 border-end">
                    <div class="card-body">
                        <h4 class="subheader"></h4>
                        <div class="list-group list-group-transparent nav nav-tabs card-header-tabs" data-bs-toggle="tabs"
                            role="tablist">

                            @if ($record->applicationFile && !$record->certificateFile)
                                <div class="nav-item border border-dark border-opacity-50" role="presentation">
                                    <a href="#tabs-home-7"
                                        class="list-group-item list-group-item-action d-flex align-items-center nav-link"
                                        data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                        Draft
                                        <i class="fa fa-circle-down ms-2"></i>
                                    </a>
                                </div>
                            @endif

                            @if ($record->certificateFile)
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
                                <input type="hidden" name="feri_type" value="{{ $record->feri_type }}" require />

                                <div class="col-12 mb-3 col-lg-4">
                                    <div class="form-label">Applicant</div>
                                    <input type="text" name="user_id" class="form-control"
                                        value="{{ $record->applicant }}" disabled>
                                </div>
                                <div class="col-12 mb-3 col-lg-4">
                                    <div class="form-label">Transport Mode</div>
                                    <input type="text" name="transport_mode" class="form-control"
                                        value="{{ $record->transport_mode }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                                </div>
                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Transporter Company</label>
                                    <select class="form-select" name="transporter_company"
                                        {{ $record->status > 1 ? 'disabled' : '' }}>
                                        <option value="">-- select --</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ old('transporter_company', $record->transporter_company ?? '') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="transporter_company"
                                        value="{{ $record->transporter_company }}">
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Entry Border to DRC</label>
                                    <select class="form-select" name="entry_border_drc"
                                        {{ $record->status > 1 ? 'disabled' : '' }} required>
                                        <option value="0"
                                            {{ $record->entry_border_drc == '0' || !$record->entry_border_drc ? 'selected' : '' }}>
                                            -- select --</option>
                                        <option value="Kasumbalesa"
                                            {{ $record->entry_border_drc == 'Kasumbalesa' ? 'selected' : '' }}>Kasumbalesa
                                        </option>
                                        <option value="Mokambo"
                                            {{ $record->entry_border_drc == 'Mokambo' ? 'selected' : '' }}>
                                            Mokambo</option>
                                        <option value="Sakania"
                                            {{ $record->entry_border_drc == 'Sakania' ? 'selected' : '' }}>
                                            Sakania</option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Border ETA</label>
                                    <input type="date" class="form-control" name="arrival_date"
                                        value="{{ $record->arrival_date }}" autocomplete="on"
                                        {{ $record->status > 1 ? 'disabled' : '' }} required />
                                </div>

                                <div class="col-12 mb-3 col-lg-4">
                                    <div class="form-label">Truck Details</div>
                                    <input type="text" name="truck_details" class="form-control"
                                        value="{{ $record->truck_details }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                                </div>
                                <div class="col-12 mb-3 col-lg-4">
                                    <div class="form-label">Arrival Station</div>
                                    <input type="text" name="arrival_station" class="form-control"
                                        value="{{ $record->arrival_station }}"
                                        {{ $record->status > 1 ? 'disabled' : '' }}>
                                </div>

                                <div class="col-12 mb-3 col-lg-4">
                                    <label class="form-label">Final Destination</label>
                                    <select class="form-select" name="final_destination"
                                        {{ $record->status > 1 ? 'disabled' : '' }} required>
                                        <option value="" {{ !$record->final_destination ? 'selected' : '' }}>--
                                            select --
                                        </option>
                                        <option value="Likasi DRC"
                                            {{ $record->final_destination == 'Likasi DRC' ? 'selected' : '' }}>Likasi DRC
                                        </option>
                                        <option value="Lubumbashi DRC"
                                            {{ $record->final_destination == 'Lubumbashi DRC' ? 'selected' : '' }}>
                                            Lubumbashi
                                            DRC</option>
                                        <option value="Kolwezi DRC"
                                            {{ $record->final_destination == 'Kolwezi DRC' ? 'selected' : '' }}>Kolwezi DRC
                                        </option>
                                        <option value="Tenke DRC"
                                            {{ $record->final_destination == 'Tenke DRC' ? 'selected' : '' }}>Tenke DRC
                                        </option>
                                        <option value="Kisanfu DRC"
                                            {{ $record->final_destination == 'Kisanfu DRC' ? 'selected' : '' }}>Kisanfu DRC
                                        </option>
                                    </select>
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
                                    value="{{ $record->importer_name }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-6">
                                <div class="form-label">Importer Phone</div>
                                <input type="text" name="importer_phone" class="form-control"
                                    value="{{ $record->importer_phone }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-6">
                                <div class="form-label">Importer Email</div>
                                <input type="text" name="importer_email" class="form-control"
                                    value="{{ $record->importer_email }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-12">
                                <div class="form-label">Fix Number</div>
                                <input type="text" name="fix_number" class="form-control"
                                    value="{{ $record->fix_number }}" {{ $record->status > 1 ? 'disabled' : '' }}>
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
                                    value="{{ $record->exporter_name }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-4">
                                <div class="form-label">Exporter Phone</div>
                                <input type="text" name="exporter_phone" class="form-control"
                                    value="{{ $record->exporter_phone }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-4">
                                <div class="form-label">Exporter Email</div>
                                <input type="text" name="exporter_email" class="form-control"
                                    value="{{ $record->exporter_email }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>

                            <div class="col-12 mb-3 col-lg-12">
                                <label class="form-label">CF Agent</label>
                                <select class="form-select" name="cf_agent" {{ $record->status > 1 ? 'disabled' : '' }}
                                    required>
                                    <option value="" {{ !$record->cf_agent ? 'selected' : '' }}>-- select --
                                    </option>
                                    <option value="AGL" {{ $record->cf_agent == 'AGL' ? 'selected' : '' }}>AGL</option>
                                    <option value="CARGO CONGO"
                                        {{ $record->cf_agent == 'CARGO CONGO' ? 'selected' : '' }}>CARGO
                                        CONGO</option>
                                    <option value="CONNEX" {{ $record->cf_agent == 'CONNEX' ? 'selected' : '' }}>CONNEX
                                    </option>
                                    <option value="African Logistics"
                                        {{ $record->cf_agent == 'African Logistics' ? 'selected' : '' }}>African Logistics
                                    </option>
                                    <option value="Afritac" {{ $record->cf_agent == 'Afritac' ? 'selected' : '' }}>Afritac
                                    </option>
                                    <option value="Amicongo" {{ $record->cf_agent == 'Amicongo' ? 'selected' : '' }}>
                                        Amicongo
                                    </option>
                                    <option value="Aristote" {{ $record->cf_agent == 'Aristote' ? 'selected' : '' }}>
                                        Aristote
                                    </option>
                                    <option value="Bollore" {{ $record->cf_agent == 'Bollore' ? 'selected' : '' }}>Bollore
                                    </option>
                                    <option value="Brasimba" {{ $record->cf_agent == 'Brasimba' ? 'selected' : '' }}>
                                        Brasimba
                                    </option>
                                    <option value="Brasimba S.A"
                                        {{ $record->cf_agent == 'Brasimba S.A' ? 'selected' : '' }}>
                                        Brasimba S.A</option>
                                    <option value="Chemaf" {{ $record->cf_agent == 'Chemaf' ? 'selected' : '' }}>Chemaf
                                    </option>
                                    <option value="Comexas Afrique"
                                        {{ $record->cf_agent == 'Comexas Afrique' ? 'selected' : '' }}>Comexas Afrique
                                    </option>
                                    <option value="Comexas" {{ $record->cf_agent == 'Comexas' ? 'selected' : '' }}>Comexas
                                    </option>
                                    <option value="DCG" {{ $record->cf_agent == 'DCG' ? 'selected' : '' }}>DCG</option>
                                    <option value="Evele & Co" {{ $record->cf_agent == 'Evele & Co' ? 'selected' : '' }}>
                                        Evele &
                                        Co</option>
                                    <option value="Gecotrans" {{ $record->cf_agent == 'Gecotrans' ? 'selected' : '' }}>
                                        Gecotrans
                                    </option>
                                    <option value="Global Logistics"
                                        {{ $record->cf_agent == 'Global Logistics' ? 'selected' : '' }}>Global Logistics
                                    </option>
                                    <option value="Malabar" {{ $record->cf_agent == 'Malabar' ? 'selected' : '' }}>Malabar
                                    </option>
                                    <option value="Polytra" {{ $record->cf_agent == 'Polytra' ? 'selected' : '' }}>Polytra
                                    </option>
                                    <option value="Spedag" {{ $record->cf_agent == 'Spedag' ? 'selected' : '' }}>Spedag
                                    </option>
                                    <option value="Tradecorp" {{ $record->cf_agent == 'Tradecorp' ? 'selected' : '' }}>
                                        Tradecorp
                                    </option>
                                    <option value="Trade Service"
                                        {{ $record->cf_agent == 'Trade Service' ? 'selected' : '' }}>
                                        Trade Service</option>
                                </select>
                            </div>

                            <div class="col-12 mb-3 col-lg-12">
                                <div class="form-label">CF Agent Contact</div>
                                <input type="text" name="cf_agent_contact" class="form-control"
                                    value="{{ $record->cf_agent_contact }}" {{ $record->status > 1 ? 'disabled' : '' }}>
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
                                    value="{{ $record->cargo_description }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-6">
                                <div class="form-label">HS Code</div>
                                <input type="text" name="hs_code" class="form-control"
                                    value="{{ $record->hs_code }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-6">
                                <div class="form-label">Package Type</div>
                                <input type="text" name="package_type" class="form-control"
                                    value="{{ $record->package_type }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-12">
                                <div class="form-label">Quantity</div>
                                <input type="text" name="quantity" class="form-control"
                                    value="{{ $record->quantity }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>

                        </div>

                    </div>

                    <div class="card-body tab-pane fade" id="tabs-home-5" role="tabpanel">

                        <!-- <h2 class="mb-4">#</h2> -->
                        <h3 class="card-title mb-5">Expedition</h3>

                        <div class="row g-3">

                            <div class="col-12 mb-3 col-lg-2">
                                <div class="form-label">PO Number</div>
                                <input type="text" name="po" class="form-control" value="{{ $record->po }}"
                                    {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>

                            <div class="col-12 mb-3 col-lg-2">
                                <div class="form-label">Company Ref</div>
                                <input type="text" name="company_ref" class="form-control"
                                    value="{{ $record->company_ref }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-4">
                                <div class="form-label">Cargo Origin</div>
                                <input type="text" name="cargo_origin" class="form-control"
                                    value="{{ $record->cargo_origin }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-4">
                                <div class="form-label">Customs Decl No</div>
                                <input type="text" name="customs_decl_no" class="form-control"
                                    value="{{ $record->customs_decl_no }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-6">
                                <div class="form-label">Manifest No</div>
                                <input type="text" name="manifest_no" class="form-control"
                                    value="{{ $record->manifest_no }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-6">
                                <div class="form-label">OCC Bivac</div>
                                <input type="text" name="occ_bivac" class="form-control"
                                    value="{{ $record->occ_bivac }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-12">
                                <div class="form-label">Instructions</div>
                                <input type="text" name="instructions" class="form-control"
                                    value="{{ $record->instructions }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>

                        </div>

                    </div>

                    <div class="card-body tab-pane fade" id="tabs-home-6" role="tabpanel">

                        <!-- <h2 class="mb-4">#</h2> -->
                        <h3 class="card-title mb-5">Values</h3>

                        <div class="row g-3">

                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">FOB Currency</div>
                                <select class="form-select" name="fob_currency"
                                    {{ $record->status > 1 ? 'disabled' : '' }} required>
                                    <option value="" {{ !$record->fob_currency ? 'selected' : '' }}>-- select --
                                    </option>
                                    <option value="USD" {{ $record->fob_currency == 'USD' ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="EUR" {{ $record->fob_currency == 'EUR' ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="TZS" {{ $record->fob_currency == 'TZS' ? 'selected' : '' }}>TZS
                                    </option>
                                    <option value="ZAR" {{ $record->fob_currency == 'ZAR' ? 'selected' : '' }}>ZAR
                                    </option>
                                    <option value="AOA" {{ $record->fob_currency == 'AOA' ? 'selected' : '' }}>AOA
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">FOB Value</div>
                                <input type="text" name="fob_value" class="form-control"
                                    value="{{ $record->fob_value }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">Incoterm</div>
                                <select class="form-select" name="incoterm" {{ $record->status > 1 ? 'disabled' : '' }}
                                    required>
                                    <option value="" {{ !$record->incoterm ? 'selected' : '' }}>-- select --
                                    </option>
                                    <option value="CFR" {{ $record->incoterm == 'CFR' ? 'selected' : '' }}>CFR
                                    </option>
                                    <option value="CIF" {{ $record->incoterm == 'CIF' ? 'selected' : '' }}>CIF
                                    </option>
                                    <option value="CIP" {{ $record->incoterm == 'CIP' ? 'selected' : '' }}>CIP
                                    </option>
                                    <option value="CPT" {{ $record->incoterm == 'CPT' ? 'selected' : '' }}>CPT
                                    </option>
                                    <option value="DAF" {{ $record->incoterm == 'DAF' ? 'selected' : '' }}>DAF
                                    </option>
                                    <option value="DAP" {{ $record->incoterm == 'DAP' ? 'selected' : '' }}>DAP
                                    </option>
                                    <option value="DAT" {{ $record->incoterm == 'DAT' ? 'selected' : '' }}>DAT
                                    </option>
                                    <option value="DDP" {{ $record->incoterm == 'DDP' ? 'selected' : '' }}>DDP
                                    </option>
                                    <option value="DDU" {{ $record->incoterm == 'DDU' ? 'selected' : '' }}>DDU
                                    </option>
                                    <option value="DEQ" {{ $record->incoterm == 'DEQ' ? 'selected' : '' }}>DEQ
                                    </option>
                                    <option value="DES" {{ $record->incoterm == 'DES' ? 'selected' : '' }}>DES
                                    </option>
                                    <option value="DPU" {{ $record->incoterm == 'DPU' ? 'selected' : '' }}>DPU
                                    </option>
                                    <option value="EXW" {{ $record->incoterm == 'EXW' ? 'selected' : '' }}>EXW
                                    </option>
                                    <option value="FAS" {{ $record->incoterm == 'FAS' ? 'selected' : '' }}>FAS
                                    </option>
                                    <option value="FCA" {{ $record->incoterm == 'FCA' ? 'selected' : '' }}>FCA
                                    </option>
                                    <option value="FOB" {{ $record->incoterm == 'FOB' ? 'selected' : '' }}>FOB
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">Freight Currency</div>
                                <select class="form-select" name="freight_currency"
                                    {{ $record->status > 1 ? 'disabled' : '' }} required>
                                    <option value="" {{ !$record->freight_currency ? 'selected' : '' }}>-- select
                                        --</option>
                                    <option value="USD" {{ $record->freight_currency == 'USD' ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="EUR" {{ $record->freight_currency == 'EUR' ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="TZS" {{ $record->freight_currency == 'TZS' ? 'selected' : '' }}>TZS
                                    </option>
                                    <option value="ZAR" {{ $record->freight_currency == 'ZAR' ? 'selected' : '' }}>ZAR
                                    </option>
                                    <option value="AOA" {{ $record->freight_currency == 'AOA' ? 'selected' : '' }}>AOA
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">Freight Value</div>
                                <input type="text" name="freight_value" class="form-control"
                                    value="{{ $record->freight_value }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">Insurance Currency</div>
                                <select class="form-select" name="insurance_currency"
                                    {{ $record->status > 1 ? 'disabled' : '' }} required>
                                    <option value="" {{ !$record->insurance_currency ? 'selected' : '' }}>-- select
                                        --</option>
                                    <option value="USD" {{ $record->insurance_currency == 'USD' ? 'selected' : '' }}>
                                        USD
                                    </option>
                                    <option value="EUR" {{ $record->insurance_currency == 'EUR' ? 'selected' : '' }}>
                                        EUR
                                    </option>
                                    <option value="TZS" {{ $record->insurance_currency == 'TZS' ? 'selected' : '' }}>
                                        TZS
                                    </option>
                                    <option value="ZAR" {{ $record->insurance_currency == 'ZAR' ? 'selected' : '' }}>
                                        ZAR
                                    </option>
                                    <option value="AOA" {{ $record->insurance_currency == 'AOA' ? 'selected' : '' }}>
                                        AOA
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">Insurance Value</div>
                                <input type="text" name="insurance_value" class="form-control"
                                    value="{{ $record->insurance_value }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">Additional Fees Currency</div>
                                <select class="form-select" name="additional_fees_currency"
                                    {{ $record->status > 1 ? 'disabled' : '' }} required>
                                    <option value="" {{ !$record->additional_fees_currency ? 'selected' : '' }}>--
                                        select --
                                    </option>
                                    <option value="USD"
                                        {{ $record->additional_fees_currency == 'USD' ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="EUR"
                                        {{ $record->additional_fees_currency == 'EUR' ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="TZS"
                                        {{ $record->additional_fees_currency == 'TZS' ? 'selected' : '' }}>TZS
                                    </option>
                                    <option value="ZAR"
                                        {{ $record->additional_fees_currency == 'ZAR' ? 'selected' : '' }}>ZAR
                                    </option>
                                    <option value="AOA"
                                        {{ $record->additional_fees_currency == 'AOA' ? 'selected' : '' }}>AOA
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 mb-3 col-lg-12">
                                <div class="form-label">Additional Fees Value</div>
                                <input type="text" name="additional_fees_value" class="form-control"
                                    value="{{ $record->additional_fees_value }}"
                                    {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            @if ($documents)
                                @foreach ($documents as $type => $path)
                                    <div
                                        class="col-12 mb-3 col-lg-{{ $record->status == 1 ? '6' : ($record->status != 1 ? '4' : '') }}">
                                        <div class="form-label">{{ ucfirst(str_replace('_', ' ', $type)) }}</div>
                                        <a href="{{ route('file.downloadfile', ['id' => $record->id, 'type' => $type]) }}"
                                            download>
                                            <div class="card py-1">
                                                <div class="card-body p-1">
                                                    Download
                                                    File
                                                </div>
                                                <div class="ribbon ribbon-top">
                                                    <i class="fa fa-file"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    @if ($record->status == 1)
                                        <div
                                            class="col-12 mb-3 col-lg-{{ $record->status == 1 ? '6' : ($record->status != 1 ? '4' : '') }}">
                                            <div class="form-label">Edit {{ ucfirst(str_replace('_', ' ', $type)) }}
                                            </div>
                                            <input type="file" name="{{ $type }}" class="form-control">
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                    </div>

                    @if ($record->applicationFile)
                        <div class="card-body tab-pane fade" id="tabs-home-7" role="tabpanel">
                            <!-- <h2 class="mb-4">#</h2> -->
                            <h3 class="card-title mb-5">Draft</h3>

                            <div class="row g-3">

                                <div class="col-12 mb-3 col-lg-12">
                                    <div class="form-label">Document</div>
                                    <input type="text" name="#" class="form-control"
                                        value="{{ $record->type }}" disabled />
                                </div>

                                <a href="{{ route('certificate.downloaddraft', ['id' => $record->id]) }}"
                                    class="text-decoration-none" download>
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
                                    <a href="{{ route('invoices.downloadinvoice', ['id' => $record->id]) }}"
                                        target="_blanck" class="text-decoration-none">
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

                    @if ($record->certificateFile)
                        <div class="card-body tab-pane fade" id="tabs-home-8" role="tabpanel">
                            <!-- <h2 class="mb-4">#</h2> -->
                            <h3 class="card-title mb-5">Certificate</h3>

                            <div class="row g-3">

                                <div class="col-12 mb-3 col-lg-12">
                                    <div class="form-label">Document</div>
                                    <input type="text" name="#" class="form-control"
                                        value="{{ $record->type }}" disabled />
                                </div>

                                <a href="{{ route('certificate.download', ['id' => $record->id]) }}"
                                    class="text-decoration-none" download>
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
                                    <a href="{{ route('invoices.downloadinvoice', ['id' => $record->id]) }}"
                                        target="_blank" class="text-decoration-none">
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
                            <a href="{{ route(Auth::user()->role . '' . '.showApps') }}"
                                class="btn btn-outline-secondary">Cancel</a>
                            <button class="btn btn-primary" type="submit">Edit</button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
        </form>
        </div>
    @else
        <div class="card">
            <div class="row g-0">
                <div class="col-12 col-md-2 border-end">
                    <div class="card-body">
                        <h4 class="subheader"></h4>
                        <div class="list-group list-group-transparent nav nav-tabs card-header-tabs" data-bs-toggle="tabs"
                            role="tablist">

                            @if ($record->applicationFile && !$record->certificateFile)
                                <div class="nav-item border border-dark border-opacity-50" role="presentation">
                                    <a href="#tabs-home-7"
                                        class="list-group-item list-group-item-action d-flex align-items-center nav-link"
                                        data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                        Draft
                                        <i class="fa fa-circle-down ms-2"></i>
                                    </a>
                                </div>
                            @endif

                            @if ($record->certificateFile)
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
                                    Freight Details
                                </a>
                            </div>

                            <div class="nav-item" role="presentation">
                                <a href="#tabs-home-2"
                                    class="list-group-item list-group-item-action d-flex align-items-center nav-link"
                                    data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                    Transport & Cargo Details
                                </a>
                            </div>

                            <div class="nav-item" role="presentation">
                                <a href="#tabs-home-3"
                                    class="list-group-item list-group-item-action d-flex align-items-center nav-link"
                                    data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                    Consignment Details
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
                            <h3 class="card-title mb-5">Freight Details</h3>

                            <div class="row g-3">

                                <div class="col-12 mb-3 col-lg-3">
                                    <div class="form-label">Applicant</div>
                                    <input type="text" name="user_id" class="form-control"
                                        value="{{ $record->applicant }}" disabled>
                                </div>

                                <div class="col-12 mb-3 col-lg-3">
                                    <div class="form-label">Company Ref</div>
                                    <input type="text" name="company_ref" class="form-control"
                                        value="{{ $record->company_ref }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                                </div>

                                <div class="col-12 mb-3 col-lg-3">
                                    <div class="form-label">PO Number</div>
                                    <input type="text" name="po" class="form-control"
                                        value="{{ $record->po }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                                </div>

                                <div class="col-12 mb-3 col-lg-3">
                                    <div class="form-label">Entry Border DRC</div>

                                    <select class="form-select" name="entry_border_drc"
                                        {{ $record->status > 1 ? 'disabled' : '' }} required>
                                        <option value="0"
                                            {{ $record->entry_border_drc == '0' || !$record->entry_border_drc ? 'selected' : '' }}>
                                            -- select --</option>
                                        <option value="Kasumbalesa"
                                            {{ $record->entry_border_drc == 'Kasumbalesa' ? 'selected' : '' }}>Kasumbalesa
                                        </option>
                                        <option value="Mokambo"
                                            {{ $record->entry_border_drc == 'Mokambo' ? 'selected' : '' }}>
                                            Mokambo</option>
                                        <option value="Sakania"
                                            {{ $record->entry_border_drc == 'Sakania' ? 'selected' : '' }}>
                                            Sakania</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-3 col-lg-4">
                                    <div class="form-label">Final Destination</div>
                                    <select class="form-select" name="final_destination"
                                        {{ $record->status > 1 ? 'disabled' : '' }} required>
                                        <option value="" {{ !$record->final_destination ? 'selected' : '' }}>--
                                            select --
                                        </option>
                                        <option value="Likasi DRC"
                                            {{ $record->final_destination == 'Likasi DRC' ? 'selected' : '' }}>Likasi DRC
                                        </option>
                                        <option value="Lubumbashi DRC"
                                            {{ $record->final_destination == 'Lubumbashi DRC' ? 'selected' : '' }}>
                                            Lubumbashi
                                            DRC</option>
                                        <option value="Kolwezi DRC"
                                            {{ $record->final_destination == 'Kolwezi DRC' ? 'selected' : '' }}>Kolwezi
                                            DRC
                                        </option>
                                        <option value="Tenke DRC"
                                            {{ $record->final_destination == 'Tenke DRC' ? 'selected' : '' }}>Tenke DRC
                                        </option>
                                        <option value="Kisanfu DRC"
                                            {{ $record->final_destination == 'Kisanfu DRC' ? 'selected' : '' }}>Kisanfu
                                            DRC
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Border ETA</label>
                                    <input type="date" class="form-control" name="arrival_date"
                                        value="{{ $record->arrival_date }}" autocomplete="on"
                                        {{ $record->status > 1 ? 'disabled' : '' }} required />
                                </div>

                                <div class="col-12 mb-3 col-lg-4">
                                    <div class="form-label">Customs Decl No</div>
                                    <input type="text" name="customs_decl_no" class="form-control"
                                        value="{{ $record->customs_decl_no }}"
                                        {{ $record->status > 1 ? 'disabled' : '' }}>
                                </div>
                                <div class="col-12 mb-3 col-lg-6">
                                    <div class="form-label">Arrival Station</div>
                                    <input type="text" name="arrival_station" class="form-control"
                                        value="{{ $record->arrival_station }}"
                                        {{ $record->status > 1 ? 'disabled' : '' }}>
                                </div>
                                <div class="col-12 mb-3 col-lg-6">
                                    <div class="form-label">Truck Details</div>
                                    <input type="text" name="truck_details" class="form-control"
                                        value="{{ $record->truck_details }}"
                                        {{ $record->status > 1 ? 'disabled' : '' }}>
                                </div>

                            </div>
                    </div>

                    <div class="card-body tab-pane fade" id="tabs-home-2" role="tabpanel">

                        <!-- <h2 class="mb-4">#</h2> -->
                        <h3 class="card-title mb-5">Transport & Cargo Details</h3>

                        <div class="row g-3">
                            <input type="hidden" name="feri_type" value="{{ $record->feri_type }}" require />

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Transporter Company</label>
                                <select class="form-select" name="transporter_company"
                                    {{ $record->status > 1 ? 'disabled' : '' }}>
                                    <option value="">-- select --</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ old('transporter_company', $record->transporter_company ?? '') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="transporter_company"
                                    value="{{ $record->transporter_company }}">
                            </div>

                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">Quantity</div>
                                <input type="text" name="quantity" class="form-control"
                                    value="{{ $record->quantity }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>

                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">Weight</div>
                                <input type="text" name="weight" class="form-control"
                                    value="{{ $record->weight }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>

                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">Volume</div>
                                <input type="text" name="volume" class="form-control"
                                    value="{{ $record->volume }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>

                            <div class="col-12 mb-3 col-lg-12">
                                <div class="form-label">Importer Name</div>
                                <input type="text" name="importer_name" class="form-control"
                                    value="{{ $record->importer_name }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 mb-3 col-lg-12">
                                <label class="form-label">CF Agent</label>
                                <select class="form-select" name="cf_agent" {{ $record->status > 1 ? 'disabled' : '' }}
                                    required>
                                    <option value="" {{ !$record->cf_agent ? 'selected' : '' }}>-- select --
                                    </option>
                                    <option value="AGL" {{ $record->cf_agent == 'AGL' ? 'selected' : '' }}>AGL
                                    </option>
                                    <option value="CARGO CONGO"
                                        {{ $record->cf_agent == 'CARGO CONGO' ? 'selected' : '' }}>CARGO
                                        CONGO</option>
                                    <option value="CONNEX" {{ $record->cf_agent == 'CONNEX' ? 'selected' : '' }}>CONNEX
                                    </option>
                                    <option value="African Logistics"
                                        {{ $record->cf_agent == 'African Logistics' ? 'selected' : '' }}>African Logistics
                                    </option>
                                    <option value="Afritac" {{ $record->cf_agent == 'Afritac' ? 'selected' : '' }}>
                                        Afritac
                                    </option>
                                    <option value="Amicongo" {{ $record->cf_agent == 'Amicongo' ? 'selected' : '' }}>
                                        Amicongo
                                    </option>
                                    <option value="Aristote" {{ $record->cf_agent == 'Aristote' ? 'selected' : '' }}>
                                        Aristote
                                    </option>
                                    <option value="Bollore" {{ $record->cf_agent == 'Bollore' ? 'selected' : '' }}>
                                        Bollore
                                    </option>
                                    <option value="Brasimba" {{ $record->cf_agent == 'Brasimba' ? 'selected' : '' }}>
                                        Brasimba
                                    </option>
                                    <option value="Brasimba S.A"
                                        {{ $record->cf_agent == 'Brasimba S.A' ? 'selected' : '' }}>
                                        Brasimba S.A</option>
                                    <option value="Chemaf" {{ $record->cf_agent == 'Chemaf' ? 'selected' : '' }}>Chemaf
                                    </option>
                                    <option value="Comexas Afrique"
                                        {{ $record->cf_agent == 'Comexas Afrique' ? 'selected' : '' }}>Comexas Afrique
                                    </option>
                                    <option value="Comexas" {{ $record->cf_agent == 'Comexas' ? 'selected' : '' }}>
                                        Comexas
                                    </option>
                                    <option value="DCG" {{ $record->cf_agent == 'DCG' ? 'selected' : '' }}>DCG
                                    </option>
                                    <option value="Evele & Co" {{ $record->cf_agent == 'Evele & Co' ? 'selected' : '' }}>
                                        Evele &
                                        Co</option>
                                    <option value="Gecotrans" {{ $record->cf_agent == 'Gecotrans' ? 'selected' : '' }}>
                                        Gecotrans
                                    </option>
                                    <option value="Global Logistics"
                                        {{ $record->cf_agent == 'Global Logistics' ? 'selected' : '' }}>Global Logistics
                                    </option>
                                    <option value="Malabar" {{ $record->cf_agent == 'Malabar' ? 'selected' : '' }}>
                                        Malabar
                                    </option>
                                    <option value="Polytra" {{ $record->cf_agent == 'Polytra' ? 'selected' : '' }}>
                                        Polytra
                                    </option>
                                    <option value="Spedag" {{ $record->cf_agent == 'Spedag' ? 'selected' : '' }}>Spedag
                                    </option>
                                    <option value="Tradecorp" {{ $record->cf_agent == 'Tradecorp' ? 'selected' : '' }}>
                                        Tradecorp
                                    </option>
                                    <option value="Trade Service"
                                        {{ $record->cf_agent == 'Trade Service' ? 'selected' : '' }}>
                                        Trade Service</option>
                                </select>
                            </div>

                        </div>

                    </div>

                    <div class="card-body tab-pane fade" id="tabs-home-3" role="tabpanel">

                        <!-- <h2 class="mb-4">#</h2> -->
                        <h3 class="card-title mb-5">Consignment Details</h3>

                        <div class="row g-3">

                            <div class="col-12 mb-3 col-lg-6">
                                <div class="form-label">Exporter Name</div>
                                <input type="text" name="exporter_name" class="form-control"
                                    value="{{ $record->exporter_name }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>

                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">Freight Currency</div>
                                <select class="form-select" name="freight_currency"
                                    {{ $record->status > 1 ? 'disabled' : '' }} required>
                                    <option value="" {{ !$record->freight_currency ? 'selected' : '' }}>-- select
                                        --</option>
                                    <option value="USD" {{ $record->freight_currency == 'USD' ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="EUR" {{ $record->freight_currency == 'EUR' ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="TZS" {{ $record->freight_currency == 'TZS' ? 'selected' : '' }}>TZS
                                    </option>
                                    <option value="ZAR" {{ $record->freight_currency == 'ZAR' ? 'selected' : '' }}>ZAR
                                    </option>
                                    <option value="AOA" {{ $record->freight_currency == 'AOA' ? 'selected' : '' }}>AOA
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 mb-3 col-lg-3">
                                <div class="form-label">Freight Value</div>
                                <input type="text" name="freight_value" class="form-control"
                                    value="{{ $record->freight_value }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>

                            <div class="col-12 mb-3 col-lg-6">
                                <div class="form-label">FOB Value</div>
                                <input type="text" name="fob_value" class="form-control"
                                    value="{{ $record->fob_value }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>

                            <div class="col-12 mb-3 col-lg-6">
                                <div class="form-label">Insurance Value</div>
                                <input type="text" name="insurance_value" class="form-control"
                                    value="{{ $record->insurance_value }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>

                            <div class="col-12 mb-3 col-lg-12">
                                <div class="form-label">Instructions</div>
                                <input type="text" name="instructions" class="form-control"
                                    value="{{ $record->instructions }}" {{ $record->status > 1 ? 'disabled' : '' }}>
                            </div>


                            @if ($documents)
                                @foreach ($documents as $type => $path)
                                    <div
                                        class="col-12 mb-3 col-lg-{{ $record->status == 1 ? '6' : ($record->status != 1 ? '4' : '') }}">
                                        <div class="form-label">{{ ucfirst(str_replace('_', ' ', $type)) }}</div>
                                        <a href="{{ route('file.downloadfile', ['id' => $record->id, 'type' => $type]) }}"
                                            download>
                                            <div class="card py-1">
                                                <div class="card-body p-1">
                                                    Download
                                                    File
                                                </div>
                                                <div class="ribbon ribbon-top">
                                                    <i class="fa fa-file"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    @if ($record->status == 1)
                                        <div
                                            class="col-12 mb-3 col-lg-{{ $record->status == 1 ? '6' : ($record->status != 1 ? '4' : '') }}">
                                            <div class="form-label">Edit {{ ucfirst(str_replace('_', ' ', $type)) }}
                                            </div>
                                            <input type="file" name="{{ $type }}" class="form-control">
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                    </div>

                    @if ($record->applicationFile)
                        <div class="card-body tab-pane fade" id="tabs-home-7" role="tabpanel">
                            <!-- <h2 class="mb-4">#</h2> -->
                            <h3 class="card-title mb-5">Draft</h3>

                            <div class="row g-3">

                                <div class="col-12 mb-3 col-lg-12">
                                    <div class="form-label">Document</div>
                                    <input type="text" name="#" class="form-control"
                                        value="{{ $record->type }}" disabled />
                                </div>

                                <a href="{{ route('certificate.downloaddraft', ['id' => $record->id]) }}"
                                    class="text-decoration-none" download>
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
                                    <a href="{{ route('invoices.downloadinvoice', ['id' => $record->id]) }}"
                                        target="_blanck" class="text-decoration-none">
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

                    @if ($record->certificateFile)
                        <div class="card-body tab-pane fade" id="tabs-home-8" role="tabpanel">
                            <!-- <h2 class="mb-4">#</h2> -->
                            <h3 class="card-title mb-5">Certificate</h3>

                            <div class="row g-3">

                                <div class="col-12 mb-3 col-lg-12">
                                    <div class="form-label">Document</div>
                                    <input type="text" name="#" class="form-control"
                                        value="{{ $record->type }}" disabled />
                                </div>

                                <a href="{{ route('certificate.download', ['id' => $record->id]) }}"
                                    class="text-decoration-none" download>
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
                                    <a href="{{ route('invoices.downloadinvoice', ['id' => $record->id]) }}"
                                        target="_blank" class="text-decoration-none">
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
                            <a href="{{ route(Auth::user()->role . '' . '.showApps') }}"
                                class="btn btn-outline-secondary">Cancel</a>
                            <button class="btn btn-primary" type="submit">Edit</button>
                        </div>
                    </div>
                @endif


            </div>
            </form>
        </div>
    @endif


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
                                    <form action="{{ route('transporter.sendchat', ['id' => $record->id]) }}"
                                        method="POST">
                                        @csrf

                                        @foreach ($chats as $chat)
                                            @if ($chat->user_id == Auth::user()->id)
                                                <div class="chat-item mb-3">
                                                    <div class="row align-items-end justify-content-end">
                                                        <div class="col col-lg-10">
                                                            <div class="chat-bubble chat-bubble-me">
                                                                @if ($chat->del == 0)
                                                                    <div class="chat-bubble-title">
                                                                        <div class="row">
                                                                            <div class="col chat-bubble-author">
                                                                                {{ Auth::user()->name }}
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
                                                                            <span
                                                                                class="fs-5">{{ $chat->formatted_date }}</span>
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
                                                                @if ($chat->del == 0)
                                                                    <div class="chat-bubble-title">
                                                                        <div class="row">
                                                                            <div class="col chat-bubble-author">Vendor
                                                                            </div>
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
                                                                            <span
                                                                                class="fs-5">{{ $chat->formatted_date }}</span>
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
