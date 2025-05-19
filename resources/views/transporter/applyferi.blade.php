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
                                <input type="text" class="form-control" value="{{ old('transport_mode') }}"
                                    name="transport_mode" autocomplete="on" required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Transporter Company</label>
                                <input type="text" class="form-control" name="transporter_company" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Entry Board to DRC</label>
                                <select class="form-select" name="entry_border_drc">
                                    <option value="0">-- select --</option>
                                    <option value="Kasumbalesa">Kasumbalesa</option>
                                    <option value="Mokambo">Mokambo</option>
                                    <option value="Sakania">Sakania</option>
                                </select>
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Truck Details</label>
                                <input type="text" class="form-control" name="truck_details" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Station of Arrival <span class="fs-6">(Rail/Air/Port)</span>
                                </label>
                                <input type="text" class="form-control" name="arrival_station" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Final Destination
                                </label>
                                <input type="text" class="form-control" name="final_destination" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                        </div>

                        <x-stepbutton />
                    </div>

                    <div class="form-step" data-step="2">
                        <h1 class="mb-5 pb-3">Import Details</h1>

                        <div class="row">
                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Importer Name</label>
                                <input type="text" class="form-control" name="importer_name" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Importer Phone</label>
                                <input type="text" class="form-control" name="importer_phone" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Importer Email</label>
                                <input type="text" class="form-control" name="importer_email" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">FIX Number</label>
                                <input type="text" class="form-control" name="fix_number" autocomplete="on" required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>
                        </div>

                        <x-stepbutton />
                    </div>

                    <div class="form-step" data-step="3">
                        <h1 class="mb-5 pb-3">Export Details</h1>

                        <div class="row">
                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Exporter Name</label>
                                <input type="text" class="form-control" name="exporter_name" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Exporter Phone</label>
                                <input type="text" class="form-control" name="exporter_phone" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Exporter Email</label>
                                <input type="text" class="form-control" name="exporter_email" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Clearing/Forwarding Agent</label>
                                <input type="text" class="form-control" name="cf_agent" autocomplete="on" required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Clearing/Forwarding Agent Contact</label>
                                <input type="text" class="form-control" name="cf_agent_contact" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>
                        </div>

                        <x-stepbutton />
                    </div>

                    <div class="form-step" data-step="4">
                        <h1 class="mb-5 pb-3">Cargo Description</h1>

                        <div class="row">
                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Cargo Description</label>
                                <textarea type="text" class="form-control" name="cargo_description" rows="1"
                                    autocomplete="on" required></textarea>
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">HS Code</label>
                                <input type="text" class="form-control" name="hs_code" autocomplete="on" required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Package Type</label>
                                <input type="text" class="form-control" name="package_type" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="text" class="form-control" name="quantity" autocomplete="on" required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>
                        </div>

                        <x-stepbutton />
                    </div>

                    <div class="form-step" data-step="5">
                        <h1 class="mb-5 pb-3">Expedition</h1>

                        <div class="row">
                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Company Reference</label>
                                <input type="text" class="form-control" name="company_ref" rows="1" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Cargo Origin</label>
                                <input type="text" class="form-control" name="cargo_origin" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-4 mb-3">
                                <label class="form-label">Customs Declaration Number</label>
                                <input type="text" class="form-control" name="customs_decl_no" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Manifest Number / VG</label>
                                <input type="text" class="form-control" name="manifest_no" autocomplete="on" required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">OCC/ BIVAC</label>
                                <input type="text" class="form-control" name="occ_bivac" autocomplete="on" required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-12 mb-3">
                                <label class="form-label">Instructions / Validation Notes</label>
                                <textarea type="text" class="form-control" name="instructions" rows="1"
                                    autocomplete="on" required></textarea>
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>
                        </div>

                        <x-stepbutton />
                    </div>

                    <div class="form-step" data-step="6">
                        <h1 class="mb-5 pb-3">Values</h1>

                        <div class="row">
                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">FOB Currency</label>
                                <textarea type="text" class="form-control" name="fob_currency" rows="1"
                                    autocomplete="on" required></textarea>
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">FOB Value / VALEUR FOB</label>
                                <input type="text" class="form-control" name="fob_value" autocomplete="on" required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Incoterm</label>
                                <input type="text" class="form-control" name="incoterm" autocomplete="on" required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Freight Currency</label>
                                <input type="text" class="form-control" name="freight_currency" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Freight Value</label>
                                <input type="text" class="form-control" name="freight_value" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Insurance Currency</label>
                                <input type="text" class="form-control" name="insurance_currency" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Insurance Value</label>
                                <input type="text" class="form-control" name="insurance_value" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-3 mb-3">
                                <label class="form-label">Additional Fees Currency</label>
                                <input type="text" class="form-control" name="additional_fees_currency"
                                    autocomplete="on" required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Additional Fees Value</label>
                                <input type="text" class="form-control" name="additional_fees_value" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>

                            <div class="col-12 col-lg-6 mb-3">
                                <label class="form-label">Documents Upload</label>
                                <input type="file" class="form-control" name="documents_upload" autocomplete="on"
                                    required />
                                <div class="error-message">Please enter your full name (minimum 3 characters)</div>
                            </div>
                        </div>

                        <div class="mb-3 text-end">
                            <button type="button" class="btn-prev btn btn-secondary" disabled>Previous</button>
                            <button type="submit" class="btn-next btn btn-primarys">Submit</button>
                        </div>
                    </div>

                    <!-- <div class="mb-3 text-end">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div> -->
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