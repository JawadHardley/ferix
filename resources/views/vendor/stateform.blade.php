@extends('layouts.admin.main')
@section('content')
    <x-errorshow />

    <div class="row p-3">
        <div class="col card p-5">
            <h1 class="">Statement Generator Form</h1>
            <p class="fs-5">Select Dates Range for invoices</p>
            <hr>
            <form id="statementForm" action="{{ route('vendor.statement_download') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-12 mb-3">
                        <div class="mb-3">
                            <label class="form-label">Choose Month Name</label>
                            {{-- <input type="text" name="date" class="form-control" required> --}}
                            <select name="date" class="form-select">
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end" class="form-control" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Download</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('statementForm').addEventListener('submit', function(e) {
            setTimeout(function() {
                window.location.reload();
            }, 3500); // Adjust delay as needed
        });
    </script>
@endsection
