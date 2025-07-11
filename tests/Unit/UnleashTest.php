<?php

use Tojoo\Unleash\Unleash;
use Unleash\Client\Configuration\Context;
use Unleash\Client\DTO\Feature;
use Unleash\Client\DTO\Variant;
use Unleash\Client\Repository\UnleashRepository;
use Unleash\Client\Unleash as UnleashClient;

it('can be instantiated with an unleash client', function () {
    $client = Mockery::mock(UnleashClient::class);
    $unleash = new Unleash($client);

    expect($unleash)->toBeInstanceOf(Unleash::class);
    expect($unleash->client)->toBe($client);
});

it('can set a new client', function () {
    $client1 = Mockery::mock(UnleashClient::class);
    $client2 = Mockery::mock(UnleashClient::class);

    $unleash = new Unleash($client1);
    $unleash->setClient($client2);

    expect($unleash->client)->toBe($client2);
});

it('checks if a feature is enabled', function () {
    $client = Mockery::mock(UnleashClient::class);
    $context = Mockery::mock(Context::class);

    $client->shouldReceive('isEnabled')
        ->with('test-feature', $context, false)
        ->once()
        ->andReturn(true);

    $unleash = new Unleash($client);
    $result = $unleash->isEnabled('test-feature', $context, false);

    expect($result)->toBeTrue();
});

it('returns default value when feature check throws exception', function () {
    $client = Mockery::mock(UnleashClient::class);

    $client->shouldReceive('isEnabled')
        ->with('test-feature', null, false)
        ->once()
        ->andThrow(new Exception('Connection failed'));

    $unleash = new Unleash($client);
    $result = $unleash->isEnabled('test-feature', null, false);

    expect($result)->toBeFalse();
});

it('returns default value true when feature check throws exception', function () {
    $client = Mockery::mock(UnleashClient::class);

    $client->shouldReceive('isEnabled')
        ->with('test-feature', null, true)
        ->once()
        ->andThrow(new Exception('Connection failed'));

    $unleash = new Unleash($client);
    $result = $unleash->isEnabled('test-feature', null, true);

    expect($result)->toBeTrue();
});

it('gets all features', function () {
    $client = Mockery::mock(UnleashClient::class);
    $repository = Mockery::mock(UnleashRepository::class);
    $feature1 = Mockery::mock(Feature::class);
    $feature2 = Mockery::mock(Feature::class);

    $feature1->shouldReceive('getName')->andReturn('feature1');
    $feature2->shouldReceive('getName')->andReturn('feature2');

    $repository->shouldReceive('getFeatures')
        ->once()
        ->andReturn([$feature1, $feature2]);

    // Create a partial mock that allows us to mock both isEnabled and getRepository
    $unleash = Mockery::mock(Unleash::class, [$client])
        ->makePartial()
        ->shouldAllowMockingProtectedMethods();

    $unleash->shouldReceive('getRepository')
        ->once()
        ->andReturn($repository);

    $unleash->shouldReceive('isEnabled')
        ->with('feature1', null)
        ->once()
        ->andReturn(true);

    $unleash->shouldReceive('isEnabled')
        ->with('feature2', null)
        ->once()
        ->andReturn(false);

    $result = $unleash->getFeatures();

    expect($result)->toHaveKey('toggles');
    expect($result['toggles'])->toHaveCount(2);
    expect($result['toggles'][0])->toEqual([
        'enabled' => true,
        'name' => 'feature1',
    ]);
    expect($result['toggles'][1])->toEqual([
        'enabled' => false,
        'name' => 'feature2',
    ]);
});

it('gets only enabled features when requested', function () {
    $client = Mockery::mock(UnleashClient::class);
    $repository = Mockery::mock(UnleashRepository::class);
    $feature1 = Mockery::mock(Feature::class);
    $feature2 = Mockery::mock(Feature::class);

    $feature1->shouldReceive('getName')->andReturn('feature1');
    $feature2->shouldReceive('getName')->andReturn('feature2');

    $repository->shouldReceive('getFeatures')
        ->once()
        ->andReturn([$feature1, $feature2]);

    // Create a partial mock that allows us to mock both isEnabled and getRepository
    $unleash = Mockery::mock(Unleash::class, [$client])
        ->makePartial()
        ->shouldAllowMockingProtectedMethods();

    $unleash->shouldReceive('getRepository')
        ->once()
        ->andReturn($repository);

    $unleash->shouldReceive('isEnabled')
        ->with('feature1', null)
        ->once()
        ->andReturn(true);

    $unleash->shouldReceive('isEnabled')
        ->with('feature2', null)
        ->once()
        ->andReturn(false);

    $result = $unleash->getFeatures(true);

    expect($result)->toHaveKey('toggles');
    expect($result['toggles'])->toHaveCount(1);
    expect($result['toggles'][0])->toEqual([
        'enabled' => true,
        'name' => 'feature1',
    ]);
});

it('returns empty array when getFeatures throws exception', function () {
    $client = Mockery::mock(UnleashClient::class);

    $unleash = Mockery::mock(Unleash::class, [$client])
        ->makePartial()
        ->shouldAllowMockingProtectedMethods();

    $unleash->shouldReceive('getRepository')
        ->once()
        ->andThrow(new Exception('Repository error'));

    $result = $unleash->getFeatures();

    expect($result)->toEqual([]);
});

it('gets variant for a feature', function () {
    $client = Mockery::mock(UnleashClient::class);
    $context = Mockery::mock(Context::class);
    $variant = Mockery::mock(Variant::class);
    $fallbackVariant = Mockery::mock(Variant::class);

    $client->shouldReceive('getVariant')
        ->with('test-feature', $context, $fallbackVariant)
        ->once()
        ->andReturn($variant);

    $unleash = new Unleash($client);
    $result = $unleash->getVariant('test-feature', $context, $fallbackVariant);

    expect($result)->toBe($variant);
});

it('registers the client', function () {
    $client = Mockery::mock(UnleashClient::class);

    $client->shouldReceive('register')
        ->once()
        ->andReturn(true);

    $unleash = new Unleash($client);
    $result = $unleash->register();

    expect($result)->toBeTrue();
});

it('can access repository via reflection', function () {
    $client = Mockery::mock(UnleashClient::class);
    $repository = Mockery::mock(UnleashRepository::class);

    $unleash = new Unleash($client);

    // Mock the getRepository method directly instead of testing real reflection
    $partialUnleash = Mockery::mock(Unleash::class, [$client])
        ->makePartial()
        ->shouldAllowMockingProtectedMethods();

    $partialUnleash->shouldReceive('getRepository')
        ->once()
        ->andReturn($repository);

    $reflectionMethod = new ReflectionMethod($partialUnleash, 'getRepository');
    $reflectionMethod->setAccessible(true);

    $result = $reflectionMethod->invoke($partialUnleash);

    expect($result)->toBe($repository);
});

afterEach(function () {
    Mockery::close();
});
