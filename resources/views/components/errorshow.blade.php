@if ($errors->any())
<div class="alert alert-danger alert-dismissible" role="alert">
    <div class="alert-icon">
        <i class="fa fa-exclamation-circle"></i>
    </div>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
</div>
@elseif (session('message'))

<!-- <div class="col-12"> -->
<div class="alert alert-{{ session('status') === 'success' ? 'success' : 'danger' }} alert-dismissible" role="alert">
    <div class="alert-icon">
        <i class="fa fa-{{ session('status') === 'success' ? 'check' : 'warning' }}"></i>
    </div>
    {{ session('message') }}
    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
</div>
<!-- </div> -->

@endif