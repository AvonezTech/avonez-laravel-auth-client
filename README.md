<div align="center">
    <h1>Avonez Auth Client</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/avonez-tech/avonez-auth-client"><img src="https://img.shields.io/packagist/v/avonez-tech/avonez-auth-client.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/avonez-tech/avonez-auth-client"><img src="https://img.shields.io/packagist/php-v/avonez-tech/avonez-auth-client.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/avonez-tech/avonez-auth-client"><img src="https://badge.laravel.cloud/badge/avonez-tech/avonez-auth-client?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/avonez-tech/avonez-auth-client/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/avonez-tech/avonez-auth-client/tests.yml?branch=main&label=Tests&style=flat-square"></a>
    <a href="https://packagist.org/packages/avonez-tech/avonez-auth-client"><img src="https://img.shields.io/packagist/dt/avonez-tech/avonez-auth-client.svg?style=flat-square" alt="Total Downloads"></a>
</p>

Package to connect to avonez account

## Installation

You can install the package via Composer:

```bash
composer require avonez-tech/avonez-auth-client
```

You may publish all of the package's resources at once:

```bash
php artisan vendor:publish --tag="avonez-auth-client"
```

Or, you may publish each resource individually:

### Publishing the Configuration File

```bash
php artisan vendor:publish --tag="avonez-auth-client-config"
```

### Publishing and Running the Migrations

```bash
php artisan vendor:publish --tag="avonez-auth-client-migrations"
php artisan migrate
```

### Publishing the Views

```bash
php artisan vendor:publish --tag="avonez-auth-client-views"
```

### Publishing the Public Assets

```bash
php artisan vendor:publish --tag="avonez-auth-client-assets"
```

## Usage

<!-- Add a basic usage example here. -->

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Avonez Auth Client! Please review our [contributing guide](.github/CONTRIBUTING.md) to get started.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [AvonezTech](https://github.com/avonez-tech)
- [All Contributors](../../contributors)

## License

Avonez Auth Client is open-sourced software licensed under the [MIT license](LICENSE.md).
