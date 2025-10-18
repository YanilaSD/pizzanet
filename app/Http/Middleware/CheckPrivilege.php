<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPrivilege
{
    public function handle($request, Closure $next, $privilege)
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Acceso denegado');
        }

        $hasPrivilege = $user->roles()->whereHas('privilegios', function ($query) use ($privilege) {
            $query->where('slug', $privilege);
        })->exists();

        if (!$hasPrivilege) {
            abort(403, 'Acceso denegado');
        }

        return $next($request);
    }

}
