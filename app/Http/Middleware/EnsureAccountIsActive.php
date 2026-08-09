<?php

namespace App\Http\Middleware;

use App\Enums\OfficeSeasonStatus;
use App\Support\CurrentOfficeContextResolver;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Replaces Laravel's built-in `verified` middleware for the office portal —
 * `verified` checks email_verified_at, but this product verifies the phone
 * number during activation, not the email. This checks that the user has an
 * active membership whose current-season office_season is actually Active.
 */
class EnsureAccountIsActive
{
    public function __construct(
        private readonly CurrentOfficeContextResolver $contextResolver,
    ) {}

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        try {
            $context = $this->contextResolver->resolve($user);
        } catch (ModelNotFoundException) {
            abort(403);
        }

        if ($context->officeSeason->status !== OfficeSeasonStatus::Active) {
            abort(403);
        }

        return $next($request);
    }
}
