<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\UserStatus;
use App\Http\Controllers\MatchmakingController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;

// IMPORTANT: We must use the Contract (Interface) for the singleton to work correctly
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\JsonResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // Rate limiting for login attempts
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        // Rate limiting for two-factor authentication
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // --- CUSTOM LOGIN RESPONSE ---
        $this->app->singleton(LoginResponseContract::class, function ($app) {
            return new class implements LoginResponseContract {
                public function toResponse($request)
                {
                    if ($request->wantsJson()) {
                        return new JsonResponse([
                            'two_factor' => false,
                        ], 200);
                    }

                    return redirect()->intended(config('fortify.home'));
                }
            };
        });

        // --- LOGOUT EVENT ---
        Event::listen(Logout::class, function ($event) {
            if ($event->user) {

                $event->user->update(['status' => UserStatus::OFFLINE]);
                $event->user->tokens()->delete();
                app(MatchmakingController::class)->handleUserDisconnection($event->user);
                Log::info("User {$event->user->email} is now OFFLINE (Event based).");
            }
        });
    }
}
