# JsonApi-Assert-Laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Infection MSI][ico-mutation]][link-mutation]
[![Total Downloads][ico-downloads]][link-downloads]

This package adds a lot of methods to the [`Illuminate\Testing\TestResponse`](https://laravel.com/api/5.8/Illuminate/Foundation/Testing/TestResponse.html) class for testing APIs that implements the [JSON:API specification](https://jsonapi.org/).

## Table of content

## Technologies

- PHP 7.3+
- PHPUnit 8.0+
- Laravel 7+
- JsonApi-Assert

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require-dev": {
        "vgirol/jsonapi-assert-laravel": "dev-master"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplified by using the following command:

```sh
composer require vgirol/jsonapi-assert-laravel
```

### Registration

The package will automatically register itself.
If you're not using Package Discovery, add the Service Provider to your config/app.php file:

```php
VGirol\JsonApiAssert\Laravel\JsonApiAssertServiceProvider::class
```

## Usage

```php
/**
 * @test
 */
public function my_first_test()
{
    // Sends request and gets response
    $response = $this->json('GET', 'endpoint');

    // Checks the response (status code, headers) and the content
    $response->assertJsonApiResponse404(
        [
            [
                'status' => '404',
                'title' => 'Not Found'
            ]
        ]
    );
}
```

## Documentation

The API documentation is available in XHTML format at the url [http://jsonapi-assert-laravel.girol.fr/docs/index.xhtml](http://jsonapi-assert-laravel.girol.fr/docs/index.xhtml).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email [vincent@girol.fr](mailto:vincent@girol.fr) instead of using the issue tracker.

## Credits

- [Girol Vincent][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/VGirol/JsonApi-Assert-Laravel.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/VGirol/JsonApi-Assert-Laravel/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/VGirol/JsonApi-Assert-Laravel.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/VGirol/JsonApi-Assert-Laravel.svg?style=flat-square
[ico-mutation]: https://img.shields.io/endpoint?style=flat-square&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FVGirol%2FJsonApi-Assert-Laravel%2Fmaster
[ico-downloads]: https://img.shields.io/packagist/dt/VGirol/JsonApi-Assert-Laravel.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/VGirol/JsonApi-Assert-Laravel
[link-travis]: https://travis-ci.org/VGirol/JsonApi-Assert-Laravel
[link-scrutinizer]: https://scrutinizer-ci.com/g/VGirol/JsonApi-Assert-Laravel/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/VGirol/JsonApi-Assert-Laravel
[link-downloads]: https://packagist.org/packages/VGirol/JsonApi-Assert-Laravel
[link-author]: https://github.com/VGirol
[link-mutation]: https://dashboard.stryker-mutator.io/reports/github.com/VGirol/JsonApi-Assert-Laravel/master
[link-contributors]: ../../contributors
