@extends('layouts.admin.main')
@section('content')


<div class="row">
    <div class="col-12">
        <x-errorshow />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of all Companies</h3>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Users</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr>
                            <td><input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox"
                                    aria-label="Select invoice"></td>
                            <td><span class="text-secondary">{{ $record->id }}</span></td>
                            <td>
                                <a href="{{ route('admin.showCompany', ['id' => $record->id]) }}" class="text-reset"
                                    tabindex="-1">
                                    {{ $record->name }}
                                </a>
                            </td>
                            <td>
                                {{ $record->email }}
                            </td>
                            <td>
                                {{ ucfirst($record->address) }}

                            </td>
                            <td>
                                xxusers
                            </td>
                            <td class="text-end">
                                <span class="dropdown">
                                    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport"
                                        data-bs-toggle="dropdown">Actions</button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                            data-bs-target="#m{{ $record->id }}">
                                            <i class="fa fa-trash pe-2"></i>Delete
                                        </a>
                                    </div>
                                </span>
                            </td>
                        </tr>

                        <!-- delete modal -->
                        <form action="{{ route('admin.destroyCompany', $record->id) }}" method="POST">
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