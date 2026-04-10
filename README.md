# MentorLink API

Plateforme de mentorat académique entre étudiants — backend RESTful.
Cours Outils de Programmation Web — IAI-Togo GLSI-3.

---

## Stack technique

| Composant | Version |
|-----------|---------|
| PHP | 8.5 |
| Laravel | 12 |
| Laravel Breeze | 2.x (stack API) |
| Laravel Sanctum | 4.x |
| L5-Swagger | 8.x |
| MySQL | 9.1 (port 3307) |

---

## Installation

```bash
# 1. Cloner le projet
git clone https://github.com/[votre-repo]/mentorlink.git
cd mentorlink

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

# 8. Documentation Swagger
php artisan l5-swagger:generate

# 9. Démarrer
php artisan serve
```

---

## Documentation API (Swagger)

```
http://localhost:8000/api/documentation
```

---

## Authentification

Toutes les routes protégées nécessitent un token Bearer :

```
Authorization: Bearer {token}
```

Le token est retourné à l'inscription (`/api/register`) et à la connexion (`/api/login`).

---

## Endpoints

### Auth (Breeze + Sanctum)
| Méthode | Route | Auth | Description |
|---------|-------|------|-------------|
| POST | `/api/register` | Non | Inscription (mentor ou mentee) |
| POST | `/api/login` | Non | Connexion — retourne un token |
| POST | `/api/logout` | Oui | Révocation du token |
| GET | `/api/me` | Oui | Profil connecté |
| POST | `/api/profile` | Oui | Mettre à jour profil + avatar |

### Mentors
| Méthode | Route | Auth | Description |
|---------|-------|------|-------------|
| GET | `/api/mentors` | Non | Liste des mentors validés (filtre `?domain=`) |
| GET | `/api/mentors/{id}` | Non | Détail d'un mentor |
| POST | `/api/mentor/profile` | Oui (mentor) | Créer/mettre à jour son profil |

### Disponibilités
| Méthode | Route | Auth | Description |
|---------|-------|------|-------------|
| GET | `/api/mentors/{id}/availabilities` | Oui | Créneaux libres d'un mentor |
| POST | `/api/availabilities` | Oui (mentor) | Ajouter un créneau |
| PUT | `/api/availabilities/{id}` | Oui (mentor) | Modifier un créneau |
| DELETE | `/api/availabilities/{id}` | Oui (mentor) | Supprimer un créneau |

### Sessions
| Méthode | Route | Auth | Description |
|---------|-------|------|-------------|
| GET | `/api/sessions` | Oui | Mes sessions |
| POST | `/api/sessions` | Oui (mentee) | Réserver une session |
| PUT | `/api/sessions/{id}/confirm` | Oui (mentor) | Confirmer |
| PUT | `/api/sessions/{id}/refuse` | Oui (mentor) | Refuser |
| PUT | `/api/sessions/{id}/cancel` | Oui | Annuler |
| PUT | `/api/sessions/{id}/complete` | Oui (mentor) | Marquer comme terminée |

### Évaluations
| Méthode | Route | Auth | Description |
|---------|-------|------|-------------|
| POST | `/api/sessions/{id}/reviews` | Oui (mentee) | Déposer une évaluation |
| GET | `/api/mentors/{id}/reviews` | Non | Évaluations d'un mentor |

### Signalements
| Méthode | Route | Auth | Description |
|---------|-------|------|-------------|
| POST | `/api/reports` | Oui | Signaler un utilisateur |

### Admin
| Méthode | Route | Auth | Description |
|---------|-------|------|-------------|
| GET | `/api/admin/stats` | Admin | Statistiques globales |
| GET | `/api/admin/pending-mentors` | Admin | Profils en attente |
| PUT | `/api/admin/mentors/{id}/validate` | Admin | Valider un profil |
| GET | `/api/admin/reports` | Admin | Liste des signalements |
| PUT | `/api/admin/reports/{id}` | Admin | Traiter un signalement |

---

## Rôles

| Rôle | Permissions |
|------|-------------|
| `mentee` | Parcourir mentors, réserver sessions, évaluer, signaler |
| `mentor` | Gérer profil, disponibilités, confirmer/refuser/terminer sessions |
| `admin` | Valider profils, gérer signalements, voir statistiques |

> Pour créer un admin : `UPDATE users SET role='admin' WHERE email='...'`

---

## Tests Postman

Importer `MentorLink/MentorLink_API.postman_collection.json` dans Postman puis utiliser le **Collection Runner** pour exécuter les 31 requêtes dans l'ordre automatiquement.

---

## Structure

```
MentorLink/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/        # MentorController, SessionController, ReviewController...
│   │   │   └── Auth/       # Breeze — RegisteredUserController, AuthenticatedSessionController
│   │   └── Requests/       # Form Requests (validation)
│   ├── Models/             # User, MentorProfile, MentorSession, Review, Availability, Report
│   ├── Policies/           # SessionPolicy, ReviewPolicy, MentorProfilePolicy
│   └── Services/           # SessionService, AvailabilityService
├── database/
│   └── migrations/         # 9 tables
└── routes/
    ├── api.php             # Routes API
    └── auth.php            # Routes Breeze
```
