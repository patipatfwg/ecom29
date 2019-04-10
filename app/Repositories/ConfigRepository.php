<?php
namespace App\Repositories;

use App;
use App\Services\Guzzle;

class ConfigRepository
{
    protected $guzzle;
    protected $messages;

    public function __construct()
    {

        $this->url    = config('api.makro_config_api');
        $this->guzzle = App::make('App\Services\Guzzle');
    }

    public function getConfigByID($id)
    {
        $data = [];

        if (!empty($id)) {

            $url = $this->url . 'configs/' . $id;

            $result = $this->guzzle->curl('GET', $url);

            if (!empty($result['data']['0'])) {
                $data = $result['data']['0'];
            }
        }

        return $data;
    }

    public function getConfigs($params)
    {
        $datas = [];

        if (!empty($params)) {
            $options = [
                'query' => $params,
            ];

            $url = $this->url . 'configs';

            $result = $this->guzzle->curl('GET', $url, $options);
            if (!empty($result['data']['records'])) {
                $datas = $result['data']['records'];
            }
        }

        return $datas;
    }

    public function createConfig($params)
    {
        $data = [];

        if (!empty($params)) {
            $options = [
                'json' => $params
            ];

            $url    = $this->url . 'configs';
            $result = $this->guzzle->curl('POST', $url, $options);
 
            if (!empty($result['data']['records'])) {
                $data = $result['data']['records'];
            }

        }

        return $data;
    }

    public function updateConfig($id, $params)
    {
        $data = [];

        if (!empty($params)) {
            $options = [
                'json' => $params
            ];

            $url    = $this->url . 'configs/' . $id;
            $result = $this->guzzle->curl('PUT', $url, $options);
            if (!empty($result['data']['records'])) {
                $data = $result['data']['records'];
            }

        }

        return $data;
    }
}
