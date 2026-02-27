@extends('layouts.userlayout')
@section('content')
    @php
        $tile1 = asset('images/designer.svg');
        $tile2 = asset('images/team.svg');

        $completed = 0;
        $pending = 0;
        $draft = 0;
        $waiting = 0;
        $today = 0;
        $total = 0;

        if (!empty($feris)) {
            foreach ($feris as $feri) {
                if ($feri->status == 5) {
                    $completed++;
                } elseif ($feri->status == 4) {
                    $waiting++;
                } elseif ($feri->status == 3) {
                    $draft++;
                } elseif ($feri->status == 1 || $feri->status == 2) {
                    $pending++;
                }

                if ($feri->created_at->isToday()) {
                    $today++;
                }
                $total++;
            }
        }

        // Prevent division by zero
        $rate = $total > 0 ? ($completed / $total) * 100 : 0;
        $rate = number_format($rate, 0);

        if ($rate <= 50) {
            $bg = 'warning';
        } else {
            $bg = 'success';
    } @endphp
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <div class="row fade-slide-in">
        <div class="col-12 col-md-4 col-lg-3 mb-3">
            <a href="{{ route('transporter.completedapps') }}" class="text-decoration-none">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-success text-white avatar">
                                    <i class="fa fa-envelope-circle-check"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $completed }}</div>
                                <div class="text-secondary">Completed Applications</div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4 col-lg-3 mb-3">
            <a href="{{ route('transporter.showApps') }}" class="text-decoration-none">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-danger text-white avatar">
                                    <i class="fa fa-database"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $pending }}</div>
                                <div class="text-secondary">Pending Applications</div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4 col-lg-3 mb-3">
            <a href="{{ route('transporter.showApps') }}" class="text-decoration-none">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $draft }}</div>
                                <div class="text-secondary">Pending Approval</div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4 col-lg-3 mb-3">
            <a href="{{ route('transporter.showApps') }}" class="text-decoration-none">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $waiting }}</div>
                                <div class="text-secondary">Waiting Certificate</div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row fade-slide-in">
        <div class="col-12 col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-12 col-sm d-flex flex-column">
                            <h3 class="h2">Welcome back, {{ Auth::user()->name }}</h3>
                            <p class="text-muted">To speed up the application process, use templates</p>
                            <div class="row g-5 mt-auto">
                                <div class="col-auto">
                                    <div class="subheader">Today's Applications</div>
                                    <div class="d-flex align-items-baseline">
                                        <div class="h3 me-2">{{ $today }}</div>
                                    </div>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: {{ $today }}%"
                                            role="progressbar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-auto d-flex justify-content-center">
                            <!-- here come  height="200" fill="none" viewBox="0 0 800 600" -->
                            <img src="{{ $tile1 }}" alt="stats illustrations" class="img-fluid"
                                style="width: 200px; height: 200px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 d-flex mb-3">
            <div class="card flex-fill">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Applications</div>
                    </div>
                    <div class="h1 mb-3 data-countup"><span data-countup>{{ $rate }}</span>%</div>
                    <div class="d-flex mb-2">
                        <div>Completion rate</div>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-{{ $bg }}" style="width: {{ $rate }}%"
                            role="progressbar">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row fade-slide-in">
        <div class="col-sm-12 col-md-12 col-lg-4 mb-3">
            <a href="{{ route(Auth::user()->role . '.applyferi') }}" class="card card-link" data-bs-toggle="modal"
                data-bs-target="#ask">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center p-5">
                            <i class="fa fa-train-subway display-2"></i>
                        </h3>
                        <p class="card-title text-center">Apply for Feri</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-4 mb-3">
            <a href="{{ route(Auth::user()->role . '.sampcalculator') }}" class="card card-link">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center p-5">
                            <i class="fa fa-calculator display-2"></i>
                        </h3>
                        <p class="card-title text-center">Cost Calculator</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-4 mb-3">
            <a href="{{ route(Auth::user()->role . '.showApps') }}" class="card card-link">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center p-5">
                            <i class="fa fa-list display-2"></i>
                        </h3>
                        <p class="card-title text-center">View All Applications</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row fade-slide-in">
        <div class="col">
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
                                <input id="advanced-table-search" type="text" class="form-control"
                                    placeholder="Search">
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
                                <th>Quick Actions</th>
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
                                    <td class="#">
                                        <a class="btn btn-neutral"
                                            href="{{ route('transporter.edittemplate', $record->id) }}">
                                            <i
                                                class="fa fa-eye pe-2"></i>View{{ Auth::user()->id == $record->user_id ? '/Edit' : '' }}
                                        </a>
                                        <a href="{{ route('transporter.applyferi', ['template' => $record->id]) }}"
                                            class="btn btn-outline-success bg-opacity-10">
                                            <i class="fa fa-network-wired pe-2"></i>Use Template
                                        </a>
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
                                <th>Quick Actions</th>
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



    <!-- Modal -->
    <div class="modal fade" id="ask" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4" id="exampleModalLabel">Feri Application Type</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                            <a href="{{ route('transporter.applyferi') }}" class="card card-link">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title text-center p-3">
                                            <i class="fa fa-mountain-city display-4"></i>
                                        </h3>
                                        <p class="card-title text-center">Regional</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                            <a href="{{ route('transporter.continueferi') }}" class="card card-link">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title text-center p-3">
                                            <i class="fa fa-truck-fast display-4"></i>
                                        </h3>
                                        <p class="card-title text-center">Continuance</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                            <a href="{{ route('transporter.listtemplate') }}" class="card card-link">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title text-center p-3">
                                            <i class="fa fa-network-wired display-4"></i>
                                        </h3>
                                        <p class="card-title text-center">Templates</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>

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
