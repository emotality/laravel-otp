<?php

namespace Emotality\OTP;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OTPHelper
{
    /**
     * The OTP config.
     *
     * @var array|null $config
     */
    protected $config = null;

    /**
     * The user model config.
     *
     * @var object|null $user_model
     */
    protected $user_model = null;

    /**
     * OTPHelper constructor.
     */
    public function __construct()
    {
        $this->config = Config::get('otp');

        if (!isset($this->config['models'])) {
            throw new \Exception('OTP config not published! (php artisan vendor:publish)');
        }
    }

    /**
     * SMS OTP to the User.
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @param bool $new_otp
     * @return void
     */
    public function sms(Authenticatable $user, bool $new_otp = true)
    {
        $user = $user ?? auth()->user();

        $config = $this->modelConfig($user);

        $otp = $user->{$config->otp_column};

        if (!$otp || $new_otp) {
            $otp = $this->set($user);
        }

        $api_class = sprintf('Emotality\OTP\Providers\%s', Str::studly($config->provider));

        (new $api_class())->sendViaSMS(
            $user->{$config->mobile_column},
            sprintf($this->config['otp_message'], $otp)
        );
    }

    /**
     * Email OTP to the User.
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @param bool $new_otp
     * @return void
     */
    public function email(Authenticatable $user, bool $new_otp = true)
    {
        $user = $user ?? auth()->user();

        $config = $this->modelConfig($user);

        if (!$user->{$config->otp_column} || $new_otp) {
            $this->set($user);
        }

        Mail::to($user->{$config->email_column})->send(new \App\Mail\OTP($user));
    }

    /**
     * SMS or Email OTP to the User.
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @param bool $new_otp
     */
    public function smsOrEmail(Authenticatable $user, bool $new_otp = true)
    {
        $config = $this->modelConfig($user);

        if ($user->{$config->mobile_column}) {
            $this->sms($user, $new_otp);
        } else {
            $this->email($user, $new_otp);
        }
    }

    /**
     * SMS and Email OTP to the User.
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @param bool $new_otp
     */
    public function smsAndEmail(Authenticatable $user, bool $new_otp = true)
    {
        $config = $this->modelConfig($user);

        if ($user->{$config->mobile_column}) {
            $this->sms($user, $new_otp);
        }

        $this->email($user, $new_otp);
    }

    /**
     * Force clear the OTP.
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @return bool
     */
    public function clear(Authenticatable $user) : bool
    {
        return $this->updateOtp($user, null);
    }

    /**
     * Force set a new OTP.
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @param null $otp
     * @return int
     */
    private function set(Authenticatable $user, $otp = null) : int
    {
        $otp = $otp ?? $this->generate();

        $user = $user ?? auth()->user();

        $this->updateOtp($user, $otp);

        return $otp;
    }

    /**
     * Update OTP value in DB.
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @param null $value
     * @return int
     */
    private function updateOtp(Authenticatable $user, $value = null)
    {
        $config = $this->modelConfig($user);

        return \Illuminate\Support\Facades\DB::table($user->getTable())
            ->where('id', $user->id)
            ->update([$config->otp_column => $value]);
    }

    /**
     * Generate a new OTP.
     *
     * @return int
     */
    private function generate() : int
    {
        $length = intval($this->config['otp_length']);

        if ($length == 4) {
            return mt_rand(1000, 9999);
        } else if ($length == 5) {
            return mt_rand(10000, 99999);
        } else if ($length == 6) {
            return mt_rand(100000, 999999);
        }

        return mt_rand(10000, 99999);
    }

    /**
     * Get Authenticatable model.
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @return array|mixed|null
     * @throws \Exception
     */
    private function modelConfig(Authenticatable $user)
    {
        if (!$this->user_model) {
            foreach ($this->config['models'] as $model) {
                if ($user instanceof $model['class']) {
                    return $this->user_model = (object) $model;
                }
            }
        }

        return $this->user_model;
    }
}
