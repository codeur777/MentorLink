#!/bin/bash
# =============================================================================
#  MentorLink — Script de création du backend complet
#  Laravel 12 | Sanctum | Breeze | Swagger (L5-Swagger)
#  Basé sur le cours Outils de Programmation Web — IAI-Togo GLSI-3
# =============================================================================
# Usage :
#   chmod +x mentorlink_backend_setup.sh
#   ./mentorlink_backend_setup.sh
# =============================================================================

set -e  # Arrêter le script en cas d'erreur

PROJECT="mentorlink"

echo "============================================="
echo "  MentorLink — Création du backend Laravel 12"
echo "============================================="

# -----------------------------------------------------------------------------
# ÉTAPE 0 — Création du projet Laravel 12
# -----------------------------------------------------------------------------
echo ""
echo ">>> [0/10] Création du projet Laravel..."
composer create-project laravel/laravel "$PROJECT" --prefer-dist
cd "$PROJECT"

# -----------------------------------------------------------------------------
# ÉTAPE 1 — Installation des dépendances
# Cours chap 7 : Breeze (auth), Sanctum (tokens API), Swagger (documentation)
# -----------------------------------------------------------------------------
echo ""
echo ">>> [1/10] Installation des dépendances..."

# Laravel Breeze — kit d'authentification (cours §7.1)
composer require laravel/breeze --dev

# Laravel Sanctum — authentification API par tokens (cours §7.4)
composer require laravel/sanctum

# L5-Swagger — documentation API OpenAPI/Swagger (exigence projet)
composer require darkaonline/l5-swagger

# Faker est inclus dans Laravel, mais on s'assure de l'avoir pour les factories
composer require --dev fakerphp/faker

echo ">>> Installation npm..."
npm install
npm run build

# -----------------------------------------------------------------------------
# ÉTAPE 2 — Installation de Breeze (stack API uniquement, pas de vues Blade)
# Cours chap 7.1 — breeze:install avec l'option api pour un backend pur
# -----------------------------------------------------------------------------
echo ""
echo ">>> [2/10] Configuration Breeze (stack API)..."
php artisan breeze:install api --no-interaction

# -----------------------------------------------------------------------------
# ÉTAPE 3 — Configuration du fichier .env
# Cours chap 9.1 — variables d'environnement
# -----------------------------------------------------------------------------
echo ""
echo ">>> [3/10] Configuration .env..."
cat > .env << 'ENVFILE'
APP_NAME=MentorLink
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mentorlink
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1

L5_SWAGGER_GENERATE_ALWAYS=true
ENVFILE

php artisan key:generate

# -----------------------------------------------------------------------------
# ÉTAPE 4 — Publication des configurations Sanctum et Swagger
# Cours §7.4 — Sanctum vendor:publish
# -----------------------------------------------------------------------------
echo ""
echo ">>> [4/10] Publication des configurations..."

php artisan vendor:publish \
    --provider="Laravel\Sanctum\SanctumServiceProvider" \
    --force

php artisan vendor:publish \
    --provider="L5Swagger\L5SwaggerServiceProvider" \
    --force

# Configuration Swagger dans config/l5-swagger.php
cat > config/l5-swagger.php << 'SWAGGERCONFIG'
<?php
return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'MentorLink API',
            ],
            'routes' => [
                'api' => 'api/documentation',
            ],
            'paths' => [
                'use_absolute_path' => true,
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),
                'annotations' => [
                    base_path('app'),
                ],
            ],
        ],
    ],
    'defaults' => [
        'routes' => [
            'docs' => 'docs',
            'oauth2_callback' => 'api/oauth2-callback',
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],
            'group_options' => [],
        ],
        'paths' => [
            'docs' => public_path('docs'),
            'views' => base_path('resources/views/vendor/l5-swagger'),
            'base' => env('L5_SWAGGER_BASE_PATH', null),
            'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'),
            'excludes' => [],
        ],
        'scanOptions' => [
            'default_processors_configuration' => [],
            'analyser' => null,
            'analysis' => null,
            'processors' => [],
            'pattern' => null,
            'exclude' => [],
            'open_api_spec_version' => env('L5_SWAGGER_OPEN_API_SPEC_VERSION', \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION),
        ],
        'securityDefinitions' => [
            'securitySchemes' => [
                'bearerAuth' => [
                    'type' => 'http',
                    'scheme' => 'bearer',
                    'bearerFormat' => 'JWT',
                ],
            ],
            'security' => [
                ['bearerAuth' => []],
            ],
        ],
        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
        'proxy' => false,
        'additional_config_url' => null,
        'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),
        'validator_url' => null,
        'ui' => [
            'display' => [
                'doc_expansion' => env('L5_SWAGGER_UI_DOC_EXPANSION', 'none'),
                'filter' => env('L5_SWAGGER_UI_FILTERS', true),
                'show_extensions' => env('L5_SWAGGER_UI_SHOW_EXTENSIONS', false),
                'show_common_extensions' => env('L5_SWAGGER_UI_SHOW_COMMON_EXTENSIONS', false),
            ],
            'authorization' => [
                'persist_authorization' => env('L5_SWAGGER_UI_PERSIST_AUTHORIZATION', false),
                'oauth2' => [
                    'use_pkce_with_authorization_code_grant' => false,
                ],
            ],
        ],
        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost:8000'),
        ],
    ],
];
SWAGGERCONFIG

# -----------------------------------------------------------------------------
# ÉTAPE 5 — Création des MIGRATIONS
# Cours chap 5.1 — versionner le schéma de la BDD
# -----------------------------------------------------------------------------
echo ""
echo ">>> [5/10] Création des migrations..."

