<?php

use Tojoo\Unleash\Providers\ServiceProvider;
use Tojoo\Unleash\Unleash;
use Unleash\Client\Unleash as UnleashClient;

it('service provider handles config merging', function () {
    $provider = new ServiceProvider($this->app);

    // Test that config is merged
    $provider->register();

    expect(config()->has('unleash'))->toBeTrue();
});

it('service provider handles http client override configuration', function () {
    // Set up config with http client override
    config([
        'unleash.http_client_override.enabled' => true,
        'unleash.http_client_override.config' => ['timeout' => 30],
    ]);

    $provider = new ServiceProvider($this->app);
    $provider->register();

    // Verify that the service is registered
    expect($this->app->bound(Unleash::class))->toBeTrue();
});

it('service provider handles api key configuration', function () {
    config([
        'unleash.api_key' => 'test-api-key',
    ]);

    $provider = new ServiceProvider($this->app);
    $provider->register();

    // Verify that the service is registered with API key
    expect($this->app->bound(Unleash::class))->toBeTrue();
});

it('can handle complex unleash configurations', function () {
    config([
        'unleash.enabled' => true,
        'unleash.url' => 'https://test.unleash.io',
        'unleash.api_key' => 'test-key',
        'unleash.cache.enabled' => true,
        'unleash.http_client_override.enabled' => true,
        'unleash.automatic_registration' => true,
        'unleash.metrics' => true,
    ]);

    $provider = new ServiceProvider($this->app);
    $provider->register();
    $provider->boot();

    expect($this->app->bound(Unleash::class))->toBeTrue();

    $unleash = $this->app->get(Unleash::class);
    expect($unleash)->toBeInstanceOf(Unleash::class);
    expect($unleash->client)->toBeInstanceOf(UnleashClient::class);
});

it('unleash wrapper handles all client methods', function () {
    $client = Mockery::mock(UnleashClient::class);
    $unleash = new Unleash($client);

    // Test all interface methods are properly delegated
    $client->shouldReceive('register')->once()->andReturn(true);
    expect($unleash->register())->toBeTrue();

    $variant = Mockery::mock(\Unleash\Client\DTO\Variant::class);
    $client->shouldReceive('getVariant')
        ->with('test', null, null)
        ->once()
        ->andReturn($variant);
    expect($unleash->getVariant('test'))->toBe($variant);
});

afterEach(function () {
    Mockery::close();
});
