<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\InternalApi;

class ModeratorController extends Controller
{
    public function home()
    {
        $response = InternalApi::request('GET', '/api/recipes');
        $recipes = $response['ok'] ? ($response['json']['data'] ?? []) : [];

        $token = request()->session()->get('api_token');
        $metricsResponse = InternalApi::request('GET', '/api/metrics/moderador', ['period' => 'day'], $token);
        $metrics = $metricsResponse['ok'] ? ($metricsResponse['json'] ?? []) : [];

        return view('moderator.home', [
            'popular' => array_slice($recipes, 0, 3),
            'metrics' => $metrics,
        ]);
    }

    public function pending(Request $request)
    {
        $token = $request->session()->get('api_token');
        $response = InternalApi::request('GET', '/api/moderador/recipes?estado=pendente', [], $token);
        $recipes = $response['ok'] ? ($response['json']['data'] ?? []) : [];
        $recipes = array_map(function ($recipe) {
            if (!empty($recipe['foto'])) {
                $recipe['foto_url'] = url('storage/'.$recipe['foto']);
            }
            return $recipe;
        }, $recipes);

        $perPage = 5;
        $page = max(1, (int) $request->query('page', 1));
        $total = count($recipes);
        $items = array_slice($recipes, ($page - 1) * $perPage, $perPage);
        $recipes = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return view('moderator.pending', [
            'recipes' => $recipes,
            'user' => $request->session()->get('user'),
        ]);
    }

    public function reportedComments(Request $request)
    {
        $token = $request->session()->get('api_token');
        $response = InternalApi::request('GET', '/api/moderador/reports', [], $token);
        $reports = $response['ok'] ? ($response['json']['data'] ?? []) : [];

        return view('moderator.reported-comments', [
            'reports' => $reports,
            'user' => $request->session()->get('user'),
        ]);
    }

    public function approve(Request $request, int $recipe)
    {
        $token = $request->session()->get('api_token');
        InternalApi::request('PATCH', "/api/moderador/recipes/{$recipe}/approve", [], $token);

        return back();
    }

    public function reject(Request $request, int $recipe)
    {
        $token = $request->session()->get('api_token');
        InternalApi::request('PATCH', "/api/moderador/recipes/{$recipe}/reject", [], $token);

        return back();
    }

    public function keepComment(Request $request, int $comment)
    {
        $token = $request->session()->get('api_token');
        $response = InternalApi::request('PATCH', "/api/moderador/comments/{$comment}/keep", [], $token);
        if (!$response['ok']) {
            $message = $response['json']['message'] ?? 'NÃ£o foi possÃ­vel manter o comentÃ¡rio.';
            return back()->withErrors(['comment' => $message]);
        }

        return back();
    }

    public function removeComment(Request $request, int $comment)
    {
        $token = $request->session()->get('api_token');
        $response = InternalApi::request('PATCH', "/api/moderador/comments/{$comment}/remove", [], $token);
        if (!$response['ok']) {
            $message = $response['json']['message'] ?? 'NÃ£o foi possÃ­vel remover o comentÃ¡rio.';
            return back()->withErrors(['comment' => $message]);
        }

        return back();
    }
}
