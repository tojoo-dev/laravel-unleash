<?php

use Tojoo\Unleash\Providers\UnleashStrategiesProvider;
use Unleash\Client\Strategy\AbstractStrategyHandler;

it('returns array of strategy handlers', function () {
    $provider = new UnleashStrategiesProvider();
    $strategies = $provider->getStrategies();

    expect($strategies)->toBeArray();
    expect($strategies)->not->toBeEmpty();

    foreach ($strategies as $strategy) {
        expect($strategy)->toBeInstanceOf(AbstractStrategyHandler::class);
    }
});

it('includes default strategy handlers', function () {
    $provider = new UnleashStrategiesProvider();
    $strategies = $provider->getStrategies();

    $strategyNames = array_map(function ($strategy) {
        return get_class($strategy);
    }, $strategies);

    expect($strategyNames)->toContain('Unleash\Client\Strategy\DefaultStrategyHandler');
    expect($strategyNames)->toContain('Unleash\Client\Strategy\IpAddressStrategyHandler');
    expect($strategyNames)->toContain('Unleash\Client\Strategy\UserIdStrategyHandler');
    expect($strategyNames)->toContain('Unleash\Client\Strategy\GradualRolloutStrategyHandler');
    expect($strategyNames)->toContain('Unleash\Client\Strategy\ApplicationHostnameStrategyHandler');
});

it('filters out non-AbstractStrategyHandler instances', function () {
    $provider = new UnleashStrategiesProvider();
    $strategies = $provider->getStrategies();

    // All strategies should be instances of AbstractStrategyHandler
    foreach ($strategies as $strategy) {
        expect($strategy)->toBeInstanceOf(AbstractStrategyHandler::class);
    }
});
