# RocketChat Monolog handler

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
$ composer require exileed/rocketchat-monolog
```

## Usage

``` php
$webhook = 'https://rocket.chat.local/hooks/bd97pizfGu3S5q5oT/ggdfhryhge';

$rocketChatHandler = new RocketChatHandler\RocketChatHandler([$webhook], Monolog\Logger::DEBUG);

$monolog = new Monolog\Logger('Rocket.Chat');
$monolog->pushHandler($rocketChatHandler);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email me@exileed.com instead of using the issue tracker.

## Credits

- [Dmitriy Kuts][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


## TODO
* Tests
* Async request

[ico-version]: https://img.shields.io/packagist/v/exileed/rocketchat-monolog.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/exileed/rocketchat-monolog/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/exileed/rocketchat-monolog.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/exileed/rocketchat-monolog.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/exileed/rocketchat-monolog.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/exileed/rocketchat-monolog
[link-travis]: https://travis-ci.org/exileed/rocketchat-monolog
[link-scrutinizer]: https://scrutinizer-ci.com/g/exileed/rocketchat-monolog/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/exileed/rocketchat-monolog
[link-downloads]: https://packagist.org/packages/exileed/rocketchat-monolog
[link-author]: https://github.com/exileed
