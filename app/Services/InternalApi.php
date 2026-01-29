<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class InternalApi
{
    /**
     * @return array{ok:bool,status:int,json:mixed}
     */
    public static function request(string $method, string $uri, array $data = [], ?string $token = null): array
    {
        $request = Request::create($uri, strtoupper($method), $data);
        $request->headers->set('Accept', 'application/json');

        if ($token) {
            $request->headers->set('Authorization', 'Bearer '.$token);
            $request->server->set('HTTP_AUTHORIZATION', 'Bearer '.$token);
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');

            $accessToken = PersonalAccessToken::findToken($token);
            if ($accessToken && $accessToken->tokenable) {
                Auth::shouldUse('sanctum');
                Auth::setUser($accessToken->tokenable);
                $request->setUserResolver(fn () => $accessToken->tokenable);
            }
        }

        // Ensure the API uses the same database session when needed
        $request->setLaravelSession(app('session')->driver());

        $response = Route::dispatch($request);
        $status = $response->getStatusCode();
        $content = (string) $response->getContent();
        $json = $content !== '' ? json_decode($content, true) : null;

        return [
            'ok' => $status >= 200 && $status < 300,
            'status' => $status,
            'json' => $json,
        ];
    }
}
