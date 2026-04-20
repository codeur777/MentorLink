# MentorLink - Dev Backend 1

## 🎯 Ma Partie (Dev Backend 1)

Cette branche contient **uniquement** ma partie selon la répartition des tâches :

### ✅ Fonctionnalités Implémentées

#### 🔐 **Auth** - Authentification complète
- Register, login, logout, /me — Sanctum tokens
- Middleware CheckRole : Contrôle d'accès par rôle (mentor/mentee/admin)

#### 👨‍🏫 **Mentors** - Profils mentors
- CRUD profil, domaines JSON, tarif, validation admin
- Disponibilités : Créneaux récurrents + AvailabilityService (filtrage)

#### 🛡️ **Admin** - Dashboard admin
- Stats globales, profils en attente, validation

#### 🗄️ **BDD** - Migrations
- users, mentor_profiles, availabilities
- Schéma + factories + seeders mentors

---

## ❌ Non Inclus (Dev Backend 2)

- **Sessions** : Réservation, cycle de vie, policies
- **Reviews** : Évaluations post-session
- **API Docs** : Documentation Swagger/OpenAPI

---

## 🚀 Installation

```bash
# Dépendances
composer install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate
php artisan db:seed

# Serveur
php artisan serve
```

## 📋 API Endpoints (Dev Backend 1 uniquement)

### Auth
- `POST /api/register` - Inscription
- `POST /api/login` - Connexion
- `POST /api/logout` - Déconnexion
- `GET /api/me` - Profil utilisateur

### Mentors
- `GET /api/mentors` - Liste des mentors
- `POST /api/mentors/profile` - Créer profil mentor
- `PUT /api/mentors/profile` - Modifier profil
- `GET /api/mentors/{id}/availabilities` - Disponibilités

### Admin
- `GET /api/admin/stats` - Statistiques
- `GET /api/admin/pending-profiles` - Profils en attente
- `PUT /api/admin/profiles/{id}/validate` - Valider profil

## 📁 Structure Backend (Dev Backend 1)

```
app/
├── Http/Controllers/Api/
│   ├── AuthController.php
│   ├── MentorController.php
│   ├── AdminController.php
│   └── AvailabilityController.php
├── Models/
│   ├── User.php
│   ├── MentorProfile.php
│   └── Availability.php
├── Services/
│   └── AvailabilityService.php
└── Http/Requests/
    ├── RegisterRequest.php
    ├── StoreMentorProfileRequest.php
    ├── StoreAvailabilityRequest.php
    └── UpdateAvailabilityRequest.php
```

## 🔧 Tests

Collection Postman disponible : `MentorLink_API.postman_collection.json`

---

**Note** : Cette branche contient uniquement la partie **Dev Backend 1**. Les sessions, reviews et documentation API seront développées par le **Dev Backend 2** sur une branche séparée.