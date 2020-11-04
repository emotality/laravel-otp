<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OTP Options
    |--------------------------------------------------------------------------
    |
    | Global OTP options.
    |
    */
    'otp_length'  => 5, // Choose between 4, 5 or 6 digits

    // ** NOTE: Place the %d where the OTP should appear! DO NOT REMOVE %d !!
    // Tip: Use \n for new lines, eg. "Line 1\nLine 2\nOTP: %d \nLine 4"
    'otp_message' => "MyApplication OTP: %d",

    /*
    |--------------------------------------------------------------------------
    | API Options
    |--------------------------------------------------------------------------
    |
    | Options for the API when making requests.
    |
    */
    'debug'       => false, // print out debug info and throw exceptions
    'verify_ssl'  => true, // verify endpoint SSL (cURL/Guzzle option)
    'timeout'     => 10, // amount of seconds to time out the request
    'retry'       => 3, // amount of retries if request has failed
    'sleep'       => 1200, // amount of milliseconds to retry after request has failed

    /*
    |--------------------------------------------------------------------------
    | Authenticatable User Models
    |--------------------------------------------------------------------------
    |
    | The users that will be using OTP.
    |
    | NOTE: The model needs to extend "Authenticatable", eg. "class User extends
    | Authenticatable".
    | (use Illuminate\Foundation\Auth\User as Authenticatable;)
    |
    */
    'models'      => [
        'user' => [
            'class'         => \App\Models\User::class,
            'otp_column'    => 'otp',
            'email_column'  => 'email',
            'mobile_column' => 'mobile',
            'provider'      => 'nexmo',
        ],
//        'example' => [
//            'class'         => \App\Models\Admin::class,
//            'otp_column'    => 'tfa_otp', // $user->tfa_otp = 12345
//            'email_column'  => 'email_address', // $user->email_address = 'john.doe@example.com'
//            'mobile_column' => 'cellphone', // $user->cellphone = 27820001234
//            'provider'      => 'panacea_mobile',
//        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Service Providers
    |--------------------------------------------------------------------------
    |
    | The available providers for sending SMS messages.
    |
    */
    'providers'   => [
        'nexmo'          => [
            'key'    => env('NEXMO_KEY'),
            'secret' => env('NEXMO_SECRET'),
            'from'   => env('NEXMO_FROM'),
        ],
        'panacea_mobile' => [
            'username' => env('PANACEA_USERNAME'),
            'password' => env('PANACEA_PASSWORD'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Contributing
    |--------------------------------------------------------------------------
    |
    | To add your own provider, please use the ProviderTemplate class and submit
    | a pull request at: https://github.com/emotality/laravel-otp/pulls
    |
    | NOTE: Provider name will be studly cased for class name, example:
    | "panacea_mobile" will be "Emotality/OTP/Providers/PanaceaMobile"
    | Search $api_class in src/OTPHelper.php for more info!
    |
    */
];
