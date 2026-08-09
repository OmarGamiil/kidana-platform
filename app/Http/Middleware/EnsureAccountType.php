<?php

namespace App\Http\Middleware;

use App\Enums\UserAccountType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Generalized so ServiceProvider/KidanaTeam route groups can reuse the same
 * primitive later — e.g. middleware('account-type:service_provider').
 */
class EnsureAccountType
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $accountType): Response
    {
        $user = $request->user();

        if (! $user || $user->account_type !== UserAccountType::from($accountType)) {
            abort(403);
        }

        return $next($request);
    }
}
