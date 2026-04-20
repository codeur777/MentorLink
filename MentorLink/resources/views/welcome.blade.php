<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MentorLink - Trouvez le mentor parfait pour votre succès</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        html, body {
            overflow-x: hidden;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #f59e0b;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f8fafc;
        }
        
        .container, .container-fluid {
            max-width: 100%;
            overflow-x: hidden;
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(rgba(37, 99, 235, 0.8), rgba(30, 64, 175, 0.8)), url('/images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(37, 99, 235, 0.1);
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            padding-left: 2rem;
        }
        
        @media (max-width: 768px) {
            .hero-content {
                padding-left: 1rem;
            }
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            opacity: 0.9;
            margin-bottom: 2.5rem;
            max-width: 600px;
        }
        
        .btn-hero {
            padding: 15px 35px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        .btn-primary-hero {
            background: white;
            color: var(--primary-color);
            border: 2px solid white;
        }
        
        .btn-outline-hero {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .section-padding {
            padding: 80px 0;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 3rem;
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            border: none;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin: 0 auto 1.5rem;
        }
        
        .program-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }
        
        .program-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stats-section {
            background: var(--bg-light);
        }
        
        .stat-item {
            text-align: center;
            padding: 2rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-top: 0.5rem;
        }
        
        .cta-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .navbar {
            padding: 1rem 0;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98) !important;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand text-primary" href="#">
                <i class="fas fa-graduation-cap me-2"></i>MentorLink
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#comment-ca-marche">Comment ça marche</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#programmes">Programmes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#apropos">À propos</a>
                    </li>
                </ul>
                
                <div class="ms-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        Inscription
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="hero-title">Trouvez le mentor parfait pour votre succès</h1>
                        <p class="hero-subtitle">
                            Imagine un coup de pouce pour tes études, ta carrière, et même ta vie de tous les jours. 
                            Nous mettons en relation des étudiants comme toi avec des mentors expérimentés.
                        </p>
                        
                        <div class="d-flex flex-wrap gap-3">
                            @auth
                                <a href="{{ route('mentors.index') }}" class="btn btn-primary-hero btn-hero">
                                    <i class="fas fa-search me-2"></i>Trouver un mentor
                                </a>
                            @else
                                <a href="{{ route('login') }}?redirect=mentors&role=mentee" class="btn btn-primary-hero btn-hero">
                                    <i class="fas fa-search me-2"></i>Trouver un mentor
                                </a>
                            @endauth
                            
                            @auth
                                @if(auth()->user()->role === 'mentor')
                                    <a href="{{ route('mentor.profile') }}" class="btn btn-outline-hero btn-hero">
                                        <i class="fas fa-user-tie me-2"></i>Mon profil mentor
                                    </a>
                                @else
                                    <a href="{{ route('register') }}?role=mentor" class="btn btn-outline-hero btn-hero">
                                        <i class="fas fa-user-tie me-2"></i>Devenir mentor
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}?redirect=mentor-profile&role=mentor" class="btn btn-outline-hero btn-hero">
                                    <i class="fas fa-user-tie me-2"></i>Devenir mentor
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-users fa-10x" style="opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pourquoi choisir MentorLink -->
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Pourquoi choisir MentorLink ?</h2>
                <p class="section-subtitle">Notre plateforme offre une expérience de mentorat unique et personnalisée</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-user-check fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Mentors vérifiés</h4>
                        <p class="text-muted">Tous nos mentors sont validés et expérimentés dans leur domaine d'expertise.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-calendar-alt fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Flexibilité totale</h4>
                        <p class="text-muted">Planifiez vos sessions selon vos disponibilités et celles de votre mentor.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-user-check fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Accompagnement personnalisé</h4>
                        <p class="text-muted">Un mentorat adapté à vos objectifs spécifiques et votre rythme d'apprentissage.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comment ça marche -->
    <section id="comment-ca-marche" class="section-padding bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Comment ça marche ?</h2>
                <p class="section-subtitle">En trois étapes simples, connectez-vous avec le mentor idéal</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="step-number">1</div>
                        <h4 class="fw-bold mb-3">Créez votre profil</h4>
                        <p class="text-muted">Inscrivez-vous et partagez vos objectifs, compétences à développer et disponibilités.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="step-number">2</div>
                        <h4 class="fw-bold mb-3">Trouvez votre mentor</h4>
                        <p class="text-muted">Parcourez les profils de nos mentors et choisissez celui qui correspond à vos besoins.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="step-number">3</div>
                        <h4 class="fw-bold mb-3">Commencez votre parcours</h4>
                        <p class="text-muted">Planifiez votre première session et lancez-vous dans votre apprentissage personnalisé.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programmes -->
    <section id="programmes" class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Nos programmes</h2>
                <p class="section-subtitle">Découvrez nos services d'accompagnement personnalisés</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="program-card">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-file-alt fa-2x text-primary me-3"></i>
                            <h5 class="fw-bold mb-0">CV & Lettre de motivation</h5>
                        </div>
                        <p class="text-muted">Apprenez à créer un CV qui met en valeur vos compétences et à rédiger des lettres persuasives.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="program-card">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-brain fa-2x text-primary me-3"></i>
                            <h5 class="fw-bold mb-0">Méthodes d'apprentissage</h5>
                        </div>
                        <p class="text-muted">Consolidez vos méthodes d'apprentissage et d'organisation pour maximiser votre potentiel.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="program-card">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-compass fa-2x text-primary me-3"></i>
                            <h5 class="fw-bold mb-0">Orientation</h5>
                        </div>
                        <p class="text-muted">Trouvez votre voie et votre cursus d'étude adapté à vos ambitions.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="program-card">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-chart-line fa-2x text-primary me-3"></i>
                            <h5 class="fw-bold mb-0">Réussite scolaire</h5>
                        </div>
                        <p class="text-muted">Techniques et stratégies pour améliorer vos résultats scolaires et exceller.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="program-card">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-briefcase fa-2x text-primary me-3"></i>
                            <h5 class="fw-bold mb-0">Insertion professionnelle</h5>
                        </div>
                        <p class="text-muted">Accompagnement pour définir votre projet professionnel et réussir votre insertion.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="program-card">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-code fa-2x text-primary me-3"></i>
                            <h5 class="fw-bold mb-0">Développement technique</h5>
                        </div>
                        <p class="text-muted">Accompagnement personnalisé sur le choix des technologies et méthodes d'apprentissage.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistiques -->
    <section class="stats-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <div class="stat-label">Étudiants accompagnés</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">150+</span>
                        <div class="stat-label">Mentors experts</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">95%</span>
                        <div class="stat-label">Taux de satisfaction</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <div class="stat-label">Support disponible</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section section-padding">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4">Prêt à accélérer votre développement ?</h2>
            <p class="lead mb-5">Rejoignez MentorLink aujourd'hui et connectez-vous avec des mentors experts</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg btn-hero">
                <i class="fas fa-rocket me-2"></i>Commencer maintenant
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row g-4">
                <!-- À propos -->
                <div class="col-lg-4 col-md-6" id="apropos">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-graduation-cap me-2"></i>MENTORLINK
                    </h5>
                    <p class="text-light mb-3">
                        Nous connectons les jeunes talents avec des experts qui les guideront vers leurs objectifs professionnels.
                    </p>
                    <p class="text-light mb-3 small">
                        MentorLink est une plateforme innovante dédiée au mentorat académique et professionnel. 
                        Nous facilitons les rencontres entre étudiants ambitieux et mentors expérimentés pour 
                        accélérer le développement des compétences et l'insertion professionnelle.
                    </p>
                    <div class="contact-info">
                        <div class="mb-2">
                            <i class="fas fa-phone me-2 text-primary"></i>
                            <span>+228 91 31 76 87 </span>
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            <span>contact@mentorlink.tg</span>
                        </div>
                    </div>
                </div>
                
                <!-- Liens Rapides -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">Liens Rapides</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('mentors.index') }}" class="text-light text-decoration-none">
                                <i class="fas fa-chevron-right me-2 small"></i>Trouver un mentor
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#comment-ca-marche" class="text-light text-decoration-none">
                                <i class="fas fa-chevron-right me-2 small"></i>Comment ça marche
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#programmes" class="text-light text-decoration-none">
                                <i class="fas fa-chevron-right me-2 small"></i>Nos programmes
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('register') }}" class="text-light text-decoration-none">
                                <i class="fas fa-chevron-right me-2 small"></i>Devenir mentor
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div class="col-lg-6 col-md-12">
                    <h6 class="fw-bold mb-3">Newsletter</h6>
                    <p class="text-light mb-3">
                        Recevez nos dernières actualités et conseils pour votre développement professionnel.
                    </p>
                    
                    <!-- Formulaire Newsletter -->
                    <form method="POST" action="{{ route('newsletter.subscribe') }}" class="newsletter-form">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="name" class="form-control bg-light border-0" 
                                       placeholder="Votre nom (optionnel)" value="{{ old('name') }}">
                            </div>
                            <div class="col-md-5">
                                <input type="email" name="email" class="form-control bg-light border-0" 
                                       placeholder="Votre email" required value="{{ old('email') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    S'abonner
                                </button>
                            </div>
                        </div>
                        
                        @if(isset($errors) && $errors->has('email'))
                            <div class="text-warning small mt-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $errors->first('email') }}
                            </div>
                        @endif
                        
                        @if(session('newsletter_success'))
                            <div class="text-success small mt-2">
                                <i class="fas fa-check-circle me-1"></i>{{ session('newsletter_success') }}
                            </div>
                        @endif
                    </form>
                    
                    <!-- Restez informé -->
                    <div class="mt-4">
                        <h6 class="fw-bold mb-2">Restez informé</h6>
                        <p class="text-light small mb-3">
                            Nous partageons des conseils d'orientation, des opportunités et des témoignages inspirants.
                        </p>
                        
                        <!-- Réseaux sociaux -->
                        <div class="social-links">
                            <a href="#" class="text-light me-3" title="Facebook">
                                <i class="fab fa-facebook-f fa-lg"></i>
                            </a>
                            <a href="#" class="text-light me-3" title="Twitter">
                                <i class="fab fa-twitter fa-lg"></i>
                            </a>
                            <a href="#" class="text-light me-3" title="LinkedIn">
                                <i class="fab fa-linkedin-in fa-lg"></i>
                            </a>
                            <a href="#" class="text-light me-3" title="Instagram">
                                <i class="fab fa-instagram fa-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4 border-secondary">
            
            <!-- Copyright -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-light mb-0">
                        © {{ date('Y') }} MentorLink. Tous droits réservés.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-light mb-0">
                        Cours Outils de Programmation Web — IAI-Togo GLSI-3
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <style>
        .newsletter-form .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
            border-color: var(--primary-color);
        }
        
        .social-links a {
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }
        
        .contact-info i {
            width: 20px;
        }
        
        footer ul li a:hover {
            color: var(--primary-color) !important;
            padding-left: 5px;
            transition: all 0.3s ease;
        }
    </style>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Effet de transparence de la navbar au scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Smooth scroll pour les liens d'ancrage
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>