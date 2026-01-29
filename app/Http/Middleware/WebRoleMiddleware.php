<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebRoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->session()->get('user');

        if (!$user || ($user['role'] ?? null) !== $role) {
            return redirect('/entrar');
        }

        return $next($request);
    }
}
