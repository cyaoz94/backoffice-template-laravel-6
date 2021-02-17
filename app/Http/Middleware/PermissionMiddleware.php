<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    /**
     * Custom permission middleware to check role permissions
     * @param $request
     * @param Closure $next
     * @param $permission
     * @param null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        if (app('auth')->guard($guard)->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if (!(in_array($permission, app('auth')->guard($guard)->user()->getAllPermissions()->pluck('name')->toArray()))) {
                throw UnauthorizedException::forPermissions($permissions);
            }
        }

        return $next($request);
    }
}
