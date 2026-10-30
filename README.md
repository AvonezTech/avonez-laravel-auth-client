# Avonez Auth Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/avonez-tech/avonez-auth-client.svg?style=flat-square)](https://packagist.org/packages/avonez-tech/avonez-auth-client)
[![PHP Version](https://img.shields.io/packagist/php-v/avonez-tech/avonez-auth-client.svg?style=flat-square)](https://packagist.org/packages/avonez-tech/avonez-auth-client)
[![Tests](https://img.shields.io/github/actions/workflow/status/avonez-tech/avonez-auth-client/tests.yml?branch=main&label=Tests&style=flat-square)](https://github.com/avonez-tech/avonez-auth-client/actions)

Avonez Auth Client adds an OAuth sign-in flow for Avonez accounts to a Laravel application. It redirects users to Avonez, handles the callback, synchronizes the account and its organizations, and signs the user into Laravel.

## Requirements

- PHP 8.3 or later
- Laravel 12 or 13
- A configured Avonez OAuth client

## Installation

Install the package with Composer:

```bash
composer require avonez-tech/avonez-auth-client
```

Laravel discovers the service provider automatically. The package registers its routes and migrations and merges its default configuration.

## Configuration

Add your OAuth client credentials and callback URL to the application's `.env` file:

```dotenv
AVONEZ_AUTH_CLIENT_ID=your-client-id
AVONEZ_AUTH_CLIENT_SECRET=your-client-secret
AVONEZ_AUTH_REDIRECT_URI="${APP_URL}/auth"
```

Register the same callback URL with your Avonez OAuth client. The package's callback route is `/auth`.

The package reads these settings from `config/avonez-auth-client.php`:

```php
'client_id' => env('AVONEZ_AUTH_CLIENT_ID'),
'client_secret' => env('AVONEZ_AUTH_CLIENT_SECRET'),
'redirect' => env('AVONEZ_AUTH_REDIRECT_URI'),
'host' => 'https://account.avonez.com',
'scope' => 'user:read organizations:read',
'user-model' => \AvonezAuthClient\Models\User::class,
'organization-model' => \AvonezAuthClient\Models\Organization::class,
'organization-user-model' => \AvonezAuthClient\Models\OrganizationUser::class,
```

Publish the config file to customize the OAuth server host, requested scopes, or model classes:

```bash
php artisan vendor:publish --tag=avonez-auth-client-config
```

The default scope is `user:read organizations:read`. The package uses the configured `user-model` to create or update users and the organization model settings when synchronizing organizations. A custom user model must extend `AvonezAuthClient\Models\User`.

The OAuth server must provide `/oauth/authorize`, `/oauth/token`, and `/api/user`. The package also requests `/api/organizations` when the access token includes the `organizations:read` scope.

## User model and database

By default, the package authenticates users using `AvonezAuthClient\Models\User`, which uses the application's `users` table. Configure the application's `users` auth provider to use the same model configured as `user-model` so Laravel can restore the authenticated user from the session:

```php
// config/auth.php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => AvonezAuthClient\Models\User::class,
    ],
],
```

The package migrations are loaded automatically. Review them before running them, especially if your application already has user data: the user migration assumes a `users` table exists, adds Avonez identity and token columns, makes `email` nullable, and drops `password`, `remember_token`, and `email_verified_at`. Its rollback does not restore those dropped columns. Back up your database and adapt or publish the migrations if those changes do not suit your application.

The `organizations` table is created only when the configured scope contains `organization`, so set the scope before running migrations. When the access token grants `organizations:read`, the package synchronizes organizations and their user relationship. The `User` model exposes an `organizations()` relationship. Access and refresh tokens are stored on the user record and hidden from its serialized attributes.

## Sign-in flow

Send users to `/login` to start Avonez authorization. After consent, Avonez returns them to `/auth`; the package validates the OAuth state, retrieves the profile, updates the local user (and organizations when permitted), logs the user in, and redirects to the intended URL or `/`. Both routes use Laravel's `web` middleware, which provides the session needed for OAuth state validation. Requests to `/register` are permanently redirected to `/login`.

The routes are registered by the package and do not require a facade call:

```blade
<a href="{{ url('/login') }}">Sign in with Avonez</a>
```

## Publishing resources

The package publishes configuration, views, public assets, and migrations. Publish all resources with:

```bash
php artisan vendor:publish --tag=avonez-auth-client
```

Or publish an individual resource:

```bash
php artisan vendor:publish --tag=avonez-auth-client-config
php artisan vendor:publish --tag=avonez-auth-client-views
php artisan vendor:publish --tag=avonez-auth-client-assets
php artisan vendor:publish --tag=avonez-auth-client-migrations
```

Views use the `avonez-auth-client::` namespace.

## Development

Run the package checks locally:

```bash
composer install
composer test
```

See the [contribution guide](.github/CONTRIBUTING.md) for contribution instructions and the [security policy](.github/SECURITY.md) to report a vulnerability.

## License

Avonez Auth Client is open-source software licensed under the [MIT License](LICENSE.md).
