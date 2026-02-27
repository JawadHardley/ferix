@extends('layouts.userlayout')
@section('content')
    <style>
        .form-section {
            opacity: 0;
            transform: translateY(-8px);
            max-height: 0;
            overflow: hidden;
            transition:
                opacity 0.3s ease,
                transform 0.3s ease,
                max-height 0.35s ease;
        }

        .form-section.show {
            opacity: 1;
            transform: translateY(0);
            max-height: 100%;
            /* must be larger than content */
        }
    </style>

    <x-errorshow />
    <div class="row mb-3">
        <div class="col-lg-6">
            <div class="card p-3">
                <h1 class="mb-2">Create New Template</h1>
                <p class="lead">
                    Create a new template by filling in the required details. This template will be used for generating FERI
                    applications.
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-3">
                <p class="fs-3 mb-0">
                <div class="w-100">
                    <h1 class="mb-2">Feri Type</h1>
                    <select id="feryType" class="form-select">
                        <option value="0">-- Choose Fery Type --</option>
                        <option value="1">Reginal</option>
                        <option value="2">Continuance</option>
                    </select>
                </div>
                </p>
                {{-- <a href="{{ route('transporter.feri.template.download') }}" class="btn btn-success">
                    <i class="fa fa-circle-down me-2"></i> Download Template
                </a> --}}
            </div>
        </div>
    </div>

    {{-- this is for regional form --}}
    {{-- this is for regional form --}}
    {{-- this is for regional form --}}
    {{-- this is for regional form --}}

    <div class="regional form-section" id="regionalSection">
        <form action="{{ route('transporter.saveTemplate') }}" method="POST" enctype="multipart/form-data" class="w-100"
            id="multiStepForm" novalidate>
            @csrf

            <input type="hidden" name="type" value="regional" />

            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                <h3 class="m-3">Template Name</h3>
                <div class="row d-flex align-items-start p-4">
                    <div class="col-12 col-md-12 tab-content" id="v-pills-tabContent">
                        <input type="text" class="form-control" name="template_name" value="{{ old('template_name') }}"
                            autocomplete="on" placeholder="Enter Template Name" required />
                    </div>
                </div>

            </div>

            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                <h3 class="m-3">Transport & Cargo Details</h3>
                <div class="row d-flex align-items-start p-4">
                    <div class="col-12 col-md-12 tab-content px-5" id="v-pills-tabContent">
                        <div class="tab-panel fade show active" id="v-pills-home" role="tabpanel"
                            aria-labelledby="v-pills-home-tab" tabindex="0">
                            <div class="row">
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Transport Mode</label>
                                    <select class="form-select" name="transport_mode" autocomplete="on" required>
                                        <option value="">-- select --</option>
                                        <option value="Road" {{ old('transport_mode') == 'Road' ? 'selected' : '' }}>Road
                                        </option>
                                        <option value="Air" {{ old('transport_mode') == 'Air' ? 'selected' : '' }}>Air
                                        </option>
                                        <option value="Maritime"
                                            {{ old('transport_mode') == 'Maritime' ? 'selected' : '' }}>
                                            Maritime</option>
                                        <option value="Rail" {{ old('transport_mode') == 'Rail' ? 'selected' : '' }}>Rail
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Transporter Company</label>
                                    <select class="form-select" name="transporter_company">
                                        <option value="">-- select --</option>
                                        @foreach ($records as $record)
                                            <option value="{{ $record->id }}"
                                                {{ old('transporter_company', $dbValue->transporter_company ?? '') == $record->id ? 'selected' : '' }}>
                                                {{ $record->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Entry Border to DRC</label>
                                    <select class="form-select" name="entry_border_drc">
                                        <option value="0" {{ old('entry_border_drc') == '0' ? 'selected' : '' }}>--
                                            select
                                            --</option>
                                        <option value="Kasumbalesa"
                                            {{ old('entry_border_drc') == 'Kasumbalesa' ? 'selected' : '' }}>Kasumbalesa
                                        </option>
                                        <option value="Mokambo"
                                            {{ old('entry_border_drc') == 'Mokambo' ? 'selected' : '' }}>
                                            Mokambo</option>
                                        <option value="Sakania"
                                            {{ old('entry_border_drc') == 'Sakania' ? 'selected' : '' }}>
                                            Sakania</option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Border ETA</label>
                                    <input type="date" class="form-control" name="arrival_date"
                                        value="{{ old('arrival_date') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Truck Details</label>
                                    <input type="text" class="form-control" name="truck_details"
                                        value="{{ old('truck_details') }}" autocomplete="on" required />
                                    <input type="hidden" class="form-control" name="feri_type" value="regional"
                                        autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Port of Arrival <span
                                            class="fs-6">(Rail/Air/Port)</span></label>
                                    <input type="text" class="form-control" name="arrival_station"
                                        value="{{ old('arrival_station') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Final Destination</label>
                                    <select class="form-select" name="final_destination">
                                        <option value="0" {{ old('final_destination') == '0' ? 'selected' : '' }}>--
                                            select
                                            --</option>
                                        <option value="Likasi DRC"
                                            {{ old('final_destination') == 'Likasi DRC' ? 'selected' : '' }}>Likasi DRC
                                        </option>
                                        <option value="Kolwezi DRC"
                                            {{ old('final_destination') == 'Kolwezi DRC' ? 'selected' : '' }}>Kolwezi DRC
                                        </option>
                                        <option value="Lubumbashi DRC"
                                            {{ old('final_destination') == 'Lubumbashi DRC' ? 'selected' : '' }}>
                                            Lubumbashi DRC</option>
                                        <option value="Tenke DRC"
                                            {{ old('final_destination') == 'Tenke DRC' ? 'selected' : '' }}>
                                            Tenke DRC</option>
                                        <option value="Kisanfu DRC"
                                            {{ old('final_destination') == 'Kisanfu DRC' ? 'selected' : '' }}>
                                            Kisanfu DRC</option>
                                        <option value="Lualaba DRC"
                                            {{ old('final_destination') == 'Lualaba DRC' ? 'selected' : '' }}>
                                            Lualaba DRC</option>
                                        <option value="Pumpi DRC"
                                            {{ old('final_destination') == 'Pumpi DRC' ? 'selected' : '' }}>
                                            Pumpi DRC</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                <h3 class="m-3">Importer Details</h3>

                <div class="row d-flex align-items-start p-4">
                    <div class="col-12 col-md-12 tab-content px-5" id="v-pills-tabContent">
                        <div class="tab-panel fade show" id="v-pills-home" role="tabpanel"
                            aria-labelledby="v-pills-home-tab" tabindex="0">
                            <div class="row">

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Importer Name</label>
                                    <input type="text" class="form-control" name="importer_name"
                                        value="{{ old('importer_name') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Importer Address</label>
                                    <input type="text" class="form-control" name="importer_address"
                                        value="{{ old('importer_address') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Importer Phone</label>
                                    <input type="text" class="form-control" name="importer_phone"
                                        value="{{ old('importer_phone') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Importer Email</label>
                                    <input type="text" class="form-control" name="importer_email"
                                        value="{{ old('importer_email') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-12 mb-3">
                                    <label class="form-label">FXI Number</label>
                                    <input type="text" class="form-control" name="fix_number"
                                        value="{{ old('fix_number') }}" autocomplete="on" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                <h3 class="m-3">Export Details</h3>

                <div class="row d-flex align-items-start p-4">
                    <div class="col-12 col-md-12 tab-content px-5" id="v-pills-tabContent">
                        <div class="tab-panel fade show" id="v-pills-disabled" role="tabpanel"
                            aria-labelledby="v-pills-disabled-tab" tabindex="0">
                            <div class="row">
                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Exporter Name</label>
                                    <input type="text" class="form-control" name="exporter_name"
                                        value="{{ old('exporter_name') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Exporter Phone</label>
                                    <input type="text" class="form-control" name="exporter_phone"
                                        value="{{ old('exporter_phone') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Exporter Email</label>
                                    <input type="email" class="form-control" name="exporter_email"
                                        value="{{ old('exporter_email') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-12 mb-3">
                                    <label class="form-label">Exporter Address</label>
                                    <input type="text" class="form-control" name="exporter_address"
                                        value="{{ old('exporter_address') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Clearing/Forwarding Agent</label>
                                    <select class="form-select" name="cf_agent">
                                        <option value="0" {{ old('cf_agent') == '0' ? 'selected' : '' }}>-- select --
                                        </option>
                                        <option value="AGL" {{ old('cf_agent') == 'AGL' ? 'selected' : '' }}>AGL
                                        </option>
                                        <option value="CARGO CONGO"
                                            {{ old('cf_agent') == 'CARGO CONGO' ? 'selected' : '' }}>
                                            CARGO CONGO</option>
                                        <option value="CONNEX" {{ old('cf_agent') == 'CONNEX' ? 'selected' : '' }}>CONNEX
                                        </option>
                                        <option value="African Logistics"
                                            {{ old('cf_agent') == 'African Logistics' ? 'selected' : '' }}>
                                            African Logistics
                                        </option>
                                        <option value="Afritac" {{ old('cf_agent') == 'Afritac' ? 'selected' : '' }}>
                                            Afritac
                                        </option>
                                        <option value="Amicongo" {{ old('cf_agent') == 'Amicongo' ? 'selected' : '' }}>
                                            Amicongo</option>
                                        <option value="Aristote" {{ old('cf_agent') == 'Aristote' ? 'selected' : '' }}>
                                            Aristote</option>
                                        <option value="Bollore" {{ old('cf_agent') == 'Bollore' ? 'selected' : '' }}>
                                            Bollore
                                        </option>
                                        <option value="Brasimba" {{ old('cf_agent') == 'Brasimba' ? 'selected' : '' }}>
                                            Brasimba</option>
                                        <option value="Brasimba S.A"
                                            {{ old('cf_agent') == 'Brasimba S.A' ? 'selected' : '' }}>
                                            Brasimba S.A</option>
                                        <option value="COSMOS" {{ old('cf_agent') == 'COSMOS' ? 'selected' : '' }}>
                                            COSMOS</option>
                                        <option value="OLA" {{ old('cf_agent') == 'OLA' ? 'selected' : '' }}>OLA
                                        </option>
                                        <option value="Chemaf" {{ old('cf_agent') == 'Chemaf' ? 'selected' : '' }}>Chemaf
                                        </option>
                                        <option value="Comexas Afrique"
                                            {{ old('cf_agent') == 'Comexas Afrique' ? 'selected' : '' }}>
                                            Comexas Afrique
                                        </option>
                                        <option value="Comexas" {{ old('cf_agent') == 'Comexas' ? 'selected' : '' }}>
                                            Comexas
                                        </option>
                                        <option value="DCG" {{ old('cf_agent') == 'DCG' ? 'selected' : '' }}>DCG
                                        </option>
                                        <option value="Evele & Co"
                                            {{ old('cf_agent') == 'Evele & Co' ? 'selected' : '' }}>
                                            Evele & Co</option>
                                        <option value="Gecotrans" {{ old('cf_agent') == 'Gecotrans' ? 'selected' : '' }}>
                                            Gecotrans</option>
                                        <option value="Global Logistics"
                                            {{ old('cf_agent') == 'Global Logistics' ? 'selected' : '' }}>
                                            Global Logistics
                                        </option>
                                        <option value="Malabar" {{ old('cf_agent') == 'Malabar' ? 'selected' : '' }}>
                                            Malabar
                                        </option>
                                        <option value="Polytra" {{ old('cf_agent') == 'Polytra' ? 'selected' : '' }}>
                                            Polytra
                                        </option>
                                        <option value="Spedag" {{ old('cf_agent') == 'Spedag' ? 'selected' : '' }}>Spedag
                                        </option>
                                        <option value="Tradecorp" {{ old('cf_agent') == 'Tradecorp' ? 'selected' : '' }}>
                                            Tradecorp</option>
                                        <option value="Trade Service"
                                            {{ old('cf_agent') == 'Trade Service' ? 'selected' : '' }}>Trade
                                            Service
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Clearing/Forwarding Agent Contact</label>
                                    <input type="text" class="form-control" name="cf_agent_contact"
                                        value="{{ old('cf_agent_contact') }}" autocomplete="on" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                <h3 class="m-3">Cargo Description</h3>

                <div class="row d-flex align-items-start p-4">
                    <div class="col-12 col-md-12 tab-content px-5" id="v-pills-tabContent">
                        <div class="tab-panel fade show" id="v-pills-messages" role="tabpanel"
                            aria-labelledby="v-pills-messages-tab" tabindex="0">

                            <div class="row">
                                <div class="col-12 col-lg-12 mb-3">
                                    <label class="form-label">Cargo Description</label>
                                    <textarea class="form-control" name="cargo_description" rows="1" autocomplete="on" required>{{ old('cargo_description') }}</textarea>
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">HS Code</label>
                                    <input type="text" class="form-control" name="hs_code"
                                        value="{{ old('hs_code') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Package Type</label>
                                    <input type="text" class="form-control" name="package_type"
                                        value="{{ old('package_type') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Quantity (PKG)</label>
                                    <input type="number" class="form-control" name="quantity"
                                        value="{{ old('quantity') }}" autocomplete="on" required />
                                </div>
                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Weight (Gross)Kg</label>
                                    <input type="number" class="form-control" name="weight"
                                        value="{{ old('weight') }}" autocomplete="on" required />
                                </div>
                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Volume (Net Weight)T</label>
                                    <input type="number" class="form-control" name="volume"
                                        value="{{ old('volume') }}" autocomplete="on" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                <h3 class="m-3">Expedition</h3>

                <div class="row d-flex align-items-start p-4">
                    <div class="col-12 col-md-12 tab-content px-5" id="v-pills-tabContent">
                        <div class="tab-panel fade show" id="v-pills-settings" role="tabpanel"
                            aria-labelledby="v-pills-settings-tab" tabindex="0">

                            <div class="row">

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">PO <span class="fs-6 text-danger">(TBS: To be added
                                            later)</span>
                                    </label>
                                    <input type="text" class="form-control" name="po"
                                        value="{{ old('po') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Company Ref <span class="fs-6 text-danger">(Trip
                                            Number)</span></label>
                                    <input type="text" class="form-control" name="company_ref"
                                        value="{{ old('company_ref') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Cargo Origin</label>
                                    <input type="text" class="form-control" name="cargo_origin"
                                        value="{{ old('cargo_origin') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Customs Declaration Number</label>
                                    <input type="text" class="form-control" name="customs_decl_no"
                                        value="{{ old('customs_decl_no') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Manifest Number / VG</label>
                                    <input type="text" class="form-control" name="manifest_no"
                                        value="{{ old('manifest_no') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">OCC/ BIVAC</label>
                                    <input type="text" class="form-control" name="occ_bivac"
                                        value="{{ old('occ_bivac') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-12 mb-3">
                                    <label class="form-label">Additional Comments</label>
                                    <textarea class="form-control" name="instructions" rows="1" autocomplete="on" required>{{ old('instructions') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                <h3 class="m-3">Values</h3>

                <div class="row d-flex align-items-start p-4">
                    <div class="col-12 col-md-12 tab-content px-5" id="v-pills-tabContent">
                        <div class="tab-panel fade show" id="v-pills-values" role="tabpanel"
                            aria-labelledby="v-pills-values-tab" tabindex="0">

                            <div class="row">
                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">FOB Currency</label>
                                    <select class="form-select" name="fob_currency">
                                        <option value="USD" {{ old('fob_currency') == 'USD' ? 'selected' : '' }}>USD
                                        </option>
                                        <option value="EUR" {{ old('fob_currency') == 'EUR' ? 'selected' : '' }}>EUR
                                        </option>
                                        <option value="TZS" {{ old('fob_currency') == 'TZS' ? 'selected' : '' }}>TZS
                                        </option>
                                        <option value="ZAR" {{ old('fob_currency') == 'ZAR' ? 'selected' : '' }}>ZAR
                                        </option>
                                        <option value="AOA" {{ old('fob_currency') == 'AOA' ? 'selected' : '' }}>AOA
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">FOB Value / VALEUR FOB</label>
                                    <input type="text" class="form-control" name="fob_value"
                                        value="{{ old('fob_value') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Freight Currency</label>
                                    <select class="form-select" name="freight_currency">
                                        <option value="USD" {{ old('freight_currency') == 'USD' ? 'selected' : '' }}>
                                            USD
                                        </option>
                                        <option value="EUR" {{ old('freight_currency') == 'EUR' ? 'selected' : '' }}>
                                            EUR
                                        </option>
                                        <option value="ZAR" {{ old('freight_currency') == 'ZAR' ? 'selected' : '' }}>
                                            ZAR
                                        </option>
                                        <option value="AOA" {{ old('freight_currency') == 'AOA' ? 'selected' : '' }}>
                                            AOA
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Freight Value</label>
                                    <input type="text" class="form-control" name="freight_value"
                                        value="{{ old('freight_value') }}" autocomplete="on" required />
                                </div>
                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Insurance Currency</label>
                                    <select class="form-select" name="insurance_currency">
                                        <option value="USD" {{ old('insurance_currency') == 'USD' ? 'selected' : '' }}>
                                            USD
                                        </option>
                                        <option value="EUR" {{ old('insurance_currency') == 'EUR' ? 'selected' : '' }}>
                                            EUR
                                        </option>
                                        <option value="ZAR" {{ old('insurance_currency') == 'ZAR' ? 'selected' : '' }}>
                                            ZAR
                                        </option>
                                        <option value="TZS" {{ old('insurance_currency') == 'TZS' ? 'selected' : '' }}>
                                            TZS
                                        </option>
                                        <option value="AOA" {{ old('insurance_currency') == 'AOA' ? 'selected' : '' }}>
                                            AOA
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Insurance Value</label>
                                    <input type="text" class="form-control" name="insurance_value"
                                        value="{{ old('insurance_value') }}" autocomplete="on" required />
                                </div>
                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Additional Fees Currency</label>
                                    <select class="form-select" name="additional_fees_currency">
                                        <option value="USD"
                                            {{ old('additional_fees_currency') == 'USD' ? 'selected' : '' }}>USD
                                        </option>
                                        <option value="EUR"
                                            {{ old('additional_fees_currency') == 'EUR' ? 'selected' : '' }}>EUR
                                        </option>
                                        <option value="ZAR"
                                            {{ old('additional_fees_currency') == 'ZAR' ? 'selected' : '' }}>ZAR
                                        </option>
                                        <option value="TZS"
                                            {{ old('additional_fees_currency') == 'TZS' ? 'selected' : '' }}>TZS
                                        </option>
                                        <option value="AOA"
                                            {{ old('additional_fees_currency') == 'AOA' ? 'selected' : '' }}>AOA
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Additional Fees Value</label>
                                    <input type="text" class="form-control" name="additional_fees_value"
                                        value="{{ old('additional_fees_value') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Incoterm</label>
                                    <select class="form-select" name="incoterm">
                                        <option value="0" {{ old('incoterm') == '0' ? 'selected' : '' }}>-- select --
                                        </option>
                                        <option value="CFR" {{ old('incoterm') == 'CFR' ? 'selected' : '' }}>CFR
                                        </option>
                                        <option value="CIF" {{ old('incoterm') == 'CIF' ? 'selected' : '' }}>CIF
                                        </option>
                                        <option value="CIP" {{ old('incoterm') == 'CIP' ? 'selected' : '' }}>CIP
                                        </option>
                                        <option value="CPT" {{ old('incoterm') == 'CPT' ? 'selected' : '' }}>CPT
                                        </option>
                                        <option value="DAF" {{ old('incoterm') == 'DAF' ? 'selected' : '' }}>DAF
                                        </option>
                                        <option value="DAP" {{ old('incoterm') == 'DAP' ? 'selected' : '' }}>DAP
                                        </option>
                                        <option value="DAT" {{ old('incoterm') == 'DAT' ? 'selected' : '' }}>DAT
                                        </option>
                                        <option value="DDP" {{ old('incoterm') == 'DDP' ? 'selected' : '' }}>DDP
                                        </option>
                                        <option value="DDU" {{ old('incoterm') == 'DDU' ? 'selected' : '' }}>DDU
                                        </option>
                                        <option value="DEQ" {{ old('incoterm') == 'DEQ' ? 'selected' : '' }}>DEQ
                                        </option>
                                        <option value="DES" {{ old('incoterm') == 'DES' ? 'selected' : '' }}>DES
                                        </option>
                                        <option value="DPU" {{ old('incoterm') == 'DPU' ? 'selected' : '' }}>DPU
                                        </option>
                                        <option value="EXW" {{ old('incoterm') == 'EXW' ? 'selected' : '' }}>EXW
                                        </option>
                                        <option value="FAS" {{ old('incoterm') == 'FAS' ? 'selected' : '' }}>FAS
                                        </option>
                                        <option value="FCA" {{ old('incoterm') == 'FCA' ? 'selected' : '' }}>FCA
                                        </option>
                                        <option value="FOB" {{ old('incoterm') == 'FOB' ? 'selected' : '' }}>FOB
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Commercial Invoice</label>
                                    <input type="file" class="form-control" name="invoice" autocomplete="on" />
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Packing list</label>
                                    <input type="file" class="form-control" name="packing_list" autocomplete="on" />
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Manifest</label>
                                    <input type="file" class="form-control" name="manifest" autocomplete="on" />
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Custom Docs <span class="fs-6 text-danger">(Merged)</span>
                                    </label>
                                    <input type="file" class="form-control" name="customs" autocomplete="on"
                                        required />
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">

                <div class="row d-flex align-items-start p-3">
                    <div class="col-12 col-md-12 tab-content" id="v-pills-tabContent">
                        <a href="./dashboard" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn-next btn btn-outline-primary">
                            <i class="fa fa-floppy-disk pe-2"></i>
                            Save
                            Template</button>
                    </div>
                </div>

            </div>


        </form>
    </div>



    {{-- this is for continuance form --}}
    {{-- this is for continuance form --}}
    {{-- this is for continuance form --}}
    {{-- this is for continuance form --}}

    <div class="continuance form-section" id="continuanceSection">
        <form action="{{ route('transporter.saveTemplate') }}" method="POST" enctype="multipart/form-data"
            class="w-100" id="multiStepForm" novalidate>
            @csrf


            <input type="hidden" name="type" value="continuance" />

            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                <h3 class="m-3">Template Name</h3>
                <div class="row d-flex align-items-start p-4">
                    <div class="col-12 col-md-12 tab-content" id="v-pills-tabContent">
                        <input type="text" class="form-control" name="template_name"
                            value="{{ old('template_name') }}" autocomplete="on" placeholder="Enter Template Name"
                            required />
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                <h3 class="m-3">Freight Details</h3>
                <div class="row d-flex align-items-start p-4">
                    <div class="col-12 col-md-12 tab-content px-5" id="v-pills-tabContent">
                        <div class="tab-pane2 fade show active" id="v-pills-home" role="tabpanel" tabindex="0"
                            aria-labelledby="v-pills-home-tab">
                            <div class="row">

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Company Ref <span class="fs-6 text-danger">(Trip
                                            Number)</span></label>
                                    <input type="text" class="form-control" name="company_ref"
                                        value="{{ old('company_ref') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">PO <span class="fs-6 text-danger">(TBS: To be added
                                            later)</span>
                                    </label>
                                    <input type="text" class="form-control" name="po"
                                        value="{{ old('po') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Validated Feri Certificate Number</label>
                                    <input type="text" class="form-control" name="validate_feri_cert"
                                        value="{{ old('validate_feri_cert') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Entry Border to DRC</label>
                                    <select class="form-select" name="entry_border_drc">
                                        <option value="0" {{ old('entry_border_drc') == '0' ? 'selected' : '' }}>--
                                            select
                                            --</option>
                                        <option value="Kasumbalesa"
                                            {{ old('entry_border_drc') == 'Kasumbalesa' ? 'selected' : '' }}>Kasumbalesa
                                        </option>
                                        <option value="Mokambo"
                                            {{ old('entry_border_drc') == 'Mokambo' ? 'selected' : '' }}>
                                            Mokambo</option>
                                        <option value="Sakania"
                                            {{ old('entry_border_drc') == 'Sakania' ? 'selected' : '' }}>
                                            Sakania</option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Final Destination</label>
                                    <select class="form-select" name="final_destination">
                                        <option value="0" {{ old('final_destination') == '0' ? 'selected' : '' }}>--
                                            select
                                            --</option>
                                        <option value="Likasi DRC"
                                            {{ old('final_destination') == 'Likasi DRC' ? 'selected' : '' }}>Likasi DRC
                                        </option>
                                        <option value="Kolwezi DRC"
                                            {{ old('final_destination') == 'Kolwezi DRC' ? 'selected' : '' }}>Kolwezi DRC
                                        </option>
                                        <option value="Lubumbashi DRC"
                                            {{ old('final_destination') == 'Lubumbashi DRC' ? 'selected' : '' }}>
                                            Lubumbashi DRC</option>
                                        <option value="Tenke DRC"
                                            {{ old('final_destination') == 'Tenke DRC' ? 'selected' : '' }}>
                                            Tenke DRC</option>
                                        <option value="Kisanfu DRC"
                                            {{ old('final_destination') == 'Kisanfu DRC' ? 'selected' : '' }}>
                                            Kisanfu DRC</option>
                                        <option value="Lualaba DRC"
                                            {{ old('final_destination') == 'Lualaba DRC' ? 'selected' : '' }}>
                                            Lualaba DRC</option>
                                        <option value="Pumpi DRC"
                                            {{ old('final_destination') == 'Pumpi DRC' ? 'selected' : '' }}>
                                            Pumpi DRC</option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Border ETA</label>
                                    <input type="date" class="form-control" name="arrival_date"
                                        value="{{ old('arrival_date') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Customs Declaration Number</label>
                                    <input type="text" class="form-control" name="customs_decl_no"
                                        value="{{ old('customs_decl_no') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Truck Details</label>
                                    <input type="text" class="form-control" name="truck_details"
                                        value="{{ old('truck_details') }}" autocomplete="on" required />
                                    <input type="hidden" class="form-control" name="feri_type" value="continuance"
                                        autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Port of Arrival <span
                                            class="fs-6">(Rail/Air/Port)</span></label>
                                    <input type="text" class="form-control" name="arrival_station"
                                        value="{{ old('arrival_station') }}" autocomplete="on" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                <h3 class="m-3">Transport & Cargo Details</h3>
                <div class="row d-flex align-items-start p-4">
                    <div class="col-12 col-md-12 tab-content px-5" id="v-pills-tabContent">
                        <div class="tab-pane2 fade show" id="v-pills-profile" role="tabpanel"
                            aria-labelledby="v-pills-profile-tab" tabindex="0">
                            <div class="row">

                                <div class="col-12 col-lg-12 mb-3">
                                    <label class="form-label">Transporter Company</label>
                                    <select class="form-select" name="transporter_company">
                                        <option value="">-- select --</option>
                                        @foreach ($records as $record)
                                            <option value="{{ $record->id }}"
                                                {{ old('transporter_company', $dbValue->transporter_company ?? '') == $record->id ? 'selected' : '' }}>
                                                {{ $record->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" class="form-control" name="quantity"
                                        value="{{ old('quantity') }}" autocomplete="on" required />
                                </div>


                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Weights: Tons/kgs</label>
                                    <input type="number" class="form-control" name="weight"
                                        value="{{ old('weight') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-4 mb-3">
                                    <label class="form-label">Volume: CBM</label>
                                    <input type="text" class="form-control" name="volume" autocomplete="on"
                                        value="{{ old('volume') }}" required />
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Importer Name</label>
                                    <input type="text" class="form-control" name="importer_name"
                                        value="{{ old('importer_name') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Shipping Line/Agent</label>
                                    <select class="form-select" name="cf_agent">
                                        <option value="0" {{ old('cf_agent') == '0' ? 'selected' : '' }}>-- select
                                            --
                                        </option>
                                        <option value="AGL" {{ old('cf_agent') == 'AGL' ? 'selected' : '' }}>AGL
                                        </option>
                                        <option value="CARGO CONGO"
                                            {{ old('cf_agent') == 'CARGO CONGO' ? 'selected' : '' }}>
                                            CARGO CONGO</option>
                                        <option value="CONNEX" {{ old('cf_agent') == 'CONNEX' ? 'selected' : '' }}>CONNEX
                                        </option>
                                        <option value="African Logistics"
                                            {{ old('cf_agent') == 'African Logistics' ? 'selected' : '' }}>African
                                            Logistics
                                        </option>
                                        <option value="Afritac" {{ old('cf_agent') == 'Afritac' ? 'selected' : '' }}>
                                            Afritac
                                        </option>
                                        <option value="Amicongo" {{ old('cf_agent') == 'Amicongo' ? 'selected' : '' }}>
                                            Amicongo</option>
                                        <option value="Aristote" {{ old('cf_agent') == 'Aristote' ? 'selected' : '' }}>
                                            Aristote</option>
                                        <option value="Bollore" {{ old('cf_agent') == 'Bollore' ? 'selected' : '' }}>
                                            Bollore
                                        </option>
                                        <option value="Brasimba" {{ old('cf_agent') == 'Brasimba' ? 'selected' : '' }}>
                                            Brasimba</option>
                                        <option value="Brasimba S.A"
                                            {{ old('cf_agent') == 'Brasimba S.A' ? 'selected' : '' }}>
                                            Brasimba S.A</option>
                                        <option value="COSMOS" {{ old('cf_agent') == 'COSMOS' ? 'selected' : '' }}>
                                            COSMOS</option>
                                        <option value="OLA" {{ old('cf_agent') == 'OLA' ? 'selected' : '' }}>OLA
                                        </option>
                                        <option value="Chemaf" {{ old('cf_agent') == 'Chemaf' ? 'selected' : '' }}>Chemaf
                                        </option>
                                        <option value="Comexas Afrique"
                                            {{ old('cf_agent') == 'Comexas Afrique' ? 'selected' : '' }}>Comexas Afrique
                                        </option>
                                        <option value="Comexas" {{ old('cf_agent') == 'Comexas' ? 'selected' : '' }}>
                                            Comexas
                                        </option>
                                        <option value="DCG" {{ old('cf_agent') == 'DCG' ? 'selected' : '' }}>DCG
                                        </option>
                                        <option value="Evele & Co"
                                            {{ old('cf_agent') == 'Evele & Co' ? 'selected' : '' }}>
                                            Evele & Co</option>
                                        <option value="Gecotrans" {{ old('cf_agent') == 'Gecotrans' ? 'selected' : '' }}>
                                            Gecotrans</option>
                                        <option value="Global Logistics"
                                            {{ old('cf_agent') == 'Global Logistics' ? 'selected' : '' }}>Global Logistics
                                        </option>
                                        <option value="Malabar" {{ old('cf_agent') == 'Malabar' ? 'selected' : '' }}>
                                            Malabar
                                        </option>
                                        <option value="Polytra" {{ old('cf_agent') == 'Polytra' ? 'selected' : '' }}>
                                            Polytra
                                        </option>
                                        <option value="Spedag" {{ old('cf_agent') == 'Spedag' ? 'selected' : '' }}>Spedag
                                        </option>
                                        <option value="Tradecorp" {{ old('cf_agent') == 'Tradecorp' ? 'selected' : '' }}>
                                            Tradecorp</option>
                                        <option value="Trade Service"
                                            {{ old('cf_agent') == 'Trade Service' ? 'selected' : '' }}>Trade Service
                                        </option>
                                    </select>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                <h3 class="m-3">Consignment Details</h3>
                <div class="row d-flex align-items-start p-4">
                    <div class="col-12 col-md-12 tab-content px-5" id="v-pills-tabContent">
                        <div class="tab-pane2 fade show" id="v-pills-disabled" role="tabpanel"
                            aria-labelledby="v-pills-disabled-tab" tabindex="0">
                            <div class="row">

                                <div class="col-12 col-lg-12 mb-3">
                                    <label class="form-label">Forwarding Agent</label>
                                    <input type="text" class="form-control" name="exporter_name"
                                        value="{{ old('exporter_name') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Freight Currency</label>
                                    <select class="form-select" name="freight_currency">
                                        <option value="USD" {{ old('freight_currency') == 'USD' ? 'selected' : '' }}>
                                            USD
                                        </option>
                                        <option value="EUR" {{ old('freight_currency') == 'EUR' ? 'selected' : '' }}>
                                            EUR
                                        </option>
                                        <option value="ZAR" {{ old('freight_currency') == 'ZAR' ? 'selected' : '' }}>
                                            ZAR
                                        </option>
                                        <option value="EUR" {{ old('freight_currency') == 'EUR' ? 'selected' : '' }}>
                                            EUR
                                        </option>
                                        <option value="AOA" {{ old('freight_currency') == 'AOA' ? 'selected' : '' }}>
                                            AOA
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Freight Cost</label>
                                    <input type="text" class="form-control" name="freight_value"
                                        value="{{ old('freight_value') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">FOB Value / VALEUR FOB</label>
                                    <input type="text" class="form-control" name="fob_value"
                                        value="{{ old('fob_value') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Insurance Value</label>
                                    <input type="text" class="form-control" name="insurance_value"
                                        value="{{ old('insurance_value') }}" autocomplete="on" required />
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="form-label">Additional Comments</label>
                                    <textarea class="form-control" name="instructions" rows="1" autocomplete="on" required>{{ old('instructions') }}</textarea>
                                </div>


                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Commercial Invoice</label>
                                    <input type="file" class="form-control" name="invoice" autocomplete="on" />
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Packing list</label>
                                    <input type="file" class="form-control" name="packing_list" autocomplete="on" />
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Manifest</label>
                                    <input type="file" class="form-control" name="manifest" autocomplete="on" />
                                </div>

                                <div class="col-12 col-lg-3 mb-3">
                                    <label class="form-label">Custom Docs <span class="fs-6 text-danger">(Merged)</span>
                                    </label>
                                    <input type="file" class="form-control" name="customs" autocomplete="on"
                                        required />
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                <div class="row d-flex align-items-start p-3">
                    <div class="col-12 col-md-12 tab-content" id="v-pills-tabContent">
                        <a href="./dashboard" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn-next btn btn-outline-primary">
                            <i class="fa fa-floppy-disk pe-2"></i>
                            Save
                            Template</button>
                    </div>
                </div>
            </div>

        </form>
    </div>


    <script>
        // show one and not the other based on selection
        // show one and not the other based on selection
        // show one and not the other based on selection
        // document.addEventListener("DOMContentLoaded", function() {

        //     const select = document.getElementById("feryType");
        //     const regional = document.getElementById("regionalSection");
        //     const continuance = document.getElementById("continuanceSection");

        //     select.addEventListener("change", function() {

        //         // Hide both first
        //         regional.classList.add("d-none");
        //         continuance.classList.add("d-none");

        //         if (this.value === "1") {
        //             regional.classList.remove("d-none");
        //         }

        //         if (this.value === "2") {
        //             continuance.classList.remove("d-none");
        //         }

        //     });

        // });

        document.addEventListener("DOMContentLoaded", function() {

            const select = document.getElementById("feryType");
            const regional = document.getElementById("regionalSection");
            const continuance = document.getElementById("continuanceSection");

            select.addEventListener("change", function() {

                // Hide both first
                regional.classList.remove("show");
                continuance.classList.remove("show");

                if (this.value === "1") {
                    regional.classList.add("show");
                }

                if (this.value === "2") {
                    continuance.classList.add("show");
                }

            });

        });
    </script>
@endsection
