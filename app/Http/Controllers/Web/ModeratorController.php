<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InternalApi;

class ModeratorController extends Controller
{
    public function home()
    {
        $response = InternalApi::request('GET', '/api/recipes');
        $recipes = $response['ok'] ? ($response['json']['data'] ?? []) : [];

        return view('moderator.home', [
            'popular' => array_slice($recipes, 0, 3),
        ]);
    }

    public function pending(Request $request)
    {
        $token = $request->session()->get('api_token');
        $response = InternalApi::request('GET', '/api/moderador/recipes?estado=pendente', [], $token);
        $recipes = $response['ok'] ? ($response['json']['data'] ?? []) : [];

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
        InternalApi::request('PATCH', "/api/moderador/comments/{$comment}/keep", [], $token);

        return back();
    }

    public function removeComment(Request $request, int $comment)
    {
        $token = $request->session()->get('api_token');
        InternalApi::request('PATCH', "/api/moderador/comments/{$comment}/remove", [], $token);

        return back();
    }
}
