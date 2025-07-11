<?php

use Tojoo\Unleash\Cache\CacheBridge;

it('returns true for deleteMultiple method', function () {
    $bridge = new CacheBridge();

    $bridge->set('key1', 'value1');
    $bridge->set('key2', 'value2');

    $result = $bridge->deleteMultiple(['key1', 'key2']);

    expect($result)->toBeTrue();
    expect($bridge->has('key1'))->toBeFalse();
    expect($bridge->has('key2'))->toBeFalse();
});

it('handles cache bridge return values correctly', function () {
    $bridge = new CacheBridge();

    // Test set returns true
    expect($bridge->set('test', 'value'))->toBeTrue();

    // Test setMultiple returns true
    expect($bridge->setMultiple(['test2' => 'value2']))->toBeTrue();

    // Test delete returns true/false correctly
    expect($bridge->delete('test'))->toBeTrue();
    expect($bridge->delete('non-existent'))->toBe(false);

    // Test clear returns true
    expect($bridge->clear())->toBeTrue();
});
