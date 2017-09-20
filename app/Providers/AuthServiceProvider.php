<?php

namespace App\Providers;

use App\Models\Post;
use App\Morning;
use App\Policies\MorningPolicy;
use App\Policies\OpenfilePolicy;
use App\Policies\ReportPolicy;
use App\Policies\PostPolicy;
use App\Policies\TestPolicy;
use App\Report;
use App\Test;
use App\Upload;
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
        Upload::class => OpenfilePolicy::class,
        Test::class => TestPolicy::class,
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
