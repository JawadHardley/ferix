@extends('layouts.userlayout')
@section('content')

@php
$send = asset('images/send.svg');
@endphp

<div class="page-wrapper">

    <x-errorshow />
    <div class="row row-deck row-cards">
        <div class="col">
            <div class="container d-flex justify-content-center align-items-center shadow-lg">
                <form action="{{ route('transporter.feriApp') }}" method="POST" enctype="multipart/form-data"
                    class="w-100 p-5" id="multiStepForm">
                    @csrf
                    <h1 class="text-muted mb-5">Feri Application Form</h1>

                    <div class="stepper text-center mx-auto">
                        <div class="progress" id="progress"></div>
                        <div class="step active" data-step="1">1</div>
                        <div class="step" data-step="2">2</div>
                        <div class="step" data-step="3">3</div>
                        <div class="step" data-step="4">4</div>
                        <div class="step" data-step="5">5</div>
                        <div class="step" data-step="6">6</div>
                    </div>

                    <div class="form-step active" data-step="1">
                        <h1 class="mb-5 pb-3">Transport & Cargo Details</h1>
                        <div class="row">
                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Transport Mode</label>
                                <select class="form-select" name="transport_mode" autocomplete="on" required>
                                    <option value="Road" {{ old('transport_mode') == 'Road' ? 'selected' : '' }}>Road
                                    </option>
                                    <option value="Air" {{ old('transport_mode') == 'Air' ? 'selected' : '' }}>Air
                                    </option>
                                    <option value="Maritime"
                                        {{ old('transport_mode') == 'Maritime' ? 'selected' : '' }}>Maritime</option>
                                    <option value="Rail" {{ old('transport_mode') == 'Rail' ? 'selected' : '' }}>Rail
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Transporter Company</label>
                                <select class="form-select" name="transporter_company">
                                    <option value="">-- select --</option>
                                    @foreach($records as $record)
                                    <option value="{{ $record->id }}"
                                        {{ old('transporter_company', $dbValue->transporter_company ?? '') == $record->id ? 'selected' : '' }}>
                                        {{ $record->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Entry Board to DRC</label>
                                <select class="form-select" name="entry_border_drc">
                                    <option value="0" {{ old('entry_border_drc') == '0' ? 'selected' : '' }}>-- select
                                        --</option>
                                    <option value="Kasumbalesa"
                                        {{ old('entry_border_drc') == 'Kasumbalesa' ? 'selected' : '' }}>Kasumbalesa
                                    </option>
                                    <option value="Mokambo"
                                        {{ old('entry_border_drc') == 'Mokambo' ? 'selected' : '' }}>Mokambo</option>
                                    <option value="Sakania"
                                        {{ old('entry_border_drc') == 'Sakania' ? 'selected' : '' }}>Sakania</option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Truck Details</label>
                                <input type="text" class="form-control" name="truck_details"
                                    value="{{ old('truck_details') }}" autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Station of Arrival <span
                                        class="fs-6">(Rail/Air/Port)</span></label>
                                <input type="text" class="form-control" name="arrival_station"
                                    value="{{ old('arrival_station') }}" autocomplete="on" required />
                            </div>

                            <!-- <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Final Destination</label>
                                <input type="text" class="form-control" name="final_destination"
                                    value="{{ old('final_destination') }}" autocomplete="on" required />
                            </div> -->

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Entry Board to DRC</label>
                                <select class="form-select" name="final_destination">
                                    <option value="0" {{ old('final_destination') == '0' ? 'selected' : '' }}>-- select
                                        --</option>
                                    <option value="Likasi, DRC"
                                        {{ old('final_destination') == "Likasi, DRC" ? 'selected' : '' }}>Likasi, DRC
                                    </option>
                                    <option value="Kolwezi, DRC"
                                        {{ old('final_destination') == "Kolwezi, DRC" ? 'selected' : '' }}>Kolwezi, DRC
                                    </option>
                                    <option value="Lubumbashi, DRC"
                                        {{ old('final_destination') == "Lubumbashi, DRC" ? 'selected' : '' }}>
                                        Lubumbashi, DRC</option>
                                    <option value="Tenke, DRC"
                                        {{ old('final_destination') == "Tenke, DRC" ? 'selected' : '' }}>
                                        Tenke, DRC</option>
                                    <option value="Kisanfu, DRC"
                                        {{ old('final_destination') == "Kisanfu, DRC" ? 'selected' : '' }}>
                                        Kisanfu, DRC</option>
                                </select>
                            </div>

                        </div>

                        <x-stepbutton />
                    </div>

                    <div class="form-step" data-step="2">
                        <h1 class="mb-5 pb-3">Import Details</h1>
                        <div class="row">

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Importer Name</label>
                                <input type="text" class="form-control" name="importer_name"
                                    value="{{ old('importer_name') }}" autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Importer Address</label>
                                <input type="text" class="form-control" name="importer_address"
                                    value="{{ old('importer_address') }}" autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Importer Details</label>
                                <input type="text" class="form-control" name="importer_details"
                                    value="{{ old('importer_details') }}" autocomplete="on" required />
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
                                <label class="form-label">FIX Number</label>
                                <input type="text" class="form-control" name="fix_number"
                                    value="{{ old('fix_number') }}" autocomplete="on" required />
                            </div>
                        </div>

                        <x-stepbutton />
                    </div>

                    <div class="form-step" data-step="3">
                        <h1 class="mb-5 pb-3">Export Details</h1>
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
                                <input type="text" class="form-control" name="exporter_email"
                                    value="{{ old('exporter_email') }}" autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Exporter Address</label>
                                <input type="text" class="form-control" name="exporter_address"
                                    value="{{ old('exporter_address') }}" autocomplete="on" required />
                            </div>

                            <!-- <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Clearing/Forwarding Agent</label>
                                <input type="text" class="form-control" name="cf_agent" value="{{ old('cf_agent') }}"
                                    autocomplete="on" required />
                            </div> -->

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Clearing/Forwarding Agent</label>
                                <select class="form-select" name="cf_agent">
                                    <option value="0" {{ old('cf_agent') == '0' ? 'selected' : '' }}>-- select --
                                    </option>
                                    <option value="AGL" {{ old('cf_agent') == "AGL" ? 'selected' : '' }}>AGL</option>
                                    <option value="CARGO CONGO"
                                        {{ old('cf_agent') == "CARGO CONGO" ? 'selected' : '' }}>CARGO CONGO</option>
                                    <option value="CONNEX" {{ old('cf_agent') == "CONNEX" ? 'selected' : '' }}>CONNEX
                                    </option>
                                    <option value="African Logistics"
                                        {{ old('cf_agent') == "African Logistics" ? 'selected' : '' }}>African Logistics
                                    </option>
                                    <option value="Afritac" {{ old('cf_agent') == "Afritac" ? 'selected' : '' }}>Afritac
                                    </option>
                                    <option value="Amicongo" {{ old('cf_agent') == "Amicongo" ? 'selected' : '' }}>
                                        Amicongo</option>
                                    <option value="Aristote" {{ old('cf_agent') == "Aristote" ? 'selected' : '' }}>
                                        Aristote</option>
                                    <option value="Bollore" {{ old('cf_agent') == "Bollore" ? 'selected' : '' }}>Bollore
                                    </option>
                                    <option value="Brasimba" {{ old('cf_agent') == "Brasimba" ? 'selected' : '' }}>
                                        Brasimba</option>
                                    <option value="Brasimba S.A"
                                        {{ old('cf_agent') == "Brasimba S.A" ? 'selected' : '' }}>Brasimba S.A</option>
                                    <option value="Chemaf" {{ old('cf_agent') == "Chemaf" ? 'selected' : '' }}>Chemaf
                                    </option>
                                    <option value="Comexas Afrique"
                                        {{ old('cf_agent') == "Comexas Afrique" ? 'selected' : '' }}>Comexas Afrique
                                    </option>
                                    <option value="Comexas" {{ old('cf_agent') == "Comexas" ? 'selected' : '' }}>Comexas
                                    </option>
                                    <option value="DCG" {{ old('cf_agent') == "DCG" ? 'selected' : '' }}>DCG</option>
                                    <option value="Evele & Co" {{ old('cf_agent') == "Evele & Co" ? 'selected' : '' }}>
                                        Evele & Co</option>
                                    <option value="Gecotrans" {{ old('cf_agent') == "Gecotrans" ? 'selected' : '' }}>
                                        Gecotrans</option>
                                    <option value="Global Logistics"
                                        {{ old('cf_agent') == "Global Logistics" ? 'selected' : '' }}>Global Logistics
                                    </option>
                                    <option value="Malabar" {{ old('cf_agent') == "Malabar" ? 'selected' : '' }}>Malabar
                                    </option>
                                    <option value="Polytra" {{ old('cf_agent') == "Polytra" ? 'selected' : '' }}>Polytra
                                    </option>
                                    <option value="Spedag" {{ old('cf_agent') == "Spedag" ? 'selected' : '' }}>Spedag
                                    </option>
                                    <option value="Tradecorp" {{ old('cf_agent') == "Tradecorp" ? 'selected' : '' }}>
                                        Tradecorp</option>
                                    <option value="Trade Service"
                                        {{ old('cf_agent') == "Trade Service" ? 'selected' : '' }}>Trade Service
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Clearing/Forwarding Agent Contact</label>
                                <input type="text" class="form-control" name="cf_agent_contact"
                                    value="{{ old('cf_agent_contact') }}" autocomplete="on" required />
                            </div>
                        </div>

                        <x-stepbutton />
                    </div>

                    <div class="form-step" data-step="4">
                        <h1 class="mb-5 pb-3">Cargo Description</h1>
                        <div class="row">
                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Cargo Description</label>
                                <textarea class="form-control" name="cargo_description" rows="1" autocomplete="on"
                                    required>{{ old('cargo_description') }}</textarea>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">HS Code</label>
                                <input type="text" class="form-control" name="hs_code" value="{{ old('hs_code') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Package Type</label>
                                <input type="text" class="form-control" name="package_type"
                                    value="{{ old('package_type') }}" autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity" value="{{ old('quantity') }}"
                                    autocomplete="on" required />
                            </div>
                        </div>

                        <x-stepbutton />
                    </div>

                    <div class="form-step" data-step="5">
                        <h1 class="mb-5 pb-3">Expedition</h1>
                        <div class="row">

                            <div class="col-12 col-lg-2 mb-3">
                                <label class="form-label">PO <span class="fs-6 text-danger">(TBS: To be added
                                        later)</span>
                                </label>
                                <input type="text" class="form-control" name="po" value="{{ old('po') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-2 mb-3">
                                <label class="form-label">Company Reference</label>
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

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Manifest Number / VG</label>
                                <input type="text" class="form-control" name="manifest_no"
                                    value="{{ old('manifest_no') }}" autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">OCC/ BIVAC</label>
                                <input type="text" class="form-control" name="occ_bivac" value="{{ old('occ_bivac') }}"
                                    autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Instructions / Validation Notes</label>
                                <textarea class="form-control" name="instructions" rows="1" autocomplete="on"
                                    required>{{ old('instructions') }}</textarea>
                            </div>
                        </div>

                        <x-stepbutton />
                    </div>

                    <div class="form-step" data-step="6">
                        <h1 class="mb-5 pb-3">Values</h1>
                        <div class="row">
                            <!-- <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">FOB Currency</label>
                                <textarea class="form-control" name="fob_currency" rows="1" autocomplete="on"
                                    required>{{ old('fob_currency') }}</textarea>
                            </div> -->


                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">FOB Currency</label>
                                <select class="form-select" name="fob_currency">
                                    <option value="USD" {{ old('fob_currency') == "USD" ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="EUR" {{ old('fob_currency') == "EUR" ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="ZAR" {{ old('fob_currency') == "ZAR" ? 'selected' : '' }}>ZAR
                                    </option>
                                    <option value="EUR" {{ old('fob_currency') == "EUR" ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="AOA" {{ old('fob_currency') == "AOA" ? 'selected' : '' }}>AOA
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">FOB Value / VALEUR FOB</label>
                                <input type="text" class="form-control" name="fob_value" value="{{ old('fob_value') }}"
                                    autocomplete="on" required />
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
                                <select class="form-select" name="freight_currency">
                                    <option value="USD" {{ old('freight_currency') == "USD" ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="EUR" {{ old('freight_currency') == "EUR" ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="ZAR" {{ old('freight_currency') == "ZAR" ? 'selected' : '' }}>ZAR
                                    </option>
                                    <option value="EUR" {{ old('freight_currency') == "EUR" ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="AOA" {{ old('freight_currency') == "AOA" ? 'selected' : '' }}>AOA
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Freight Value</label>
                                <input type="text" class="form-control" name="freight_value"
                                    value="{{ old('freight_value') }}" autocomplete="on" required />
                            </div>

                            <!-- <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Insurance Currency</label>
                                <input type="text" class="form-control" name="insurance_currency"
                                    value="{{ old('insurance_currency') }}" autocomplete="on" required />
                            </div> -->

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Insurance Currency</label>
                                <select class="form-select" name="insurance_currency">
                                    <option value="USD" {{ old('insurance_currency') == "USD" ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="EUR" {{ old('insurance_currency') == "EUR" ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="ZAR" {{ old('insurance_currency') == "ZAR" ? 'selected' : '' }}>ZAR
                                    </option>
                                    <option value="EUR" {{ old('insurance_currency') == "EUR" ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="AOA" {{ old('insurance_currency') == "AOA" ? 'selected' : '' }}>AOA
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Insurance Value</label>
                                <input type="text" class="form-control" name="insurance_value"
                                    value="{{ old('insurance_value') }}" autocomplete="on" required />
                            </div>

                            <!-- <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Additional Fees Currency</label>
                                <input type="text" class="form-control" name="additional_fees_currency"
                                    value="{{ old('additional_fees_currency') }}" autocomplete="on" required />
                            </div> -->

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Additional Fees Currency</label>
                                <select class="form-select" name="additional_fees_currency">
                                    <option value="USD"
                                        {{ old('additional_fees_currency') == "USD" ? 'selected' : '' }}>USD
                                    </option>
                                    <option value="EUR"
                                        {{ old('additional_fees_currency') == "EUR" ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="ZAR"
                                        {{ old('additional_fees_currency') == "ZAR" ? 'selected' : '' }}>ZAR
                                    </option>
                                    <option value="EUR"
                                        {{ old('additional_fees_currency') == "EUR" ? 'selected' : '' }}>EUR
                                    </option>
                                    <option value="AOA"
                                        {{ old('additional_fees_currency') == "AOA" ? 'selected' : '' }}>AOA
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Additional Fees Value</label>
                                <input type="text" class="form-control" name="additional_fees_value"
                                    value="{{ old('additional_fees_value') }}" autocomplete="on" required />
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Incoterm</label>
                                <select class="form-select" name="incoterm">
                                    <option value="0" {{ old('incoterm') == '0' ? 'selected' : '' }}>-- select --
                                    </option>
                                    <option value="CFR" {{ old('incoterm') == "CFR" ? 'selected' : '' }}>CFR</option>
                                    <option value="CIF" {{ old('incoterm') == "CIF" ? 'selected' : '' }}>CIF</option>
                                    <option value="CIP" {{ old('incoterm') == "CIP" ? 'selected' : '' }}>CIP</option>
                                    <option value="CPT" {{ old('incoterm') == "CPT" ? 'selected' : '' }}>CPT</option>
                                    <option value="DAF" {{ old('incoterm') == "DAF" ? 'selected' : '' }}>DAF</option>
                                    <option value="DAP" {{ old('incoterm') == "DAP" ? 'selected' : '' }}>DAP</option>
                                    <option value="DAT" {{ old('incoterm') == "DAT" ? 'selected' : '' }}>DAT</option>
                                    <option value="DDP" {{ old('incoterm') == "DDP" ? 'selected' : '' }}>DDP</option>
                                    <option value="DDU" {{ old('incoterm') == "DDU" ? 'selected' : '' }}>DDU</option>
                                    <option value="DEQ" {{ old('incoterm') == "DEQ" ? 'selected' : '' }}>DEQ</option>
                                    <option value="DES" {{ old('incoterm') == "DES" ? 'selected' : '' }}>DES</option>
                                    <option value="DPU" {{ old('incoterm') == "DPU" ? 'selected' : '' }}>DPU</option>
                                    <option value="EXW" {{ old('incoterm') == "EXW" ? 'selected' : '' }}>EXW</option>
                                    <option value="FAS" {{ old('incoterm') == "FAS" ? 'selected' : '' }}>FAS</option>
                                    <option value="FCA" {{ old('incoterm') == "FCA" ? 'selected' : '' }}>FCA</option>
                                    <option value="FOB" {{ old('incoterm') == "FOB" ? 'selected' : '' }}>FOB</option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Documents Upload</label>
                                <input type="file" class="form-control" name="documents_upload" autocomplete="on"
                                    required />
                            </div>
                        </div>

                        <div class="mb-3 text-end">
                            <button type="button" class="btn-prev btn btn-secondary" disabled>Previous</button>
                            <button type="submit" class="btn-next btn btn-primarys">Submit</button>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col p-5 m-5 success-message">
                        <div class="p-5">
                            <img src="{{ $send }}" class="img-fluid" style="width: 12rem; height: 12rem;"
                                alt="application sent image" />
                        </div>

                        <h2>Thank you!</h2>
                        <p>Your form has been successfully submitted.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection