<?php

namespace App\Http\Middleware;

use App\Models\ActivationInvite;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards every activation step past the first token-bearing hop. The raw
 * token only ever appears in the URL once (see ActivationController::start);
 * from then on the invite is resolved from a short-lived activation session
 * value, and these headers prevent the token page itself from being cached
 * or leaking via a referrer header.
 */
class EnsureActivationSession
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $inviteId = $request->session()->get('activation_invite_id');
        $invite = $inviteId ? ActivationInvite::find($inviteId) : null;

        if (! $invite) {
            abort(404);
        }

        $request->attributes->set('activationInvite', $invite);

        $response = $next($request);

        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->set('Cache-Control', 'no-store');

        return $response;
    }
}
