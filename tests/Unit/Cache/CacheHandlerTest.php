<?php

use Tojoo\Unleash\Cache\CacheBridge;
use Tojoo\Unleash\Cache\CacheHandler;

it('creates a cache bridge instance', function () {
    $handler = new CacheHandler();
    $bridge = $handler->init();

    expect($bridge)->toBeInstanceOf(CacheBridge::class);
});