# -- Migration : users (modifiée pour ajouter role, bio, avatar)
cat > database/migrations/2024_01_01_000000_modify_users_table.php << 'MIGRATION_USERS'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Modification de la table users pour MentorLink.
 * Ajout des champs : role, bio, avatar (cours §5.1)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['mentor', 'mentee', 'admin'])
                  ->default('mentee')
                  ->after('email');
            $table->text('bio')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'bio', 'avatar']);
        });
    }
};
MIGRATION_USERS

# -- Migration : mentor_profiles
cat > database/migrations/2024_01_01_000001_create_mentor_profiles_table.php << 'MIGRATION_MENTOR'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table mentor_profiles.
 * Colonnes clés : user_id, domains (JSON), hourly_rate, is_validated,
 * average_rating calculé via accesseur Eloquent (cours §5.2)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete(); // cours §5.1 : cascadeOnDelete
            $table->json('domains');          // domaines de compétences
            $table->decimal('hourly_rate', 8, 2)->nullable(); // null = gratuit
            $table->boolean('is_validated')->default(false);  // validé par admin
            // average_rating est calculé via un accesseur Eloquent (cours §5.2)
            // Pas de colonne physique : on l'obtient dynamiquement
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentor_profiles');
    }
};
MIGRATION_MENTOR

# -- Migration : availabilities
cat > database/migrations/2024_01_01_000002_create_availabilities_table.php << 'MIGRATION_AVAIL'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table availabilities — créneaux horaires des mentors.
 * day_of_week : 0 (dimanche) à 6 (samedi)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->tinyInteger('day_of_week'); // 0-6
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
MIGRATION_AVAIL

# -- Migration : sessions
cat > database/migrations/2024_01_01_000003_create_sessions_table.php << 'MIGRATION_SESSIONS'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table sessions — réservations entre mentor et mentoré.
 * status : en_attente | confirmée | terminée | annulée
 * Contrainte de conflit gérée au niveau applicatif (Policy + Service)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentor_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('mentee_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->unsignedSmallInteger('duration_min')->default(60);
            $table->enum('status', [
                'en_attente',
                'confirmee',
                'terminee',
                'annulee',
            ])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentor_sessions');
    }
};
MIGRATION_SESSIONS

# -- Migration : reviews
cat > database/migrations/2024_01_01_000004_create_reviews_table.php << 'MIGRATION_REVIEWS'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table reviews — évaluations post-session.
 * rating : 1 à 5
 * Policy : seul le mentoré concerné peut déposer un avis (cours §7.3)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')
                  ->constrained('mentor_sessions')
                  ->cascadeOnDelete();
            $table->foreignId('reviewer_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->tinyInteger('rating');    // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();

            // Un mentoré ne peut évaluer une session qu'une seule fois
            $table->unique(['session_id', 'reviewer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
MIGRATION_REVIEWS

# -----------------------------------------------------------------------------
# ÉTAPE 6 — Création des MODÈLES ELOQUENT
# Cours chap 5.2 — modèles, relations, accesseurs, $fillable
# -----------------------------------------------------------------------------
echo ""
echo ">>> [6/10] Création des modèles Eloquent..."

mkdir -p app/Models

# -- Modèle User (remplacement)
cat > app/Models/User.php << 'MODEL_USER'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modèle User — trois rôles : mentor, mentee, admin
 * Utilise HasApiTokens pour l'authentification Sanctum (cours §7.4)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bio',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // -----------------------------------------------------------------------
    // Relations (cours §5.4)
    // -----------------------------------------------------------------------

    /** Un mentor possède un profil mentor */
    public function mentorProfile(): HasOne
    {
        return $this->hasOne(MentorProfile::class);
    }

    /** Un mentor définit des disponibilités */
    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class, 'mentor_id');
    }

    /** Sessions en tant que mentor */
    public function mentorSessions(): HasMany
    {
        return $this->hasMany(MentorSession::class, 'mentor_id');
    }

    /** Sessions en tant que mentoré */
    public function menteeSessions(): HasMany
    {
        return $this->hasMany(MentorSession::class, 'mentee_id');
    }

    // -----------------------------------------------------------------------
    // Helpers de rôles
    // -----------------------------------------------------------------------

    public function isMentor(): bool
    {
        return $this->role === 'mentor';
    }

    public function isMentee(): bool
    {
        return $this->role === 'mentee';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
MODEL_USER

# -- Modèle MentorProfile
cat > app/Models/MentorProfile.php << 'MODEL_MENTOR_PROFILE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle MentorProfile
 *
 * Accesseur average_rating : calculé dynamiquement via les reviews
 * liées aux sessions du mentor. Cela évite de stocker une valeur
 * qui deviendrait désynchronisée (cours §5.2 — accesseurs Eloquent)
 */
class MentorProfile extends Model
{
    use HasFactory;

    protected $table = 'mentor_profiles';

    protected $fillable = [
        'user_id',
        'domains',
        'hourly_rate',
        'is_validated',
    ];

    protected $casts = [
        'domains'      => 'array',   // JSON <-> tableau PHP automatiquement
        'is_validated' => 'boolean',
        'hourly_rate'  => 'decimal:2',
    ];

    // -----------------------------------------------------------------------
    // Accesseur average_rating (cours §5.2 — attributs calculés)
    // -----------------------------------------------------------------------

    /**
     * Calcule la note moyenne du mentor à partir des reviews de ses sessions.
     * Retourne null si aucune review n'existe encore (pour éviter de le
     * désavantager visuellement — question UX du projet).
     */
    public function getAverageRatingAttribute(): ?float
    {
        $avg = Review::whereHas('session', function ($q) {
            $q->where('mentor_id', $this->user_id);
        })->avg('rating');

        return $avg ? round($avg, 2) : null;
    }

    // -----------------------------------------------------------------------
    // Relations (cours §5.4)
    // -----------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
MODEL_MENTOR_PROFILE

# -- Modèle Availability
cat > app/Models/Availability.php << 'MODEL_AVAIL'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle Availability — créneau horaire d'un mentor.
 * Les disponibilités affichées excluent les créneaux déjà réservés :
 * la logique de filtrage est dans AvailabilityService.
 */
class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }
}
MODEL_AVAIL

# -- Modèle MentorSession
cat > app/Models/MentorSession.php << 'MODEL_SESSION'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modèle MentorSession — réservation entre mentor et mentoré.
 *
 * Statuts possibles : en_attente | confirmee | terminee | annulee
 * Contrainte de conflit : vérifiée dans SessionService avant création.
 */
class MentorSession extends Model
{
    use HasFactory;

    protected $table = 'mentor_sessions';

    protected $fillable = [
        'mentor_id',
        'mentee_id',
        'scheduled_at',
        'duration_min',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'duration_min' => 'integer',
    ];

    // -----------------------------------------------------------------------
    // Relations (cours §5.4)
    // -----------------------------------------------------------------------

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'session_id');
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmee';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'terminee';
    }
}
MODEL_SESSION

