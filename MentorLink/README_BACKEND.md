## 1. Présentation du projet:

**MentorLink** est une plateforme de mentorat académique permettant à des étudiants de se connecter avec des mentors(des étudiants séniors) dans différents domaines techniques.

### Rôles utilisateurs

**Mentor**: Professionnel ou étudiant avancé qui propose des sessions de mentorat. Il définit ses disponibilités, confirme ou refuse les demandes de sessions.

**Mentoré**: (mentee) Étudiant qui recherche un mentor, réserve des sessions et laisse des avis après les sessions terminées.

**Administrateur** (admin): Gère la plateforme : valide les profils mentors, consulte les statistiques, traite les signalements et peut suspendre des utilisateurs.

### Fonctionnalités principales

- Inscription et connexion (Laravel Breeze)
- Gestion de profil mentor (domaines, tarif horaire)
- Validation des profils mentors par l'administrateur
- Définition des disponibilités hebdomadaires (récurrentes)
- Réservation de sessions avec vérification des disponibilités et des conflits
- Gestion du cycle de vie des sessions (en attente → confirmée → terminée / annulée)
- Système d'avis (note 1–5 + commentaire, une seule fois par session terminée)
- Système de signalement entre participants d'une session terminée
- Suspension de comptes par l'administrateur
- Tableau de bord adapté au rôle de l'utilisateur connecté

---

## 2. Stack technique

| Composant | Technologie |
|-----------|-------------|
| Framework | Laravel 12 |
| Vues | Blade (templates PHP natifs Laravel) |
| Authentification | Laravel Breeze (sessions web, pas de token API) |
| Base de données | MySQL (configurable SQLite pour les tests) |
| ORM | Eloquent |
| Autorisation | Laravel Policies + Gates |
| Validation | Form Requests Laravel |
| Gestion des dépendances | Composer (PHP) |

> ⚠️ **Ce projet n'est PAS une API REST.** Toutes les réponses sont des vues Blade ou des redirections. Les contrôleurs dans `app/Http/Controllers/Api/` sont des résidus de développement et ne sont pas utilisés par les routes web actives.

---

## 3. Installation et lancement

### Prérequis

- PHP >= 8.2
- Composer
- MySQL (ou SQLite)
- Node.js / npm (optionnel, si assets front à compiler)

### Étapes

```bash
# 1. Cloner le dépôt
git clone https://github.com/codeur777/MentorLink.git
cd MentorLink

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances JS (optionnel)
npm install

# 4. Copier et configurer le fichier d'environnement
cp .env.example .env
```

### Configuration du fichier `.env`

Ouvrir `.env` et renseigner les variables suivantes :

```dotenv
APP_NAME=MentorLink
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mentorlink
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

MAIL_MAILER=log          # Utiliser "log" en développement (pas d'envoi réel)
```

```bash
# 5. Générer la clé d'application
php artisan key:generate

# 6. Exécuter les migrations
php artisan migrate

# 7. (Optionnel) Peupler la base avec des données de test
php artisan db:seed

# 8. Lancer le serveur de développement
php artisan serve
```

### Accès

| URL | Description |
|-----|-------------|
| `http://localhost:8000` | Page d'accueil |
| `http://localhost:8000/register` | Inscription |
| `http://localhost:8000/login` | Connexion |
| `http://localhost:8000/dashboard` | Tableau de bord (après connexion) |


## 4. Structure du projet

### Modèles (`app/Models/`)

| Fichier | Table | Description |
|---------|-------|-------------|
| `User.php` | `users` | Utilisateur avec rôle (`mentor`, `mentee`, `admin`) et flag `suspended` |
| `MentorProfile.php` | `mentor_profiles` | Profil mentor : domaines (JSON), tarif horaire, statut de validation |
| `Availability.php` | `availabilities` | Disponibilités hebdomadaires récurrentes d'un mentor (jour + plage horaire) |
| `Session.php` | `mentor_sessions` | Session de mentorat entre un mentor et un mentoré |
| `Review.php` | `reviews` | Avis laissé par un mentoré sur une session terminée |
| `Report.php` | `reports` | Signalement d'un participant contre l'autre après une session terminée |

> **Note :** La table des sessions s'appelle `mentor_sessions` (et non `sessions`) pour éviter le conflit avec la table native `sessions` de Laravel utilisée pour le stockage des sessions web.

### Schéma de base de données

