<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\Todo;
use App\Observers\TaskObserver;
use App\Observers\TodoObserver;
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
    public function boot()
    {
        Todo::observe(TodoObserver::class);
        Task::observe(TaskObserver::class);
    }
}