# -- Modèle Review
cat > app/Models/Review.php << 'MODEL_REVIEW'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle Review — évaluation post-session.
 *
 * Policy ReviewPolicy : seul le mentoré concerné peut déposer un avis
 * sur sa propre session (cours §7.3 — Policies).
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'reviewer_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // -----------------------------------------------------------------------
    // Relations (cours §5.4)
    // -----------------------------------------------------------------------

    public function session(): BelongsTo
    {
        return $this->belongsTo(MentorSession::class, 'session_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
MODEL_REVIEW

# -----------------------------------------------------------------------------
# ÉTAPE 7 — Création des POLICIES
# Cours §7.3 — autorisation par Policy
# -----------------------------------------------------------------------------
echo ""
echo ">>> [7/10] Création des Policies..."
mkdir -p app/Policies

# -- Policy : SessionPolicy
cat > app/Policies/SessionPolicy.php << 'POLICY_SESSION'
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MentorSession;

/**
 * SessionPolicy — règles d'autorisation sur les sessions.
 * Cours §7.3 : les Policies regroupent les règles d'autorisation
 * relatives à un modèle spécifique.
 */
class SessionPolicy
{
    /** Le mentor de la session peut confirmer ou refuser */
    public function confirm(User $user, MentorSession $session): bool
    {
        return $user->id === $session->mentor_id;
    }

    /** Le mentoré ou le mentor peut annuler (si pas encore terminée) */
    public function cancel(User $user, MentorSession $session): bool
    {
        return in_array($user->id, [$session->mentor_id, $session->mentee_id])
            && $session->status !== 'terminee';
    }

    /** Le mentor peut marquer la session comme terminée */
    public function complete(User $user, MentorSession $session): bool
    {
        return $user->id === $session->mentor_id
            && $session->status === 'confirmee';
    }
}
POLICY_SESSION

# -- Policy : ReviewPolicy
cat > app/Policies/ReviewPolicy.php << 'POLICY_REVIEW'
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MentorSession;

/**
 * ReviewPolicy — règles d'autorisation pour les évaluations.
 *
 * Contrainte projet : seul le mentoré concerné peut déposer un avis
 * sur sa propre session (cours §7.3).
 */
class ReviewPolicy
{
    public function create(User $user, MentorSession $session): bool
    {
        // Seul le mentoré de cette session peut évaluer
        return $user->id === $session->mentee_id
            && $session->status === 'terminee'
            && $session->review === null; // une seule review par session
    }
}
POLICY_REVIEW

# -- Policy : MentorProfilePolicy
cat > app/Policies/MentorProfilePolicy.php << 'POLICY_MENTOR'
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MentorProfile;

/**
 * MentorProfilePolicy — validation des profils mentors.
 * Seul l'admin peut valider un profil (is_validated).
 */
class MentorProfilePolicy
{
    public function validate(User $user, MentorProfile $profile): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, MentorProfile $profile): bool
    {
        return $user->id === $profile->user_id || $user->isAdmin();
    }
}
POLICY_MENTOR

# Enregistrement des Policies dans AuthServiceProvider
cat > app/Providers/AuthServiceProvider.php << 'AUTH_PROVIDER'
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\MentorSession;
use App\Models\MentorProfile;
use App\Policies\SessionPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\MentorProfilePolicy;

/**
 * Enregistrement des Policies (cours §7.3)
 */
class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        MentorSession::class  => SessionPolicy::class,
        MentorProfile::class  => MentorProfilePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
AUTH_PROVIDER

# -----------------------------------------------------------------------------
# ÉTAPE 8 — Création des SERVICES (logique métier extraite)
# Cours §3.4 : "la logique métier complexe est à déléguer à des classes de service"
# -----------------------------------------------------------------------------
echo ""
echo ">>> [8/10] Création des Services métier..."
mkdir -p app/Services

# -- SessionService : vérification des conflits de réservation
cat > app/Services/SessionService.php << 'SERVICE_SESSION'
<?php

namespace App\Services;

use App\Models\MentorSession;
use Carbon\Carbon;

/**
 * SessionService — logique métier des sessions.
 *
 * Extrait du contrôleur pour respecter le principe SRP.
 * Cours §3.4 : "la logique métier complexe est à déléguer à des
 * classes de service dédiées".
 */
class SessionService
{
    /**
     * Vérifie si un mentor a déjà une session confirmée qui chevauche
     * le créneau demandé.
     *
     * Contrainte technique projet : "une session ne peut pas être
     * réservée si le mentor a déjà une session confirmée sur ce créneau".
     */
    public function hasConflict(
        int $mentorId,
        Carbon $scheduledAt,
        int $durationMin
    ): bool {
        $end = $scheduledAt->copy()->addMinutes($durationMin);

        return MentorSession::where('mentor_id', $mentorId)
            ->where('status', 'confirmee')
            ->where(function ($query) use ($scheduledAt, $end) {
                $query->whereBetween('scheduled_at', [$scheduledAt, $end])
                      ->orWhereRaw(
                          'DATE_ADD(scheduled_at, INTERVAL duration_min MINUTE) BETWEEN ? AND ?',
                          [$scheduledAt, $end]
                      );
            })
            ->exists();
    }
}
SERVICE_SESSION

# -- AvailabilityService : créneaux libres
cat > app/Services/AvailabilityService.php << 'SERVICE_AVAIL'
<?php

namespace App\Services;

use App\Models\Availability;
use App\Models\MentorSession;
use Carbon\Carbon;

/**
 * AvailabilityService — gestion des disponibilités.
 *
 * Contrainte projet : "afficher les disponibilités d'un mentor en
 * excluant les créneaux déjà réservés".
 */
class AvailabilityService
{
    /**
     * Retourne les disponibilités d'un mentor pour une semaine donnée,
     * en soustrayant les créneaux déjà confirmés.
     */
    public function getAvailableSlots(int $mentorId, Carbon $weekStart): array
    {
        // Toutes les disponibilités récurrentes du mentor
        $availabilities = Availability::where('mentor_id', $mentorId)->get();

        // Sessions déjà confirmées sur cette semaine
        $bookedSessions = MentorSession::where('mentor_id', $mentorId)
            ->where('status', 'confirmee')
            ->whereBetween('scheduled_at', [
                $weekStart,
                $weekStart->copy()->endOfWeek(),
            ])
            ->get();

        $slots = [];

        foreach ($availabilities as $availability) {
            // Construire la date réelle du jour pour cette semaine
            $date = $weekStart->copy()
                ->startOfWeek()
                ->addDays($availability->day_of_week);

            $slotStart = Carbon::parse(
                $date->toDateString() . ' ' . $availability->start_time
            );
            $slotEnd = Carbon::parse(
                $date->toDateString() . ' ' . $availability->end_time
            );

            // Vérifier si ce créneau est déjà pris
            $isBooked = $bookedSessions->contains(function ($session) use ($slotStart) {
                return Carbon::parse($session->scheduled_at)
                    ->isSameHour($slotStart);
            });

            if (!$isBooked) {
                $slots[] = [
                    'date'       => $date->toDateString(),
                    'start_time' => $availability->start_time,
                    'end_time'   => $availability->end_time,
                    'day_label'  => $date->locale('fr')->isoFormat('dddd D MMMM'),
                ];
            }
        }

        return $slots;
    }
}
SERVICE_AVAIL

# -----------------------------------------------------------------------------
# ÉTAPE 9 — Création des FORM REQUESTS
# Cours §6.3 — Form Requests pour la validation et l'autorisation
# -----------------------------------------------------------------------------
echo ""
echo ">>> [9/10] Création des Form Requests..."
mkdir -p app/Http/Requests

# -- Request : RegisterRequest (inscription)
cat > app/Http/Requests/RegisterRequest.php << 'REQUEST_REGISTER'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * RegisterRequest — validation de l'inscription.
 * Cours §6.3 — Form Request.
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // tout le monde peut s'inscrire
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:mentor,mentee',
        ];
    }
}
REQUEST_REGISTER

