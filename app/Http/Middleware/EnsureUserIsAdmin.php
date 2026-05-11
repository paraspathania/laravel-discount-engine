<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RBAC guard for admin-only routes.
 *
 * Behaviour:
 *  - Unauthenticated users  → redirect to /login (handled upstream by 'auth' middleware)
 *  - Authenticated customer → 403 Forbidden (JSON for API, abort() for web)
 *  - Authenticated admin    → passes through
 *
 * Always stack AFTER the 'auth' middleware so $request->user() is guaranteed non-null.
 *
 * Route usage:
 *   Route::middleware(['auth', 'admin'])->prefix('admin')->group(...)
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Guard: must be authenticated (auth middleware should run first)
        if (! $request->user()) {
            abort(401, 'Unauthenticated.');
        }

        // Role check — only 'admin' role passes
        if ($request->user()->role !== 'admin') {
            // Return JSON for API/AJAX requests, HTTP redirect for web
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden. Admin access required.',
                ], Response::HTTP_FORBIDDEN);
            }

            abort(403, 'Forbidden. Admin access required.');
        }

        return $next($request);
    }
}
