@extends('layouts.admin.main')
@section('content')




<x-errorshow />

<div class="card">
    <div class="row">
        <div class="col-12 d-flex flex-column">
            <div class="card-body tab-pane fade active show" id="tabs-home-1" role="tabpanel">
                <form action="{{ route('admin.updateCompany', ['id' => $record->id]) }}" method="POST">
                    @csrf
                    <!-- @method('PUT') -->

                    <!-- <h2 class="mb-4">#</h2> -->
                    <h3 class="card-title mb-5">Edit Company Details</h3>

                    <div class="row g-3">

                        <!-- <div class="col-12 mb-3 col-lg-3">
                            <div class="form-label">User ID</div>
                            <input type="text" name="user_id" class="form-control" value="{{ $record->user_id }}">
                        </div> -->

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="name" value="{{ $record->name }}" required />
                        </div>

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label" name="type">Type</label>
                            <select class="form-select" name="type">
                                <option value="{{ $record->type }}">{{ $record->type }}</option>
                                <option value="admin">Admin</option>
                                <option value="vendor">Vendor</option>
                                <option value="transporter">Transporter</option>
                            </select>
                        </div>

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label">Official Email</label>
                            <input type="text" class="form-control" name="email" value="{{ $record->email }}"
                                required />
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" value="{{ $record->address }}"
                                required />
                        </div>

                    </div>

                    <div class="mt-5 pt-5"></div>
                    <div class="col">
                        <a href="{{ route('admin.showCompanies') }}" class="btn btn-secondary">Cancel</a>
                        <button class="btn btn-primary" type="submit">Edit</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>





@endsection