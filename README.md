# Laravel OTP

[![Packagist License](https://poser.pugx.org/emotality/laravel-otp/license.png)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://poser.pugx.org/emotality/laravel-otp/version.png)](https://packagist.org/packages/emotality/laravel-otp)
[![Total Downloads](https://poser.pugx.org/emotality/laravel-otp/d/total.png)](https://packagist.org/packages/emotality/laravel-otp)

A Laravel package to send OTP via email & SMS. Specify different models and their columns!

## Installation

1. `composer require emotality/laravel-otp`
2. `php artisan vendor:publish --provider="Emotality\OTP\OTPServiceProvider"`
3. Configure your `config/otp.php` file
4. `php artisan make:mail OTP`
5. `php artisan make:migration add_otp_to_users_table`
6. Edit the migration file as follows:

```php
return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('otp')->after('email')->nullable();
        });
    }
};
```
7. `php artisan migrate`

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

Validates if OTP was entered correctly:

```php
$status = OTP::validateOtp($user, $value);
```

If OTP was entered correctly, you can remove OTP from database:

```php
OTP::clear($user);
```
## Usage

Sample controller example:

```php
    public function verify_otp(Request $request)
    {
        $validated = $request->validate([
            'otp'   => 'required|numeric|max_digits:6',
        ]);
        
        $status = OTP::validateOtp(Auth::user(), $validated['otp']);

        if ($status)
        {
            $status = 'Your email is now verified. Thank you.';
            OTP::clear(Auth::user());
            Auth::user()->markEmailAsVerified();     
        } else {
            $status = 'Wrong OTP. Please retry.';
        }

        return back()->with('status', $status);
    }
```

## Providers

- [Nexmo/Vonage](https://www.vonage.com)
- [PanaceaMobile](https://www.panaceamobile.com)
- More coming soon!

## License

laravel-otp is released under the MIT license. See [LICENSE](https://github.com/emotality/laravel-otp/blob/master/LICENSE) for details.
