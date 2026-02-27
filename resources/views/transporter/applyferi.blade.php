@extends('layouts.userlayout')
@section('content')
    <x-errorshow />
    <div class="card fade-slide-in">
        {{-- <h1 class="m-3">
            Regional Feri Application Form
            @if ($template)
                <span class="text-warning">:{{ $template->name }}</span>
            @endif
        </h1>
         --}}
        <h1 class="m-3 justify-content-start d-flex align-items-middle">
            <span class="me-5">Regional Feri Application Form</span>
            @if (!empty($template))
                <span class="fs-4 bg-success rounded bg-opacity-10 px-3">
                    <span class="pe-2 fs-5 text-opacity-25 text-muted">
                        <i class="fa fa-network-wired text-warning pe-2"></i>Template:
                    </span>
                    {{ $template->name }}
                </span>
            @endif
        </h1>
        <hr />
        <form action="{{ route('transporter.feriApp') }}" method="POST" enctype="multipart/form-data" class="w-100 p-5"
            id="multiStepForm" novalidate>
            @csrf

            <div class="row d-flex align-items-start">
                <div class="col-12 col-md-2 nav flex-column nav-pills border" id="v-pills-tab" role="tablist"
                    aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home"
                        aria-selected="true">Transport & Cargo Details</button>
                    <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile"
                        aria-selected="false">Importer Details</button>
                    <button class="nav-link" id="v-pills-disabled-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-disabled" type="button" role="tab" aria-controls="v-pills-disabled"
                        aria-selected="false">Export Details</button>
                    <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages"
                        aria-selected="false">Cargo Description</button>
                    <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings"
                        aria-selected="false">Expedition</button>
                    <button class="nav-link" id="v-pills-values-tab" data-bs-toggle="pill" data-bs-target="#v-pills-values"
                        type="button" role="tab" aria-controls="v-pills-values" aria-selected="false">Values</button>
                </div>
                <div class="col-12 col-md-10 tab-content px-5" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                        aria-labelledby="v-pills-home-tab" tabindex="0">
                        <div class="row">
                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Transport Mode</label>
                                @php
                                    $selectedTransportMode = old('transport_mode', $formData['transport_mode'] ?? '');
                                @endphp
                                <select class="form-select" name="transport_mode" autocomplete="on" required>
                                    <option value="">-- select --</option>
                                    <option value="Road" {{ $selectedTransportMode == 'Road' ? 'selected' : '' }}>Road
                                    </option>
                                    <option value="Air" {{ $selectedTransportMode == 'Air' ? 'selected' : '' }}>Air
                                    </option>
                                    <option value="Maritime" {{ $selectedTransportMode == 'Maritime' ? 'selected' : '' }}>
                                        Maritime</option>
                                    <option value="Rail" {{ $selectedTransportMode == 'Rail' ? 'selected' : '' }}>Rail
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Transporter Company</label>
                                @php
                                    $selectedTransporterCompany = old(
                                        'transporter_company',
                                        $formData['transporter_company'] ?? '',
                                    );
                                @endphp
                                <select class="form-select" name="transporter_company">
                                    <option value="">-- select --</option>
                                    @foreach ($records as $record)
                                        <option value="{{ $record->id }}"
                                            {{ $selectedTransporterCompany == $record->id ? 'selected' : '' }}>
                                            {{ $record->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Entry Border to DRC</label>
                                @php
                                    $selectedEntryBorder = old('entry_border_drc', $formData['entry_border_drc'] ?? '');
                                @endphp
                                <select class="form-select" name="entry_border_drc">
                                    <option value="0" {{ $selectedEntryBorder == '0' ? 'selected' : '' }}>-- select --
                                    </option>
                                    <option value="Kasumbalesa"
                                        {{ $selectedEntryBorder == 'Kasumbalesa' ? 'selected' : '' }}>Kasumbalesa</option>
                                    <option value="Mokambo" {{ $selectedEntryBorder == 'Mokambo' ? 'selected' : '' }}>
                                        Mokambo</option>
                                    <option value="Sakania" {{ $selectedEntryBorder == 'Sakania' ? 'selected' : '' }}>
                                        Sakania</option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Border ETA</label>
                                <input type="date" class="form-control" name="arrival_date"
                                    value="{{ old('arrival_date', $formData['arrival_date'] ?? '') }}" autocomplete="on"
                                    required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Truck Details</label>
                                <input type="text" class="form-control" name="truck_details"
                                    value="{{ old('truck_details', $formData['truck_details'] ?? '') }}"
                                    autocomplete="on" required />
                                <input type="hidden" class="form-control" name="feri_type"
                                    value="{{ old('feri_type', $formData['feri_type'] ?? 'regional') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Port of Arrival <span
                                        class="fs-6">(Rail/Air/Port)</span></label>
                                <input type="text" class="form-control" name="arrival_station"
                                    value="{{ old('arrival_station', $formData['arrival_station'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Final Destination</label>
                                @php
                                    $selectedFinalDestination = old(
                                        'final_destination',
                                        $formData['final_destination'] ?? '',
                                    );
                                @endphp
                                <select class="form-select" name="final_destination">
                                    <option value="0" {{ $selectedFinalDestination == '0' ? 'selected' : '' }}>--
                                        select --</option>
                                    <option value="Likasi DRC"
                                        {{ $selectedFinalDestination == 'Likasi DRC' ? 'selected' : '' }}>Likasi DRC
                                    </option>
                                    <option value="Kolwezi DRC"
                                        {{ $selectedFinalDestination == 'Kolwezi DRC' ? 'selected' : '' }}>Kolwezi DRC
                                    </option>
                                    <option value="Lubumbashi DRC"
                                        {{ $selectedFinalDestination == 'Lubumbashi DRC' ? 'selected' : '' }}>Lubumbashi
                                        DRC</option>
                                    <option value="Tenke DRC"
                                        {{ $selectedFinalDestination == 'Tenke DRC' ? 'selected' : '' }}>Tenke DRC</option>
                                    <option value="Kisanfu DRC"
                                        {{ $selectedFinalDestination == 'Kisanfu DRC' ? 'selected' : '' }}>Kisanfu DRC
                                    </option>
                                    <option value="Lualaba DRC"
                                        {{ $selectedFinalDestination == 'Lualaba DRC' ? 'selected' : '' }}>Lualaba DRC
                                    </option>
                                    <option value="Pumpi DRC"
                                        {{ $selectedFinalDestination == 'Pumpi DRC' ? 'selected' : '' }}>Pumpi DRC</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                        aria-labelledby="v-pills-profile-tab" tabindex="0">
                        <div class="row">

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Importer Name</label>
                                <input type="text" class="form-control" name="importer_name"
                                    value="{{ old('importer_name', $formData['importer_name'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Importer Address</label>
                                <input type="text" class="form-control" name="importer_address"
                                    value="{{ old('importer_address', $formData['importer_address'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Importer Phone</label>
                                <input type="text" class="form-control" name="importer_phone"
                                    value="{{ old('importer_phone', $formData['importer_phone'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Importer Email</label>
                                <input type="text" class="form-control" name="importer_email"
                                    value="{{ old('importer_email', $formData['importer_email'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">FXI Number</label>
                                <input type="text" class="form-control" name="fix_number"
                                    value="{{ old('fix_number', $formData['fix_number'] ?? '') }}" autocomplete="on" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-disabled" role="tabpanel"
                        aria-labelledby="v-pills-disabled-tab" tabindex="0">
                        <div class="row">
                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Exporter Name</label>
                                <input type="text" class="form-control" name="exporter_name"
                                    value="{{ old('exporter_name', $formData['exporter_name'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Exporter Phone</label>
                                <input type="text" class="form-control" name="exporter_phone"
                                    value="{{ old('exporter_phone', $formData['exporter_phone'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Exporter Email</label>
                                <input type="email" class="form-control" name="exporter_email"
                                    value="{{ old('exporter_email', $formData['exporter_email'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Exporter Address</label>
                                <input type="text" class="form-control" name="exporter_address"
                                    value="{{ old('exporter_address', $formData['exporter_address'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <!-- <div class="col-12 col-lg-6 mb-3">
                                                                                                                                                                                                                                                                        <label class="form-label">Clearing/Forwarding Agent</label>
                                                                                                                                                                                                                                                                        <input type="text" class="form-control" name="cf_agent" value="{{ old('cf_agent') }}"
                                                                                                                                                                                                                                                                            autocomplete="on" required />
                                                                                                                                                                                                                                                                    </div> -->

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Clearing/Forwarding Agent</label>
                                @php
                                    $selectedCfAgent = old('cf_agent', $formData['cf_agent'] ?? '');
                                @endphp
                                <select class="form-select" name="cf_agent">
                                    <option value="0" {{ $selectedCfAgent == '0' ? 'selected' : '' }}>-- select --
                                    </option>
                                    <option value="AGL" {{ $selectedCfAgent == 'AGL' ? 'selected' : '' }}>AGL</option>
                                    <option value="CARGO CONGO" {{ $selectedCfAgent == 'CARGO CONGO' ? 'selected' : '' }}>
                                        CARGO CONGO</option>
                                    <option value="CONNEX" {{ $selectedCfAgent == 'CONNEX' ? 'selected' : '' }}>CONNEX
                                    </option>
                                    <option value="African Logistics"
                                        {{ $selectedCfAgent == 'African Logistics' ? 'selected' : '' }}>African Logistics
                                    </option>
                                    <option value="Afritac" {{ $selectedCfAgent == 'Afritac' ? 'selected' : '' }}>Afritac
                                    </option>
                                    <option value="Amicongo" {{ $selectedCfAgent == 'Amicongo' ? 'selected' : '' }}>
                                        Amicongo</option>
                                    <option value="Aristote" {{ $selectedCfAgent == 'Aristote' ? 'selected' : '' }}>
                                        Aristote</option>
                                    <option value="Bollore" {{ $selectedCfAgent == 'Bollore' ? 'selected' : '' }}>Bollore
                                    </option>
                                    <option value="Brasimba" {{ $selectedCfAgent == 'Brasimba' ? 'selected' : '' }}>
                                        Brasimba</option>
                                    <option value="Brasimba S.A"
                                        {{ $selectedCfAgent == 'Brasimba S.A' ? 'selected' : '' }}>Brasimba S.A</option>
                                    <option value="COSMOS" {{ $selectedCfAgent == 'COSMOS' ? 'selected' : '' }}>COSMOS
                                    </option>
                                    <option value="OLA" {{ $selectedCfAgent == 'OLA' ? 'selected' : '' }}>OLA</option>
                                    <option value="Chemaf" {{ $selectedCfAgent == 'Chemaf' ? 'selected' : '' }}>Chemaf
                                    </option>
                                    <option value="Comexas Afrique"
                                        {{ $selectedCfAgent == 'Comexas Afrique' ? 'selected' : '' }}>Comexas Afrique
                                    </option>
                                    <option value="Comexas" {{ $selectedCfAgent == 'Comexas' ? 'selected' : '' }}>Comexas
                                    </option>
                                    <option value="DCG" {{ $selectedCfAgent == 'DCG' ? 'selected' : '' }}>DCG</option>
                                    <option value="Evele & Co" {{ $selectedCfAgent == 'Evele & Co' ? 'selected' : '' }}>
                                        Evele & Co</option>
                                    <option value="Gecotrans" {{ $selectedCfAgent == 'Gecotrans' ? 'selected' : '' }}>
                                        Gecotrans</option>
                                    <option value="Global Logistics"
                                        {{ $selectedCfAgent == 'Global Logistics' ? 'selected' : '' }}>Global Logistics
                                    </option>
                                    <option value="Malabar" {{ $selectedCfAgent == 'Malabar' ? 'selected' : '' }}>Malabar
                                    </option>
                                    <option value="Polytra" {{ $selectedCfAgent == 'Polytra' ? 'selected' : '' }}>Polytra
                                    </option>
                                    <option value="Spedag" {{ $selectedCfAgent == 'Spedag' ? 'selected' : '' }}>Spedag
                                    </option>
                                    <option value="Tradecorp" {{ $selectedCfAgent == 'Tradecorp' ? 'selected' : '' }}>
                                        Tradecorp</option>
                                    <option value="Trade Service"
                                        {{ $selectedCfAgent == 'Trade Service' ? 'selected' : '' }}>Trade Service</option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Clearing/Forwarding Agent Contact</label>
                                <input type="text" class="form-control" name="cf_agent_contact"
                                    value="{{ old('cf_agent_contact', $formData['cf_agent_contact'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                        aria-labelledby="v-pills-messages-tab" tabindex="0">

                        <div class="row">
                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Cargo Description</label>
                                <textarea class="form-control" name="cargo_description" rows="1" autocomplete="on" required>{{ old('cargo_description', $formData['cargo_description'] ?? '') }}</textarea>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">HS Code</label>
                                <input type="text" class="form-control" name="hs_code"
                                    value="{{ old('hs_code', $formData['hs_code'] ?? '') }}" autocomplete="on"
                                    required />
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Package Type</label>
                                <input type="text" class="form-control" name="package_type"
                                    value="{{ old('package_type', $formData['package_type'] ?? '') }}" autocomplete="on"
                                    required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Quantity (PKG)</label>
                                <input type="number" class="form-control" name="quantity"
                                    value="{{ old('quantity', $formData['quantity'] ?? '') }}" autocomplete="on"
                                    required />
                            </div>
                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Weight (Gross)Kg</label>
                                <input type="number" class="form-control" name="weight"
                                    value="{{ old('weight', $formData['weight'] ?? '') }}" autocomplete="on" required />
                            </div>
                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Volume (Net Weight)T</label>
                                <input type="number" class="form-control" name="volume"
                                    value="{{ old('volume', $formData['volume'] ?? '') }}" autocomplete="on" required />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                        aria-labelledby="v-pills-settings-tab" tabindex="0">

                        <div class="row">

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">PO <span class="fs-6 text-danger">(TBS: To be added
                                        later)</span>
                                </label>
                                <input type="text" class="form-control" name="po"
                                    value="{{ old('po', $formData['po'] ?? '') }}" autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Company Ref <span class="fs-6 text-danger">(Trip
                                        Number)</span></label>
                                <input type="text" class="form-control" name="company_ref"
                                    value="{{ old('company_ref', $formData['company_ref'] ?? '') }}" autocomplete="on"
                                    required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Cargo Origin</label>
                                <input type="text" class="form-control" name="cargo_origin"
                                    value="{{ old('cargo_origin', $formData['cargo_origin'] ?? '') }}" autocomplete="on"
                                    required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Customs Declaration Number</label>
                                <input type="text" class="form-control" name="customs_decl_no"
                                    value="{{ old('customs_decl_no', $formData['customs_decl_no'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Manifest Number / VG</label>
                                <input type="text" class="form-control" name="manifest_no"
                                    value="{{ old('manifest_no', $formData['manifest_no'] ?? '') }}" autocomplete="on"
                                    required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">OCC/ BIVAC</label>
                                <input type="text" class="form-control" name="occ_bivac"
                                    value="{{ old('occ_bivac', $formData['occ_bivac'] ?? '') }}" autocomplete="on"
                                    required />
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Additional Comments</label>
                                <textarea class="form-control" name="instructions" rows="1" autocomplete="on" required>{{ old('instructions', $formData['instructions'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-values" role="tabpanel" aria-labelledby="v-pills-values-tab"
                        tabindex="0">

                        <div class="row">
                            <!-- <div class="col-12 col-lg-3 mb-3">
                                                                                                                                                                                                                                                                        <label class="form-label">FOB Currency</label>
                                                                                                                                                                                                                                                                        <textarea class="form-control" name="fob_currency" rows="1" autocomplete="on" required>{{ old('fob_currency') }}</textarea>
                                                                                                                                                                                                                                                                    </div> -->


                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">FOB Currency</label>
                                @php
                                    $selectedFobCurrency = old('fob_currency', $formData['fob_currency'] ?? '');
                                @endphp
                                <select class="form-select" name="fob_currency">
                                    <option value="USD" {{ $selectedFobCurrency == 'USD' ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="EUR" {{ $selectedFobCurrency == 'EUR' ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="TZS" {{ $selectedFobCurrency == 'TZS' ? 'selected' : '' }}>TZS
                                    </option>
                                    <option value="ZAR" {{ $selectedFobCurrency == 'ZAR' ? 'selected' : '' }}>ZAR
                                    </option>
                                    <option value="AOA" {{ $selectedFobCurrency == 'AOA' ? 'selected' : '' }}>AOA
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">FOB Value / VALEUR FOB</label>
                                <input type="text" class="form-control" name="fob_value"
                                    value="{{ old('fob_value', $formData['fob_value'] ?? '') }}" autocomplete="on"
                                    required />
                            </div>

                            <!-- <div class="col-12 col-lg-3 mb-3">
                                                                                                                                                                                                                                                                        <label class="form-label">Incoterm</label>
                                                                                                                                                                                                                                                                        <input type="text" class="form-control" name="incoterm" value="{{ old('incoterm') }}"
                                                                                                                                                                                                                                                                            autocomplete="on" required />
                                                                                                                                                                                                                                                                    </div> -->


                            <!-- <div class="col-12 col-lg-3 mb-3">
                                                                                                                                                                                                                                                                        <label class="form-label">Freight Currency</label>
                                                                                                                                                                                                                                                                        <input type="text" class="form-control" name="freight_currency"
                                                                                                                                                                                                                                                                            value="{{ old('freight_currency') }}" autocomplete="on" required />
                                                                                                                                                                                                                                                                    </div> -->

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Freight Currency</label>
                                @php
                                    $selectedFreightCurrency = old(
                                        'freight_currency',
                                        $formData['freight_currency'] ?? '',
                                    );
                                @endphp
                                <select class="form-select" name="freight_currency">
                                    <option value="USD" {{ $selectedFreightCurrency == 'USD' ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="EUR" {{ $selectedFreightCurrency == 'EUR' ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="ZAR" {{ $selectedFreightCurrency == 'ZAR' ? 'selected' : '' }}>ZAR
                                    </option>
                                    <option value="AOA" {{ $selectedFreightCurrency == 'AOA' ? 'selected' : '' }}>AOA
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Freight Value</label>
                                <input type="text" class="form-control" name="freight_value"
                                    value="{{ old('freight_value', $formData['freight_value'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <!-- <div class="col-12 col-lg-3 mb-3">
                                                                                                                                                                                                                                                                        <label class="form-label">Insurance Currency</label>
                                                                                                                                                                                                                                                                        <input type="text" class="form-control" name="insurance_currency"
                                                                                                                                                                                                                                                                            value="{{ old('insurance_currency') }}" autocomplete="on" required />
                                                                                                                                                                                                                                                                    </div> -->

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Insurance Currency</label>
                                @php
                                    $selectedInsuranceCurrency = old(
                                        'insurance_currency',
                                        $formData['insurance_currency'] ?? '',
                                    );
                                @endphp
                                <select class="form-select" name="insurance_currency">
                                    <option value="USD" {{ $selectedInsuranceCurrency == 'USD' ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="EUR" {{ $selectedInsuranceCurrency == 'EUR' ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="ZAR" {{ $selectedInsuranceCurrency == 'ZAR' ? 'selected' : '' }}>ZAR
                                    </option>
                                    <option value="TZS" {{ $selectedInsuranceCurrency == 'TZS' ? 'selected' : '' }}>TZS
                                    </option>
                                    <option value="AOA" {{ $selectedInsuranceCurrency == 'AOA' ? 'selected' : '' }}>AOA
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Insurance Value</label>
                                <input type="text" class="form-control" name="insurance_value"
                                    value="{{ old('insurance_value', $formData['insurance_value'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <!-- <div class="col-12 col-lg-3 mb-3">
                                                                                                                                                                                                                                                                        <label class="form-label">Additional Fees Currency</label>
                                                                                                                                                                                                                                                                        <input type="text" class="form-control" name="additional_fees_currency"
                                                                                                                                                                                                                                                                            value="{{ old('additional_fees_currency') }}" autocomplete="on" required />
                                                                                                                                                                                                                                                                    </div> -->

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Additional Fees Currency</label>
                                @php
                                    $selectedAdditionalFeesCurrency = old(
                                        'additional_fees_currency',
                                        $formData['additional_fees_currency'] ?? '',
                                    );
                                @endphp
                                <select class="form-select" name="additional_fees_currency">
                                    <option value="USD"
                                        {{ $selectedAdditionalFeesCurrency == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="EUR"
                                        {{ $selectedAdditionalFeesCurrency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="ZAR"
                                        {{ $selectedAdditionalFeesCurrency == 'ZAR' ? 'selected' : '' }}>ZAR</option>
                                    <option value="TZS"
                                        {{ $selectedAdditionalFeesCurrency == 'TZS' ? 'selected' : '' }}>TZS</option>
                                    <option value="AOA"
                                        {{ $selectedAdditionalFeesCurrency == 'AOA' ? 'selected' : '' }}>AOA</option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Additional Fees Value</label>
                                <input type="text" class="form-control" name="additional_fees_value"
                                    value="{{ old('additional_fees_value', $formData['additional_fees_value'] ?? '') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Incoterm</label>
                                @php
                                    $selectedIncoterm = old('incoterm', $formData['incoterm'] ?? '');
                                @endphp
                                <select class="form-select" name="incoterm">
                                    <option value="0" {{ $selectedIncoterm == '0' ? 'selected' : '' }}>-- select --
                                    </option>
                                    <option value="CFR" {{ $selectedIncoterm == 'CFR' ? 'selected' : '' }}>CFR
                                    </option>
                                    <option value="CIF" {{ $selectedIncoterm == 'CIF' ? 'selected' : '' }}>CIF
                                    </option>
                                    <option value="CIP" {{ $selectedIncoterm == 'CIP' ? 'selected' : '' }}>CIP
                                    </option>
                                    <option value="CPT" {{ $selectedIncoterm == 'CPT' ? 'selected' : '' }}>CPT
                                    </option>
                                    <option value="DAF" {{ $selectedIncoterm == 'DAF' ? 'selected' : '' }}>DAF
                                    </option>
                                    <option value="DAP" {{ $selectedIncoterm == 'DAP' ? 'selected' : '' }}>DAP
                                    </option>
                                    <option value="DAT" {{ $selectedIncoterm == 'DAT' ? 'selected' : '' }}>DAT
                                    </option>
                                    <option value="DDP" {{ $selectedIncoterm == 'DDP' ? 'selected' : '' }}>DDP
                                    </option>
                                    <option value="DDU" {{ $selectedIncoterm == 'DDU' ? 'selected' : '' }}>DDU
                                    </option>
                                    <option value="DEQ" {{ $selectedIncoterm == 'DEQ' ? 'selected' : '' }}>DEQ
                                    </option>
                                    <option value="DES" {{ $selectedIncoterm == 'DES' ? 'selected' : '' }}>DES
                                    </option>
                                    <option value="DPU" {{ $selectedIncoterm == 'DPU' ? 'selected' : '' }}>DPU
                                    </option>
                                    <option value="EXW" {{ $selectedIncoterm == 'EXW' ? 'selected' : '' }}>EXW
                                    </option>
                                    <option value="FAS" {{ $selectedIncoterm == 'FAS' ? 'selected' : '' }}>FAS
                                    </option>
                                    <option value="FCA" {{ $selectedIncoterm == 'FCA' ? 'selected' : '' }}>FCA
                                    </option>
                                    <option value="FOB" {{ $selectedIncoterm == 'FOB' ? 'selected' : '' }}>FOB
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
                                <input type="file" class="form-control" name="customs" autocomplete="on" required />
                            </div>

                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="./dashboard" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn-next btn btn-primarys">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('multiStepForm');
            const tabPanes = document.querySelectorAll('.tab-pane');
            const navLinks = document.querySelectorAll('[data-bs-toggle="pill"]');

            // Helper to update nav-link and input borders
            function updateValidationUI() {
                tabPanes.forEach(function(tabPane) {
                    const requiredInputs = tabPane.querySelectorAll('[required], select[required], select');
                    let hasInvalid = false;

                    requiredInputs.forEach(function(input) {
                        let invalid = false;
                        if (input.tagName === 'SELECT') {
                            // If the first option is "" or "0", treat as invalid if selected
                            if (input.value === "" || input.value == "0") {
                                invalid = true;
                            }
                        } else if (!input.value || (input.type === 'checkbox' && !input.checked)) {
                            invalid = true;
                        }
                        if (invalid) {
                            input.classList.add('is-invalid');
                            hasInvalid = true;
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    });

                    // Find the corresponding nav-link
                    const tabId = tabPane.id;
                    const navLink = document.querySelector('[data-bs-target="#' + tabId + '"]');
                    if (navLink) {
                        navLink.textContent = navLink.textContent.replace(/!$/, '');
                        if (hasInvalid) {
                            navLink.style.color = 'red';
                            navLink.textContent = navLink.textContent.trim() + '!';
                        } else {
                            navLink.style.color = '';
                        }
                    }
                });
            }

            // On submit, validate and update UI
            form.addEventListener('submit', function(e) {
                updateValidationUI();

                // Prevent submit if any invalid
                const anyInvalid = form.querySelector('.is-invalid');
                if (anyInvalid) {
                    e.preventDefault();
                }
            });

            // On input, update UI live
            form.querySelectorAll('[required]').forEach(function(input) {
                input.addEventListener('input', updateValidationUI);
                input.addEventListener('change', updateValidationUI);
            });
        });






        // The company ajax search functionality
        // The company ajax search functionality
        // The company ajax search functionality
        // document.addEventListener("DOMContentLoaded", function() {

        //     const input = document.getElementById('company_input');
        //     const hiddenInput = document.getElementById('company_id');
        //     const suggestionsBox = document.getElementById('company_suggestions');

        //     input.addEventListener('input', function() {
        //         let query = this.value;

        //         // Clear hidden ID when typing
        //         hiddenInput.value = "";

        //         if (query.length < 2) {
        //             suggestionsBox.innerHTML = "";
        //             return;
        //         }

        //         fetch(`/companies/search?q=${query}`)
        //             .then(response => response.json())
        //             .then(data => {
        //                 suggestionsBox.innerHTML = "";

        //                 data.forEach(company => {
        //                     let item = document.createElement("a");
        //                     item.href = "#";
        //                     item.classList.add("list-group-item", "list-group-item-action");
        //                     item.textContent = company.name;

        //                     item.addEventListener("click", function(e) {
        //                         e.preventDefault();
        //                         input.value = company.name;
        //                         hiddenInput.value = company.id;
        //                         suggestionsBox.innerHTML = "";
        //                     });

        //                     suggestionsBox.appendChild(item);
        //                 });
        //             });
        //     });

        //     // Hide suggestions when clicking outside
        //     document.addEventListener("click", function(e) {
        //         if (!input.contains(e.target)) {
        //             suggestionsBox.innerHTML = "";
        //         }
        //     });

        // });
    </script>
@endsection
