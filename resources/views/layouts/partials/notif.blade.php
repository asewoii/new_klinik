{{-- NOTIFIKASI --}}
@if (session('success'))
    <div class="notification notification-success" id="notification-success">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="notification notification-error" id="notification-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        {{ session('error') }}
    </div>
@endif

@if (session('warning'))
    <div class="notification notification-warning" id="notification-warning">
        <i class="fa-solid fa-triangle-exclamation"></i>
        {{ session('warning') }}
    </div>
@endif

@if (session('info'))
    <div class="notification notification-info" id="notification-info">
        <i class="fa-solid fa-circle-info"></i>
        {{ session('info') }}
    </div>
@endif
