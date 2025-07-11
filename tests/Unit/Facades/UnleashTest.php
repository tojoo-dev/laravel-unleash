<?php

use Tojoo\Unleash\Facades\Unleash as UnleashFacade;
use Tojoo\Unleash\Unleash;

it('resolves to the correct class', function () {
    // Test the facade accessor using reflection
    $reflection = new ReflectionClass(UnleashFacade::class);
    $method = $reflection->getMethod('getFacadeAccessor');
    $method->setAccessible(true);

    $accessor = $method->invoke(null); // Static method call

    expect($accessor)->toBe(Unleash::class);
});

it('can call isEnabled through facade', function () {
    $unleash = Mockery::mock(Unleash::class);
    $unleash->shouldReceive('isEnabled')
        ->with('test-feature')
        ->once()
        ->andReturn(true);

    $this->app->instance(Unleash::class, $unleash);

    $result = UnleashFacade::isEnabled('test-feature');

    expect($result)->toBeTrue();
});

afterEach(function () {
    Mockery::close();
});
