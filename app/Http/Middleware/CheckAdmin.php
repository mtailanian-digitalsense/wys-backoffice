<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = 'platform.index')
    {
        if (Auth::check() && !Auth::User()->hasAccess($permission)) {
            Auth::logout();
            Toast::error('Usuario no posee las credenciales necesarias.')
                ->autoHide(false);
            //return redirect()->to('platform.login')->with('username', 'User has no administrator access');
        }
        return $next($request);

    }

}
