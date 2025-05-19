@extends('layouts.admin.main')
@section('content')

<div class="page-wrapper">

    <x-errorshow />
    <div class="row row-deck row-cards">
        <div class="col">
            <div class="container d-flex justify-content-center align-items-center shadow-lg">
                <form action="{{ route('admin.storeCompany') }}" method="POST" enctype="multipart/form-data"
                    class="w-100 p-5" id="multiStepForm">
                    @csrf
                    <h1 class="text-muted mb-5">Add New Company</h1>


                    <!-- <div class="row">
                        <div class="col-4 mb-3">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" class="form-control" name="invoice_number"
                                placeholder="e.g. INV-2025-001" required />
                        </div>
                    </div> -->

                    <div class="row">

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="name" autocomplete="on" required />
                        </div>

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label" name="type">Type</label>
                            <select class="form-select" name="type">
                                <option value="admin">Admin</option>
                                <option value="vendor">Vendor</option>
                                <option value="transporter">Transporter</option>
                            </select>
                        </div>

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label">Official Email</label>
                            <input type="text" class="form-control" name="email" autocomplete="on" required />
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" autocomplete="on" required />
                        </div>

                    </div>

                    <div class="mb-3 text-end">
                        <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}">Cancel</a>
                        <button class="btn btn-primary" type="submit">Add</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


@endsection