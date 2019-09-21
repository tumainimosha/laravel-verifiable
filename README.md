# verifiable

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Travis](https://img.shields.io/travis/tumainimosha/verifiable.svg?style=flat-square)]()
[![Total Downloads](https://img.shields.io/packagist/dt/tumainimosha/verifiable.svg?style=flat-square)](https://packagist.org/packages/tumainimosha/verifiable)

## Install
`composer require tumainimosha/laravel-verifiable`

### Publish Migrations

```bash
php artisan vendor:publish --provider="Tumainimosha\Verifiable\VerifiableServiceProvider" --tag="migrations"
php artisan migrate
```

### Publish Configuration File

Publish config file to customize the default package config.

```bash
php artisan vendor:publish --provider="Tumainimosha\Verifiable\VerifiableServiceProvider" --tag="config"
```

## Usage
Write a few lines about the usage of this package.

## Testing
Run the tests with:

``` bash
vendor/bin/phpunit
```

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security
If you discover any security-related issues, please email princeton.mosha@gmail.com instead of using the issue tracker.

## License
The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.