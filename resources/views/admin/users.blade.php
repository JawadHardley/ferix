@extends('layouts.admin.main')
@section('content')


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List All Users</h3>
            </div>
            <div class="table-respnsive">
                <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox"
                                    aria-label="Select all invoices"></th>
                            <th class="w-1">
                                ID
                            </th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr>
                            <td><input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox"
                                    aria-label="Select invoice"></td>
                            <td><span class="text-secondary">{{ $record->id }}</span></td>
                            <td><a href="invoice.html" class="text-reset" tabindex="-1">
                                    {{ $record->name }}
                                </a></td>
                            <td>
                                {{ $record->email }}
                            </td>
                            <td>
                                {{ ucfirst($record->role) }}

                            </td>
                            <td>
                                {{ ucfirst($record->company) }}
                            </td>
                            <td>
                                <span class="badge bg-success me-1"></span> Paid
                            </td>
                            <td class="text-end">
                                <span class="dropdown">
                                    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport"
                                        data-bs-toggle="dropdown">Actions</button>
                                    <div class="dropdown-menu dropdown-menu">
                                        <a class="dropdown-item" href="#"> Action </a>
                                        <a class="dropdown-item" href="#"> Another </a>
                                    </div>
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-secondary">Entries</p>
                <ul class="pagination m-0 ms-auto">
                    {{ $records->links() }}
                </ul>
            </div>
        </div>
    </div>
</div>


@endsection