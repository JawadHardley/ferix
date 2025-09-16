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

                        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                            <a href="{{ route('transporter.applyferi') }}" class="card card-link">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title text-center p-5">
                                            <i class="fa fa-mountain-city display-4"></i>
                                        </h3>
                                        <p class="card-title text-center">Regional</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                            <a href="{{ route('transporter.continueferi') }}" class="card card-link">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title text-center p-5">
                                            <i class="fa fa-truck-fast display-4"></i>
                                        </h3>
                                        <p class="card-title text-center">Continuance</p>
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

                // Always hide divs with class dt-layout-row
                document.querySelectorAll('.dt-search').forEach(function(el) {
                    el.style.display = 'none';
                });

                // Always hide divs with class dt-layout-row
                document.querySelectorAll('.dt-info').forEach(function(el) {
                    el.style.display = 'none';
                });

                // Move the dt-length dropdown into your card header if you want
                const dtLength = document.querySelector('.dt-length');
                const cardHeader = document.querySelector('.card-header .btn-list');
                if (dtLength && cardHeader) {
                    cardHeader.appendChild(dtLength);
                }

                // Move the DataTables pagination into the card-footer
                const dtPagination = document.querySelector('nav[aria-label="pagination"]');
                const cardFooter = document.querySelector('.card-footer.d-flex.align-items-center');
                if (dtPagination && cardFooter) {
                    cardFooter.appendChild(dtPagination);
                }
            }
        });
    </script>
@endsection
