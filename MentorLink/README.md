# MentorLink API

Plateforme de mentorat académique — backend RESTful Laravel.
Développé dans le cadre du cours Outils de Programmation Web — IAI-Togo GLSI-3.

---

## Stack technique

- PHP 8.5 / Laravel 10
- MySQL 9.1
- Laravel Sanctum (authentification par token)
- Laravel Breeze (scaffold auth)
- L5-Swagger (documentation OpenAPI)

---

## Installation

### Prérequis

- PHP >= 8.1 avec extensions : `pdo_mysql`, `zip`, `mbstring`, `openssl`
- Composer
- MySQL
- Node.js + npm

### Étapes

```bash
# 1. Cloner le projet
git clone https://github.com/[votre-repo]/mentorlink.git
cd mentorlink

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances JS
npm install && npm run build

# 4. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 5. Configurer la base de données dans .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

# 6. Migrer la base de données
php artisan migrate

# 7. Générer la documentation Swagger
php artisan l5-swagger:generate

# 8. Démarrer le serveur
php artisan serve
```

---

## Documentation API

Une fois le serveur démarré, la documentation Swagger est accessible sur :

```
http://localhost:8000/api/documentation
```

---

## Endpoints principaux

### Authentification
| Méthode | Route | Description |
|---------|-------|-------------|
| POST | `/api/register` | Inscription (mentor ou mentoré) |
| POST | `/api/login` | Connexion — retourne un token |
| POST | `/api/logout` | Déconnexion |
| GET | `/api/me` | Profil de l'utilisateur connecté |

### Mentors
| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/api/mentors` | Liste des mentors validés |
| GET | `/api/mentors/{id}` | Détail d'un mentor |
| POST | `/api/mentor/profile` | Créer/mettre à jour son profil |

### Sessions
| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/api/sessions` | Mes sessions |
| POST | `/api/sessions` | Réserver une session |
| PUT | `/api/sessions/{id}/confirm` | Confirmer une session (mentor) |
| PUT | `/api/sessions/{id}/cancel` | Annuler une session |
| PUT | `/api/sessions/{id}/complete` | Marquer comme terminée (mentor) |

### Disponibilités
| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/api/mentors/{id}/availabilities` | Créneaux libres d'un mentor |
| POST | `/api/availabilities` | Ajouter un créneau (mentor) |
| DELETE | `/api/availabilities/{id}` | Supprimer un créneau (mentor) |

### Évaluations
| Méthode | Route | Description |
|---------|-------|-------------|
| POST | `/api/sessions/{id}/reviews` | Déposer une évaluation |
| GET | `/api/mentors/{id}/reviews` | Évaluations d'un mentor |

### Admin
| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/api/admin/stats` | Statistiques globales |
| GET | `/api/admin/pending-mentors` | Profils en attente de validation |
| PUT | `/api/admin/mentors/{id}/validate` | Valider un profil mentor |

---

## Authentification

Toutes les routes protégées nécessitent un token Bearer dans le header :

```
Authorization: Bearer {token}
```

Le token est retourné lors du login ou de l'inscription.

---

## Rôles utilisateurs

| Rôle | Permissions |
|------|-------------|
| `mentee` | Rechercher des mentors, réserver des sessions, déposer des évaluations |
| `mentor` | Gérer son profil, ses disponibilités, confirmer/terminer des sessions |
| `admin` | Valider les profils mentors, accéder aux statistiques |

---

## Structure du projet

```
app/
├── Http/
│   ├── Controllers/Api/    # AuthController, MentorController, SessionController...
│   └── Requests/           # Form Requests (validation)
├── Models/                 # User, MentorProfile, MentorSession, Review, Availability
├── Policies/               # SessionPolicy, ReviewPolicy, MentorProfilePolicy
└── Services/               # SessionService, AvailabilityService
database/
└── migrations/             # Schéma complet de la BDD
routes/
└── api.php                 # Toutes les routes API
```
