<?php namespace EvolutionCMS\Redis;

use EvolutionCMS\ServiceProvider;

class EvoRedisServiceProvider extends ServiceProvider
{
    protected $namespace = 'redis';

    public function register()
    {
        $this->loadPluginsFrom(dirname(__DIR__) . '/plugins/');
    }

    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/publish' => MODX_BASE_PATH
        ]);
    }
}