<?php

namespace Emotality\OTP;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class ProviderAPI
{
    /**
     * The API request to be made.
     *
     * @var \Illuminate\Http\Client\PendingRequest $request
     */
    protected $request;

    /**
     * OTP config file.
     *
     * @var array|null $config
     */
    protected $config;

    /**
     * ProviderAPI constructor.
     */
    public function __construct()
    {
        $this->config = Config::get('otp');

        $this->setOptions();
        $this->setTimeout();
        $this->setRetry();
    }

    /**
     * Set API options.
     *
     * @return void
     */
    private function setOptions()
    {
        $config = Config::get('otp');

        if (!isset($config['models'])) {
            throw new \Exception('OTP config not published! (php artisan vendor:publish)');
        }

        $this->request = Http::withOptions([
            'debug'      => boolval($config['debug'] ?? false),
            'verify_ssl' => boolval($config['verify_ssl'] ?? true),
        ]);
    }

    /**
     * Set API timeout.
     *
     * @return void
     */
    private function setTimeout()
    {
        $this->request = $this->request->timeout(
            intval($this->config['timeout'] ?? 10)
        );
    }

    /**
     * Set API retry values.
     *
     * @return void
     */
    private function setRetry()
    {
        $this->request = $this->request->retry(
            intval($this->config['retry'] ?? 3),
            intval($this->config['sleep'] ?? 1200)
        );
    }

    /**
     * @param string $provider
     * @return array|mixed
     * @throws \Exception
     */
    protected function configForProvider(string $provider)
    {
        if (!isset($this->config['providers'][$provider])) {
            throw new \Exception(sprintf('Provider [%s] does not exist in config!', $provider));
        }

        return $this->config['providers'][$provider];
    }

    /**
     * Indicate the request contains form parameters.
     * (application/x-www-form-urlencoded)
     *
     * @return \Emotality\OTP\ProviderAPI
     */
    public function asForm()
    {
        $this->request->asForm();

        return $this;
    }

    /**
     * DELETE Request.
     *
     * @param string $url
     * @param array $data
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function delete(string $url, array $data = [])
    {
        $response = $this->request->delete($url, $data);

        if ($this->config['debug'] ?? false) {
            $response->throw();
        }

        return $response->json() ?? [];
    }

    /**
     * GET Request.
     *
     * @param string $url
     * @param array $query
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function get(string $url, array $query = [])
    {
        $response = $this->request->get($url, $query);

        if ($this->config['debug'] ?? false) {
            $response->throw();
        }

        return $response->json() ?? [];
    }

    /**
     * HEAD Request.
     *
     * @param string $url
     * @param array $query
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function head(string $url, array $query = [])
    {
        $response = $this->request->head($url, $query);

        if ($this->config['debug'] ?? false) {
            $response->throw();
        }

        return $response->json() ?? [];
    }

    /**
     * PATCH Request.
     *
     * @param string $url
     * @param array $data
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function patch(string $url, array $data = [])
    {
        $response = $this->request->patch($url, $data);

        if ($this->config['debug'] ?? false) {
            $response->throw();
        }

        return $response->json() ?? [];
    }

    /**
     * POST Request.
     *
     * @param string $url
     * @param array $data
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function post(string $url, array $data = [])
    {
        $response = $this->request->post($url, $data);

        if ($this->config['debug'] ?? false) {
            $response->throw();
        }

        return $response->json() ?? [];
    }

    /**
     * PUT Request.
     *
     * @param string $url
     * @param array $data
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function put(string $url, array $data = [])
    {
        $response = $this->request->put($url, $data);

        if ($this->config['debug'] ?? false) {
            $response->throw();
        }

        return $response->json() ?? [];
    }
}
