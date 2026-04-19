# MentorLink

Plateforme de mentorat académique entre étudiants — Application web Laravel avec Blade.
Cours Outils de Programmation Web — IAI-Togo GLSI-3.

---

## Stack technique

| Composant | Version |
|-----------|---------|
| PHP | 8.5 |
| Laravel | 12 |
| Laravel Breeze | 2.x (stack Blade) |
| Bootstrap | 5.x |
| MySQL | 9.1 (port 3307) |

---

## Installation

```bash
# 1. Cloner le projet
git clone https://github.com/[votre-repo]/mentorlink.git
cd mentorlink/MentorLink

# 2. Dépendances PHP
composer install

# 3. Dépendances JS
npm install && npm run build

# 4. Environnement
cp .env.example .env
php artisan key:generate

# 5. Base de données (.env)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

# 6. Migrations
php artisan migrate

# 7. Lien storage (avatars)
php artisan storage:link

# 8. Démarrer
php artisan serve
```

Accéder à l'application : `http://localhost:8000`

---

## Authentification

L'application utilise Laravel Breeze avec authentification par session.

- **Inscription** : `/register`
- **Connexion** : `/login`
- **Dashboard** : `/dashboard` (après connexion)

---

## Fonctionnalités

### Pages Web
| Route | Auth | Description |
|-------|------|-------------|
| `/` | Non | Page d'accueil |
| `/register` | Non | Inscription (mentor ou mentee) |
| `/login` | Non | Connexion |
| `/dashboard` | Oui | Dashboard principal selon le rôle |
| `/mentors` | Oui | Liste des mentors validés |
| `/mentors/{id}` | Oui | Profil détaillé d'un mentor |
| `/mentor/profile` | Oui (mentor) | Gérer son profil mentor |
| `/availabilities/create` | Oui (mentor) | Ajouter des disponibilités |

### Administration
| Route | Auth | Description |
|-------|------|-------------|
| `/admin/dashboard` | Admin | Dashboard administrateur |
| `/admin/pending-mentors` | Admin | Profils mentors en attente |
| `/admin/stats` | Admin | Statistiques globales |

---

## Rôles

| Rôle | Permissions |
|------|-------------|
| `mentee` | Parcourir mentors, réserver sessions, évaluer, signaler |
| `mentor` | Gérer profil, disponibilités, confirmer/refuser/terminer sessions |
| `admin` | Valider profils, gérer signalements, voir statistiques |

> Pour créer un admin : `UPDATE users SET role='admin' WHERE email='...'`

---

## Tests

Tester l'application directement dans le navigateur :

1. Créer un compte mentee/mentor via `/register`
2. Se connecter via `/login`
3. Naviguer dans le dashboard selon le rôle
4. Tester les fonctionnalités (profil mentor, disponibilités, etc.)

---

## Structure

```
MentorLink/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Web/        # DashboardController, MentorController, AdminController...
│   │   │   └── Auth/       # Breeze — RegisteredUserController, AuthenticatedSessionController
│   │   └── Requests/       # Form Requests (validation)
│   ├── Models/             # User, MentorProfile, Availability
│   ├── Policies/           # MentorProfilePolicy
│   └── Services/           # AvailabilityService
├── database/
│   └── migrations/         # Tables users, mentor_profiles, availabilities
├── resources/
│   └── views/              # Vues Blade (dashboard, mentors, admin)
└── routes/
    ├── web.php             # Routes web principales
    └── auth.php            # Routes Breeze
```
