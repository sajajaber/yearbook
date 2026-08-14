<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // the ... means "collect any number of extra arguments into an array called $roles"
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // $request->user() gets the current logged-in user
        if (! $request->user() || ! in_array($request->user()->role->role_name, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}