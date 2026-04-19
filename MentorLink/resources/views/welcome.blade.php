<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MentorLink - Plateforme de mentorat académique</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-graduation-cap text-primary me-2"></i>MentorLink
            </a>
            
            <div class="navbar-nav ms-auto">
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-sign-in-alt me-1"></i>Connexion
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i>Inscription
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Plateforme de mentorat académique</h1>
            <p class="lead mb-5">Connectez-vous avec des mentors expérimentés pour accélérer votre apprentissage</p>
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            @auth
                                <a href="{{ route('mentors.index') }}" class="btn btn-light btn-lg w-100">
                                    <i class="fas fa-user-graduate text-primary me-2"></i>
                                    Je cherche un mentor
                                </a>
                            @else
                                <a href="{{ route('login') }}?redirect=mentors&role=mentee" class="btn btn-light btn-lg w-100">
                                    <i class="fas fa-user-graduate text-primary me-2"></i>
                                    Je cherche un mentor
                                </a>
                            @endauth
                        </div>
                        <div class="col-md-6 mb-3">
                            @auth
                                @if(auth()->user()->role === 'mentor')
                                    <a href="{{ route('mentor.profile') }}" class="btn btn-outline-light btn-lg w-100">
                                        <i class="fas fa-chalkboard-teacher me-2"></i>
                                        Mon profil mentor
                                    </a>
                                @else
                                    <a href="{{ route('register') }}?role=mentor" class="btn btn-outline-light btn-lg w-100">
                                        <i class="fas fa-chalkboard-teacher me-2"></i>
                                        Je veux devenir mentor
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}?redirect=mentor-profile&role=mentor" class="btn btn-outline-light btn-lg w-100">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>
                                    Je veux devenir mentor
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Pourquoi choisir MentorLink ?</h2>
                <p class="text-muted">Une plateforme complète pour le mentorat académique</p>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-users fa-3x text-primary mb-3"></i>
                            <h5 class="fw-bold">Mentors qualifiés</h5>
                            <p class="text-muted">Tous nos mentors sont validés et expérimentés dans leur domaine</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-calendar-alt fa-3x text-success mb-3"></i>
                            <h5 class="fw-bold">Planification flexible</h5>
                            <p class="text-muted">Réservez des sessions selon vos disponibilités et celles de votre mentor</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-shield-alt fa-3x text-info mb-3"></i>
                            <h5 class="fw-bold">Plateforme sécurisée</h5>
                            <p class="text-muted">Vos données sont protégées et vos sessions sont encadrées</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Comment ça marche ?</h2>
                <p class="text-muted">En quelques étapes simples</p>
            </div>
            
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">1</span>
                    </div>
                    <h6 class="fw-bold">Inscription</h6>
                    <p class="text-muted small">Créez votre compte mentee ou mentor</p>
                </div>
                
                <div class="col-md-3 text-center mb-4">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">2</span>
                    </div>
                    <h6 class="fw-bold">Recherche</h6>
                    <p class="text-muted small">Trouvez le mentor parfait pour vos besoins</p>
                </div>
                
                <div class="col-md-3 text-center mb-4">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">3</span>
                    </div>
                    <h6 class="fw-bold">Réservation</h6>
                    <p class="text-muted small">Planifiez votre session de mentorat</p>
                </div>
                
                <div class="col-md-3 text-center mb-4">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">4</span>
                    </div>
                    <h6 class="fw-bold">Apprentissage</h6>
                    <p class="text-muted small">Profitez de votre session personnalisée</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">Prêt à commencer ?</h2>
            <p class="lead mb-4">Rejoignez notre communauté de mentors et mentees dès aujourd'hui</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                <i class="fas fa-rocket me-2"></i>Commencer maintenant
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-dark text-white">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-graduation-cap me-2"></i>MentorLink</h6>
                    <p class="small text-muted">Plateforme de mentorat académique</p>
                </div>
                <div class="col-md-6">
                    <p class="small text-muted mb-0">
                        Cours Outils de Programmation Web — IAI-Togo GLSI-3
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>