# -- Request : StoreMentorProfileRequest
cat > app/Http/Requests/StoreMentorProfileRequest.php << 'REQUEST_PROFILE'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * StoreMentorProfileRequest — validation du profil mentor.
 * Cours §6.3 — Form Request + autorisation.
 */
class StoreMentorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isMentor();
    }

    public function rules(): array
    {
        return [
            'domains'     => 'required|array|min:1',
            'domains.*'   => 'string|max:100',
            'hourly_rate' => 'nullable|numeric|min:0',
            'bio'         => 'nullable|string|max:1000',
        ];
    }
}
REQUEST_PROFILE

# -- Request : StoreSessionRequest
cat > app/Http/Requests/StoreSessionRequest.php << 'REQUEST_SESSION'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * StoreSessionRequest — validation d'une demande de session.
 * Cours §6.3 — Form Request.
 */
class StoreSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Seul un mentoré peut réserver une session
        return Auth::check() && Auth::user()->isMentee();
    }

    public function rules(): array
    {
        return [
            'mentor_id'    => 'required|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'duration_min' => 'required|integer|min:30|max:180',
        ];
    }

    public function messages(): array
    {
        return [
            'scheduled_at.after' => 'La session doit être planifiée dans le futur.',
            'mentor_id.exists'   => 'Le mentor sélectionné n\'existe pas.',
        ];
    }
}
REQUEST_SESSION

# -- Request : StoreReviewRequest
cat > app/Http/Requests/StoreReviewRequest.php << 'REQUEST_REVIEW'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * StoreReviewRequest — validation d'une évaluation.
 * Cours §6.2 : règles de validation expressives.
 */
class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }
}
REQUEST_REVIEW

# -- Request : StoreAvailabilityRequest
cat > app/Http/Requests/StoreAvailabilityRequest.php << 'REQUEST_AVAIL'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isMentor();
    }

    public function rules(): array
    {
        return [
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ];
    }
}
REQUEST_AVAIL

# -----------------------------------------------------------------------------
# ÉTAPE 10 — Création des CONTRÔLEURS API
# Cours §3 — Contrôleurs + §8 — API RESTful
# Annotations OpenAPI pour Swagger (exigence projet)
# -----------------------------------------------------------------------------
echo ""
echo ">>> [10/10] Création des contrôleurs API..."
mkdir -p app/Http/Controllers/Api

