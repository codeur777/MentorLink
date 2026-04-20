<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'MentorLink')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        html, body {
            overflow-x: hidden;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        
        .container-fluid {
            padding: 0;
            margin: 0;
            max-width: 100vw;
            overflow-x: hidden;
        }
        
        .row {
            margin: 0;
            width: 100%;
        }
        
        .sidebar {
            min-height: 100vh;
            max-height: 100vh;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            z-index: 1000;
            overflow: hidden;
        }
        
        .sidebar-wrapper {
            width: calc(100% + 17px); /* Ajouter 17px pour masquer la scrollbar */
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 17px; /* Compenser l'ajout de largeur */
            margin-right: -17px; /* Masquer la scrollbar */
        }
        
        .sidebar-inner {
            padding: 1rem;
            width: 250px;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin: 0.25rem 0;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left-color: #3498db;
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(52, 152, 219, 0.2);
            border-left-color: #3498db;
        }
        .sidebar .section-title {
            color: #bdc3c7;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 1rem 0 0.5rem 0;
            padding-left: 1rem;
        }
        .sidebar-content {
            display: flex;
            flex-direction: column;
            height: 100vh;
            padding-bottom: 1rem;
        }
        
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding-right: 0.5rem;
        }
        
        .user-info {
            background: rgba(255,255,255,0.1);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-top: auto;
            flex-shrink: 0;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 0.75rem;
        }
        .logout-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }
        .logout-btn:hover {
            background: linear-gradient(135deg, #c0392b, #a93226);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
            margin-left: 250px;
            padding: 2rem;
            width: calc(100vw - 250px);
            overflow-x: hidden;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
        
        /* Styles pour la modal de déconnexion */
        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px 15px 0 0;
        }
        
        .modal-footer .btn {
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        
        .alert {
            border: none;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card-success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        .stat-card-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="sidebar-wrapper">
                    <div class="sidebar-inner">
                        <div class="sidebar-content">
                            <div>
                                <div class="d-flex align-items-center mb-4">
                                    <i class="fas fa-graduation-cap fa-2x text-white me-2"></i>
                                    <h4 class="text-white mb-0">MentorLink</h4>
                                </div>
                            </div>
                            
                            <div class="sidebar-nav">
                                <nav class="nav flex-column">
                                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                    </a>
                                    
                                    @if(auth()->user()->role === 'admin')
                                        <div class="section-title">Administration</div>
                                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-chart-bar me-2"></i> Admin Dashboard
                                        </a>
                                        <a class="nav-link {{ request()->routeIs('admin.pending-mentors') ? 'active' : '' }}" href="{{ route('admin.pending-mentors') }}">
                                            <i class="fas fa-user-clock me-2"></i> Mentors en attente
                                        </a>
                                        <a class="nav-link {{ request()->routeIs('admin.newsletters') ? 'active' : '' }}" href="{{ route('admin.newsletters') }}">
                                            <i class="fas fa-envelope me-2"></i> Newsletter
                                        </a>
                                        <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                            <i class="fas fa-users me-2"></i> Utilisateurs
                                        </a>
                                        <a class="nav-link {{ request()->routeIs('admin.stats') ? 'active' : '' }}" href="{{ route('admin.stats') }}">
                                            <i class="fas fa-chart-pie me-2"></i> Statistiques
                                        </a>
                                    @endif
                                    
                                    @if(auth()->user()->role === 'mentor')
                                        <div class="section-title">Espace Mentor</div>
                                        <a class="nav-link {{ request()->routeIs('mentor.profile') ? 'active' : '' }}" href="{{ route('mentor.profile') }}">
                                            <i class="fas fa-user-edit me-2"></i> Mon Profil
                                        </a>
                                        <a class="nav-link {{ request()->routeIs('availabilities.*') ? 'active' : '' }}" href="{{ route('availabilities.create') }}">
                                            <i class="fas fa-calendar-plus me-2"></i> Disponibilités
                                        </a>
                                    @endif
                                    
                                    <div class="section-title">Navigation</div>
                                    <a class="nav-link {{ request()->routeIs('mentors.*') ? 'active' : '' }}" href="{{ route('mentors.index') }}">
                                        <i class="fas fa-chalkboard-teacher me-2"></i> Mentors
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('sessions.*') ? 'active' : '' }}" href="{{ route('sessions.index') }}">
                                        <i class="fas fa-calendar-alt me-2"></i> Mes Sessions
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                                        <i class="fas fa-bell me-2"></i> Notifications
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <span class="badge bg-danger ms-2">{{ auth()->user()->unreadNotifications->count() }}</span>
                                        @endif
                                    </a>
                                </nav>
                            </div>
                            
                            <!-- User Info -->
                            <div class="user-info">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold">{{ auth()->user()->name }}</div>
                                        <div class="text-white-50 small">{{ ucfirst(auth()->user()->role) }}</div>
                                    </div>
                                </div>
                                <button type="button" class="logout-btn" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="main-content">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Modal de confirmation de déconnexion -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="logoutModalLabel">
                        <i class="fas fa-sign-out-alt me-2 text-warning"></i>Confirmation de déconnexion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-question-circle fa-3x text-warning"></i>
                    </div>
                    <h6 class="mb-3">Êtes-vous sûr de vouloir vous déconnecter ?</h6>
                    <p class="text-muted mb-0">Vous devrez vous reconnecter pour accéder à votre compte.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>Se déconnecter
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Formulaire de déconnexion caché -->
    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
        @csrf
    </form>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Notifications Web -->
    <script>
        // Demander la permission pour les notifications
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
        
        // Fonction pour afficher une notification web
        function showWebNotification(title, message, options = {}) {
            if ('Notification' in window && Notification.permission === 'granted') {
                const notification = new Notification(title, {
                    body: message,
                    icon: '/favicon.ico',
                    badge: '/favicon.ico',
                    ...options
                });
                
                // Auto-fermer après 10 secondes
                setTimeout(() => notification.close(), 10000);
                
                return notification;
            }
        }
        
        // Simuler les notifications de session (à remplacer par Laravel Echo en production)
        function checkSessionReminders() {
            fetch('/api/session-reminders')
                .then(response => response.json())
                .then(data => {
                    data.forEach(reminder => {
                        showWebNotification(
                            'Session de mentorat',
                            reminder.message,
                            {
                                tag: 'session-' + reminder.session_id,
                                requireInteraction: true
                            }
                        );
                    });
                })
                .catch(error => {
                    // Erreur silencieuse pour les notifications
                });
        }
        
        // Vérifier les notifications toutes les minutes
        if (typeof window.sessionReminderInterval === 'undefined') {
            window.sessionReminderInterval = setInterval(checkSessionReminders, 60000);
        }
    </script>
    
    @yield('scripts')
</body>
</html>