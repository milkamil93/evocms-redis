<?php namespace EvolutionCMS\Redis;

use EvolutionCMS\ServiceProvider;

class RedisServiceProvider extends ServiceProvider
{
    protected $namespace = 'redis';

    public function register()
    {
        $this->loadPluginsFrom(dirname(__DIR__) . '/plugins/');
    }
}