<?php

namespace App\Services\Bss;

use App;

class CacheServices
{
    protected $url;
    protected $guzzle;

    function __construct()
    {
        $this->url    = config('api.makro_bss_api');
        $this->guzzle = App::make('App\Services\Guzzle');
    }

    public function flushCache($config_type, $MS = 'config')
    {
        $status = false;
        try {

            $url = $this->url . 'cache/'.$MS.'/flush';

            $options = [
                'headers' => ['api-key' => env('MSIS_APIKEY')],
                'json'    => [
                    "config_type" => $config_type
                ],
            ];

            $response = $this->guzzle->eposCurl('POST', $url, $options);

            if ($response->getStatusCode() == '200') {
                $status = true;
            }

            return $status;
        } catch (\Exception $e) {
            return '404';
        }
    }

    public function flushCacheByService($service = '')
    {
        $status = false;

        try {
            if (!empty($service)) {

                $url = $this->url . 'cache/flush';

                $options = [
                    'headers' => ['api-key' => env('MSIS_APIKEY')],
                    'json'    => [
                        "service" => $service
                    ],
                ];

                $response = $this->guzzle->eposCurl('POST', $url, $options);

                if ($response->getStatusCode() == '200') {
                    $status = true;
                }
            }

            return $status;
        } catch (\Exception $e) {

            return '404';
        }
    }

}