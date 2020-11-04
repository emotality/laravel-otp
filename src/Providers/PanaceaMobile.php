<?php

namespace Emotality\OTP\Providers;

use Emotality\OTP\Interfaces\SMSProvider;
use Emotality\OTP\ProviderAPI;

class PanaceaMobile extends ProviderAPI implements SMSProvider
{
    /**
     * The provider, as stated in otp.php config file.
     *
     * @var string $provider
     */
    protected $provider = 'panacea_mobile';

    /**
     * Handle API request.
     *
     * @param string $to
     * @param string $message
     * @return bool
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function sendViaSMS(string $to, string $message)
    {
        $to = str_replace('+', '', $to);

        $config = $this->configForProvider($this->provider);

        $response = $this->get('https://api.panaceamobile.com/json', [
            'username' => $config['username'],
            'password' => $config['password'],
            'action'   => 'message_send',
            'to'       => $to,
            'text'     => $message,
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
