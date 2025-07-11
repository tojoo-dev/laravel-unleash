<?php

use Illuminate\Support\Facades\Route;
use Tojoo\Unleash\Cache\CacheBridge;
use Tojoo\Unleash\Cache\CacheHandler;
use Tojoo\Unleash\Facades\Unleash as UnleashFacade;
use Tojoo\Unleash\Providers\UnleashContextProvider;
use Tojoo\Unleash\Providers\UnleashStrategiesProvider;
use Tojoo\Unleash\Unleash;

beforeEach(function () {
    // Set up a test route for middleware testing
    Route::get('/test-feature-route', function () {
        return response('Feature is enabled!');
    })->middleware('feature:test-feature');

    Route::get('/disabled-feature-route', function () {
        return response('This should not be accessible');
    })->middleware('feature:disabled-feature');
});

it('can create all service instances from config', function () {
    $cacheHandler = app(config('unleash.cache.handler'));
    expect($cacheHandler)->toBeInstanceOf(CacheHandler::class);

    $strategiesProvider = app(config('unleash.strategy_provider'));
    expect($strategiesProvider)->toBeInstanceOf(UnleashStrategiesProvider::class);

    $contextProvider = app(config('unleash.context_provider'));
    expect($contextProvider)->toBeInstanceOf(UnleashContextProvider::class);
});

it('middleware allows access when feature is enabled', function () {
    $unleash = Mockery::mock(Unleash::class);
    $unleash->shouldReceive('isEnabled')
        ->with('test-feature')
        ->once()
        ->andReturn(true);

    $this->app->instance(Unleash::class, $unleash);

    $response = $this->get('/test-feature-route');

    expect($response->getStatusCode())->toBe(200);
    expect($response->getContent())->toBe('Feature is enabled!');
});

it('middleware blocks access when feature is disabled', function () {
    $unleash = Mockery::mock(Unleash::class);
    $unleash->shouldReceive('isEnabled')
        ->with('disabled-feature')
        ->once()
        ->andReturn(false);

    $this->app->instance(Unleash::class, $unleash);

    $response = $this->get('/disabled-feature-route');

    expect($response->getStatusCode())->toBe(404);
});

it('facade works correctly', function () {
    $unleash = Mockery::mock(Unleash::class);
    $unleash->shouldReceive('isEnabled')
        ->with('test-feature')
        ->once()
        ->andReturn(true);

    $unleash->shouldReceive('getFeatures')
        ->withNoArgs()
        ->once()
        ->andReturn(['toggles' => []]);

    $this->app->instance(Unleash::class, $unleash);

    expect(UnleashFacade::isEnabled('test-feature'))->toBeTrue();
    expect(UnleashFacade::getFeatures())->toEqual(['toggles' => []]);
});

it('cache bridge integrates with laravel cache', function () {
    $bridge = new CacheBridge();

    $bridge->set('integration-test', 'value', 60);
    expect($bridge->get('integration-test'))->toBe('value');
    expect($bridge->has('integration-test'))->toBeTrue();

    $bridge->delete('integration-test');
    expect($bridge->has('integration-test'))->toBeFalse();
});

it('strategies provider returns valid strategies', function () {
    $provider = new UnleashStrategiesProvider();
    $strategies = $provider->getStrategies();

    expect($strategies)->toBeArray();
    expect($strategies)->not->toBeEmpty();

    foreach ($strategies as $strategy) {
        expect($strategy)->toBeInstanceOf(\Unleash\Client\Strategy\AbstractStrategyHandler::class);
    }
});

it('context provider creates proper context', function () {
    $provider = new UnleashContextProvider();
    $context = $provider->getContext();

    expect($context)->toBeInstanceOf(\Unleash\Client\Configuration\Context::class);
});

it('config values are properly set', function () {
    expect(config('unleash.enabled'))->toBeTrue();
    expect(config('unleash.url'))->toBe('https://test.unleash.io');
    expect(config('unleash.instance_id'))->toBe('test-instance');
    expect(config('unleash.environment'))->toBe('test');
    expect(config('unleash.cache.enabled'))->toBeTrue();
    expect(config('unleash.cache.ttl'))->toBe(30);
});

afterEach(function () {
    Mockery::close();
});
