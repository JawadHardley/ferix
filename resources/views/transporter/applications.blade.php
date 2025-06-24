@extends('layouts.userlayout')
@section('content')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>

    <div class="row">
        <div class="col-12">
            <x-errorshow />
            <div class="card fade-slide-in">
                <div class="card-header">
                    <div class="row w-full">
                        <div class="col">
                            <h3 class="card-title mb-0">List of Applications</h3>
                            <p class="text-secondary m-0">
                                with extensive search
                            </p>
                        </div>
                        <div class="col-md-auto col-sm-12 d-flex">
                            <div class="input-group mb-3 mx-2">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input id="advanced-table-search" type="text" class="form-control" placeholder="Search">
                            </div>
                            <div class="input-group mb-3 mx-2">
                                <select class="form-select">
                                    <option selected>10</option>
                                    <option value="1">20</option>
                                    <option value="2">50</option>
                                    <option value="3">100</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-auto col-sm-12 d-flex">
                            <div class="input-group mb-3">
                                <a href="{{ route('transporter.exportapps') }}" class="btn btn-outline-success">
                                    <i class="fa fa-circle-down me-2"></i> Export
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-respnsive">
                    <table id="linework"
                        class="table table-selectable card-table table-vcenter text-nowrap datatable display">
                        <thead>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>Reference</th>
                                <th>Applicant</th>
                                <th>Date</th>
                                <th>PO</th>
                                <th>Type</th>
                                <th>Customs No</th>
                                <th>Feri Cert NO</th>
                                <th>Document</th>
                                <th>Status</th>
                                <th>Query</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    <td><span class="text-secondary">{{ $record->id }}</span></td>
                                    <td><a href="{{ route('transporter.showApp', ['id' => $record->id]) }}"
                                            class="text-reset" tabindex="-1">
                                            {{ ucfirst($record->company_ref) }}
                                        </a></td>
                                    <td>
                                        {{ $record->applicant }}
                                    </td>
                                    <td>
                                        {{ $record->created_at->format('j F Y') }}

                                    </td>
                                    <td>
                                        @if (is_numeric($record->po))
                                            <span class="badge bg-teal-lt text-teal-lt-fg">{{ $record->po }}</span>
                                        @else
                                            <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="to be added">TBS</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ ucfirst($record->feri_type) }}
                                    </td>
                                    <td>
                                        {{ ucfirst($record->customs_decl_no) }}
                                    </td>
                                    <td>
                                        {{ ucfirst($record->feri_cert_no) }}
                                    </td>
                                    <td class="text-start">
                                        @if ($record->status == 1 || $record->status == 2)
                                            <i class="fa fa-spinner" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="In progress"></i>
                                        @endif

                                        @if ($record->status == 3 || $record->status == 4 || $record->status == 6)
                                            <a href="{{ route('certificate.downloaddraft', ['id' => $record->id]) }}"
                                                class="text-decoration-none mx-1" download>
                                                <i class="fa fa-file" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Draft"></i>
                                            </a> +
                                            <a href="{{ route('invoices.downloadinvoice', ['id' => $record->id]) }}"
                                                class="text-decoration-none mx-1">
                                                <i class="fa fa-file-invoice-dollar" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Invoice"></i>
                                            </a>
                                        @endif

                                        @if ($record->status == 5)
                                            <a href="{{ route('certificate.download', ['id' => $record->id]) }}"
                                                class="text-decoration-none mx-1" download>
                                                <i class="fa fa-certificate" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Certificate"></i>
                                            </a> +
                                            <a href="{{ route('invoices.downloadinvoice', ['id' => $record->id]) }}"
                                                class="text-decoration-none mx-1">
                                                <i class="fa fa-file-invoice-dollar" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Invoice"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($record->status == 1)
                                            <span class="badge bg-danger me-1"></span> Pending
                                        @elseif ($record->status == 2)
                                            <span class="badge bg-warning me-1"></span> Pending
                                        @elseif ($record->status == 3)
                                            <span class="status-dot status-dot-animated status-cyan me-1"></span> Draft
                                            Approval
                                        @elseif ($record->status == 4)
                                            <span class="badge bg-primary me-1"></span> In progress
                                        @elseif ($record->status == 5)
                                            <span class="status-dot status-dot-animated status-green me-1"></span> Complete
                                        @elseif ($record->status == 6)
                                            <span class="status-dot status-dot-animated status-danger me-1"></span> Rejected
                                        @endif
                                    </td>
                                    <td class="">
                                        @php
                                            $unreadChats = $chats->filter(function ($chat) use ($record) {
                                                return $chat->user_id != Auth::id() &&
                                                    $chat->read == 0 &&
                                                    $chat->application_id == $record->id;
                                            });
                                        @endphp

                                        <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                            data-bs-target="#chat{{ $record->id }}">
                                            <i class="fa fa-comment-dots"></i>

                                            @if ($unreadChats->isNotEmpty())
                                                <span class="badge bg-red mb-2"></span>
                                            @endif
                                        </a>


                                    </td>
                                    <td class="text-start">
                                        <span class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport"
                                                data-bs-toggle="dropdown" data-bs-offset="0,0" data-bs-display="static">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-start">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#mXX">
                                                    <i class="fa fa-comment-dots pe-2"></i>Query
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('transporter.showApp', ['id' => $record->id]) }}">
                                                    <i class="fa fa-eye pe-2"></i>View
                                                </a>

                                                @if ($record->status == 1)
                                                    <a class="dropdown-item text-danger" href="#"
                                                        data-bs-toggle="modal" data-bs-target="#m{{ $record->id }}">
                                                        <i class="fa fa-trash pe-2"></i>Delete
                                                    </a>
                                                @endif

                                            </div>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>Reference</th>
                                <th>Applicant</th>
                                <th>Date</th>
                                <th>PO</th>
                                <th>Manifest</th>
                                <th>Type</th>
                                <th>Document</th>
                                <th>Status</th>
                                <th>Query</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-secondary">Entries</p>
                    <ul class="pagination m-0 ms-auto">

                    </ul>
                </div>
            </div>
        </div>
    </div>

    @foreach ($records as $record)
        <!-- Modal -->
        <div class="modal fade" id="chat{{ $record->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-3" id="exampleModalLabel">Queries -
                            {{ ucfirst($record->company_ref) }}</h1>
                        <span class="fs-5 ms-auto">
                            <a href="{{ route('transporter.readchat', ['id' => $record->id]) }}">mark
                                as read</a>
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body scrollable" style="height: 300px; overflow-y: auto;">
                                <div class="chat">
                                    <div class="chat-bubbles">
                                        <form action="{{ route('transporter.sendchat', ['id' => $record->id]) }}"
                                            method="POST">
                                            @csrf

                                            @foreach ($chats as $chat)
                                                @if ($chat->user_id == Auth::user()->id && $chat->application_id == $record->id)
                                                    <div class="chat-item mb-3">
                                                        <div class="row align-items-end justify-content-end">
                                                            <div class="col col-lg-10">
                                                                <div class="chat-bubble chat-bubble-me">
                                                                    @if ($chat->del == 0)
                                                                        <div class="chat-bubble-title">
                                                                            <div class="row">
                                                                                <div class="col chat-bubble-author">
                                                                                    {{ Auth::user()->name }}
                                                                                </div>
                                                                                <div
                                                                                    class="col-auto chat-bubble-date fs-4">
                                                                                    {{ $chat->formatted_date }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="chat-bubble-body">
                                                                            <p class="mb-0 text-break"
                                                                                style="word-break:break-word;white-space:pre-line;overflow-wrap:anywhere;">
                                                                                {{ $chat->message }}
                                                                            </p>
                                                                        </div>
                                                                        <span class="fs-5">
                                                                            <a
                                                                                href="{{ route('transporter.deletechat', ['id' => $chat->id]) }}">delete</a>
                                                                        </span>
                                                                    @else
                                                                        <div class="row">
                                                                            <div class="col">
                                                                                <p>
                                                                                    <i class="fa fa-ban"></i>
                                                                                    Deleted
                                                                                    message
                                                                                </p>
                                                                                <span
                                                                                    class="fs-5">{{ $chat->formatted_date }}</span>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="col-auto">
                                                                <span class="avatar avatar-1">
                                                                    <i class="fa fa-user p-auto"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($chat->application_id == $record->id)
                                                    <div class="chat-item mb-3">
                                                        <div class="row align-items-end">
                                                            <div class="col-auto">
                                                                <span class="avatar avatar-1">
                                                                    <i class="fa fa-user-shield  p-auto"></i>
                                                                </span>
                                                            </div>
                                                            <div class="col col-lg-10">
                                                                <div class="chat-bubble">
                                                                    @if ($chat->del == 0)
                                                                        <div class="chat-bubble-title">
                                                                            <div class="row">
                                                                                <div class="col chat-bubble-author">
                                                                                    Vendor
                                                                                </div>
                                                                                <div class="col-auto chat-bubble-date">
                                                                                    {{ $chat->formatted_date }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="chat-bubble-body">
                                                                            <p class="mb-0 text-break"
                                                                                style="word-break:break-word;white-space:pre-line;overflow-wrap:anywhere;">
                                                                                {{ $chat->message }}
                                                                            </p>
                                                                        </div>
                                                                    @else
                                                                        <div class="row">
                                                                            <div class="col">
                                                                                <p>
                                                                                    <i class="fa fa-ban"></i>
                                                                                    Deleted
                                                                                    message
                                                                                </p>
                                                                                <span
                                                                                    class="fs-5">{{ $chat->formatted_date }}</span>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer px-4 pb-4">
                        <div class="input-group input-group-flat">
                            <input type="text" name="message" class="form-control" autocomplete="off"
                                placeholder="Type message">
                            <span class="input-group-text">
                                <button type="submit" class="btn border-0">
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                            </span>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($records as $record)
        <!-- delete modal -->
        <form action="{{ route('transporter.destroyApp', $record->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="modal fade" id="m{{ $record->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-status bg-danger"></div>
                        <div class="modal-body text-center py-4">
                            <i class="mb-2 p-2 display-2 text-danger fa fa-warning" width="24" height="24"></i>
                            <h3>Caution!</h3>
                            <div class="text-secondary">
                                Are you sure you want to delete application
                                {{ $record->company_ref }}
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
                                        <button type="submit" class="btn btn-danger w-100" data-bs-dismiss="modal">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // For all chat modals
            document.querySelectorAll('.modal[id^="chat"]').forEach(function(modal) {
                modal.addEventListener('shown.bs.modal', function() {
                    // Find the scrollable chat body inside this modal
                    var chatBody = modal.querySelector('.card-body.scrollable');
                    if (chatBody) {
                        chatBody.scrollTo({
                            top: chatBody.scrollHeight,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>

    <script>
        new DataTable('#linework', {
            initComplete: function() {
                // Use the existing advanced-table-search input for global search
                const api = this.api();
                const advInput = document.getElementById('advanced-table-search');
                if (advInput) {
                    advInput.addEventListener('keyup', function() {
                        api.search(this.value).draw();
                    });
                }

                // Hide DataTables' default search and info
                document.querySelectorAll('.dt-search').forEach(function(el) {
                    el.style.display = 'none';
                });
                document.querySelectorAll('.dt-info').forEach(function(el) {
                    el.style.display = 'none';
                });

                // Move the dt-length dropdown into your custom input-group
                const dtLength = document.querySelector('.dt-length');
                const customLengthDiv = document.querySelectorAll('.input-group.mb-3.mx-2')[
                    1]; // second input-group is the select
                if (dtLength && customLengthDiv) {
                    dtLength.className = '';
                    customLengthDiv.innerHTML = '';
                    const select = dtLength.querySelector('select');
                    if (select) {
                        select.className = 'form-select';
                        customLengthDiv.appendChild(select);
                    }
                    const label = dtLength.querySelector('label');
                    if (label) {
                        label.className = 'input-group-text';
                        customLengthDiv.prepend(label);
                    }
                }

                // Bootstrap-style pagination
                function updateBootstrapPagination() {
                    // Hide the default DataTables pagination nav
                    document.querySelectorAll('nav[aria-label="pagination"]').forEach(function(el) {
                        el.style.display = 'none';
                    });

                    // Find the DataTables pagination buttons
                    const dtNav = document.querySelector('nav[aria-label="pagination"]');
                    const ul = document.querySelector('.card-footer ul.pagination');
                    if (!ul) return;

                    ul.innerHTML = ''; // Clear existing

                    if (!dtNav) return;

                    // Map DataTables buttons to Bootstrap
                    dtNav.querySelectorAll('button.dt-paging-button').forEach(function(btn) {
                        let li = document.createElement('li');
                        li.classList.add('page-item');
                        if (btn.classList.contains('current')) li.classList.add('active');
                        if (btn.classList.contains('disabled')) li.classList.add('disabled');

                        let a = document.createElement('a');
                        a.classList.add('page-link');
                        a.href = "#";
                        a.tabIndex = btn.tabIndex;
                        a.setAttribute('aria-label', btn.getAttribute('aria-label') || '');
                        a.innerHTML = btn.innerHTML;

                        // Click event to trigger DataTables pagination
                        a.addEventListener('click', function(e) {
                            e.preventDefault();
                            if (btn.classList.contains('disabled') || btn.classList.contains(
                                    'current')) return;

                            const idx = btn.getAttribute('data-dt-idx');
                            if (idx === 'previous') {
                                api.page('previous').draw('page');
                            } else if (idx === 'next') {
                                api.page('next').draw('page');
                            } else if (idx === 'first') {
                                api.page('first').draw('page');
                            } else if (idx === 'last') {
                                api.page('last').draw('page');
                            } else {
                                // Numeric page
                                api.page(parseInt(idx)).draw('page');
                            }
                        });

                        li.appendChild(a);
                        ul.appendChild(li);
                    });
                }

                // Initial render
                updateBootstrapPagination();

                // Re-render on table draw (page change, etc)
                api.on('draw', function() {
                    updateBootstrapPagination();
                });

                // Add individual column search inputs to the table footer
                this.api()
                    .columns()
                    .every(function() {
                        let column = this;
                        let title = column.footer().textContent;
                        let input = document.createElement("input");
                        input.type = "text";
                        input.className = "form-control";
                        input.placeholder = "Search " + title;
                        input.style.width = "100%";
                        input.addEventListener('keyup', function() {
                            column.search(this.value).draw();
                        });
                        column.footer().innerHTML = '';
                        column.footer().appendChild(input);
                    });
            }
        });
    </script>
@endsection
