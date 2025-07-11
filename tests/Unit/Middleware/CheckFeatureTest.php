<?php

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tojoo\Unleash\Middleware\CheckFeature;
use Tojoo\Unleash\Unleash;

it('allows request to pass when feature is enabled', function () {
    $unleash = Mockery::mock(Unleash::class);
    $unleash->shouldReceive('isEnabled')
        ->with('test-feature')
        ->once()
        ->andReturn(true);

    $this->app->instance(Unleash::class, $unleash);

    $request = Request::create('/test');
    $middleware = new CheckFeature();

    $response = $middleware->handle($request, function ($req) {
        return response('success');
    }, 'test-feature');

    expect($response->getContent())->toBe('success');
});

it('throws 404 when feature is disabled', function () {
    $unleash = Mockery::mock(Unleash::class);
    $unleash->shouldReceive('isEnabled')
        ->with('test-feature')
        ->once()
        ->andReturn(false);

    $this->app->instance(Unleash::class, $unleash);

    $request = Request::create('/test');
    $middleware = new CheckFeature();

    expect(function () use ($middleware, $request) {
        $middleware->handle($request, function ($req) {
            return response('success');
        }, 'test-feature');
    })->toThrow(NotFoundHttpException::class);
});

afterEach(function () {
    Mockery::close();
});
