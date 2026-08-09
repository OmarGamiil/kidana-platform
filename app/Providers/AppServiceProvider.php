<?php

namespace App\Providers;

use App\Contracts\OtpSender;
use App\Models\ActivationInvite;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OtpSender::class, function () {
            $driver = config('otp.driver');
            $class = config("otp.drivers.{$driver}.class");

            return $this->app->make($class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureRateLimiters();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * The OTP verify/resend routes don't carry an email or delegate number, so
     * they're keyed by the activation invite's public id + IP instead of an
     * identifier — a normalized-identifier key (as used for login) would not
     * apply here. The invite is resolved from the activation session by the
     * EnsureActivationSession middleware (these routes carry no {activationInvite}
     * route parameter — the raw token only appears in the URL on the first hop),
     * so it's read from the request attribute that middleware sets, not
     * $request->route().
     */
    protected function configureRateLimiters(): void
    {
        RateLimiter::for('otp', function (Request $request) {
            $invite = $request->attributes->get('activationInvite');
            $key = ($invite instanceof ActivationInvite ? $invite->public_id : 'unknown').'|'.$request->ip();

            return Limit::perMinutes(1, 10)->by($key);
        });
    }
}
