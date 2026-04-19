<?php

// Test de connexion admin via cURL
$baseUrl = 'http://localhost:8000';

// 1. Récupérer le token CSRF depuis la page de login
echo "1. Récupération du token CSRF...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
$loginPage = curl_exec($ch);

// Extraire le token CSRF
preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPage, $matches);
$csrfToken = $matches[1] ?? null;

if (!$csrfToken) {
    echo "❌ Impossible de récupérer le token CSRF\n";
    exit(1);
}

echo "✅ Token CSRF récupéré: " . substr($csrfToken, 0, 10) . "...\n";

// 2. Connexion avec les identifiants admin
echo "2. Connexion admin...\n";
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_token' => $csrfToken,
    'email' => 'admin@mentorlink.com',
    'password' => 'admin123',
]));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_HEADER, true);
$loginResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "Code HTTP: $httpCode\n";

if ($httpCode == 302) {
    // Récupérer l'URL de redirection
    preg_match('/Location: (.+)/', $loginResponse, $matches);
    $redirectUrl = trim($matches[1] ?? '');
    echo "✅ Redirection vers: $redirectUrl\n";
    
    if (strpos($redirectUrl, '/admin/dashboard') !== false) {
        echo "🎉 SUCCÈS: L'admin est bien redirigé vers son dashboard !\n";
    } elseif (strpos($redirectUrl, '/dashboard') !== false) {
        echo "⚠️  L'admin est redirigé vers le dashboard général, pas l'admin dashboard\n";
    } else {
        echo "❌ Redirection inattendue: $redirectUrl\n";
    }
} else {
    echo "❌ Échec de la connexion (Code: $httpCode)\n";
    echo "Réponse: " . substr($loginResponse, -500) . "\n";
}

curl_close($ch);

// Nettoyer
if (file_exists('cookies.txt')) {
    unlink('cookies.txt');
}