# -- Contrôleur Auth
cat > app/Http/Controllers/Api/AuthController.php << 'CTRL_AUTH'
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(
 *     title="MentorLink API",
 *     version="1.0.0",
 *     description="API RESTful de la plateforme de mentorat académique MentorLink.
 *     Authentification par token Bearer (Sanctum — cours §7.4).
 *     Utiliser POST /api/login pour obtenir votre token.",
 *     @OA\Contact(email="admin@mentorlink.tg")
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token"
 * )
 *
 * @OA\Server(url=L5_SWAGGER_CONST_HOST, description="Serveur local")
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Inscription d'un nouvel utilisateur",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation","role"},
     *             @OA\Property(property="name", type="string", example="Kofi Mensah"),
     *             @OA\Property(property="email", type="string", example="kofi@iai.tg"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123"),
     *             @OA\Property(property="role", type="string", enum={"mentor","mentee"})
     *         )
     *     ),
     *     @OA\Response(response=201, description="Utilisateur créé avec le token"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Connexion — obtenir un token Sanctum",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="kofi@iai.tg"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Token retourné"),
     *     @OA\Response(response=401, description="Identifiants invalides")
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Identifiants invalides.',
            ], 401);
        }

        $token = $request->user()->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $request->user(),
            'token' => $token,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Déconnexion — révoquer le token",
     *     tags={"Authentification"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=204, description="Déconnecté")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté.'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Profil de l'utilisateur connecté",
     *     tags={"Authentification"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Données de l'utilisateur connecté")
     * )
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
CTRL_AUTH

# -- Contrôleur MentorController
cat > app/Http/Controllers/Api/MentorController.php << 'CTRL_MENTOR'
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMentorProfileRequest;
use App\Models\MentorProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Mentors", description="Gestion des profils mentors")
 */
class MentorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/mentors",
     *     summary="Liste des mentors validés (filtre par domaine possible)",
     *     tags={"Mentors"},
     *     @OA\Parameter(
     *         name="domain", in="query", required=false,
     *         @OA\Schema(type="string", example="Algorithmique")
     *     ),
     *     @OA\Response(response=200, description="Liste paginée des mentors")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = MentorProfile::with('user')
            ->where('is_validated', true);

        // Filtrage par domaine (cours §5.3 — requêtes avec conditions)
        if ($request->has('domain')) {
            $query->whereJsonContains('domains', $request->domain);
        }

        $mentors = $query->latest()->paginate(15);

        // Ajout de l'accesseur average_rating dans la réponse
        $mentors->getCollection()->transform(function ($profile) {
            $profile->average_rating = $profile->average_rating;
            return $profile;
        });

        return response()->json($mentors);
    }

    /**
     * @OA\Get(
     *     path="/api/mentors/{id}",
     *     summary="Détail d'un profil mentor",
     *     tags={"Mentors"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Profil mentor"),
     *     @OA\Response(response=404, description="Mentor introuvable")
     * )
     */
    public function show(int $id): JsonResponse
    {
        // Route Model Binding manuel pour inclure les relations (cours §2.4)
        $profile = MentorProfile::with('user')
            ->where('user_id', $id)
            ->where('is_validated', true)
            ->firstOrFail();

        $profile->average_rating = $profile->average_rating;

        return response()->json($profile);
    }

    /**
     * @OA\Post(
     *     path="/api/mentor/profile",
     *     summary="Créer ou mettre à jour son profil mentor",
     *     tags={"Mentors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"domains"},
     *             @OA\Property(property="domains", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="hourly_rate", type="number", example=5000),
     *             @OA\Property(property="bio", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Profil mis à jour"),
     *     @OA\Response(response=403, description="Non autorisé")
     * )
     */
    public function upsertProfile(StoreMentorProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        // Mise à jour de la bio sur l'utilisateur
        if ($request->has('bio')) {
            $user->update(['bio' => $request->bio]);
        }

        // updateOrCreate : cours §5.3
        $profile = MentorProfile::updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['domains', 'hourly_rate'])
        );

        return response()->json($profile);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/mentors/{id}/validate",
     *     summary="[Admin] Valider le profil d'un mentor",
     *     tags={"Mentors", "Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Profil validé"),
     *     @OA\Response(response=403, description="Réservé à l'administrateur")
     * )
     */
    public function validateProfile(int $id): JsonResponse
    {
        $profile = MentorProfile::where('user_id', $id)->firstOrFail();

        // Vérification via Policy (cours §7.3)
        $this->authorize('validate', $profile);

        $profile->update(['is_validated' => true]);

        return response()->json([
            'message' => 'Profil validé avec succès.',
            'profile' => $profile,
        ]);
    }
}
CTRL_MENTOR

# -- Contrôleur AvailabilityController
cat > app/Http/Controllers/Api/AvailabilityController.php << 'CTRL_AVAIL'
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAvailabilityRequest;
use App\Models\Availability;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Disponibilités", description="Gestion des créneaux mentors")
 */
