<?php

namespace App\Providers;

use App\Models\Menuoperations;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        Paginator::useBootstrap();
        $requestMethod = $request->method();
        $currentUrl = $request->path();
        $pathSegments = explode('/', ltrim($currentUrl, "/"));
        $current_page =  $pathSegments[0];
        $layout_menu_exist = Menuoperations::where('url_path', $current_page)->value('menu_path');
        if (trim(request()->path()) == '/' || $current_page == 'home') {
            view()->share('path', 'Dashboard (Beta UI)');
        } else {

            view()->share('path', $current_page);
        }

        view()->share('layout_menu_exist', $layout_menu_exist);

        view()->share('requestMethod', $requestMethod);
    }
}
