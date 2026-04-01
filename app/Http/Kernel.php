<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\RoleMiddleware;
use Spatie\Permission\Middlewares\RoleMiddleware as SpatieRoleMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleOrPermissionMiddleware;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        // Өмнөх middleware-үүд
        // 'role' => SpatieRoleMiddleware::class,  // Спати package-ийн role middleware
        // 'role' => \App\Http\Middleware\RoleMiddleware::class, // Манай custom role middleware
        // 'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'role' => SpatieRoleMiddleware::class, // Custom RoleMiddleware
        'permission' => PermissionMiddleware::class,
        'role_or_permission' => RoleOrPermissionMiddleware::class,
        'checkRole' => \App\Http\Middleware\CheckRole::class,
    ];

    // protected $routeMiddleware = [
    //     'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    // ];
}
