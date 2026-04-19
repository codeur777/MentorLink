<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Désabonnement - MentorLink</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body text-center p-5">
                        <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
                        <h2 class="fw-bold mb-3">Désabonnement réussi</h2>
                        
                        @if(session('success'))
                            <p class="text-muted mb-4">{{ session('success') }}</p>
                        @else
                            <p class="text-muted mb-4">Vous avez été désabonné de notre newsletter.</p>
                        @endif
                        
                        <p class="small text-muted mb-4">
                            Nous sommes désolés de vous voir partir. Vous pouvez vous réabonner à tout moment depuis notre site web.
                        </p>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-home me-2"></i>Retour à l'accueil
                            </a>
                            <a href="{{ route('mentors.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-users me-2"></i>Découvrir nos mentors
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>