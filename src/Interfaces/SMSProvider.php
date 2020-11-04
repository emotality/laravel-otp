<?php

namespace Emotality\OTP\Interfaces;

interface SMSProvider
{
    /**
     * Send an SMS.
     *
     * @param string $to
     * @param string $message
     * @return bool
     */
    public function sendViaSMS(string $to, string $message);

    /**
     * Handle response to return a boolean.
     *
     * @param array|mixed $response
     * @return bool
     */
    public function handleResponse($response);
}
