<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InternalApi;

class CommentController extends Controller
{
    public function store(Request $request, int $recipe)
    {
        $validated = $request->validate([
            'conteudo' => ['required', 'string', 'max:2000'],
        ]);

        $token = $request->session()->get('api_token');
        $response = InternalApi::request('POST', "/api/explorador/recipes/{$recipe}/comments", $validated, $token);

        if (!$response['ok']) {
            $message = $response['json']['message'] ?? 'Não foi possível enviar o comentário.';
            return back()->withErrors(['conteudo' => $message]);
        }

        return back();
    }
}
