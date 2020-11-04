<?php

namespace Emotality\OTP\Interfaces;

interface EmailProvider
{
    /**
     * Send an Email.
     *
     * @param string $to
     * @param string $message
     * @return mixed
     */
    public function sendViaEmail(string $to, string $message);
}