class AvailabilityController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availabilityService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/mentors/{mentorId}/availabilities",
     *     summary="Créneaux libres d'un mentor pour la semaine courante",
     *     tags={"Disponibilités"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="mentorId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(
     *         name="week_start", in="query", required=false,
     *         description="Date de début de semaine (Y-m-d)",
     *         @OA\Schema(type="string", example="2025-06-02")
     *     ),
     *     @OA\Response(response=200, description="Liste des créneaux disponibles")
     * )
     */
    public function index(int $mentorId, Request $request): JsonResponse
    {
        $weekStart = $request->has('week_start')
            ? Carbon::parse($request->week_start)
            : Carbon::now()->startOfWeek();

        $slots = $this->availabilityService->getAvailableSlots($mentorId, $weekStart);

        return response()->json(['slots' => $slots]);
    }

    /**
     * @OA\Post(
     *     path="/api/availabilities",
     *     summary="Ajouter un créneau de disponibilité",
     *     tags={"Disponibilités"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"day_of_week","start_time","end_time"},
     *             @OA\Property(property="day_of_week", type="integer", example=1, description="0=dim, 6=sam"),
     *             @OA\Property(property="start_time", type="string", example="09:00"),
     *             @OA\Property(property="end_time", type="string", example="11:00")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Créneau créé"),
     *     @OA\Response(response=403, description="Réservé aux mentors")
     * )
     */
    public function store(StoreAvailabilityRequest $request): JsonResponse
    {
        $availability = Availability::create([
            'mentor_id'   => $request->user()->id,
            'day_of_week' => $request->day_of_week,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
        ]);

        return response()->json($availability, 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/availabilities/{id}",
     *     summary="Supprimer un créneau de disponibilité",
     *     tags={"Disponibilités"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Créneau supprimé")
     * )
     */
    public function destroy(Availability $availability): JsonResponse
    {
        // Seul le mentor propriétaire peut supprimer son créneau
        if ($availability->mentor_id !== request()->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $availability->delete();

        return response()->json(null, 204);
    }
}
CTRL_AVAIL

# -- Contrôleur SessionController
cat > app/Http/Controllers/Api/SessionController.php << 'CTRL_SESSION'
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use App\Models\MentorSession;
use App\Services\SessionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Sessions", description="Réservation et gestion des sessions de mentorat")
 */
class SessionController extends Controller
{
    public function __construct(
        private readonly SessionService $sessionService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/sessions",
     *     summary="Mes sessions (mentor ou mentoré selon le rôle connecté)",
     *     tags={"Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Liste des sessions")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Eager loading pour éviter le problème N+1 (cours §5.4)
        if ($user->isMentor()) {
            $sessions = MentorSession::with(['mentee', 'review'])
                ->where('mentor_id', $user->id)
                ->latest()
                ->paginate(15);
        } else {
            $sessions = MentorSession::with(['mentor.mentorProfile', 'review'])
                ->where('mentee_id', $user->id)
                ->latest()
                ->paginate(15);
        }

        return response()->json($sessions);
    }

    /**
     * @OA\Post(
     *     path="/api/sessions",
     *     summary="Réserver une session avec un mentor",
     *     tags={"Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"mentor_id","scheduled_at","duration_min"},
     *             @OA\Property(property="mentor_id", type="integer", example=3),
     *             @OA\Property(property="scheduled_at", type="string", example="2025-06-10 14:00:00"),
     *             @OA\Property(property="duration_min", type="integer", example=60)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Session créée — statut en_attente"),
     *     @OA\Response(response=409, description="Conflit de créneau"),
     *     @OA\Response(response=403, description="Réservé aux mentorés")
     * )
     */
    public function store(StoreSessionRequest $request): JsonResponse
    {
        $scheduledAt  = Carbon::parse($request->scheduled_at);
        $durationMin  = $request->duration_min;

        // Vérification du conflit de réservation (contrainte technique projet)
        if ($this->sessionService->hasConflict(
            $request->mentor_id,
            $scheduledAt,
            $durationMin
        )) {
            return response()->json([
                'message' => 'Le mentor a déjà une session confirmée sur ce créneau.',
            ], 409);
        }

        $session = MentorSession::create([
            'mentor_id'    => $request->mentor_id,
            'mentee_id'    => $request->user()->id,
            'scheduled_at' => $scheduledAt,
            'duration_min' => $durationMin,
            'status'       => 'en_attente',
        ]);

        return response()->json($session->load(['mentor', 'mentee']), 201);
    }

    /**
     * @OA\Put(
     *     path="/api/sessions/{id}/confirm",
     *     summary="[Mentor] Confirmer une demande de session",
     *     tags={"Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Session confirmée"),
     *     @OA\Response(response=403, description="Réservé au mentor de la session")
     * )
     */
    public function confirm(MentorSession $session): JsonResponse
    {
        $this->authorize('confirm', $session);

        $session->update(['status' => 'confirmee']);

        return response()->json(['message' => 'Session confirmée.', 'session' => $session]);
    }

    /**
     * @OA\Put(
     *     path="/api/sessions/{id}/cancel",
     *     summary="Annuler une session",
     *     tags={"Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Session annulée"),
     *     @OA\Response(response=403, description="Non autorisé")
     * )
     */
    public function cancel(MentorSession $session): JsonResponse
    {
        $this->authorize('cancel', $session);

        $session->update(['status' => 'annulee']);

        return response()->json(['message' => 'Session annulée.', 'session' => $session]);
    }

    /**
     * @OA\Put(
     *     path="/api/sessions/{id}/complete",
     *     summary="[Mentor] Marquer une session comme terminée",
     *     tags={"Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Session terminée"),
     *     @OA\Response(response=403, description="Réservé au mentor de la session")
     * )
     */
    public function complete(MentorSession $session): JsonResponse
    {
        $this->authorize('complete', $session);

        $session->update(['status' => 'terminee']);

        return response()->json(['message' => 'Session marquée comme terminée.', 'session' => $session]);
    }
}
CTRL_SESSION

# -- Contrôleur ReviewController
cat > app/Http/Controllers/Api/ReviewController.php << 'CTRL_REVIEW'
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\MentorSession;
use App\Models\Review;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(name="Évaluations", description="Évaluations post-session")
 */
class ReviewController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/sessions/{sessionId}/reviews",
     *     summary="Déposer une évaluation après une session terminée",
     *     tags={"Évaluations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="sessionId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rating"},
     *             @OA\Property(property="rating", type="integer", example=4, minimum=1, maximum=5),
     *             @OA\Property(property="comment", type="string", example="Très bonne session !")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Évaluation enregistrée"),
     *     @OA\Response(response=403, description="Seul le mentoré concerné peut évaluer"),
     *     @OA\Response(response=409, description="Évaluation déjà déposée pour cette session")
     * )
     */
    public function store(StoreReviewRequest $request, int $sessionId): JsonResponse
    {
        $session = MentorSession::findOrFail($sessionId);

        // Vérification via Policy (cours §7.3 — ReviewPolicy)
        $this->authorize('create', [Review::class, $session]);

        // Vérifier qu'aucune review n'existe déjà pour cette session
        if ($session->review !== null) {
            return response()->json([
                'message' => 'Une évaluation a déjà été déposée pour cette session.',
            ], 409);
        }

        $review = Review::create([
            'session_id'  => $session->id,
            'reviewer_id' => $request->user()->id,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
        ]);

        return response()->json($review->load('session'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/mentors/{mentorId}/reviews",
     *     summary="Toutes les évaluations d'un mentor",
     *     tags={"Évaluations"},
     *     @OA\Parameter(name="mentorId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Liste des reviews")
     * )
     */
    public function indexForMentor(int $mentorId): JsonResponse
    {
        $reviews = Review::whereHas('session', function ($q) use ($mentorId) {
            $q->where('mentor_id', $mentorId);
        })
        ->with(['reviewer', 'session'])
        ->latest()
        ->paginate(10);

        return response()->json($reviews);
    }
}
CTRL_REVIEW

# -- Contrôleur AdminController
cat > app/Http/Controllers/Api/AdminController.php << 'CTRL_ADMIN'
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MentorProfile;
use App\Models\MentorSession;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(name="Admin", description="Statistiques et gestion admin")
 */
class AdminController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/stats",
     *     summary="[Admin] Statistiques globales de la plateforme",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Statistiques"),
     *     @OA\Response(response=403, description="Réservé à l'administrateur")
     * )
     */
    public function stats(): JsonResponse
    {
        // Mentors les mieux notés (cours §5.3 — requêtes avec conditions)
        $topMentors = MentorProfile::with('user')
            ->where('is_validated', true)
            ->get()
            ->map(fn($p) => [
                'mentor'         => $p->user->name,
                'domains'        => $p->domains,
                'average_rating' => $p->average_rating,
            ])
            ->sortByDesc('average_rating')
            ->take(10)
            ->values();

        return response()->json([
            'total_users'         => User::count(),
            'total_mentors'       => User::where('role', 'mentor')->count(),
            'total_mentees'       => User::where('role', 'mentee')->count(),
            'pending_validations' => MentorProfile::where('is_validated', false)->count(),
            'total_sessions'      => MentorSession::count(),
            'sessions_by_status'  => MentorSession::selectRaw('status, count(*) as total')
                                        ->groupBy('status')
                                        ->pluck('total', 'status'),
            'top_mentors'         => $topMentors,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/pending-mentors",
     *     summary="[Admin] Liste des profils mentors en attente de validation",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Profils en attente")
     * )
     */
    public function pendingMentors(): JsonResponse
    {
        $profiles = MentorProfile::with('user')
            ->where('is_validated', false)
            ->latest()
            ->paginate(15);

        return response()->json($profiles);
    }
}
CTRL_ADMIN

# -----------------------------------------------------------------------------
# ÉTAPE 11 — Définition des ROUTES API
# Cours §8.2 — routes API avec Sanctum (auth:sanctum)
# -----------------------------------------------------------------------------
echo ""
echo ">>> [11/12] Définition des routes API..."

cat > routes/api.php << 'ROUTES_API'
<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\MentorController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes API — MentorLink
| Cours §2 : routage, middlewares (cours §2.3), Route Model Binding (cours §2.4)
| Authentification Sanctum (cours §7.4)
|--------------------------------------------------------------------------
*/

// ------------------------------------------------------------------
// Routes PUBLIQUES (aucune authentification requise)
// ------------------------------------------------------------------

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Liste et détail des mentors validés — publics
Route::get('/mentors',              [MentorController::class, 'index']);
Route::get('/mentors/{id}',         [MentorController::class, 'show']);
Route::get('/mentors/{id}/reviews', [ReviewController::class, 'indexForMentor']);

// ------------------------------------------------------------------
// Routes PROTÉGÉES (token Sanctum requis — cours §7.4)
// ------------------------------------------------------------------

Route::middleware('auth:sanctum')->group(function () {

    // Authentification
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Profil mentor (le mentor gère son propre profil)
    Route::post('/mentor/profile', [MentorController::class, 'upsertProfile']);

    // Disponibilités
    Route::get('/mentors/{mentorId}/availabilities', [AvailabilityController::class, 'index']);
    Route::post('/availabilities',                   [AvailabilityController::class, 'store']);
    Route::delete('/availabilities/{availability}',  [AvailabilityController::class, 'destroy']);

    // Sessions
    Route::get('/sessions',                  [SessionController::class, 'index']);
    Route::post('/sessions',                 [SessionController::class, 'store']);
    Route::put('/sessions/{session}/confirm',  [SessionController::class, 'confirm']);
    Route::put('/sessions/{session}/cancel',   [SessionController::class, 'cancel']);
    Route::put('/sessions/{session}/complete', [SessionController::class, 'complete']);

    // Évaluations
    Route::post('/sessions/{sessionId}/reviews', [ReviewController::class, 'store']);

    // ------------------------------------------------------------------
    // Routes ADMIN (middleware supplémentaire vérifiant le rôle)
    // ------------------------------------------------------------------

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/stats',                         [AdminController::class, 'stats']);
        Route::get('/pending-mentors',               [AdminController::class, 'pendingMentors']);
        Route::put('/mentors/{id}/validate',         [MentorController::class, 'validateProfile']);
    });
});
ROUTES_API

# -----------------------------------------------------------------------------
# ÉTAPE 12 — Middleware de rôle + Factories + Seeders
# Cours §2.3 — middleware personnalisé | §5.5 — seeders et factories
# -----------------------------------------------------------------------------
echo ""
echo ">>> [12/12] Middleware de rôle, Factories et Seeders..."

# -- Middleware CheckRole
cat > app/Http/Middleware/CheckRole.php << 'MW_ROLE'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CheckRole — vérifie le rôle de l'utilisateur connecté.
 * Cours §2.3 — middleware personnalisé : php artisan make:middleware VerifierRole
 *
 * Usage : ->middleware('role:admin')
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            return response()->json([
                'message' => 'Accès refusé. Rôle requis : ' . $role,
            ], 403);
        }

        return $next($request);
    }
}
MW_ROLE

