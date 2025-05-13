@extends('layouts.admin.main')
@section('content')


<div class="row">
    <div class="col-12">
        @if(session('message'))
        <!-- <div class="col-12"> -->
        <div class="alert my-2 alert-{{ session('status') === 'success' ? 'success' : 'danger' }} alert-dismissible"
            role="alert">
            <div class="alert-icon">
                <i class="fa fa-check"></i>
            </div>
            {{ session('message') }}
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        <!-- </div> -->
        @endif
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of all Users</h3>
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
                                @if ($record->user_auth == 1)
                                <span class="badge bg-success me-1"></span> Active
                                @else
                                <span class="badge bg-danger me-1"></span> Blocked
                                @endif
                            </td>
                            <td class="text-end">
                                <span class="dropdown">
                                    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport"
                                        data-bs-toggle="dropdown">Actions</button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#x{{ $record->id }}">
                                            @if ($record->user_auth == 1)
                                            <i class="fa fa-lock pe-2"></i>Block
                                            @else
                                            <i class="fa fa-lock-open pe-2"></i>Unblock
                                            @endif
                                        </a>
                                        <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                            data-bs-target="#m{{ $record->id }}">
                                            <i class="fa fa-trash pe-2"></i>Delete
                                        </a>
                                    </div>
                                </span>
                            </td>
                        </tr>

                        <!-- delete modal -->
                        <form action="{{ route('admin.deleteUser', $record->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="modal fade" id="m{{ $record->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                        <div class="modal-status bg-danger"></div>
                                        <div class="modal-body text-center py-4">
                                            <i class="mb-2 p-2 display-2 text-danger fa fa-warning" width="24"
                                                height="24"></i>
                                            <h3>Caution!</h3>
                                            <div class="text-secondary">
                                                Are you sure you want to delete {{ $record->name }}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="w-100">
                                                <div class="row">
                                                    <div class="col">
                                                        <a href="#" class="btn w-100" data-bs-dismiss="modal"> Cancel
                                                        </a>
                                                    </div>
                                                    <div class="col">
                                                        <button type="submit" class="btn btn-danger w-100"
                                                            data-bs-dismiss="modal">
                                                            <i class="fa fa-trash me-2"></i> Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Block/unblock modal -->
                        <form action="{{ route('admin.toggleAuth', $record->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="modal fade" id="x{{ $record->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                        <div class="modal-status bg-primary"></div>
                                        <div class="modal-body text-center py-4">
                                            <i class="mb-2 p-2 display-2 text-primary fa fa-user-shield" width="24"
                                                height="24"></i>
                                            <h3>Caution!</h3>
                                            <div class="text-secondary">
                                                Are you sure you want to
                                                {{ $record->user_auth ? 'Revoke Access to' : 'Authorize' }}
                                                {{ $record->name }}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="w-100">
                                                <div class="row">
                                                    <div class="col">
                                                        <a href="#" class="btn w-100" data-bs-dismiss="modal"> Cancel
                                                        </a>
                                                    </div>
                                                    <div class="col">
                                                        @if ($record->user_auth == 1)
                                                        <button type="submit" class="btn btn-danger w-100"
                                                            data-bs-dismiss="modal">
                                                            <i class="fa fa-lock me-2"></i> Block</button>
                                                        @else
                                                        <button type="submit" class="btn btn-success w-100"
                                                            data-bs-dismiss="modal">
                                                            <i class="fa fa-lock-open me-2"></i> Athorize</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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