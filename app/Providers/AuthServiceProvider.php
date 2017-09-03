<?php

namespace App\Providers;

use App\Http\Controllers\OpenfilesController;
use App\Models\Post;
use App\Morning;
use App\Policies\MorningPolicy;
use App\Policies\ReportPolicy;
use App\Policies\PostPolicy;
use App\Report;
use App\Upload;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        Morning::class => MorningPolicy::class,
        Report::class => ReportPolicy::class,
        Upload::class => OpenfilesController::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
