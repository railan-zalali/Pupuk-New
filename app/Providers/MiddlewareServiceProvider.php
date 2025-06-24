<?php

namespace App\Providers;

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\ServiceProvider;

class MiddlewareServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $router = $this->app['router'];

        // Register middleware aliases
        $router->aliasMiddleware('role', CheckRole::class);
        $router->aliasMiddleware('permission', CheckPermission::class);
    }
}
