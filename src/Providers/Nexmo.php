<?php

namespace Emotality\OTP\Providers;

use Emotality\OTP\Interfaces\SMSProvider;
use Emotality\OTP\ProviderAPI;

class Nexmo extends ProviderAPI implements SMSProvider
{
    /**
     * The provider, as stated in otp.php config file.
     *
     * @var string $provider
     */
    protected $provider = 'nexmo';

    /**
     * Send an SMS.
     *
     * @param string $to
     * @param string $message
     * @return bool
     */
    public function sendViaSMS(string $to, string $message) : bool
    {
        $to = str_replace('+', '', $to);

        $config = $this->configForProvider($this->provider);

        $response = $this->asForm()->post('https://rest.nexmo.com/sms/json', [
            'api_key'    => $config['key'],
            'api_secret' => $config['secret'],
            'from'       => $config['from'],
            'to'         => $to,
            'text'       => $message,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Handle response to return a boolean.
     *
     * @param array|mixed $response
     * @return bool
     */
    public function handleResponse($response) : bool
    {
        return ($response['status'] ?? 0) == 1;
    }
}
