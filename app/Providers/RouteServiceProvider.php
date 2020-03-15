<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $adminNamespace = 'App\Http\Controllers\Admin'; // 管理后台

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $list = explode('.', request()->getHost());
        switch ($list[0]){
            case "api": // API接口
                $this->mapApiRoutes();
                break;
            case "www": // 默认页
                $this->mapWebRoutes();
                break;
            case "admin": // 管理后台
                $this->mapAdminRoutes();
                break;
            default: // 默认页
                $this->mapWebRoutes();
        }
        unset($list);

        // 通用路由
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/route.php'));

        // 回退路由
        $this->fallback(function () {
            return error();
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * 管理后台
     */
    protected function mapAdminRoutes()
    {
        Route::name('admin.')
            ->middleware('admin')
            ->namespace($this->adminNamespace)
            ->group(base_path('routes/admin.php'));
    }
}
