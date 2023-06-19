<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton('elasticsearch', function ($app) {
            $hosts = [
                env('ELASTICSEARCH_HOSTS', '127.0.0.1:9200'),
            ];
            return ClientBuilder::create()
                ->setHosts($hosts)
                ->build();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
