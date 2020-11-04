<?php

namespace Emotality\OTP\Providers;

use Emotality\OTP\Interfaces\SMSProvider;
use Emotality\OTP\ProviderAPI;

class ProviderTemplate extends ProviderAPI implements SMSProvider
{
    /**
     * The provider, as stated in otp.php config file.
     *
     * @var string $provider
     */
    protected $provider = 'provider_name';

    /**
     * Send an SMS.
     *
     * @param string $to
     * @param string $message
     * @return bool
     */
    public function sendViaSMS(string $to, string $message) : bool
    {
        // Optional, remove if necessary
        $to = str_replace('+', '', $to);

        // TODO: Add your keys & values to the config file
        $config = $this->configForProvider($this->provider);

        // TODO: Handle the API request to send SMS
        $response = $this->post('https://api.example.com/sms', [
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
        // TODO: Handle the response to return a bool. Below is just an example!
        // Note: Some providers still return a 200 even if request failed.
        return ($response['status'] ?? 0) == 1;
    }
}
