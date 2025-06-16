@extends('layouts.admin.main')
@section('content')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>

    <div class="row">
        <div class="col-12">
            <x-errorshow />
            <div class="card">
                <div class="card-header">

                    <div class="row w-full">
                        <div class="col">
                            <h3 class="card-title mb-0">List of Invoices</h3>
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
                        class="table table-selectable card-table table-vcenter text-nowrap  datatable display">
                        <thead>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>Company Ref</th>
                                <th>Invoice</th>
                                <th>Customer Trip No</th>
                                <th>Date</th>
                                <th>PO</th>
                                <th>Cert No</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    <td><span class="text-secondary">{{ $record->id }}</span></td>
                                    <td><a href="{{ route('vendor.showApp', ['id' => $record->appid]) }}" class="text-reset"
                                            tabindex="-1">
                                            {{ $record->customer_ref }}
                                        </a></td>
                                    <td>
                                        PRES-{{ $record->created_at->format('Y') }}-{{ $record->id }}
                                    </td>
                                    <td>
                                        {{ $record->customer_trip_no }}
                                    </td>
                                    <td>
                                        {{ $record->created_at->format('j F Y') }}

                                    </td>
                                    <td>
                                        @if (is_numeric($record->customer_po))
                                            <span class="badge bg-teal-lt text-teal-lt-fg">{{ $record->customer_po }}</span>
                                        @else
                                            <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="to be added">TBS</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ ucfirst($record->certificate_no) }}
                                    </td>

                                    <td>
                                        $ {{ number_format($record->grandTotal, 2) }}
                                    </td>

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <a href="#" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                                Actions
                                            </a>
                                            <div class="dropdown-menu dropstart">
                                                <a class="dropdown-item"
                                                    href="{{ route('invoices.downloadinvoice2', ['id' => $record->cert_id]) }}">
                                                    <i class="fa fa-circle-down pe-2"></i>Download
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
                        <tfoot>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>Invoice</th>
                                <th>Customer Trip No</th>
                                <th>Company</th>
                                <th>Date</th>
                                <th>PO</th>
                                <th>Cert No</th>
                                <th>Amount</th>
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
