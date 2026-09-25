<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BuildingCoordinatorMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        abort_unless(
            $user !== null &&
            $user->role?->name === 'Building Coordinator',
            403
        );

        abort_unless(
            $user->buildings()->exists(),
            403
        );

        return $next($request);
    }
}