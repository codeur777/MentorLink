<?php

namespace App\Http\Controllers;

use App\Models\MentorProfile;
use App\Models\Mentorship;
use App\Models\MentorshipGoal;
use App\Models\MentorshipSession;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class FrontendController extends Controller
{
    public function landing(): View
    {
        $platform = $this->platformBase();
        $platformEndpoint = $platform['api']['overview'];
        $headerAction = '<a href="#api" class="button button-secondary">Voir la base technique</a>';
        $dbState = $this->dbState();

        $heroMetrics = [
            [
                'value' => (string) $dbState['mentors_count'],
                'label' => 'Mentors publies dans la base.',
            ],
            [
                'value' => (string) $dbState['active_mentorships_count'],
                'label' => 'Relations actives actuellement enregistrees.',
            ],
            [
                'value' => (string) $dbState['upcoming_sessions_count'],
                'label' => 'Sessions planifiees a venir.',
            ],
        ];

        $featuredMentorship = $this->safeQuery(function (): ?array {
            return Mentorship::query()
                ->with(['mentor', 'mentee'])
                ->where('status', 'active')
                ->latest('next_session_at')
                ->first()
                ?->toArray();
        }, null);

        return view('welcome', [
            'platform' => $platform,
            'pageTitle' => 'MentorLink | Accueil',
            'platformEndpoint' => $platformEndpoint,
            'headerAction' => $headerAction,
            'heroMetrics' => $heroMetrics,
            'dbState' => $dbState,
            'featuredMentorship' => $featuredMentorship,
        ]);
    }

    public function mentors(): View
    {
        $platform = $this->platformBase();
        $headerAction = '<a href="' . route('access.index') . '" class="button button-secondary">Demander un acces</a>';

        $filters = $this->safeQuery(function (): Collection {
            return MentorProfile::query()
                ->select('focus_area')
                ->where('is_listed', true)
                ->whereNotNull('focus_area')
                ->distinct()
                ->orderBy('focus_area')
                ->pluck('focus_area');
        }, collect());

        $mentors = $this->safeQuery(function (): Collection {
            return MentorProfile::query()
                ->with('user')
                ->where('is_listed', true)
                ->orderByDesc('years_experience')
                ->orderBy('focus_area')
                ->get();
        }, collect());

        return view('mentors', [
            'platform' => $platform,
            'pageTitle' => 'MentorLink | Mentors',
            'headerAction' => $headerAction,
            'filters' => $filters,
            'mentors' => $mentors,
        ]);
    }

    public function dashboard(): View
    {
        $platform = $this->platformBase();
        $headerAction = '<a href="' . route('mentors.index') . '" class="button button-secondary">Voir les mentors</a>';
        $dbState = $this->dbState();

        $overviewMetrics = [
            [
                'label' => 'Relations actives',
                'value' => (string) $dbState['active_mentorships_count'],
                'hint' => 'Compteur issu de la table mentorships.',
            ],
            [
                'label' => 'Objectifs ouverts',
                'value' => (string) $dbState['open_goals_count'],
                'hint' => 'Objectifs encore en cours dans mentorship_goals.',
            ],
            [
                'label' => 'Sessions prevues',
                'value' => (string) $dbState['upcoming_sessions_count'],
                'hint' => 'Sessions futures dans mentorship_sessions.',
            ],
        ];

        $goals = $this->safeQuery(function (): Collection {
            return MentorshipGoal::query()
                ->with(['mentorship.mentor', 'mentorship.mentee'])
                ->whereIn('status', ['draft', 'active'])
                ->orderByDesc('progress')
                ->orderBy('due_at')
                ->limit(6)
                ->get();
        }, collect());

        $sessions = $this->safeQuery(function (): Collection {
            return MentorshipSession::query()
                ->with(['mentorship.mentor', 'mentorship.mentee'])
                ->where('starts_at', '>=', now())
                ->orderBy('starts_at')
                ->limit(6)
                ->get();
        }, collect());

        $checkpoints = $this->safeQuery(function (): Collection {
            return Mentorship::query()
                ->withCount([
                    'goals as open_goals_count' => fn ($query) => $query->whereIn('status', ['draft', 'active']),
                    'sessions as planned_sessions_count' => fn ($query) => $query->where('starts_at', '>=', now()),
                ])
                ->where('status', 'active')
                ->orderByDesc('updated_at')
                ->limit(4)
                ->get();
        }, collect());

        return view('dashboard', [
            'platform' => $platform,
            'pageTitle' => 'MentorLink | Dashboard',
            'headerAction' => $headerAction,
            'overviewMetrics' => $overviewMetrics,
            'goals' => $goals,
            'sessions' => $sessions,
            'checkpoints' => $checkpoints,
            'dbState' => $dbState,
        ]);
    }

    public function access(): View
    {
        $platform = $this->platformBase();
        $headerAction = '<a href="' . route('landing') . '" class="button button-secondary">Retour accueil</a>';
        $dbState = $this->dbState();

        return view('access', [
            'platform' => $platform,
            'pageTitle' => 'MentorLink | Acces',
            'headerAction' => $headerAction,
            'dbState' => $dbState,
        ]);
    }

    public function dbState(): array
    {
        $defaults = [
            'connected' => false,
            'database_name' => config('database.connections.mysql.database'),
            'mentors_count' => 0,
            'mentees_count' => 0,
            'active_mentorships_count' => 0,
            'open_goals_count' => 0,
            'upcoming_sessions_count' => 0,
        ];

        return $this->safeQuery(function () use ($defaults): array {
            if (! $this->databaseReady()) {
                return $defaults;
            }

            return [
                'connected' => true,
                'database_name' => DB::connection()->getDatabaseName(),
                'mentors_count' => User::query()->where('role', 'mentor')->count(),
                'mentees_count' => User::query()->where('role', 'mentee')->count(),
                'active_mentorships_count' => Mentorship::query()->where('status', 'active')->count(),
                'open_goals_count' => MentorshipGoal::query()->whereIn('status', ['draft', 'active'])->count(),
                'upcoming_sessions_count' => MentorshipSession::query()->where('starts_at', '>=', now())->count(),
            ];
        }, $defaults);
    }

    private function platformBase(): array
    {
        return [
            'name' => 'MentorLink',
            'tagline' => 'La plateforme qui rapproche mentors, talents et objectifs concrets.',
            'footer' => 'MentorLink combine maintenant une facade plus convaincante et un socle Laravel propre a faire grandir.',
            'stack' => [
                'Laravel 12',
                'Blade',
                'Vite',
                'MySQL',
                'Sanctum',
                'Eloquent',
            ],
            'features' => [
                [
                    'title' => 'Pages branchees sur MySQL',
                    'description' => 'Les vues lisent maintenant les tables reelles plutot que des tableaux PHP statiques.',
                ],
                [
                    'title' => 'Schema clair pour MentorLink',
                    'description' => 'Le projet distingue utilisateurs, profils mentors, relations, objectifs et sessions.',
                ],
                [
                    'title' => 'Etats vides propres',
                    'description' => 'L interface reste exploitable meme avant que vous ayez saisi vos donnees locales.',
                ],
                [
                    'title' => 'Base extensible',
                    'description' => 'Le schema reste simple a faire evoluer si vous ajoutez matching, notifications ou reporting.',
                ],
            ],
            'journey' => [
                [
                    'step' => '01',
                    'title' => 'Creer les utilisateurs',
                    'description' => 'Les personnes vivent dans la table users avec un role explicite mentor, mentee ou admin.',
                ],
                [
                    'step' => '02',
                    'title' => 'Publier les profils mentors',
                    'description' => 'Les details d expertise et de disponibilite sont stockes dans mentor_profiles.',
                ],
                [
                    'step' => '03',
                    'title' => 'Suivre les relations',
                    'description' => 'Les objectifs et sessions se rattachent a mentorships pour garder une vue claire du parcours.',
                ],
            ],
            'capabilities' => [
                [
                    'label' => 'Page web active',
                    'value' => '/',
                ],
                [
                    'label' => 'Statut public plateforme',
                    'value' => '/api/platform-overview',
                ],
                [
                    'label' => 'Endpoint securise utilisateur',
                    'value' => '/api/user',
                ],
            ],
            'api' => [
                'overview' => url('/api/platform-overview'),
                'user' => url('/api/user'),
            ],
        ];
    }

    private function databaseReady(): bool
    {
        try {
            return Schema::hasTable('users')
                && Schema::hasTable('mentor_profiles')
                && Schema::hasTable('mentorships')
                && Schema::hasTable('mentorship_goals')
                && Schema::hasTable('mentorship_sessions');
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @template T
     *
     * @param  callable(): T  $callback
     * @param  T  $fallback
     * @return T
     */
    private function safeQuery(callable $callback, mixed $fallback): mixed
    {
        try {
            return $callback();
        } catch (Throwable) {
            return $fallback;
        }
    }
}
