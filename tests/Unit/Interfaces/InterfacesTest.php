<?php

use Tojoo\Unleash\Interfaces\UnleashCacheHandlerInterface;
use Tojoo\Unleash\Interfaces\UnleashStrategiesProviderInterface;

it('cache handler interface defines init method', function () {
    $reflection = new ReflectionClass(UnleashCacheHandlerInterface::class);

    expect($reflection->isInterface())->toBeTrue();
    expect($reflection->hasMethod('init'))->toBeTrue();

    $method = $reflection->getMethod('init');
    expect($method->isPublic())->toBeTrue();
});

it('strategies provider interface defines getStrategies method', function () {
    $reflection = new ReflectionClass(UnleashStrategiesProviderInterface::class);

    expect($reflection->isInterface())->toBeTrue();
    expect($reflection->hasMethod('getStrategies'))->toBeTrue();

    $method = $reflection->getMethod('getStrategies');
    expect($method->isPublic())->toBeTrue();
});
