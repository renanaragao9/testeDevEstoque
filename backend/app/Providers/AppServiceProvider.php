<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

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
        Collection::macro('paginateWithSort', function ($perPage = 15, $pageName = 'page', $page = null) {
            $page = $page ?: \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage($pageName);
            $items = $this->forPage($page, $perPage);
            return new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $this->count(),
                $perPage,
                $page,
                [
                    'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
