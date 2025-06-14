@extends('layouts.userlayout')
@section('content')

<div class="row card p-3 mb-3">
    <div class="col d-flex justify-content-end align-items-center gap-3">
        <p class="fs-3 mb-0">
            Download Excel template for Feri Application.
        </p>
        <a href="{{ route('transporter.feri.template.download') }}" class="btn btn-success">
            <i class="fa fa-circle-down me-2"></i> Download Template
        </a>
    </div>
</div>

<x-errorshow />

<div class="row p-5 card">
    <h1 class="mb-3">Feri Application Template - Excel</h1>
    <div class="col">
        <form action="{{ route('transporter.feri.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <div class="col-12 mb-4">
                    <label class="form-label">Excel File</label>
                    <input type="file" name="excel_file" class="form-control" required />
                </div>

                <!-- <div class="col-12 mb-3">
                    <label class="form-label">Feri Type</label>
                    <select class="form-select" aria-label="Default select example">
                        <option value="regional" selected>Regional</option>
                        <option value="continuance">Continuance</option>
                    </select>
                </div> -->

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


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>



@endsection