<?php

use Tojoo\Unleash\Facades\Unleash as UnleashFacade;
use Tojoo\Unleash\Unleash;

it('can resolve unleash from container', function () {
    $unleash = app(Unleash::class);

    expect($unleash)->toBeInstanceOf(Unleash::class);
});

it('can use unleash facade', function () {
    $unleash = Mockery::mock(Unleash::class);
    $unleash->shouldReceive('isEnabled')
        ->with('test-feature')
        ->once()
        ->andReturn(true);

    $this->app->instance(Unleash::class, $unleash);

    $result = UnleashFacade::isEnabled('test-feature');

    expect($result)->toBeTrue();
});

it('middleware blocks request when feature is disabled', function () {
    $unleash = Mockery::mock(Unleash::class);
    $unleash->shouldReceive('isEnabled')
        ->with('disabled-feature')
        ->once()
        ->andReturn(false);

    $this->app->instance(Unleash::class, $unleash);

    $response = $this->get('/test-route');

    expect($response->getStatusCode())->toBe(404);
})->skip('Requires route setup');

it('config is properly loaded', function () {
    expect(config('unleash.enabled'))->toBeTrue();
    expect(config('unleash.url'))->toBe('https://test.unleash.io');
    expect(config('unleash.instance_id'))->toBe('test-instance');
});

afterEach(function () {
    Mockery::close();
});
