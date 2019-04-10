<?php

namespace App\Services;

use App;

class CurlServices
{
    protected $curl;
    protected $url = '';

    function __construct()
    {
        $this->curl = App::make('App\Services\Curl');

        if (isset($this->url)) {
            $this->setUrl($this->url);
        }
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setHeader($key, $value)
    {
        $this->curl->setHeader($key, $value);
    }

    private function crateUri($uri)
    {
        return (!empty($uri))?('/' . $uri):'';
    }

    public function callApi($type, $uri, $params=array(), $arrayFormat=false)
    {
        $request_url = rtrim($this->url, '/') . $this->crateUri($uri);
        $this->curl->$type($request_url, $params);

        // if ($this->curl->error) {
        //     return $this->curl->error_code;
        // } else {
        //     return json_decode($this->curl->response, $arrayFormat);
        // }

        return json_decode($this->curl->response, $arrayFormat);
    }

    public function callXmlApi($type, $uri, $params=array())
    {
        $request_url = rtrim($this->url, '/') . $this->crateUri($uri);
        $this->curl->$type($request_url, $params);

        $xml = simplexml_load_string($this->curl->response);
        $json = json_encode($xml);

        return json_decode($json, true);
    }


    public function callApiNoDeCode($type, $uri, $params=array())
    {
        $request_url = rtrim($this->url, '/') . $this->crateUri($uri);
        $this->curl->$type($request_url, $params);

        if ($this->curl->error) {
            return $this->curl->error_code;
        } else {
            return $this->curl->response;
        }

    }

    public function callUploadFile($type, $uri, $params, $size, $arrayFormat=false)
    {
        $request_url = rtrim($this->url, '/') . $this->crateUri($uri);

        $this->curl->$type($request_url, $params);

        // if ($this->curl->error) {
        //     return $this->curl->error_code;
        // } else {
        //     return json_decode($this->curl->response, $arrayFormat);
        // }
        return json_decode($this->curl->response, $arrayFormat);
    }
}