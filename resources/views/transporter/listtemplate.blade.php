@extends('layouts.userlayout')
@section('content')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>

    <div class="row">
        <div class="col-12">
            <x-errorshow />
            <div class="card fade-slide-in mb-5 pb-5">
                <div class="card-header">

                    <div class="row w-full">
                        <div class="col">
                            <h3 class="card-title mb-0">List of all Company Templates</h3>
                            <p class="text-secondary m-0">with extensive search</p>
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
                                <th>Name</th>
                                <th>Date Created</th>
                                <th>Feri Type</th>
                                <th>Owner</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($records as $record)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>
                                        <a href="{{ route('transporter.edittemplate', $record->id) }}" class="text-reset"
                                            tabindex="-1">
                                            {{ $record->name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $record->created_at->format('d M Y') }}
                                    </td>
                                    <td>
                                        {{ ucfirst($record->type) }}
                                    </td>
                                    <td>
                                        {{ $record->user ? $record->user->name : 'N/A' }}
                                    </td>
                                    <td class="text-start">
                                        <span class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport"
                                                data-bs-toggle="dropdown" data-bs-offset="0,0" data-bs-display="static">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-start">
                                                <a class="dropdown-item"
                                                    href="{{ route('transporter.edittemplate', $record->id) }}">
                                                    <i class="fa fa-eye pe-2"></i>View/Edit
                                                </a>
                                                <a href="{{ route('transporter.applyferi', ['template' => $record->id]) }}"
                                                    class="dropdown-item">
                                                    <i class="fa fa-network-wired text-primary pe-2"></i>Use Template
                                                </a>
                                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#templateDeleteModal{{ $record->id }}">
                                                    <i class="fa fa-trash pe-2"></i>Delete
                                                </a>

                                            </div>
                                        </span>
                                    </td>
                                </tr>


                                {{-- Template delete modal
                                    Template delete modal
                                    Template delete modal
                                    Template delete modal --}}
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>Date Created</th>
                                <th>Name</th>
                                <th>Feri Type</th>
                                <th>Owner</th>
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

    {{-- for the template delete --}}
    {{-- for the template delete --}}
    {{-- for the template delete --}}
    {{-- for the template delete --}}
    {{-- for the template delete --}}
    @foreach ($records as $record)
        <form action="{{ route('transporter.destroyTemplate', $record->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="modal" id="templateDeleteModal{{ $record->id }}" tabindex="-1">
                <div class="modal-dialog modal-s" role="document">
                    <div class="modal-content">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-status bg-danger"></div>
                        <div class="modal-body text-center py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 9v2m0 4v.01" />
                                <path
                                    d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                            </svg>
                            <h3>Are you sure?</h3>
                            <div class="text-secondary">
                                Do you really want to delete this template?
                                <br />
                                <br />
                                <div>
                                    <mark>{{ $record->name }}</mark>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="w-100">
                                <div class="row">
                                    <div class="col">
                                        <a href="#" class="btn w-100" data-bs-dismiss="modal">
                                            Cancel </a>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-danger w-100" data-bs-dismiss="modal">
                                            <i class="fa fa-trash me-2"></i> Delete Template</button>
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
