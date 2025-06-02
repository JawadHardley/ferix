@extends('layouts.admin.main')
@section('content')



<div class="page-wrapper">
    <!-- BEGIN PAGE HEADER -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Account Settings</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE HEADER -->

    <!-- <div class="alert alert-danger alert-dismissible mt-2" role="alert">
        <div class="alert-icon">
            <i class="fa fa-warning"></i>
        </div>
        This is a danger alert
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div> -->


    <x-errorshow />

    <!-- BEGIN PAGE BODY -->
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="row g-0">
                    <div class="col-12 col-md-3 border-end">
                        <div class="card-body">
                            <h4 class="subheader">Business settings</h4>
                            <div class="list-group list-group-transparent nav nav-tabs card-header-tabs"
                                data-bs-toggle="tabs" role="tablist">


                                <div class="nav-item" role="presentation">
                                    <a href="#tabs-home-8"
                                        class="list-group-item list-group-item-action d-flex align-items-center nav-link active"
                                        data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">My
                                        Account</a>
                                </div>
                                <div class="nav-item" role="presentation">
                                    <a href="#tabs-home-9"
                                        class="list-group-item list-group-item-action d-flex align-items-cente nav-link"
                                        data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                        Password
                                    </a>
                                </div>

                            </div>
                            <!-- <h4 class="subheader mt-4">#Leave</h4>
                            <div class="list-group list-group-transparent">
                                <a href="#" class="list-group-item list-group-item-action">Give Feedback</a>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-12 col-md-9 d-flex flex-column tab-content">
                        <div class="card-body tab-pane fade active show" id="tabs-home-8" role="tabpanel">
                            <form action="{{ route(Auth::user()->role . '.updateProfile') }}" method="POST">
                                @csrf

                                <h2 class="mb-4">My Account</h2>
                                <h3 class="card-title">Edit Profile Details</h3>
                                <div class="row align-items-center mb-4">
                                    <div class="col-auto">
                                        <span class="avatar avatar-xl text-primary">
                                            <i class="fa fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md">
                                        <div class="form-label">Full Name</div>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ Auth::user()->name }}">
                                    </div>
                                    <div class="col-md">
                                        <div class="form-label">Role</div>
                                        <input type="text" name="email" class="form-control"
                                            value="{{ Auth::user()->role }}" disabled>
                                    </div>
                                    <div class="col-md">
                                        <div class="form-label">Company</div>
                                        <input type="text" name="company" class="form-control"
                                            value="{{ $company->name }}" disabled>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md">
                                        <h3 class="card-title mt-4">Email</h3>
                                        <p class="card-subtitle">This contact will be shown to others publicly, so
                                            choose it
                                            carefully.</p>

                                        <div class="row g-3">
                                            <div class="col">
                                                <input type="text" name="email" class="form-control"
                                                    value="{{ Auth::user()->email }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="card-title mt-4">Change Theme</h3>
                                <p class="card-subtitle">Making your dashboard dark or light according to your liking.
                                </p>
                                <div>
                                    <label class="form-check form-switch form-switch-lg">
                                        <input class="form-check-input" type="checkbox">
                                        <span class="form-check-label form-check-label-on">Dark Theme</span>
                                        <span class="form-check-label form-check-label-off">Light Theme</span>
                                    </label>
                                </div>


                                <!-- modal for changing profile -->
                                <div class="modal fade" id="x1" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                            <div class="modal-status bg-primary"></div>
                                            <div class="modal-body text-center py-4">
                                                <i class="mb-2 p-2 display-2 text-primary fa fa-fingerprint" width="24"
                                                    height="24"></i>
                                                <h3 class="mb-4">
                                                    Enter password to confirm changes
                                                </h3>


                                                <div class="mb-3">
                                                    <!-- <label class="form-label">Password</label> -->
                                                    <div class="input-group input-group-flat">
                                                        <input type="password" id="password" class="form-control"
                                                            name="password" placeholder="Password" autocomplete="off">
                                                        <x-password-toggle />
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <div class="w-100">
                                                    <div class="row">
                                                        <div class="col">
                                                            <a href="#" class="btn w-100" data-bs-dismiss="modal">
                                                                Cancel
                                                            </a>
                                                        </div>
                                                        <div class="col">
                                                            <button type="submit" class="btn btn-primary w-100"
                                                                data-bs-dismiss="modal">
                                                                Save
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                            <div class="card-footer bg-transparent mt-auto">
                                <div class="btn-list justify-content-end">
                                    <a href="#" class="btn btn-1"> Cancel </a>
                                    <a href="#" class="btn btn-primary btn-2" data-bs-toggle="modal"
                                        data-bs-target="#x1">
                                        Submit </a>
                                </div>
                            </div>

                        </div>

                        <div class="card-body tab-pane fade" id="tabs-home-9" role="tabpanel">
                            <form action="{{ route(Auth::user()->role . '.changePassword') }}" method="POST">
                                @csrf
                                <h2 class="mb-4">Change Password</h2>
                                <h3 class="card-title">~</h3>

                                <input type="hidden" id="active-tab-id"
                                    value="{{ session('active_tab', 'tabs-home-9') }}">

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-label">Current Password</div>
                                        <input type="password" name="current_password" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-label">New Password</div>
                                        <input type="password" name="new_password" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-label">Confirm Password</div>
                                        <input type="password" name="new_password_confirmation" class="form-control">
                                    </div>
                                </div>

                                <div class="card-footer bg-transparent mt-auto">
                                    <div class="btn-list justify-content-end">
                                        <a href="#" class="btn btn-1"> Cancel </a>
                                        <a href="#" class="btn btn-primary btn-2" data-bs-toggle="modal"
                                            data-bs-target="#x2">
                                            Change </a>
                                    </div>
                                </div>

                                <!-- modal for changing password -->
                                <div class="modal fade" id="x2" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                            <div class="modal-status bg-primary"></div>
                                            <div class="modal-body text-center py-4">
                                                <i class="mb-2 p-2 display-2 text-primary fa fa-shield-halved"
                                                    width="24" height="24"></i>
                                                <h3 class="mb-4">
                                                    Caution !
                                                </h3>
                                                <p class="mb">
                                                    Are you sure you want to change your password !?
                                                </p>

                                            </div>
                                            <div class="modal-footer">
                                                <div class="w-100">
                                                    <div class="row">
                                                        <div class="col">
                                                            <a href="#" class="btn w-100" data-bs-dismiss="modal">
                                                                Cancel
                                                            </a>
                                                        </div>
                                                        <div class="col">
                                                            <button type="submit" class="btn btn-primary w-100"
                                                                data-bs-dismiss="modal">
                                                                Change
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE BODY -->
</div>




@endsection


<!-- <div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#tabs-home-8" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab"
                        tabindex="-1">Home</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tabs-profile-8" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab"
                        tabindex="-1">Profile</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tabs-activity-8" class="nav-link active" data-bs-toggle="tab" aria-selected="true"
                        role="tab">Activity</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade" id="tabs-home-8" role="tabpanel">
                    <h4>Home tab</h4>
                    <div>
                        Cursus turpis vestibulum, dui in pharetra vulputate id sed non turpis ultricies fringilla at sed
                        facilisis lacus pellentesque purus
                        nibh
                    </div>
                </div>
                <div class="tab-pane fade" id="tabs-profile-8" role="tabpanel">
                    <h4>Profile tab</h4>
                    <div>
                        Fringilla egestas nunc quis tellus diam rhoncus ultricies tristique enim at diam, sem nunc amet,
                        pellentesque id egestas velit sed
                    </div>
                </div>
                <div class="tab-pane fade active show" id="tabs-activity-8" role="tabpanel">
                    <h4>Activity tab</h4>
                    <div>
                        Donec ac vitae diam amet vel leo egestas consequat rhoncus in luctus amet, facilisi sit mauris
                        accumsan nibh habitant senectus
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->