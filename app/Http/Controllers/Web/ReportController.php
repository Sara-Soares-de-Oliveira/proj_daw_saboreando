<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InternalApi;

class ReportController extends Controller
{
    public function store(Request $request, int $comment)
    {
        $validated = $request->validate([
            'motivo' => ['required', 'string', 'max:500'],
        ]);

        $token = $request->session()->get('api_token');
        $response = InternalApi::request('POST', "/api/explorador/comments/{$comment}/reports", $validated, $token);

        if (!$response['ok']) {
            return back()->withErrors(['motivo' => 'Não foi possível denunciar o comentário.']);
        }

        return back();
    }
}
