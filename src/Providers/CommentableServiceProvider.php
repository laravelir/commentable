<?php

namespace Laravelir\Commentable\Providers;

use App\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravelir\Commentable\Facades\Commentable;
use Laravelir\Commentable\Console\Commands\InstallPackageCommand;
use Laravelir\Commentable\Console\Commands\InstallCommentableCommand;

class CommentableServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . "/../../config/commentable.php", 'commentable');

        $this->registerFacades();
    }

    public function boot(): void
    {
        $this->registerCommands();
        $this->registerConfig();
        $this->registerMigrations();
        // $this->registerRoutes();
        // $this->registerBladeDirectives();
        // $this->registerLivewireComponents();
    }

    private function registerFacades()
    {
        $this->app->bind('commentable', function ($app) {
            return new Commentable();
        });
    }

    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                InstallPackageCommand::class,
            ]);
        }
    }

    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../../config/commentable.php' => config_path('commentable.php')
        ], 'commentable-config');
    }

    protected function registerMigrations()
    {
        $timestamp = date('Y_m_d_His', time());
        $this->publishes([
            __DIR__ . '/../database/migrations/create_commentable_table.stub.php' => database_path() . "/migrations/{$timestamp}_commentable_table.php",
        ], 'commentable-migrations');
    }

    // protected function definePermissions()
    // {
    //     foreach (Config::get('comments.permissions', []) as $permission => $policy) {
    //         Gate::define($permission, $policy);
    //     }
    // }

    // private function registerRoutes()
    // {
    //     Route::group($this->routeConfiguration(), function () {
    //         $this->loadRoutesFrom(__DIR__ . '/../../routes/commentable.php', 'commentable-routes');
    //     });
    // }

    // private function routeConfiguration()
    // {
    //     return [
    //         'prefix' => config('commentable.routes.prefix'),
    //         'middleware' => config('commentable.routes.middleware'),
    //         'as' => 'commentable.'
    //     ];
    // }
}