# Enregistrement du middleware dans bootstrap/app.php
# (Laravel 12 utilise le nouveau système de bootstrap)
cat > bootstrap/app.php << 'BOOTSTRAP'
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Enregistrement du middleware de rôle (cours §2.3)
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
BOOTSTRAP

# -- Factory : UserFactory (mise à jour)
cat > database/factories/UserFactory.php << 'FACTORY_USER'
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Factory User — cours §5.5 : génération de fausses instances avec Faker.
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'), // mot de passe par défaut
            'role'              => fake()->randomElement(['mentor', 'mentee']),
            'bio'               => fake()->paragraph(),
            'avatar'            => null,
            'remember_token'    => Str::random(10),
        ];
    }

    public function mentor(): static
    {
        return $this->state(['role' => 'mentor']);
    }

    public function mentee(): static
    {
        return $this->state(['role' => 'mentee']);
    }

    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }
}
FACTORY_USER

# -- Factory : MentorProfileFactory
cat > database/factories/MentorProfileFactory.php << 'FACTORY_PROFILE'
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MentorProfileFactory extends Factory
{
    public function definition(): array
    {
        $domains = ['Algorithmique', 'Base de données', 'Laravel', 'JavaScript',
                    'Python', 'Machine Learning', 'Réseaux', 'Mathématiques'];

        return [
            'user_id'      => User::factory()->mentor(),
            'domains'      => fake()->randomElements($domains, fake()->numberBetween(1, 3)),
            'hourly_rate'  => fake()->boolean(70) ? fake()->numberBetween(1000, 10000) : null,
            'is_validated' => fake()->boolean(80),
        ];
    }
}
FACTORY_PROFILE

