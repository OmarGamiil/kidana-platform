<?php

namespace App\Http\Controllers\Activation;

use App\Actions\Activation\CompleteActivationAction;
use App\Actions\Activation\DefineOfficeAccountAction;
use App\Actions\Activation\ResendOtpAction;
use App\Actions\Activation\VerifyOtpAction;
use App\Exceptions\Activation\ActivationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activation\CreatePasswordRequest;
use App\Http\Requests\Activation\DefineAccountRequest;
use App\Http\Requests\Activation\VerifyOtpRequest;
use App\Models\ActivationInvite;
use App\Support\ActivationFlowGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Thin controller: validates via FormRequests, delegates every business
 * decision to an Action or the ActivationFlowGuard, and turns the result (or
 * a caught ActivationException) into a redirect/Inertia response. No
 * business logic lives here.
 */
class ActivationController extends Controller
{
    public function __construct(
        private readonly ActivationFlowGuard $guard,
    ) {}

    public function start(Request $request, ActivationInvite $activationInvite, string $token): RedirectResponse
    {
        try {
            $this->guard->ensureTokenMatches($activationInvite, $token);
            $this->guard->ensureInviteIsUsable($activationInvite);
        } catch (ActivationException $e) {
            return $this->redirectWithError($e);
        }

        $request->session()->put('activation_invite_id', $activationInvite->id);
        $request->session()->regenerate();

        return to_route('activation.account.show');
    }

    public function showAccount(Request $request): Response|RedirectResponse
    {
        $invite = $this->currentInvite($request);

        try {
            $this->guard->ensureAccountStepIsAllowed($invite);
        } catch (ActivationException $e) {
            return $this->redirectWithError($e);
        }

        return Inertia::render('office/activation/Account', [
            'officeName' => $invite->officeSeason->office->name,
        ]);
    }

    public function storeAccount(DefineAccountRequest $request, DefineOfficeAccountAction $action): RedirectResponse
    {
        $invite = $this->currentInvite($request);

        try {
            $action->handle(
                invite: $invite,
                mobileNumber: $request->string('mobile_number')->toString(),
                officialEmail: $request->string('official_email')->toString(),
                termsVersion: (string) config('activation.current_terms_version'),
            );
        } catch (ActivationException $e) {
            return $this->redirectWithError($e);
        }

        return to_route('activation.verify.show');
    }

    public function showVerify(Request $request): Response|RedirectResponse
    {
        $invite = $this->currentInvite($request);

        try {
            $this->guard->ensureOtpStepIsAllowed($invite);
        } catch (ActivationException $e) {
            return $this->redirectWithError($e);
        }

        $mobileNumber = $invite->officeSeason->mobile_number;

        return Inertia::render('office/activation/Verify', [
            'mobileNumberHint' => $mobileNumber ? '••'.Str::substr($mobileNumber, -4) : null,
            'otpLength' => (int) config('activation.otp_length'),
            'resendCooldownSeconds' => (int) config('activation.otp_resend_cooldown_seconds'),
            'debugCode' => session('otp_debug_code'),
        ]);
    }

    public function storeVerify(VerifyOtpRequest $request, VerifyOtpAction $action): RedirectResponse
    {
        $invite = $this->currentInvite($request);

        try {
            $action->handle($invite, $request->string('code')->toString());
        } catch (ActivationException $e) {
            return $this->redirectWithError($e);
        }

        return to_route('activation.password.show');
    }

    public function resendVerify(Request $request, ResendOtpAction $action): RedirectResponse
    {
        $invite = $this->currentInvite($request);

        try {
            $action->handle($invite);
        } catch (ActivationException $e) {
            return $this->redirectWithError($e);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('activation.otp_resent')]);

        return back();
    }

    public function showPassword(Request $request): Response|RedirectResponse
    {
        $invite = $this->currentInvite($request);

        try {
            $this->guard->ensurePasswordStepIsAllowed($invite);
        } catch (ActivationException $e) {
            return $this->redirectWithError($e);
        }

        return Inertia::render('office/activation/Password');
    }

    public function storePassword(CreatePasswordRequest $request, CompleteActivationAction $action): RedirectResponse
    {
        $invite = $this->currentInvite($request);

        try {
            $user = $action->handle($invite, $request->string('password')->toString());
        } catch (ActivationException $e) {
            return $this->redirectWithError($e);
        }

        $request->session()->forget('activation_invite_id');

        // Manual login (outside the Action's DB transaction, and outside any
        // transaction here too) — must explicitly regenerate the session,
        // Fortify's own login pipeline does this automatically but this path
        // doesn't go through it.
        Auth::login($user);
        $request->session()->regenerate();

        return to_route('dashboard');
    }

    private function currentInvite(Request $request): ActivationInvite
    {
        /** @var ActivationInvite */
        return $request->attributes->get('activationInvite');
    }

    private function redirectWithError(ActivationException $e): RedirectResponse
    {
        Inertia::flash('toast', ['type' => 'error', 'message' => __($e->translationKey())]);

        // Redirects to the marketing homepage rather than the not-yet-built
        // Access role-picker screen (Package 3) — revisit once that route exists.
        return to_route('home');
    }
}
