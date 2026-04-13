# MentorLink - Backend API

## 🎯 Partie Backend Principale

Cette branche contient **uniquement** la partie backend/API développée selon les tâches assignées :

### ✅ Fonctionnalités Implémentées

#### 🔐 **Auth** - Authentification complète
- Register, login, logout, /me
- Sanctum tokens
- Middleware CheckRole (mentor/mentee/admin)

#### 👨‍🏫 **Mentors** - Profils mentors
- CRUD profil complet
- Domaines JSON, tarif, validation admin
- Disponibilités avec créneaux récurrents
- AvailabilityService (filtrage)

#### 🛡️ **Admin** - Dashboard admin
- Stats globales
- Profils en attente, validation
- Gestion des rapports

#### 🗄️ **BDD** - Base de données
- Migrations : users, mentor_profiles, availabilities, sessions, reviews, reports
- Schéma + factories + seeders mentors

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

## 📋 API Endpoints

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

## 📁 Structure Backend

```
app/
├── Http/Controllers/Api/
│   ├── AuthController.php
│   ├── MentorController.php
│   ├── AdminController.php
│   ├── AvailabilityController.php
│   └── ...
├── Models/
│   ├── User.php
│   ├── MentorProfile.php
│   ├── Availability.php
│   └── ...
├── Services/
│   ├── AvailabilityService.php
│   └── SessionService.php
└── Http/Requests/
    ├── StoreMentorProfileRequest.php
    └── ...
```

## 🔧 Tests

Collection Postman disponible : `MentorLink_API.postman_collection.json`

---

**Note** : Cette branche ne contient que le backend. Le frontend sera développé par un autre développeur sur une branche séparée.