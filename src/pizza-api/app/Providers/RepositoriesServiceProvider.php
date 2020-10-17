<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\IRepository;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;


class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(IRepository::class, function($app){

            $action = request()->route()->getAction();
            $controller = explode('Controller',explode('@', class_basename($action['controller']))[0])[0];

            $repository = '\\App\\Repositories\\'.$controller."Repository";

            if(class_exists($repository))
            {
                $model = '\\App\\Models\\'.$controller;

                if(class_exists($model))
                return new $repository(new $model());
            }
        });
        $this->bindSearchClient();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->build();
        });
    }
}
