<?php

namespace Emotality\OTP;

use Illuminate\Support\Facades\Facade;

class OTP extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return \Emotality\OTP\OTPHelper::class;
    }
}
