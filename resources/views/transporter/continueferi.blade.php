@extends('layouts.userlayout')
@section('content')
<x-errorshow />
<div class="card">
    <h1 class="m-3">Continuance Feri Application Form</h1>
    <hr />
    <form action="{{ route('transporter.feriApp') }}" method="POST" enctype="multipart/form-data" class="w-100 p-5"
        id="multiStepForm" novalidate>
        @csrf

        <div class="row d-flex align-items-start">
            <div class="col-12 col-md-2 nav flex-column nav-pills border" id="v-pills-tab" role="tablist"
                aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home"
                    aria-selected="true">Freight Details </button>
                <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile"
                    aria-selected="false">Transport & Cargo Details</button>
                <button class="nav-link" id="v-pills-disabled-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-disabled" type="button" role="tab" aria-controls="v-pills-disabled"
                    aria-selected="false">Export + Values Details</button>
            </div>
            <div class="col-12 col-md-10 tab-content px-5" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                    aria-labelledby="v-pills-home-tab" tabindex="0">
                    <div class="row">

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label">Company Ref <span class="fs-6 text-danger">(Trip
                                    Number)</span></label>
                            <input type="text" class="form-control" name="company_ref" value="{{ old('company_ref') }}"
                                autocomplete="on" required />
                        </div>

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label">PO <span class="fs-6 text-danger">(TBS: To be added
                                    later)</span>
                            </label>
                            <input type="text" class="form-control" name="po" value="{{ old('po') }}" autocomplete="on"
                                required />
                        </div>

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label">Validated Feri Certificate Number</label>
                            <input type="text" class="form-control" name="validate_feri_cert"
                                value="{{ old('validate_feri_cert') }}" autocomplete="on" required />
                        </div>

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label">Entry Boarder to DRC</label>
                            <select class="form-select" name="entry_border_drc">
                                <option value="0" {{ old('entry_border_drc') == '0' ? 'selected' : '' }}>-- select
                                    --</option>
                                <option value="Kasumbalesa"
                                    {{ old('entry_border_drc') == 'Kasumbalesa' ? 'selected' : '' }}>Kasumbalesa
                                </option>
                                <option value="Mokambo" {{ old('entry_border_drc') == 'Mokambo' ? 'selected' : '' }}>
                                    Mokambo</option>
                                <option value="Sakania" {{ old('entry_border_drc') == 'Sakania' ? 'selected' : '' }}>
                                    Sakania</option>
                            </select>
                        </div>

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label">Final Destination</label>
                            <select class="form-select" name="final_destination">
                                <option value="0" {{ old('final_destination') == '0' ? 'selected' : '' }}>-- select
                                    --</option>
                                <option value="Likasi, DRC"
                                    {{ old('final_destination') == "Likasi DRC" ? 'selected' : '' }}>Likasi DRC
                                </option>
                                <option value="Kolwezi, DRC"
                                    {{ old('final_destination') == "Kolwezi DRC" ? 'selected' : '' }}>Kolwezi DRC
                                </option>
                                <option value="Lubumbashi, DRC"
                                    {{ old('final_destination') == "Lubumbashi DRC" ? 'selected' : '' }}>
                                    Lubumbashi DRC</option>
                                <option value="Tenke, DRC"
                                    {{ old('final_destination') == "Tenke DRC" ? 'selected' : '' }}>
                                    Tenke DRC</option>
                                <option value="Kisanfu, DRC"
                                    {{ old('final_destination') == "Kisanfu DRC" ? 'selected' : '' }}>
                                    Kisanfu DRC</option>
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
                            <label class="form-label">Port of Arrival <span class="fs-6">(Rail/Air/Port)</span></label>
                            <input type="text" class="form-control" name="arrival_station"
                                value="{{ old('arrival_station') }}" autocomplete="on" required />
                        </div>

                        <!-- <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Final Destination</label>
                                <input type="text" class="form-control" name="final_destination"
                                    value="{{ old('final_destination') }}" autocomplete="on" required />
                            </div> -->


                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab"
                    tabindex="0">
                    <div class="row">

                        <div class="col-12 col-lg-12 mb-3">
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

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" value="{{ old('quantity') }}"
                                autocomplete="on" required />
                        </div>


                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label">Weights: Tons/kgs</label>
                            <input type="number" class="form-control" name="weight" value="{{ old('weight') }}"
                                autocomplete="on" required />
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
                                <option value="0" {{ old('cf_agent') == '0' ? 'selected' : '' }}>-- select --
                                </option>
                                <option value="AGL" {{ old('cf_agent') == "AGL" ? 'selected' : '' }}>AGL</option>
                                <option value="CARGO CONGO" {{ old('cf_agent') == "CARGO CONGO" ? 'selected' : '' }}>
                                    CARGO CONGO</option>
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
                                <option value="Brasimba S.A" {{ old('cf_agent') == "Brasimba S.A" ? 'selected' : '' }}>
                                    Brasimba S.A</option>
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


                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-disabled" role="tabpanel" aria-labelledby="v-pills-disabled-tab"
                    tabindex="0">
                    <div class="row">

                        <div class="col-12 col-lg-12 mb-3">
                            <label class="form-label">Forwarding Agent</label>
                            <input type="text" class="form-control" name="exporter_name"
                                value="{{ old('exporter_name') }}" autocomplete="on" required />
                        </div>

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
                            <label class="form-label">Freight Cost</label>
                            <input type="text" class="form-control" name="freight_value"
                                value="{{ old('freight_value') }}" autocomplete="on" required />
                        </div>

                        <div class="col-12 col-lg-3 mb-3">
                            <label class="form-label">FOB Value / VALEUR FOB</label>
                            <input type="text" class="form-control" name="fob_value" value="{{ old('fob_value') }}"
                                autocomplete="on" required />
                        </div>

                        <div class="col-12 col-lg-3 mb-3">
                            <label class="form-label">Insurance Value</label>
                            <input type="text" class="form-control" name="insurance_value"
                                value="{{ old('insurance_value') }}" autocomplete="on" required />
                        </div>

                        <div class="col-12 col-lg-6 mb-3">
                            <label class="form-label">Additional Comments</label>
                            <textarea class="form-control" name="instructions" rows="1" autocomplete="on"
                                required>{{ old('instructions') }}</textarea>
                        </div>


                        <div class="col-12 col-lg-3 mb-3">
                            <label class="form-label">Commercial Invoice</label>
                            <input type="file" class="form-control" name="invoice" autocomplete="on" required />
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
                            <input type="file" class="form-control" name="customs" autocomplete="on" />
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
                    if (input.value === "" || input.value === "0") {
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
</script>

@endsection