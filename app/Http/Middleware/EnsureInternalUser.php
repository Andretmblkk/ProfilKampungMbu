<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInternalUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless(
            $user
            && in_array($user->role, ['administrator', 'operator'], true)
            && $user->status === 'aktif',
            403
        );

        return $next($request);
    }
}
