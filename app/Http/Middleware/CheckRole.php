<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user) {
            abort(401);
        }

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        \Log::warning('Forbidden access', [
            'user_id' => $user->id,
            'roles_required' => $roles,
            'user_roles' => $user->roles()->pluck('name')->toArray()
        ]);

        abort(403);
    }
}