<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Providers\RouteServiceProvider;

class RedirectIfAuthenticated
{
    /**
     * The callback that should be used to generate the authentication redirect path.
     *
     * @var callable|null
     */
    protected static $redirectToCallback;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                if ($guard === 'farmer') {
                    return redirect()->route('farmer.dashboard');
                }
                if ($guard === 'processor') {
                    return redirect()->route('processor.dashboard');
                }
                if ($guard === 'distributor') {
                    return redirect()->route('distributor.dashboard');
                }
                if ($guard === 'retailer') {
                    return redirect()->route('retailer.dashboard');
                }
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if($request->routeIs('admin.login')){
            return route('admin.dashboard');
        }

        if($request->routeIs('farmer.login')){
            return route('farmer.dashboard');
        }

        if($request->routeIs('processor.login')){
            return route('processor.dashboard');
        }

        if($request->routeIs('distributor.login')){
            return route('distributor.dashboard');
        }

        if($request->routeIs('retailer.login')){
            return route('retailer.dashboard');
        }


        return static::$redirectToCallback
            ? call_user_func(static::$redirectToCallback, $request)
            : $this->defaultRedirectUri();
    }

    /**
     * Get the default URI the user should be redirected to when they are authenticated.
     */
    protected function defaultRedirectUri(): string
    {
        foreach (['dashboard', 'home'] as $uri) {
            if (Route::has($uri)) {
                return route($uri);
            }
        }

        $routes = Route::getRoutes()->get('GET');

        foreach (['dashboard', 'home'] as $uri) {
            if (isset($routes[$uri])) {
                return '/'.$uri;
            }
        }

        return '/';
    }

    /**
     * Specify the callback that should be used to generate the redirect path.
     *
     * @param  callable  $redirectToCallback
     * @return void
     */
    public static function redirectUsing(callable $redirectToCallback)
    {
        static::$redirectToCallback = $redirectToCallback;
    }
}