```
users
  ├── id, name, email, password
  ├── role (enum: mentor | mentee | admin)
  ├── bio, avatar
  ├── suspended (boolean, défaut: false)
  └── email_verified_at, remember_token, timestamps

mentor_profiles
  ├── id
  ├── user_id (FK → users)
  ├── domains (JSON : tableau de chaînes)
  ├── hourly_rate (decimal)
  ├── is_validated (boolean, défaut: false)
  └── timestamps

availabilities
  ├── id
  ├── mentor_id (FK → users)
  ├── day_of_week (0=Dimanche … 6=Samedi)
  ├── start_time, end_time (TIME)
  └── timestamps

mentor_sessions
  ├── id
  ├── mentor_id (FK → users)
  ├── mentee_id (FK → users)
  ├── date (DATE)
  ├── start_time, end_time (TIME)
  ├── status (enum: pending | confirmed | completed | cancelled)
  ├── note (text, nullable)
  └── timestamps

reviews
  ├── id
  ├── session_id (FK → mentor_sessions, UNIQUE)
  ├── mentee_id (FK → users)
  ├── mentor_id (FK → users)
  ├── rating (tinyint, 1–5)
  ├── comment (text, nullable)
  └── timestamps

reports
  ├── id
  ├── reporter_id (FK → users)
  ├── reported_id (FK → users)
  ├── session_id (FK → mentor_sessions)
  ├── reason (text)
  ├── status (enum: open | resolved, défaut: open)
  └── timestamps
  UNIQUE(reporter_id, session_id)
```

### Contrôleurs (`app/Http/Controllers/`)

#### Web (`Web/`)

| Fichier | Responsabilité |
|---------|---------------|
| `DashboardController.php` | Tableau de bord adapté au rôle (stats différentes selon mentor / mentoré / admin) |
| `MentorController.php` | Liste des mentors, détail d'un profil, gestion du profil mentor connecté |
| `AvailabilityController.php` | Affichage, création et suppression des disponibilités d'un mentor |
| `SessionController.php` | Réservation, confirmation, annulation, complétion des sessions |
| `ReviewController.php` | Formulaire et enregistrement d'un avis sur une session terminée |
| `ReportController.php` | Formulaire et enregistrement d'un signalement |
| `AdminController.php` | Validation des profils, statistiques, gestion des signalements, suspension |

#### Auth (`Auth/`)

| Fichier | Responsabilité |
|---------|---------------|
| `AuthenticatedSessionController.php` | Connexion / déconnexion (vérifie aussi la suspension au login) |
| `RegisteredUserController.php` | Inscription (rôle mentor ou mentoré) |
| `PasswordResetLinkController.php` | Envoi du lien de réinitialisation de mot de passe |
| `NewPasswordController.php` | Traitement du nouveau mot de passe |
| `VerifyEmailController.php` | Vérification de l'adresse e-mail (désactivée par défaut) |
| `EmailVerificationNotificationController.php` | Renvoi de l'e-mail de vérification |

### Routes (`routes/`)

| Fichier | Rôle |
|---------|------|
| `web.php` | **Toutes les routes de l'application** (Blade, sessions, CSRF) |
| `auth.php` | Routes d'authentification (login, register, password reset) — inclus dans `web.php` |
| `api.php` | Résidus non utilisés — ignorés en production |

### Services (`app/Services/`)

| Fichier | Responsabilité |
|---------|---------------|
| `AvailabilityService.php` | Calcul des créneaux disponibles par semaine avec marquage des créneaux déjà réservés ; validation serveur d'un créneau demandé |

### Policies (`app/Policies/`)

