<?php

use Illuminate\Support\Facades\Cache;
use Tojoo\Unleash\Cache\CacheBridge;

beforeEach(function () {
    Cache::flush();
});

it('can get and set cache values', function () {
    $bridge = new CacheBridge();

    $result = $bridge->set('test-key', 'test-value', 60);
    expect($result)->toBeTrue();

    $value = $bridge->get('test-key');
    expect($value)->toBe('test-value');
});

it('returns default value when key does not exist', function () {
    $bridge = new CacheBridge();

    $value = $bridge->get('non-existent-key', 'default-value');
    expect($value)->toBe('default-value');
});

it('can delete cache values', function () {
    $bridge = new CacheBridge();

    $bridge->set('test-key', 'test-value');
    expect($bridge->has('test-key'))->toBeTrue();

    $result = $bridge->delete('test-key');
    expect($result)->toBeTrue();
    expect($bridge->has('test-key'))->toBeFalse();
});

it('can clear all cache', function () {
    $bridge = new CacheBridge();

    $bridge->set('key1', 'value1');
    $bridge->set('key2', 'value2');

    $result = $bridge->clear();
    expect($result)->toBeTrue();

    expect($bridge->has('key1'))->toBeFalse();
    expect($bridge->has('key2'))->toBeFalse();
});

it('can get multiple values', function () {
    $bridge = new CacheBridge();

    $bridge->set('key1', 'value1');
    $bridge->set('key2', 'value2');

    $values = $bridge->getMultiple(['key1', 'key2', 'key3'], 'default');

    expect($values)->toEqual([
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'default',
    ]);
});

it('can set multiple values', function () {
    $bridge = new CacheBridge();

    $result = $bridge->setMultiple([
        'key1' => 'value1',
        'key2' => 'value2',
    ], 60);

    expect($result)->toBeTrue();
    expect($bridge->get('key1'))->toBe('value1');
    expect($bridge->get('key2'))->toBe('value2');
});

it('can delete multiple values', function () {
    $bridge = new CacheBridge();

    $bridge->set('key1', 'value1');
    $bridge->set('key2', 'value2');
    $bridge->set('key3', 'value3');

    $result = $bridge->deleteMultiple(['key1', 'key2']);
    expect($result)->toBeTrue();

    expect($bridge->has('key1'))->toBeFalse();
    expect($bridge->has('key2'))->toBeFalse();
    expect($bridge->has('key3'))->toBeTrue();
});

it('can check if key exists', function () {
    $bridge = new CacheBridge();

    expect($bridge->has('non-existent'))->toBeFalse();

    $bridge->set('existing-key', 'value');
    expect($bridge->has('existing-key'))->toBeTrue();
});
