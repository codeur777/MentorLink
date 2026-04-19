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
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin: 0.25rem 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
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
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-graduation-cap fa-2x text-white me-2"></i>
                    <h4 class="text-white mb-0">MentorLink</h4>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    
                    @if(auth()->user()->role === 'admin')
                        <hr class="text-white-50">
                        <h6 class="text-white-50 text-uppercase small">Administration</h6>
                        <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-bar me-2"></i> Admin Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.pending-mentors') ? 'active' : '' }}" href="{{ route('admin.pending-mentors') }}">
                            <i class="fas fa-user-clock me-2"></i> Mentors en attente
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                            <i class="fas fa-users me-2"></i> Utilisateurs
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.stats') ? 'active' : '' }}" href="{{ route('admin.stats') }}">
                            <i class="fas fa-chart-pie me-2"></i> Statistiques
                        </a>
                    @endif
                    
                    @if(auth()->user()->role === 'mentor')
                        <hr class="text-white-50">
                        <h6 class="text-white-50 text-uppercase small">Mentor</h6>
                        <a class="nav-link {{ request()->routeIs('mentor.profile') ? 'active' : '' }}" href="{{ route('mentor.profile') }}">
                            <i class="fas fa-user-edit me-2"></i> Mon Profil
                        </a>
                        <a class="nav-link {{ request()->routeIs('availabilities.*') ? 'active' : '' }}" href="{{ route('availabilities.create') }}">
                            <i class="fas fa-calendar-plus me-2"></i> Disponibilités
                        </a>
                    @endif
                    
                    <hr class="text-white-50">
                    <a class="nav-link {{ request()->routeIs('mentors.*') ? 'active' : '' }}" href="{{ route('mentors.index') }}">
                        <i class="fas fa-chalkboard-teacher me-2"></i> Mentors
                    </a>
                </nav>
                
                <!-- User Info -->
                <div class="mt-auto pt-4">
                    <div class="text-white-50 small">
                        <div><i class="fas fa-user me-1"></i> {{ auth()->user()->name }}</div>
                        <div><i class="fas fa-tag me-1"></i> {{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-4">
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
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>