| Fichier | Modèle protégé | Règles principales |
|---------|---------------|-------------------|
| `MentorProfilePolicy.php` | `MentorProfile` | `validate` (admin), `update` (propriétaire ou admin) |
| `AvailabilityPolicy.php` | `Availability` | `delete` / `update` (mentor propriétaire ou admin) |
| `SessionPolicy.php` | `Session` | `confirm` (mentor), `cancel` (mentor ou mentoré), `complete` (mentor) |
| `ReviewPolicy.php` | `Review` | `create` (mentoré de la session, session terminée, pas encore d'avis) |
| `ReportPolicy.php` | `Report` | `create` (participant de la session, session terminée, pas de doublon) |

### Middleware (`app/Http/Middleware/`)

| Fichier | Alias | Rôle |
|---------|-------|------|
| `AdminMiddleware.php` | `admin` | Bloque l'accès aux routes admin si l'utilisateur n'est pas administrateur (abort 403) |
| `CheckSuspended.php` | *(dans le groupe `web`)* | Déconnecte automatiquement tout utilisateur suspendu à chaque requête |
| `Authenticate.php` | `auth` | Redirige vers `/login` si non authentifié |

### Form Requests (`app/Http/Requests/`)

| Fichier | Utilisé par | Validation principale |
|---------|------------|----------------------|
| `StoreSessionRequest.php` | `SessionController::store` | Mentoré uniquement, date future, plage horaire valide |
| `StoreReviewRequest.php` | `ReviewController::store` | Mentoré uniquement, note 1–5 |
| `StoreMentorProfileRequest.php` | `MentorController::updateProfile` | Mentor uniquement, domaines requis |
| `StoreAvailabilityRequest.php` | `AvailabilityController::store` | Mentor uniquement, format horaire valide |
| `UpdateAvailabilityRequest.php` | `AvailabilityController::update` | Mentor uniquement, champs optionnels |
| `UpdateProfileRequest.php` | Profil utilisateur | Nom, bio, avatar (image) |
| `RegisterRequest.php` | `AuthController` (API, non utilisé en web) | — |

---

## 5. Logique métier principale

### Réservation de session

1. Le mentoré accède au profil d'un mentor validé et clique sur **Réserver une session**.
2. Le formulaire affiche les créneaux hebdomadaires du mentor avec leur statut (disponible / indisponible).
3. Le mentoré sélectionne un créneau ou saisit manuellement une date et une plage horaire.
4. À la soumission, le serveur vérifie via `AvailabilityService::isSlotValid()` :
   - Le jour de la semaine correspond à une disponibilité du mentor.
   - La plage horaire demandée est entièrement couverte par cette disponibilité.
   - Aucune session `pending` ou `confirmed` ne chevauche ce créneau.
5. Si valide, la session est créée avec le statut `pending`.

### Cycle de vie d'une session

```
pending  →  confirmed  →  completed
   ↓             ↓
cancelled     cancelled
```

| Transition | Qui peut l'effectuer |
|-----------|---------------------|
| `pending` → `confirmed` | Mentor |
| `confirmed` → `completed` | Mentor |
| `pending` → `cancelled` | Mentor ou Mentoré |
| `confirmed` → `cancelled` | Mentor ou Mentoré |

### Gestion des disponibilités

- Les disponibilités sont **hebdomadaires et récurrentes** (ex. : tous les lundis de 09h à 12h).
- Seul le mentor propriétaire peut ajouter ou supprimer ses créneaux.
- L'affichage des créneaux dans le formulaire de réservation exclut les créneaux déjà occupés par une session `pending` ou `confirmed`, **sans révéler l'identité du réservant**.

### Système d'avis (Reviews)

- Un avis ne peut être laissé que par le **mentoré** de la session.
- La session doit avoir le statut **`completed`**.
- **Un seul avis par session** (contrainte unique en base).
- L'avis comprend une note (1 à 5) et un commentaire optionnel.
- La note moyenne d'un mentor est calculée dynamiquement via un accesseur Eloquent (`getAverageRatingAttribute`) sur `MentorProfile`.

### Système de signalement (Reports)

- Un mentor peut signaler un mentoré, et vice-versa.
- Le signalement n'est possible que si les deux utilisateurs ont **participé à une session terminée ensemble**.
- **Un seul signalement par session par utilisateur** (contrainte unique en base).
- Le motif est obligatoire (minimum 10 caractères).
- L'administrateur peut marquer un signalement comme **résolu** et suspendre l'utilisateur signalé.

### Validation des profils mentors

- Lors de l'inscription ou de la mise à jour du profil, `is_validated` est automatiquement mis à `false`.
- Seul l'administrateur peut valider un profil via le panneau d'administration.
- Un mentor non validé n'apparaît pas dans la liste publique des mentors et ne peut pas recevoir de réservations.

### Suspension de compte

- L'administrateur peut suspendre n'importe quel utilisateur (sauf un autre administrateur).
- Un utilisateur suspendu est **immédiatement déconnecté** par le middleware `CheckSuspended` qui s'exécute à chaque requête web.
- La tentative de connexion d'un compte suspendu est bloquée dans `AuthenticatedSessionController::store`.

---

## 6. Permissions et rôles


### Règles importantes

- Un mentoré **ne peut pas** réserver une session en dehors des disponibilités du mentor.
- Un mentoré **ne peut pas** réserver un créneau déjà occupé (même si le réservant n'est pas affiché).
- Un avis **ne peut pas** être modifié ou supprimé une fois soumis.
- Un signalement **ne peut pas** être soumis deux fois pour la même session par le même utilisateur.
- Un administrateur **ne peut pas** être suspendu.

---

## 7. Inventaire des vues Blade

> Toutes les vues sont dans `resources/views/`. Les URLs sont relatives à la racine du site (`http://localhost:8000`).

### Vues publiques / authentification

| Fichier Blade | URL | Description |
|---------------|-----|-------------|
| `welcome.blade.php` | `/` | Page d'accueil avec liens vers connexion et inscription |
| `auth/login.blade.php` | `/login` | Formulaire de connexion (email + mot de passe) |
| `auth/register.blade.php` | `/register` | Formulaire d'inscription (nom, email, mot de passe, rôle) |
| `auth/forgot-password.blade.php` | `/forgot-password` | Formulaire de demande de réinitialisation de mot de passe |
| `auth/reset-password.blade.php` | `/reset-password/{token}` | Formulaire de saisie du nouveau mot de passe |
| `auth/verify-email.blade.php` | `/verify-email` | Page d'invitation à vérifier l'adresse e-mail (vérification désactivée par défaut) |

### Tableau de bord

| Fichier Blade | URL | Description |
|---------------|-----|-------------|
| `dashboard.blade.php` | `/dashboard` | Tableau de bord personnalisé selon le rôle. Affiche des statistiques différentes pour le mentor (statut profil, disponibilités, sessions), le mentoré (sessions) et l'admin (totaux globaux). Navigation vers les sections principales. |

### Mentors

| Fichier Blade | URL | Description |
|---------------|-----|-------------|
| `mentors/index.blade.php` | `/mentors` | Liste paginée des mentors validés avec filtre par domaine. Affiche nom, domaines, tarif, note moyenne. Bouton **Réserver** visible pour les mentorés. |
| `mentors/show.blade.php` | `/mentors/{id}` | Profil détaillé d'un mentor : informations générales, domaines, tarif, note moyenne, disponibilités hebdomadaires, liste complète des avis (note + commentaire + date), bouton de réservation pour les mentorés. |
| `mentors/profile.blade.php` | `/mentor/profile` | Formulaire de création / mise à jour du profil mentor (domaines par cases à cocher, tarif horaire). Affiche le statut de validation. Liste les disponibilités existantes avec bouton de suppression. Lien vers l'ajout de disponibilité. |

### Disponibilités

| Fichier Blade | URL | Description |
|---------------|-----|-------------|
| `availabilities/create.blade.php` | `/availabilities/create` | Formulaire d'ajout d'une disponibilité hebdomadaire (jour de la semaine, heure de début, heure de fin). Réservé aux mentors. |

### Sessions

| Fichier Blade | URL | Description |
|---------------|-----|-------------|
| `sessions/index.blade.php` | `/sessions` | Liste paginée des sessions de l'utilisateur connecté. Pour le mentor : affiche le mentoré, les boutons **Confirmer**, **Marquer terminée**, **Annuler**. Pour le mentoré : affiche le mentor, le bouton **Annuler**, le lien **Laisser un avis** (session terminée sans avis), le lien **Signaler** (session terminée). Affiche l'avis existant si présent. |
| `sessions/create.blade.php` | `/sessions/book?mentor_id={id}` | Formulaire de réservation d'une session. Affiche les créneaux de la semaine sélectionnée avec leur statut (disponible en vert / indisponible en rouge). Navigation semaine précédente / suivante. Bouton **Sélectionner** pour pré-remplir le formulaire. Validation côté serveur des disponibilités et des conflits. |

### Avis

| Fichier Blade | URL | Description |
|---------------|-----|-------------|
| `reviews/create.blade.php` | `/sessions/{session}/review` | Formulaire de soumission d'un avis : sélection de la note (1 à 5 étoiles par boutons radio) et commentaire optionnel. Accessible uniquement au mentoré de la session, une seule fois, après que la session est terminée. |

### Signalements

| Fichier Blade | URL | Description |
|---------------|-----|-------------|
| `reports/create.blade.php` | `/sessions/{session}/report` | Formulaire de signalement : affiche la session concernée et l'utilisateur signalé. Champ texte pour le motif (obligatoire, min. 10 caractères). Accessible aux deux participants d'une session terminée, une seule fois par session. |

### Administration

| Fichier Blade | URL | Description |
|---------------|-----|-------------|
| `admin/dashboard.blade.php` | `/admin/dashboard` | Tableau de bord administrateur : statistiques globales (utilisateurs, mentors, sessions, signalements ouverts), liens rapides vers les sections d'administration. |
| `admin/stats.blade.php` | `/admin/stats` | Statistiques détaillées : totaux par rôle, sessions par statut, signalements ouverts / résolus, comptes suspendus. |
| `admin/pending-mentors.blade.php` | `/admin/pending-mentors` | Liste paginée des profils mentors en attente de validation. Pour chaque profil : nom, email, domaines, tarif, date de création. Bouton **Valider** et lien vers le profil détaillé. |
| `admin/reports.blade.php` | `/admin/reports` | Liste paginée de tous les signalements. Pour chaque signalement : signalant, signalé (avec indicateur de suspension), session concernée, motif, statut. Actions : **Marquer résolu**, **Suspendre** / **Lever la suspension** de l'utilisateur signalé. |

---

## 8. Notes importantes pour le frontend

### Architecture actuelle

- Les vues Blade actuelles sont **intentionnellement minimalistes** : HTML brut sans framework CSS, sans JavaScript (sauf un petit script inline dans le formulaire de réservation pour pré-remplir les champs).
- L'objectif était de valider la logique backend, pas de produire une interface finale.
- **Le frontend peut entièrement remplacer les vues Blade** par une SPA (React, Vue, etc.) en consommant les mêmes routes via des requêtes classiques ou en migrant vers une API.

### Points d'attention pour une migration frontend

| Sujet | Détail |
|-------|--------|
| **CSRF** | Toutes les requêtes `POST`, `PUT`, `PATCH`, `DELETE` nécessitent le token CSRF (`@csrf` en Blade, header `X-CSRF-TOKEN` en JS). |
| **Sessions** | L'authentification est basée sur les sessions Laravel (cookies), pas sur des tokens Bearer. |
| **Redirections** | Les contrôleurs retournent des `RedirectResponse` après les actions. En cas de migration vers une API, ces retours devront être convertis en JSON. |
| **Validation** | Les erreurs de validation sont flashées en session et disponibles via `$errors` en Blade. En JS, elles seront retournées en HTTP 422 avec un corps JSON si le header `Accept: application/json` est envoyé. |
| **Pagination** | Laravel retourne des objets `LengthAwarePaginator`. En Blade, `{{ $collection->links() }}` génère les liens. En API, la pagination est déjà sérialisable en JSON. |
| **Statuts de session** | Les valeurs possibles sont : `pending`, `confirmed`, `completed`, `cancelled`. |
| **Rôles utilisateur** | Les valeurs possibles sont : `mentor`, `mentee`, `admin`. |
| **Disponibilités** | `day_of_week` suit la convention PHP `date('w')` : `0` = Dimanche, `1` = Lundi, …, `6` = Samedi. |
| **Note moyenne** | Calculée dynamiquement via un accesseur Eloquent sur `MentorProfile` (`average_rating`, `review_count`). Non stockée en base. |
| **Suspension** | Le champ `suspended` (boolean) sur `User` est vérifié à chaque requête web par le middleware `CheckSuspended`. |

### Variables disponibles dans les vues principales

| Vue | Variables passées |
|-----|------------------|
| `dashboard` | `$user`, `$stats` (tableau associatif selon le rôle) |
| `mentors/index` | `$mentors` (paginator de `User` avec `mentorProfile`) |
| `mentors/show` | `$mentor` (User avec `mentorProfile`, `availabilities`), `$reviews` (collection de `Review` avec `mentee`) |
| `sessions/index` | `$sessions` (paginator), `$user` |
| `sessions/create` | `$mentor`, `$slots` (tableau de créneaux avec statut `booked`), `$weekStart`, `$prevWeek`, `$nextWeek` |
| `reviews/create` | `$session` (avec `mentor`) |
| `reports/create` | `$session`, `$reported` (User) |
| `admin/reports` | `$reports` (paginator avec `reporter`, `reported`, `session`) |
| `admin/pending-mentors` | `$profiles` (paginator de `MentorProfile` avec `user`) |
| `admin/stats` | `$stats` (tableau associatif) |

### Structure d'un créneau (`$slots` dans `sessions/create`)

```php
[
    'availability_id' => int,
    'date'            => 'YYYY-MM-DD',
    'day_of_week'     => int,   // 0=Dim … 6=Sam
    'start_time'      => 'HH:MM',
    'end_time'        => 'HH:MM',
    'booked'          => bool,  // true = créneau occupé (sans révéler qui)
]
```