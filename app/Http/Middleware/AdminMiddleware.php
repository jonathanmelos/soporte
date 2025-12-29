<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $allowed = collect(explode(',', env('ADMIN_EMAILS', '')))->map(fn($e) => trim($e))->filter();

        if ($user && ($user->is_admin ?? false || $allowed->contains($user->email))) {
            return $next($request);
        }

        abort(403);
    }
}
