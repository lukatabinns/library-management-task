<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    protected $role;
    public function handle(Request $request, Closure $next, $role)
    {
        $user = auth()->user();
        if (!$user || $user->role !== $role) {
            return response()->json(['message' => 'Forbidden. Insufficient permissions.'], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}