# -- Seeder principal
cat > database/seeders/DatabaseSeeder.php << 'SEEDER'
<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\MentorProfile;
use App\Models\MentorSession;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DatabaseSeeder — peuplement de la base de données.
 * Cours §5.5 : seeders + factories pour les tests et le développement.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Compte administrateur fixe
        $admin = User::factory()->admin()->create([
            'name'  => 'Admin MentorLink',
            'email' => 'admin@mentorlink.tg',
            'password' => Hash::make('password'),
        ]);

        // 10 mentors avec leur profil et des disponibilités
        $mentors = User::factory()->mentor()->count(10)->create();

        foreach ($mentors as $mentor) {
            MentorProfile::factory()->create(['user_id' => $mentor->id]);

            // Créneaux horaires récurrents (cours §5.5)
            foreach ([1, 3, 5] as $day) { // Lundi, Mercredi, Vendredi
                Availability::create([
                    'mentor_id'   => $mentor->id,
                    'day_of_week' => $day,
                    'start_time'  => '09:00',
                    'end_time'    => '11:00',
                ]);
            }
        }

        // 20 mentorés
        $mentees = User::factory()->mentee()->count(20)->create();

        // Quelques sessions pour tester
        foreach ($mentees->take(5) as $mentee) {
            $mentor  = $mentors->random();
            $session = MentorSession::create([
                'mentor_id'    => $mentor->id,
                'mentee_id'    => $mentee->id,
                'scheduled_at' => now()->addDays(rand(1, 30)),
                'duration_min' => 60,
                'status'       => 'terminee',
            ]);

            // Review sur la session terminée
            Review::create([
                'session_id'  => $session->id,
                'reviewer_id' => $mentee->id,
                'rating'      => rand(3, 5),
                'comment'     => fake()->sentence(),
            ]);
        }

        $this->command->info('Base de données peuplée avec succès !');
        $this->command->info('Admin : admin@mentorlink.tg / password');
    }
}
SEEDER

# -----------------------------------------------------------------------------
# GÉNÉRATION SWAGGER ET LANCEMENT DES MIGRATIONS
# -----------------------------------------------------------------------------
echo ""
echo ">>> Exécution des migrations et génération de la doc Swagger..."

# Créer la base de données si elle n'existe pas (SQLite pour les tests)
# Pour MySQL, créer manuellement la base puis changer le .env
sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
echo "" >> .env
echo "DB_DATABASE=" >> .env
touch database/database.sqlite

php artisan migrate --force
php artisan db:seed --force

# Générer la documentation Swagger
php artisan l5-swagger:generate

echo ""
echo "============================================="
echo "  MentorLink — Backend créé avec succès !"
echo "============================================="
echo ""
echo "  Démarrer le serveur :  php artisan serve"
echo "  Documentation API   :  http://localhost:8000/api/documentation"
echo "  Swagger JSON        :  http://localhost:8000/docs/api-docs.json"
echo ""
echo "  Compte admin de test :"
echo "    Email    : admin@mentorlink.tg"
echo "    Password : password"
echo ""
echo "  Flux d'utilisation API :"
echo "    1. POST /api/register  -> obtenir un token"
echo "    2. POST /api/login     -> si déjà inscrit"
echo "    3. Ajouter le token dans Authorization: Bearer <token>"
echo "    4. Explorer via Swagger : /api/documentation"
echo "============================================="
