<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="bi bi-fuel-pump"></i> Control Volumétrico
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <!-- Notificaciones -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-danger rounded-pill" id="notificationCount">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" id="notificationsList">
                        <li><span class="dropdown-item-text">Cargando...</span></li>
                    </ul>
                </li>
                
                <!-- Usuario -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ session('user_name', 'Usuario') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('auth.user') }}">
                                <i class="bi bi-person"></i> Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('password.change.form') }}">
                                <i class="bi bi-key"></i> Cambiar Contraseña
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
// Cargar notificaciones
function loadNotifications() {
    $.get('/api/notificaciones', function(response) {
        if (response.success) {
            let count = response.data.length;
            $('#notificationCount').text(count);
            
            let html = '';
            if (count > 0) {
                response.data.forEach(function(notif) {
                    html += `<li>
                        <a class="dropdown-item" href="${notif.url}">
                            <small class="text-muted">${notif.created_at}</small><br>
                            ${notif.mensaje}
                        </a>
                    </li>`;
                });
            } else {
                html = '<li><span class="dropdown-item-text">No hay notificaciones</span></li>';
            }
            $('#notificationsList').html(html);
        }
    }).fail(function() {
        $('#notificationsList').html('<li><span class="dropdown-item-text text-danger">Error al cargar</span></li>');
    });
}

$(document).ready(function() {
    loadNotifications();
    setInterval(loadNotifications, 60000); // Recargar cada minuto
});
</script>