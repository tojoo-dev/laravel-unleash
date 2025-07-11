<?php

use Illuminate\Support\Facades\Auth;
use Tojoo\Unleash\Providers\UnleashContextProvider;
use Unleash\Client\Configuration\Context;

it('returns context instance', function () {
    $provider = new UnleashContextProvider();
    $context = $provider->getContext();

    expect($context)->toBeInstanceOf(Context::class);
});

it('includes user id when authenticated', function () {
    // Mock an authenticated user
    Auth::shouldReceive('check')->once()->andReturn(true);
    Auth::shouldReceive('id')->once()->andReturn(123);

    $provider = new UnleashContextProvider();
    $context = $provider->getContext();

    expect($context->getCurrentUserId())->toBe('123');
});

it('does not include user id when not authenticated', function () {
    Auth::shouldReceive('check')->once()->andReturn(false);

    $provider = new UnleashContextProvider();
    $context = $provider->getContext();

    expect($context->getCurrentUserId())->toBeNull();
});

afterEach(function () {
    Mockery::close();
});
