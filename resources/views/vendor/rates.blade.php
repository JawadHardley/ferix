@extends('layouts.admin.main')
@section('content')

<div class="row">
    @foreach ($records as $rate)
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{ $rate->currency }}</h3>
                <p class="text-teal fs-3">{{ $rate->amount }}</p>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#rate{{ $rate->id }}">Edit</button>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="rate{{ $rate->id }}" tabindex="-1" aria-labelledby="rate{{ $rate->id }}"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $rate->currency }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('vendor.rateupdate', ['id' => $rate->id]) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">New Amount</label>
                            <input type="number" step="0.01" class="form-control" id="exampleFormControlInput1"
                                name="amount" placeholder="eg {{ $rate->amount }}">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection