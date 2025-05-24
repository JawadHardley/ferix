@extends('layouts.admin.main')
@section('content')


<div class="row">
    <div class="col-12">
        <x-errorshow />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of all Feri Applications</h3>
            </div>
            <div class="table-respnsive">
                <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th>
                                ID
                            </th>
                            <th>Reference</th>
                            <th>Applicant</th>
                            <th>Company</th>
                            <th>Date</th>
                            <th>PO</th>
                            <th>Manifest</th>
                            <th>Type</th>
                            <th>Document</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr>
                            <td><span class="text-secondary">{{ $record->id }}</span></td>
                            <td><a href="{{ route('vendor.showApp', ['id' => $record->id]) }}" class="text-reset"
                                    tabindex="-1">
                                    {{ ucfirst($record->company_ref) }}
                                </a></td>
                            <td>
                                {{ $record->applicantName }}
                            </td>
                            <td>
                                {{ $record->companyName }}
                            </td>
                            <td>
                                {{ $record->created_at->format('j F Y') }}

                            </td>
                            <td>
                                {{ ucfirst($record->fix_number) }}
                            </td>
                            <td>
                                {{ ucfirst($record->manifest_no) }}
                            </td>
                            <td>
                                {{ ucfirst($record->transport_mode) }}
                            </td>
                            <td class="text-center">
                                @if ($record->status == 1 || $record->status == 2)
                                <i class="fa fa-spinner" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="In progress"></i>
                                @endif

                                @if ($record->status == 3 || $record->status == 4)
                                <a href="{{ route('certificate.downloaddraft', ['id' => $record->id]) }}"
                                    class="text-decoration-none" download>
                                    <i class="fa fa-file" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Draft"></i>
                                </a>
                                @endif

                                @if ($record->status == 5)
                                <a href="{{ route('certificate.download', ['id' => $record->id]) }}"
                                    class="text-decoration-none" download>
                                    <i class="fa fa-certificate" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Certificate"></i>
                                </a>
                                @endif
                            </td>
                            <td>
                                @if ($record->status == 1)
                                <!-- <span class="badge bg-danger me-1"></span> New Entry -->
                                <span class="status-dot status-dot-animated status-red me-1"></span> New Entry
                                @elseif ($record->status == 2)
                                <span class="badge bg-warning me-1"></span> Process
                                @elseif ($record->status == 3)
                                <span class="status-dot status-cyan me-1"></span> Awaiting Approval
                                @elseif ($record->status == 4)
                                <span class="badge bg-primary me-1"></span> Approved
                                @elseif ($record->status == 5)
                                <span class="status-dot status-green me-1"></span> Complete
                                @endif
                            <td class="text-end">
                                <div class="dropdown">
                                    <a href="#" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                        Actions
                                    </a>
                                    <div class="dropdown-menu dropstart">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#mXX">
                                            <i class="fa fa-message pe-2"></i>Query
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('vendor.showApp', ['id' => $record->id]) }}">
                                            <i class="fa fa-eye pe-2"></i>View
                                        </a>
                                    </div>
                                </div>
                                <!-- <div class="dropdown">
                                    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport"
                                        data-bs-toggle="dropdown">Actions</button>
                                    <div class="dropdown-menu dropdown-menu-start">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#mXX">
                                            <i class="fa fa-message pe-2"></i>Query
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('vendor.showApp', ['id' => $record->id]) }}">
                                            <i class="fa fa-eye pe-2"></i>View
                                        </a>

                                    </div>
                                </div> -->
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