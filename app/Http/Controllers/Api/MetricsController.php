<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\LoginSession;
use App\Models\Recipe;
use App\Models\RecipeView;
use App\Models\Report;
use App\Models\UserActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MetricsController extends Controller
{
    /**
     * @return array{from:Carbon|null,to:Carbon|null}
     */
    private function resolvePeriod(Request $request): array
    {
        $from = null;
        $to = null;

        if ($request->filled('from') || $request->filled('to')) {
            $from = $request->filled('from') ? Carbon::parse($request->input('from'))->startOfDay() : null;
            $to = $request->filled('to') ? Carbon::parse($request->input('to'))->endOfDay() : null;
        } elseif ($request->filled('period')) {
            $period = (string) $request->input('period');
            $to = now()->endOfDay();
            if ($period === 'day') {
                $from = now()->startOfDay();
            } elseif ($period === 'week') {
                $from = now()->startOfWeek();
            } elseif ($period === 'month') {
                $from = now()->startOfMonth();
            } elseif ($period === 'year') {
                $from = now()->startOfYear();
            }
        }

        return ['from' => $from, 'to' => $to];
    }

    private function applyPeriod($query, string $column, ?Carbon $from, ?Carbon $to)
    {
        if ($from) {
            $query->where($column, '>=', $from);
        }
        if ($to) {
            $query->where($column, '<=', $to);
        }
        return $query;
    }

    public function explorador(Request $request)
    {
        $user = $request->user();
        $recipeIds = Recipe::where('user_id', $user->id)->pluck('id');
        $period = $this->resolvePeriod($request);
        $from = $period['from'];
        $to = $period['to'];

        $sessionsQuery = LoginSession::where('user_id', $user->id);
        $this->applyPeriod($sessionsQuery, 'login_at', $from, $to);
        $sessions = $sessionsQuery->get(['login_at', 'logout_at', 'duration_seconds']);

        $totalSeconds = $sessions->sum(function ($session) {
            if ($session->login_at) {
                $start = Carbon::parse($session->login_at);
                $end = $session->logout_at ? Carbon::parse($session->logout_at) : now();
                return max(0, $end->diffInSeconds($start, true));
            }
            return 0;
        });

        $allSessions = LoginSession::where('user_id', $user->id)->get(['login_at', 'logout_at', 'duration_seconds']);
        $allTotalSeconds = $allSessions->sum(function ($session) {
            if ($session->login_at) {
                $start = Carbon::parse($session->login_at);
                $end = $session->logout_at ? Carbon::parse($session->logout_at) : now();
                return max(0, $end->diffInSeconds($start, true));
            }
            return 0;
        });

        $viewsQuery = RecipeView::where('user_id', $user->id);
        $this->applyPeriod($viewsQuery, 'view_start', $from, $to);
        $views = $viewsQuery->get(['id', 'recipe_id', 'view_start', 'view_end', 'duration_seconds']);

        $viewDurations = $views->map(function ($view) {
            if ($view->view_start) {
                $start = Carbon::parse($view->view_start);
                $end = $view->view_end ? Carbon::parse($view->view_end) : now();
                return max(0, $end->diffInSeconds($start, true));
            }
            return 0;
        });
        $avgViewTime = $viewDurations->count() ? (int) round($viewDurations->avg()) : 0;

        $activitiesQuery = UserActivity::where('user_id', $user->id);
        $this->applyPeriod($activitiesQuery, 'created_at', $from, $to);
        $activities = $activitiesQuery->get(['action_type']);

        $recipesCreatedQuery = Recipe::where('user_id', $user->id);
        $this->applyPeriod($recipesCreatedQuery, 'created_at', $from, $to);

        $data = [
            'period' => [
                'from' => $from?->toDateString(),
                'to' => $to?->toDateString(),
            ],
            'recipes_total' => $recipeIds->count(),
            'recipes_pendentes' => Recipe::where('user_id', $user->id)->where('estado', 'pendente')->count(),
            'recipes_aprovadas' => Recipe::where('user_id', $user->id)->where('estado', 'aprovado')->count(),
            'recipes_rejeitadas' => Recipe::where('user_id', $user->id)->where('estado', 'rejeitado')->count(),
            'comments_made' => Comment::where('user_id', $user->id)->count(),
            'comments_received' => Comment::whereIn('recipe_id', $recipeIds)->count(),
            'views_total' => RecipeView::whereIn('recipe_id', $recipeIds)->count(),
            'period_recipes_created' => $recipesCreatedQuery->count(),
            'period_sessions' => $sessions->count(),
            'period_active_days' => $sessions->pluck('login_at')->map(fn ($dt) => Carbon::parse($dt)->toDateString())->unique()->count(),
            'period_time_seconds' => $totalSeconds,
            'total_time_seconds' => $allTotalSeconds,
            'period_views' => $views->count(),
            'period_avg_view_seconds' => $avgViewTime ? (int) round($avgViewTime) : 0,
            'period_interactions' => $activities->whereIn('action_type', ['comment_created', 'report_created'])->count(),
            'period_top_viewed_recipes' => RecipeView::where('recipe_views.user_id', $user->id)
                ->when($from, fn ($q) => $q->where('view_start', '>=', $from))
                ->when($to, fn ($q) => $q->where('view_start', '<=', $to))
                ->join('recipes', 'recipe_views.recipe_id', '=', 'recipes.id')
                ->select('recipe_views.recipe_id', 'recipes.titulo', DB::raw('count(*) as views_count'))
                ->groupBy('recipe_views.recipe_id', 'recipes.titulo')
                ->orderByDesc('views_count')
                ->limit(5)
                ->get(),
            'top_approved_recipes' => RecipeView::join('recipes', 'recipe_views.recipe_id', '=', 'recipes.id')
                ->where('recipes.user_id', $user->id)
                ->where('recipes.estado', 'aprovado')
                ->select('recipe_views.recipe_id', 'recipes.titulo', DB::raw('count(*) as views_count'))
                ->groupBy('recipe_views.recipe_id', 'recipes.titulo')
                ->orderByDesc('views_count')
                ->limit(5)
                ->get(),
            'period_views_by_day' => RecipeView::where('user_id', $user->id)
                ->when($from, fn ($q) => $q->where('view_start', '>=', $from))
                ->when($to, fn ($q) => $q->where('view_start', '<=', $to))
                ->select(DB::raw('DATE(view_start) as day'), DB::raw('count(*) as total'))
                ->groupBy(DB::raw('DATE(view_start)'))
                ->orderBy('day')
                ->get(),
            'period_recent_views' => RecipeView::where('user_id', $user->id)
                ->when($from, fn ($q) => $q->where('view_start', '>=', $from))
                ->when($to, fn ($q) => $q->where('view_start', '<=', $to))
                ->with('recipe:id,titulo')
                ->latest('view_start')
                ->limit(10)
                ->get(),
            'top_recipes' => Recipe::where('user_id', $user->id)
                ->withCount(['comments', 'views'])
                ->orderByDesc('views_count')
                ->orderByDesc('comments_count')
                ->limit(5)
                ->get(['id', 'titulo', 'estado']),
        ];

        return response()->json($data);
    }

    public function moderador(Request $request)
    {
        $period = $this->resolvePeriod($request);
        $from = $period['from'];
        $to = $period['to'];

        $loginsQuery = LoginSession::query();
        $this->applyPeriod($loginsQuery, 'login_at', $from, $to);

        $recipesQuery = Recipe::query();
        $this->applyPeriod($recipesQuery, 'created_at', $from, $to);

        $commentsQuery = Comment::query();
        $this->applyPeriod($commentsQuery, 'created_at', $from, $to);

        $reportsQuery = Report::query();
        $this->applyPeriod($reportsQuery, 'created_at', $from, $to);

        $activitiesQuery = UserActivity::query();
        $this->applyPeriod($activitiesQuery, 'created_at', $from, $to);

        $moderatorIds = User::where('role', 'moderador')->pluck('id');
        $explorerIds = User::where('role', 'explorador')->pluck('id');

        $activeUsersQuery = (clone $loginsQuery)->whereNotIn('user_id', $moderatorIds);

        $creatorUserIds = (clone $recipesQuery)
            ->whereIn('user_id', $explorerIds)
            ->distinct()
            ->pluck('user_id');

        $commentUserIds = (clone $commentsQuery)
            ->whereIn('user_id', $explorerIds)
            ->distinct()
            ->pluck('user_id');

        $reportUserIds = (clone $reportsQuery)
            ->whereIn('user_id', $explorerIds)
            ->distinct()
            ->pluck('user_id');

        $interactorUserIds = $commentUserIds->merge($reportUserIds)->unique();

        $viewerUserIds = RecipeView::query()
            ->when($from, fn ($q) => $q->where('view_start', '>=', $from))
            ->when($to, fn ($q) => $q->where('view_start', '<=', $to))
            ->whereIn('user_id', $explorerIds)
            ->distinct()
            ->pluck('user_id');

        $viewersOnlyCount = $viewerUserIds
            ->diff($creatorUserIds)
            ->diff($interactorUserIds)
            ->count();

        $data = [
            'period' => [
                'from' => $from?->toDateString(),
                'to' => $to?->toDateString(),
            ],
            'recipes_total' => Recipe::count(),
            'recipes_pendentes' => Recipe::where('estado', 'pendente')->count(),
            'recipes_aprovadas' => Recipe::where('estado', 'aprovado')->count(),
            'recipes_rejeitadas' => Recipe::where('estado', 'rejeitado')->count(),
            'comments_total' => Comment::count(),
            'comments_removidos' => Comment::where('estado', 'removido')->count(),
            'reports_total' => Report::count(),
            'period_active_users' => (clone $activeUsersQuery)->distinct('user_id')->count('user_id'),
            'period_logins' => (clone $loginsQuery)->count(),
            'period_recipes_created' => (clone $recipesQuery)->whereIn('user_id', $explorerIds)->count(),
            'period_recipes_approved' => (clone $activitiesQuery)
                ->where('action_type', 'recipe_approved')
                ->count(),
            'period_recipes_rejected' => (clone $activitiesQuery)
                ->where('action_type', 'recipe_rejected')
                ->count(),
            'period_comments_created' => (clone $commentsQuery)->whereIn('user_id', $explorerIds)->count(),
            'period_reports_created' => (clone $reportsQuery)->whereIn('user_id', $explorerIds)->count(),
            'period_comments_removed' => (clone $activitiesQuery)
                ->where('action_type', 'comment_removed')
                ->count(),
            'period_comments_kept' => (clone $activitiesQuery)
                ->where('action_type', 'comment_kept')
                ->count(),
            'period_users_creators' => $creatorUserIds->count(),
            'period_users_interactors' => $interactorUserIds->count(),
            'period_users_viewers_only' => $viewersOnlyCount,
            'period_peak_hours' => [
                'logins' => (clone $loginsQuery)
                    ->select(DB::raw('HOUR(login_at) as hour'), DB::raw('count(*) as total'))
                    ->groupBy(DB::raw('HOUR(login_at)'))
                    ->orderByDesc('total')
                    ->get(),
                'recipes' => (clone $recipesQuery)
                    ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as total'))
                    ->groupBy(DB::raw('HOUR(created_at)'))
                    ->orderByDesc('total')
                    ->get(),
                'reports' => (clone $reportsQuery)
                    ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as total'))
                    ->groupBy(DB::raw('HOUR(created_at)'))
                    ->orderByDesc('total')
                    ->get(),
            ],
            'period_daily_activity' => [
                'logins' => (clone $loginsQuery)
                    ->select(DB::raw('DATE(login_at) as day'), DB::raw('count(*) as total'))
                    ->groupBy(DB::raw('DATE(login_at)'))
                    ->orderBy('day')
                    ->get(),
                'recipes' => (clone $recipesQuery)
                    ->select(DB::raw('DATE(created_at) as day'), DB::raw('count(*) as total'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('day')
                    ->get(),
                'reports' => (clone $reportsQuery)
                    ->select(DB::raw('DATE(created_at) as day'), DB::raw('count(*) as total'))
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('day')
                    ->get(),
            ],
            'period_user_participation' => [
                'created_recipes_users' => (clone $activitiesQuery)
                    ->where('action_type', 'recipe_created')
                    ->whereIn('user_id', $explorerIds)
                    ->distinct('user_id')
                    ->count('user_id'),
                'commented_users' => (clone $activitiesQuery)
                    ->where('action_type', 'comment_created')
                    ->whereIn('user_id', $explorerIds)
                    ->distinct('user_id')
                    ->count('user_id'),
                'reported_users' => (clone $activitiesQuery)
                    ->where('action_type', 'report_created')
                    ->whereIn('user_id', $explorerIds)
                    ->distinct('user_id')
                    ->count('user_id'),
            ],
            'top_reported_comments' => Comment::withCount('reports')
                ->orderByDesc('reports_count')
                ->limit(5)
                ->get(['id', 'recipe_id', 'user_id', 'conteudo', 'estado']),
        ];

        return response()->json($data);
    }
}
