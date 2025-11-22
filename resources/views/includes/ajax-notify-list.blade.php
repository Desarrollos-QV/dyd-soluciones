
@foreach ($notifications as $key)
<a href="{{ $key->route_redirect }}" class="dropdown-item card-notify-list" @if($key->is_read == 0) style="background-color: #e9e8e8cc;" @endif >
    <div class="icon">
        <i data-feather="alert-circle"></i>
    </div>
    <div class="content">
        <h5>{{ $key->title }}</h5>
        <p>{{ $key->message }}</p>
        <p class="sub-text text-muted">{{ $key->created_at->diffForHumans() }}</p>
    </div>
</a>
@endforeach


{{-- 
<div class="dropdown-footer d-flex align-items-center justify-content-center">
    <a href="javascript:;">View all</a>
</div> 
--}}