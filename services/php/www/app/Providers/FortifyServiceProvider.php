<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

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
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        // Rate limiting for two-factor authentication
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // --- CUSTOM LOGIN RESPONSE FOR UNITY INTEGRATION ---
        // We bind our custom implementation to the Fortify LoginResponse Contract
        $this->app->singleton(LoginResponseContract::class, function ($app) {
            return new class implements LoginResponseContract {
                /**
                 * Create an HTTP response that represents the object.
                 *
                 * @param  \Illuminate\Http\Request  $request
                 * @return \Symfony\Component\HttpFoundation\Response
                 */
                public function toResponse($request)
                {
                    $user = $request->user();
                    
                    // Generate a new Sanctum token for the Unity client
                    // You could optionally revoke old tokens here: $user->tokens()->delete();
                    $token = $user->createToken('unity_token')->plainTextToken;

                    // If the request expects JSON (XHR/Fetch with Accept: application/json)
                    if ($request->wantsJson()) {
                        return new JsonResponse([
                            'two_factor' => false,
                            'unity_token' => $token
                        ], 200);
                    }

                    // Default behavior for standard web form submissions
                    return redirect()->intended(config('fortify.home'));
                }
            };
        });
    }
}
