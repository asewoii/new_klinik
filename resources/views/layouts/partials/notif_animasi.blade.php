<!-- Notifikasi animasi -->
@if(session('success') || session('error'))
    <div class="notification {{ session('success') ? 'notification-success' : 'notification-error' }}" id="notif-global">
        <i class="bi {{ session('success') ? 'bi-clipboard-check-fill' : 'bi-exclamation-octagon-fill' }}"></i>
        <span>{{ session('success') ?? session('error') }}</span>
        <button type="button" class="notif-close" onclick="closeNotif()">×</button>
    </div>


    
@endif
