<?php

declare(strict_types=1);

use AvonezAuthClient\AvonezAuthClient;

it('resolves the singleton', function () {
    expect(app(AvonezAuthClient::class))->toBeInstanceOf(AvonezAuthClient::class);
});

it('returns the same instance from the container', function () {
    expect(app(AvonezAuthClient::class))->toBe(app(AvonezAuthClient::class));
});

it('merges the package config', function () {
    expect(config('avonez-auth-client.placeholder'))->toBe('default');
});

it('loads the package views', function () {
    expect(view()->exists('avonez-auth-client::placeholder'))->toBeTrue();
});

it('registers the artisan command', function () {
    $this->artisan('avonez-auth-client:placeholder')
        ->expectsOutputToContain('AvonezAuthClient placeholder command executed.')
        ->assertSuccessful();
});
