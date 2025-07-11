<?php

namespace Tojoo\Unleash\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tojoo\Unleash\Providers\ServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // Register the middleware alias
        $app['router']->aliasMiddleware('feature', \Tojoo\Unleash\Middleware\CheckFeature::class);

        config()->set('unleash.enabled', true);
        config()->set('unleash.url', 'https://test.unleash.io');
        config()->set('unleash.instance_id', 'test-instance');
        config()->set('unleash.environment', 'test');
        config()->set('unleash.automatic_registration', false);
        config()->set('unleash.metrics', false);
        config()->set('unleash.cache.enabled', true);
        config()->set('unleash.cache.ttl', 30);
        config()->set('unleash.cache.handler', \Tojoo\Unleash\Cache\CacheHandler::class);
        config()->set('unleash.strategy_provider', \Tojoo\Unleash\Providers\UnleashStrategiesProvider::class);
        config()->set('unleash.context_provider', \Tojoo\Unleash\Providers\UnleashContextProvider::class);
    }
}
