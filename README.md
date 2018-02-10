# An JustSend.pl driver for [kduma/sms](https://github.com/kduma-OSS/L5-SMS) package

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

An JustSend.pl driver for [kduma/sms](https://github.com/kduma-OSS/L5-SMS) package

## Install

Via Composer

```bash
$ composer require kduma/sms-driver-justsend
```

In Laravel 5.6, service provider is automatically discovered. If you don't use package discovery, 
add the Service Provider to the providers array in `config/app.php`:

    KDuma\SMS\Drivers\JustSend\JustSendServiceProvider::class,
    
Create new channel or reconfigure existing in `sms.php` config file:

```php
'justsend' => [
    'driver' => 'justsend',
    'key'    => env('SMS_JUSTSEND_KEY'),
    'sender' => 'INFORMACJA',
    'eco'    => true,
],
```

## Available Options

| Option   | Default | Description                                 |
|----------|---------|---------------------------------------------|
| key      | `null`  | Api Key for justsend.pl                     |
| sender   | `null`  | Sender name                                 |
| eco      | `true`  | Send eco message                            |
    

## Credits

- [Krystian Duma][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/kduma/sms-driver-justsend.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/kduma/sms-driver-justsend.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/kduma/sms-driver-justsend
[link-downloads]: https://packagist.org/packages/kduma/sms-driver-justsend
[link-author]: https://github.com/kduma
[link-contributors]: ../../contributors
