# Laravel OTP

[![Packagist License](https://poser.pugx.org/emotality/laravel-otp/license.png)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://poser.pugx.org/emotality/laravel-otp/version.png)](https://packagist.org/packages/emotality/laravel-otp)
[![Total Downloads](https://poser.pugx.org/emotality/laravel-otp/d/total.png)](https://packagist.org/packages/emotality/laravel-otp)

A Laravel package to send OTP via email & SMS. Specify different models and their columns!

## Installation

1. `composer require emotality/laravel-otp`
2. `php artisan vendor:publish --provider="Emotality\OTP\OTPServiceProvider"`
3. Configure your `config/otp.php` file

## Usage

Import `OTP` class:

```php
use Emotality\OTP\OTP;
```

Send OTP to the user that just logged in:

```php
OTP::email($user);
OTP::sms($user);
OTP::smsOrEmail($user);
OTP::smsAndEmail($user);
```

If OTP was entered correctly, you can remove OTP from database:

```php
OTP::clear($user);
```

## Providers

- [Nexmo/Vonage](https://www.vonage.com)
- [PanaceaMobile](https://www.panaceamobile.com)
- More coming soon!

## License

laravel-otp is released under the MIT license. See [LICENSE](https://github.com/emotality/laravel-otp/blob/master/LICENSE) for details.
