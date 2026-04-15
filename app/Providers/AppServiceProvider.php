<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\News;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
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
        Model::unguard();
        
        Paginator::useBootstrap();
        
        View::composer('*', function ($view) {
            $menus = Menu::whereNull('parent_id')
                ->where('active', 1)
                ->with('children')
                ->orderBy('sort')
                ->get();

                // dd($menus);
    
            $view->with('menus', $menus);

            // $news = News::where('is_active', 1)
            //     ->orderBy('publish_at', 'desc')
            //     ->latest()
            //     ->take(15)
            //     ->get();

            $news = News::where('is_active', 1)
                ->whereHas('menu', function ($q) {
                    $q->where('type', 'news');
                })
                ->orderBy('publish_at', 'desc')
                ->latest('publish_at')
                ->take(15)
                ->get();

            $view->with('latestNews', $news);
        });
    }
}
