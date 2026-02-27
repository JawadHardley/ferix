@extends('layouts.userlayout')
@section('content')
    <x-errorshow />
    {{-- {{ session('success') }}
    {{ session('error') }} --}}
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="card p-3">
                <h1 class="mb-2">Edit Template</h1>
                <p class="lead">
                    Edit an existing template by filling in the required details. This template will be used for generating
                    FERI
                    applications.
                </p>
            </div>
        </div>
    </div>

    {{-- this is for regional form --}}
    {{-- this is for regional form --}}
    {{-- this is for regional form --}}
    {{-- this is for regional form --}}

    @if ($formData['type'] == 'regional')
        <div class="regional form-section" id="regionalSection">
            <form action="{{ route('transporter.updatetemplate', $template->id) }}" method="POST"
                enctype="multipart/form-data" class="w-100" id="multiStepForm" novalidate>
                @csrf
                @method('PUT')

                {{-- <input type="hidden" name="type" value="regional" /> --}}

                <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                    <div class="row px-4">
                        <div class="col-lg-6">
                            <h3 class="m-3">Template Name</h3>
                            <div class="row d-flex align-items-start p-4">
                                <div class="col-12 col-md-12 tab-content" id="v-pills-tabContent">
                                    <input type="text" class="form-control" name="template_name"
                                        value="{{ $template['name'] }}" autocomplete="on" placeholder="Enter Template Name"
                                        required {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h3 class="m-3">Feri Type</h3>
                            <div class="row d-flex align-items-start p-4">
                                <div class="col-12 col-md-12 tab-content" id="v-pills-tabContent">

                                    @php
                                        $selectedType = old('template_type', $template->type ?? '');
                                    @endphp

                                    <select name="type" class="form-select"
                                        {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} required>
                                        <option value="">-- Select Feri Type --</option>
                                        <option value="regional" {{ $selectedType === 'regional' ? 'selected' : '' }}>
                                            Regional
                                        </option>
                                        <option value="continuance" {{ $selectedType === 'continuance' ? 'selected' : '' }}>
                                            Continuance
                                        </option>
                                    </select>

                                </div>
                            </div>
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
                                        <input type="text" name="transport_mode" class="form-control"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}
                                            value="{{ old('transport_mode', $formData['transport_mode'] ?? '') }}">
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">Transporter Company</label>
                                        <select class="form-select" name="transporter_company"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="">-- select --</option>

                                            @foreach ($records as $company)
                                                <option value="{{ $company->id }}"
                                                    {{ old('transporter_company', $formData['transporter_company'] ?? '') == $company->id ? 'selected' : '' }}>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">Entry Border to DRC</label>
                                        <select class="form-select" name="entry_border_drc"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>

                                            <option value="0"
                                                {{ $formData['entry_border_drc'] == null ? 'selected' : '' }}>--
                                                select
                                                --</option>
                                            <option value="Kasumbalesa"
                                                {{ $formData['entry_border_drc'] == 'Kasumbalesa' ? 'selected' : '' }}>
                                                Kasumbalesa
                                            </option>
                                            <option value="Mokambo"
                                                {{ $formData['entry_border_drc'] == 'Mokambo' ? 'selected' : '' }}>
                                                Mokambo</option>
                                            <option value="Sakania"
                                                {{ $formData['entry_border_drc'] == 'Sakania' ? 'selected' : '' }}>
                                                Sakania</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">Border ETA</label>
                                        <input type="date" class="form-control" name="arrival_date"
                                            value="{{ $formData['arrival_date'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Truck Details</label>
                                        <input type="text" class="form-control" name="truck_details"
                                            value="{{ $formData['truck_details'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                        <input type="hidden" class="form-control" name="feri_type" value="regional"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Port of Arrival <span
                                                class="fs-6">(Rail/Air/Port)</span></label>
                                        <input type="text" class="form-control" name="arrival_station"
                                            value="{{ $formData['arrival_station'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Final Destination</label>
                                        <select class="form-select" name="final_destination"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="0"
                                                {{ $formData['final_destination'] == null ? 'selected' : '' }}>
                                                --
                                                select
                                                --</option>
                                            <option value="Likasi DRC"
                                                {{ $formData['final_destination'] == 'Likasi DRC' ? 'selected' : '' }}>
                                                Likasi DRC
                                            </option>
                                            <option value="Kolwezi DRC"
                                                {{ $formData['final_destination'] == 'Kolwezi DRC' ? 'selected' : '' }}>
                                                Kolwezi
                                                DRC
                                            </option>
                                            <option value="Lubumbashi DRC"
                                                {{ $formData['final_destination'] == 'Lubumbashi DRC' ? 'selected' : '' }}>
                                                Lubumbashi DRC</option>
                                            <option value="Tenke DRC"
                                                {{ $formData['final_destination'] == 'Tenke DRC' ? 'selected' : '' }}>
                                                Tenke DRC</option>
                                            <option value="Kisanfu DRC"
                                                {{ $formData['final_destination'] == 'Kisanfu DRC' ? 'selected' : '' }}>
                                                Kisanfu DRC</option>
                                            <option value="Lualaba DRC"
                                                {{ $formData['final_destination'] == 'Lualaba DRC' ? 'selected' : '' }}>
                                                Lualaba DRC</option>
                                            <option value="Pumpi DRC"
                                                {{ $formData['final_destination'] == 'Pumpi DRC' ? 'selected' : '' }}>
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
                                            value="{{ old('importer_name', $formData['importer_name'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">Importer Address</label>
                                        <input type="text" class="form-control" name="importer_address"
                                            value="{{ old('importer_address', $formData['importer_address'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">Importer Phone</label>
                                        <input type="text" class="form-control" name="importer_phone"
                                            value="{{ old('importer_phone', $formData['importer_phone'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">Importer Email</label>
                                        <input type="text" class="form-control" name="importer_email"
                                            value="{{ old('importer_email', $formData['importer_email'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-12 mb-3">
                                        <label class="form-label">FXI Number</label>
                                        <input type="text" class="form-control" name="fix_number"
                                            value="{{ old('fix_number', $formData['fix_number'] ?? '') }}"
                                            autocomplete="on"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
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
                                            value="{{ old('exporter_name', $formData['exporter_name'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Exporter Phone</label>
                                        <input type="text" class="form-control" name="exporter_phone"
                                            value="{{ old('exporter_phone', $formData['exporter_phone'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Exporter Email</label>
                                        <input type="email" class="form-control" name="exporter_email"
                                            value="{{ old('exporter_email', $formData['exporter_email'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-12 mb-3">
                                        <label class="form-label">Exporter Address</label>
                                        <input type="text" class="form-control" name="exporter_address"
                                            value="{{ old('exporter_address', $formData['exporter_address'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">Clearing/Forwarding Agent</label>
                                        <select class="form-select" name="cf_agent"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="0" {{ $formData['cf_agent'] == null ? 'selected' : '' }}>
                                                --
                                                select --
                                            </option>
                                            <option value="AGL" {{ $formData['cf_agent'] == 'AGL' ? 'selected' : '' }}>
                                                AGL
                                            </option>
                                            <option value="CARGO CONGO"
                                                {{ $formData['cf_agent'] == 'CARGO CONGO' ? 'selected' : '' }}>
                                                CARGO CONGO</option>
                                            <option value="CONNEX"
                                                {{ $formData['cf_agent'] == 'CONNEX' ? 'selected' : '' }}>
                                                CONNEX
                                            </option>
                                            <option value="African Logistics"
                                                {{ $formData['cf_agent'] == 'African Logistics' ? 'selected' : '' }}>
                                                African Logistics
                                            </option>
                                            <option value="Afritac"
                                                {{ $formData['cf_agent'] == 'Afritac' ? 'selected' : '' }}>
                                                Afritac
                                            </option>
                                            <option value="Amicongo"
                                                {{ $formData['cf_agent'] == 'Amicongo' ? 'selected' : '' }}>
                                                Amicongo</option>
                                            <option value="Aristote"
                                                {{ $formData['cf_agent'] == 'Aristote' ? 'selected' : '' }}>
                                                Aristote</option>
                                            <option value="Bollore"
                                                {{ $formData['cf_agent'] == 'Bollore' ? 'selected' : '' }}>
                                                Bollore
                                            </option>
                                            <option value="Brasimba"
                                                {{ $formData['cf_agent'] == 'Brasimba' ? 'selected' : '' }}>
                                                Brasimba</option>
                                            <option value="Brasimba S.A"
                                                {{ $formData['cf_agent'] == 'Brasimba S.A' ? 'selected' : '' }}>
                                                Brasimba S.A</option>
                                            <option value="COSMOS"
                                                {{ $formData['cf_agent'] == 'COSMOS' ? 'selected' : '' }}>
                                                COSMOS</option>
                                            <option value="OLA" {{ $formData['cf_agent'] == 'OLA' ? 'selected' : '' }}>
                                                OLA
                                            </option>
                                            <option value="Chemaf"
                                                {{ $formData['cf_agent'] == 'Chemaf' ? 'selected' : '' }}>
                                                Chemaf
                                            </option>
                                            <option value="Comexas Afrique"
                                                {{ $formData['cf_agent'] == 'Comexas Afrique' ? 'selected' : '' }}>
                                                Comexas Afrique
                                            </option>
                                            <option value="Comexas"
                                                {{ $formData['cf_agent'] == 'Comexas' ? 'selected' : '' }}>
                                                Comexas
                                            </option>
                                            <option value="DCG" {{ $formData['cf_agent'] == 'DCG' ? 'selected' : '' }}>
                                                DCG
                                            </option>
                                            <option value="Evele & Co"
                                                {{ $formData['cf_agent'] == 'Evele & Co' ? 'selected' : '' }}>
                                                Evele & Co</option>
                                            <option value="Gecotrans"
                                                {{ $formData['cf_agent'] == 'Gecotrans' ? 'selected' : '' }}>
                                                Gecotrans</option>
                                            <option value="Global Logistics"
                                                {{ $formData['cf_agent'] == 'Global Logistics' ? 'selected' : '' }}>
                                                Global Logistics
                                            </option>
                                            <option value="Malabar"
                                                {{ $formData['cf_agent'] == 'Malabar' ? 'selected' : '' }}>
                                                Malabar
                                            </option>
                                            <option value="Polytra"
                                                {{ $formData['cf_agent'] == 'Polytra' ? 'selected' : '' }}>
                                                Polytra
                                            </option>
                                            <option value="Spedag"
                                                {{ $formData['cf_agent'] == 'Spedag' ? 'selected' : '' }}>
                                                Spedag
                                            </option>
                                            <option value="Tradecorp"
                                                {{ $formData['cf_agent'] == 'Tradecorp' ? 'selected' : '' }}>
                                                Tradecorp</option>
                                            <option value="Trade Service"
                                                {{ $formData['cf_agent'] == 'Trade Service' ? 'selected' : '' }}>Trade
                                                Service
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">Clearing/Forwarding Agent Contact</label>
                                        <input type="text" class="form-control" name="cf_agent_contact"
                                            value="{{ old('cf_agent_contact', $formData['cf_agent_contact'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
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
                                        <textarea class="form-control" name="cargo_description" rows="1" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>{{ old('cargo_description', $formData['cargo_description'] ?? '') }}</textarea>
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">HS Code</label>
                                        <input type="text" class="form-control" name="hs_code"
                                            value="{{ old('hs_code', $formData['hs_code'] ?? '') }}" autocomplete="on"
                                            required {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">Package Type</label>
                                        <input type="text" class="form-control" name="package_type"
                                            value="{{ old('package_type', $formData['package_type'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Quantity (PKG)</label>
                                        <input type="number" class="form-control" name="quantity"
                                            value="{{ old('quantity', $formData['quantity'] ?? '') }}" autocomplete="on"
                                            required {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>
                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Weight (Gross)Kg</label>
                                        <input type="number" class="form-control" name="weight"
                                            value="{{ old('weight', $formData['weight'] ?? '') }}" autocomplete="on"
                                            required {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>
                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Volume (Net Weight)T</label>
                                        <input type="number" class="form-control" name="volume"
                                            value="{{ old('volume', $formData['volume'] ?? '') }}" autocomplete="on"
                                            required {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
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
                                            value="{{ old('po', $formData['po'] ?? '') }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Company Ref <span class="fs-6 text-danger">(Trip
                                                Number)</span></label>
                                        <input type="text" class="form-control" name="company_ref"
                                            value="{{ old('company_ref', $formData['company_ref'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Cargo Origin</label>
                                        <input type="text" class="form-control" name="cargo_origin"
                                            value="{{ old('cargo_origin', $formData['cargo_origin'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Customs Declaration Number</label>
                                        <input type="text" class="form-control" name="customs_decl_no"
                                            value="{{ old('customs_decl_no', $formData['customs_decl_no'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Manifest Number / VG</label>
                                        <input type="text" class="form-control" name="manifest_no"
                                            value="{{ old('manifest_no', $formData['manifest_no'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">OCC/ BIVAC</label>
                                        <input type="text" class="form-control" name="occ_bivac"
                                            value="{{ old('occ_bivac', $formData['occ_bivac'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-12 mb-3">
                                        <label class="form-label">Additional Comments</label>
                                        <textarea class="form-control" name="instructions" rows="1" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>{{ old('instructions', $formData['instructions'] ?? '') }}</textarea>
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
                                        <select class="form-select" name="fob_currency"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="USD"
                                                {{ old('fob_currency', $formData['fob_currency'] ?? '') == 'USD' ? 'selected' : '' }}>
                                                USD
                                            </option>
                                            <option value="EUR"
                                                {{ old('fob_currency', $formData['fob_currency'] ?? '') == 'EUR' ? 'selected' : '' }}>
                                                EUR
                                            </option>
                                            <option value="TZS"
                                                {{ old('fob_currency', $formData['fob_currency'] ?? '') == 'TZS' ? 'selected' : '' }}>
                                                TZS
                                            </option>
                                            <option value="ZAR"
                                                {{ old('fob_currency', $formData['fob_currency'] ?? '') == 'ZAR' ? 'selected' : '' }}>
                                                ZAR
                                            </option>
                                            <option value="AOA"
                                                {{ old('fob_currency', $formData['fob_currency'] ?? '') == 'AOA' ? 'selected' : '' }}>
                                                AOA
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">FOB Value / VALEUR FOB</label>
                                        <input type="text" class="form-control" name="fob_value"
                                            value="{{ old('fob_value', $formData['fob_value'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Freight Currency</label>
                                        <select class="form-select" name="freight_currency"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="USD"
                                                {{ old('freight_currency', $formData['freight_currency'] ?? '') == 'USD' ? 'selected' : '' }}>
                                                USD
                                            </option>
                                            <option value="EUR"
                                                {{ old('freight_currency', $formData['freight_currency'] ?? '') == 'EUR' ? 'selected' : '' }}>
                                                EUR
                                            </option>
                                            <option value="ZAR"
                                                {{ old('freight_currency', $formData['freight_currency'] ?? '') == 'ZAR' ? 'selected' : '' }}>
                                                ZAR
                                            </option>
                                            <option value="AOA"
                                                {{ old('freight_currency', $formData['freight_currency'] ?? '') == 'AOA' ? 'selected' : '' }}>
                                                AOA
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Freight Value</label>
                                        <input type="text" class="form-control" name="freight_value"
                                            value="{{ old('freight_value', $formData['freight_value'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>
                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Insurance Currency</label>
                                        <select class="form-select" name="insurance_currency"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="USD"
                                                {{ old('insurance_currency', $formData['insurance_currency'] ?? '') == 'USD' ? 'selected' : '' }}>
                                                USD
                                            </option>
                                            <option value="EUR"
                                                {{ old('insurance_currency', $formData['insurance_currency'] ?? '') == 'EUR' ? 'selected' : '' }}>
                                                EUR
                                            </option>
                                            <option value="ZAR"
                                                {{ old('insurance_currency', $formData['insurance_currency'] ?? '') == 'ZAR' ? 'selected' : '' }}>
                                                ZAR
                                            </option>
                                            <option value="TZS"
                                                {{ old('insurance_currency', $formData['insurance_currency'] ?? '') == 'TZS' ? 'selected' : '' }}>
                                                TZS
                                            </option>
                                            <option value="AOA"
                                                {{ old('insurance_currency', $formData['insurance_currency'] ?? '') == 'AOA' ? 'selected' : '' }}>
                                                AOA
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Insurance Value</label>
                                        <input type="text" class="form-control" name="insurance_value"
                                            value="{{ old('insurance_value', $formData['insurance_value'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>
                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Additional Fees Currency</label>
                                        <select class="form-select" name="additional_fees_currency"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="USD"
                                                {{ old('additional_fees_currency', $formData['additional_fees_currency'] ?? '') == 'USD' ? 'selected' : '' }}>
                                                USD
                                            </option>
                                            <option value="EUR"
                                                {{ old('additional_fees_currency', $formData['additional_fees_currency'] ?? '') == 'EUR' ? 'selected' : '' }}>
                                                EUR
                                            </option>
                                            <option value="ZAR"
                                                {{ old('additional_fees_currency', $formData['additional_fees_currency'] ?? '') == 'ZAR' ? 'selected' : '' }}>
                                                ZAR
                                            </option>
                                            <option value="TZS"
                                                {{ old('additional_fees_currency', $formData['additional_fees_currency'] ?? '') == 'TZS' ? 'selected' : '' }}>
                                                TZS
                                            </option>
                                            <option value="AOA"
                                                {{ old('additional_fees_currency', $formData['additional_fees_currency'] ?? '') == 'AOA' ? 'selected' : '' }}>
                                                AOA
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Additional Fees Value</label>
                                        <input type="text" class="form-control" name="additional_fees_value"
                                            value="{{ old('additional_fees_value', $formData['additional_fees_value'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Incoterm</label>
                                        <select class="form-select" name="incoterm"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="0"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == '0' ? 'selected' : '' }}>
                                                --
                                                select --
                                            </option>
                                            <option value="CFR"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'CFR' ? 'selected' : '' }}>
                                                CFR
                                            </option>
                                            <option value="CIF"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'CIF' ? 'selected' : '' }}>
                                                CIF
                                            </option>
                                            <option value="CIP"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'CIP' ? 'selected' : '' }}>
                                                CIP
                                            </option>
                                            <option value="CPT"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'CPT' ? 'selected' : '' }}>
                                                CPT
                                            </option>
                                            <option value="DAF"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'DAF' ? 'selected' : '' }}>
                                                DAF
                                            </option>
                                            <option value="DAP"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'DAP' ? 'selected' : '' }}>
                                                DAP
                                            </option>
                                            <option value="DAT"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'DAT' ? 'selected' : '' }}>
                                                DAT
                                            </option>
                                            <option value="DDP"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'DDP' ? 'selected' : '' }}>
                                                DDP
                                            </option>
                                            <option value="DDU"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'DDU' ? 'selected' : '' }}>
                                                DDU
                                            </option>
                                            <option value="DEQ"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'DEQ' ? 'selected' : '' }}>
                                                DEQ
                                            </option>
                                            <option value="DES"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'DES' ? 'selected' : '' }}>
                                                DES
                                            </option>
                                            <option value="DPU"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'DPU' ? 'selected' : '' }}>
                                                DPU
                                            </option>
                                            <option value="EXW"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'EXW' ? 'selected' : '' }}>
                                                EXW
                                            </option>
                                            <option value="FAS"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'FAS' ? 'selected' : '' }}>
                                                FAS
                                            </option>
                                            <option value="FCA"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'FCA' ? 'selected' : '' }}>
                                                FCA
                                            </option>
                                            <option value="FOB"
                                                {{ old('incoterm', $formData['incoterm'] ?? '') == 'FOB' ? 'selected' : '' }}>
                                                FOB
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Commercial Invoice</label>
                                        <input type="file" class="form-control" name="invoice" autocomplete="on"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Packing list</label>
                                        <input type="file" class="form-control" name="packing_list" autocomplete="on"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Manifest</label>
                                        <input type="file" class="form-control" name="manifest" autocomplete="on"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Custom Docs <span
                                                class="fs-6 text-danger">(Merged)</span>
                                        </label>
                                        <input type="file" class="form-control" name="customs" autocomplete="on"
                                            required {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">

                    <div class="row d-flex align-items-start p-3">
                        <div class="col-12 col-md-6 tab-content d-flex gap-2" id="v-pills-tabContent">
                            <a href="{{ route(Auth::user()->role . '' . '.listtemplate') }}"
                                class="btn btn-secondary">Cancel</a>
                            <a href="{{ route('transporter.applyferi', ['template' => $template->id]) }}"
                                class="btn btn-success">
                                <i class="fa fa-network-wired pe-2"></i>Use Template
                            </a>
                        </div>

                        @if (Auth::user()->id == $template->user_id)
                            <div class="col-12 col-md-6 tab-content d-flex justify-content-end gap-2"
                                id="v-pills-tabContent">
                                <button type="submit" class="btn-next btn btn-outline-primary">
                                    <i class="fa fa-floppy-disk pe-2"></i>
                                    Save
                                    Template
                                </button>

                                <a class="btn btn-outline-danger" href="#" data-bs-toggle="modal"
                                    data-bs-target="#templateDeleteModal{{ $template->id }}">
                                    <i class="fa fa-trash pe-2"></i>Delete
                                </a>
                            </div>
                        @endif
                    </div>

                </div>


            </form>
        </div>



        {{-- this is for continuance form --}}
        {{-- this is for continuance form --}}
        {{-- this is for continuance form --}}
        {{-- this is for continuance form --}}
    @else
        <div class="continuance form-section" id="continuanceSection">
            <form action="{{ route('transporter.updatetemplate', $template->id) }}" method="POST"
                enctype="multipart/form-data" class="w-100" id="multiStepForm" novalidate>
                @csrf
                @method('PUT')


                <input type="hidden" name="type" value="continuance" />

                <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                    <div class="row px-4">
                        <div class="col-lg-6">
                            <h3 class="m-3">Template Name</h3>
                            <div class="row d-flex align-items-start p-4">
                                <div class="col-12 col-md-12 tab-content" id="v-pills-tabContent">
                                    <input type="text" class="form-control" name="template_name"
                                        value="{{ $template['name'] }}" autocomplete="on"
                                        placeholder="Enter Template Name" required
                                        {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h3 class="m-3">Feri Type</h3>
                            <div class="row d-flex align-items-start p-4">
                                <div class="col-12 col-md-12 tab-content" id="v-pills-tabContent">

                                    @php
                                        $selectedType = old('template_type', $template->type ?? '');
                                    @endphp

                                    <select name="type" class="form-select" required
                                        {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                        <option value="">-- Select Feri Type --</option>
                                        <option value="regional" {{ $selectedType === 'regional' ? 'selected' : '' }}>
                                            Regional
                                        </option>
                                        <option value="continuance"
                                            {{ $selectedType === 'continuance' ? 'selected' : '' }}>
                                            Continuance
                                        </option>
                                    </select>

                                </div>
                            </div>
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
                                            value="{{ $formData['company_ref'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">PO <span class="fs-6 text-danger">(TBS: To be added
                                                later)</span>
                                        </label>
                                        <input type="text" class="form-control" name="po"
                                            value="{{ $formData['po'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Validated Feri Certificate Number</label>
                                        <input type="text" class="form-control" name="validate_feri_cert"
                                            value="{{ old('validate_feri_cert', $formData['validate_feri_cert'] ?? '') }}"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Entry Border to DRC</label>
                                        <select class="form-select" name="entry_border_drc"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="0"
                                                {{ $formData['entry_border_drc'] == null ? 'selected' : '' }}>--
                                                select
                                                --</option>
                                            <option value="Kasumbalesa"
                                                {{ $formData['entry_border_drc'] == 'Kasumbalesa' ? 'selected' : '' }}>
                                                Kasumbalesa
                                            </option>
                                            <option value="Mokambo"
                                                {{ $formData['entry_border_drc'] == 'Mokambo' ? 'selected' : '' }}>
                                                Mokambo</option>
                                            <option value="Sakania"
                                                {{ $formData['entry_border_drc'] == 'Sakania' ? 'selected' : '' }}>
                                                Sakania</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Final Destination</label>
                                        <select class="form-select" name="final_destination"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="0"
                                                {{ $formData['final_destination'] == '0' ? 'selected' : '' }}>--
                                                select
                                                --</option>
                                            <option value="Likasi DRC"
                                                {{ $formData['final_destination'] == 'Likasi DRC' ? 'selected' : '' }}>
                                                Likasi
                                                DRC
                                            </option>
                                            <option value="Kolwezi DRC"
                                                {{ $formData['final_destination'] == 'Kolwezi DRC' ? 'selected' : '' }}>
                                                Kolwezi
                                                DRC
                                            </option>
                                            <option value="Lubumbashi DRC"
                                                {{ $formData['final_destination'] == 'Lubumbashi DRC' ? 'selected' : '' }}>
                                                Lubumbashi DRC</option>
                                            <option value="Tenke DRC"
                                                {{ $formData['final_destination'] == 'Tenke DRC' ? 'selected' : '' }}>
                                                Tenke DRC</option>
                                            <option value="Kisanfu DRC"
                                                {{ $formData['final_destination'] == 'Kisanfu DRC' ? 'selected' : '' }}>
                                                Kisanfu DRC</option>
                                            <option value="Lualaba DRC"
                                                {{ $formData['final_destination'] == 'Lualaba DRC' ? 'selected' : '' }}>
                                                Lualaba DRC</option>
                                            <option value="Pumpi DRC"
                                                {{ $formData['final_destination'] == 'Pumpi DRC' ? 'selected' : '' }}>
                                                Pumpi DRC</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Border ETA</label>
                                        <input type="date" class="form-control" name="arrival_date"
                                            value="{{ $formData['arrival_date'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Customs Declaration Number</label>
                                        <input type="text" class="form-control" name="customs_decl_no"
                                            value="{{ $formData['customs_decl_no'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Truck Details</label>
                                        <input type="text" class="form-control" name="truck_details"
                                            value="{{ $formData['truck_details'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                        <input type="hidden" class="form-control" name="feri_type" value="continuance"
                                            autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Port of Arrival <span
                                                class="fs-6">(Rail/Air/Port)</span></label>
                                        <input type="text" class="form-control" name="arrival_station"
                                            value="{{ $formData['arrival_station'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
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
                                        <select class="form-select" name="transporter_company"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="">-- select --</option>

                                            @foreach ($records as $company)
                                                <option value="{{ $company->id }}"
                                                    {{ old('transporter_company', $formData['transporter_company'] ?? '') == $company->id ? 'selected' : '' }}>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" class="form-control" name="quantity"
                                            value="{{ $formData['quantity'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>


                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Weights: Tons/kgs</label>
                                        <input type="number" class="form-control" name="weight"
                                            value="{{ $formData['weight'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-4 mb-3">
                                        <label class="form-label">Volume: CBM</label>
                                        <input type="text" class="form-control" name="volume" autocomplete="on"
                                            value="{{ $formData['volume'] }}" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">Importer Name</label>
                                        <input type="text" class="form-control" name="importer_name"
                                            value="{{ $formData['importer_name'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">Shipping Line/Agent</label>
                                        <select class="form-select" name="cf_agent"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="0" {{ $formData['cf_agent'] == null ? 'selected' : '' }}>
                                                --
                                                select
                                                --
                                            </option>
                                            <option value="AGL"
                                                {{ $formData['cf_agent'] == 'AGL' ? 'selected' : '' }}>AGL
                                            </option>
                                            <option value="CARGO CONGO"
                                                {{ $formData['cf_agent'] == 'CARGO CONGO' ? 'selected' : '' }}>
                                                CARGO CONGO</option>
                                            <option value="CONNEX"
                                                {{ $formData['cf_agent'] == 'CONNEX' ? 'selected' : '' }}>
                                                CONNEX
                                            </option>
                                            <option value="African Logistics"
                                                {{ $formData['cf_agent'] == 'African Logistics' ? 'selected' : '' }}>
                                                African
                                                Logistics
                                            </option>
                                            <option value="Afritac"
                                                {{ $formData['cf_agent'] == 'Afritac' ? 'selected' : '' }}>
                                                Afritac
                                            </option>
                                            <option value="Amicongo"
                                                {{ $formData['cf_agent'] == 'Amicongo' ? 'selected' : '' }}>
                                                Amicongo</option>
                                            <option value="Aristote"
                                                {{ $formData['cf_agent'] == 'Aristote' ? 'selected' : '' }}>
                                                Aristote</option>
                                            <option value="Bollore"
                                                {{ $formData['cf_agent'] == 'Bollore' ? 'selected' : '' }}>
                                                Bollore
                                            </option>
                                            <option value="Brasimba"
                                                {{ $formData['cf_agent'] == 'Brasimba' ? 'selected' : '' }}>
                                                Brasimba</option>
                                            <option value="Brasimba S.A"
                                                {{ $formData['cf_agent'] == 'Brasimba S.A' ? 'selected' : '' }}>
                                                Brasimba S.A</option>
                                            <option value="COSMOS"
                                                {{ $formData['cf_agent'] == 'COSMOS' ? 'selected' : '' }}>
                                                COSMOS</option>
                                            <option value="OLA"
                                                {{ $formData['cf_agent'] == 'OLA' ? 'selected' : '' }}>OLA
                                            </option>
                                            <option value="Chemaf"
                                                {{ $formData['cf_agent'] == 'Chemaf' ? 'selected' : '' }}>
                                                Chemaf
                                            </option>
                                            <option value="Comexas Afrique"
                                                {{ $formData['cf_agent'] == 'Comexas Afrique' ? 'selected' : '' }}>Comexas
                                                Afrique
                                            </option>
                                            <option value="Comexas"
                                                {{ $formData['cf_agent'] == 'Comexas' ? 'selected' : '' }}>
                                                Comexas
                                            </option>
                                            <option value="DCG"
                                                {{ $formData['cf_agent'] == 'DCG' ? 'selected' : '' }}>DCG
                                            </option>
                                            <option value="Evele & Co"
                                                {{ $formData['cf_agent'] == 'Evele & Co' ? 'selected' : '' }}>
                                                Evele & Co</option>
                                            <option value="Gecotrans"
                                                {{ $formData['cf_agent'] == 'Gecotrans' ? 'selected' : '' }}>
                                                Gecotrans</option>
                                            <option value="Global Logistics"
                                                {{ $formData['cf_agent'] == 'Global Logistics' ? 'selected' : '' }}>Global
                                                Logistics
                                            </option>
                                            <option value="Malabar"
                                                {{ $formData['cf_agent'] == 'Malabar' ? 'selected' : '' }}>
                                                Malabar
                                            </option>
                                            <option value="Polytra"
                                                {{ $formData['cf_agent'] == 'Polytra' ? 'selected' : '' }}>
                                                Polytra
                                            </option>
                                            <option value="Spedag"
                                                {{ $formData['cf_agent'] == 'Spedag' ? 'selected' : '' }}>
                                                Spedag
                                            </option>
                                            <option value="Tradecorp"
                                                {{ $formData['cf_agent'] == 'Tradecorp' ? 'selected' : '' }}>
                                                Tradecorp</option>
                                            <option value="Trade Service"
                                                {{ $formData['cf_agent'] == 'Trade Service' ? 'selected' : '' }}>Trade
                                                Service
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
                                            value="{{ $formData['exporter_name'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Freight Currency</label>
                                        <select class="form-select" name="freight_currency"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>
                                            <option value="USD"
                                                {{ $formData['freight_currency'] == 'USD' ? 'selected' : '' }}>
                                                USD
                                            </option>
                                            <option value="EUR"
                                                {{ $formData['freight_currency'] == 'EUR' ? 'selected' : '' }}>
                                                EUR
                                            </option>
                                            <option value="ZAR"
                                                {{ $formData['freight_currency'] == 'ZAR' ? 'selected' : '' }}>
                                                ZAR
                                            </option>
                                            <option value="EUR"
                                                {{ $formData['freight_currency'] == 'EUR' ? 'selected' : '' }}>
                                                EUR
                                            </option>
                                            <option value="AOA"
                                                {{ $formData['freight_currency'] == 'AOA' ? 'selected' : '' }}>
                                                AOA
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Freight Cost</label>
                                        <input type="text" class="form-control" name="freight_value"
                                            value="{{ $formData['freight_value'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">FOB Value / VALEUR FOB</label>
                                        <input type="text" class="form-control" name="fob_value"
                                            value="{{ $formData['fob_value'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Insurance Value</label>
                                        <input type="text" class="form-control" name="insurance_value"
                                            value="{{ $formData['insurance_value'] }}" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-6 mb-3">
                                        <label class="form-label">Additional Comments</label>
                                        <textarea class="form-control" name="instructions" rows="1" autocomplete="on" required
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }}>{{ $formData['instructions'] }}</textarea>
                                    </div>


                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Commercial Invoice</label>
                                        <input type="file" class="form-control" name="invoice" autocomplete="on"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Packing list</label>
                                        <input type="file" class="form-control" name="packing_list" autocomplete="on"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Manifest</label>
                                        <input type="file" class="form-control" name="manifest" autocomplete="on"
                                            {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-12 col-lg-3 mb-3">
                                        <label class="form-label">Custom Docs <span
                                                class="fs-6 text-danger">(Merged)</span>
                                        </label>
                                        <input type="file" class="form-control" name="customs" autocomplete="on"
                                            required {{ Auth::user()->id != $template->user_id ? 'disabled' : '' }} />
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-lg border border-secondary-subtle fade-slide-in mb-5">
                    <div class="row d-flex align-items-start p-3">
                        <div class="col-12 col-md-12 tab-content" id="v-pills-tabContent">

                            <a href="{{ route(Auth::user()->role . '' . '.dashboard') }}"
                                class="btn btn-secondary">Cancel</a>

                            <button type="submit" class="btn-next btn btn-outline-primary">
                                <i class="fa fa-floppy-disk pe-2"></i>
                                Save
                                Template
                            </button>

                            <a href="{{ route('transporter.applyferi', ['template' => $template->id]) }}"
                                class="btn btn-success justify-content-end">
                                <i class="fa fa-network-wired pe-2"></i>Use Template
                            </a>

                            <a class="btn btn-outline-danger" href="#" data-bs-toggle="modal"
                                data-bs-target="#templateDeleteModal{{ $template->id }}">
                                <i class="fa fa-trash pe-2"></i>Delete
                            </a>

                        </div>
                    </div>
                </div>

            </form>
        </div>
    @endif

    <form action="{{ route('transporter.destroyTemplate', $template->id) }}" method="POST">
        @csrf
        @method('DELETE')

        <div class="modal" id="templateDeleteModal{{ $template->id }}" tabindex="-1">
            <div class="modal-dialog modal-s" role="document">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-danger"></div>
                    <div class="modal-body text-center py-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 9v2m0 4v.01" />
                            <path
                                d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                        </svg>
                        <h3>Are you sure?</h3>
                        <div class="text-secondary">
                            Do you really want to delete this template?
                            <br />
                            <br />
                            <div>
                                <mark>{{ $template->name }}</mark>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <a href="#" class="btn w-100" data-bs-dismiss="modal">
                                        Cancel </a>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-danger w-100" data-bs-dismiss="modal">
                                        <i class="fa fa-trash me-2"></i> Delete Template</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


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

        // document.addEventListener("DOMContentLoaded", function() {

        //     const select = document.getElementById("feryType");
        //     const regional = document.getElementById("regionalSection");
        //     const continuance = document.getElementById("continuanceSection");

        //     select.addEventListener("change", function() {

        //         // Hide both first
        //         regional.classList.remove("show");
        //         continuance.classList.remove("show");

        //         if (this.value === "1") {
        //             regional.classList.add("show");
        //         }

        //         if (this.value === "2") {
        //             continuance.classList.add("show");
        //         }

        //     });

        // });
    </script>
@endsection
