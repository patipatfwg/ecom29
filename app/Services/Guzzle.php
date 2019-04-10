<?php
namespace App\Services;

use Session;
use Cache;
use GuzzleHttp\Client;
use MakroLog\LogProvider as Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Guzzle
{
    public $client;
    public $config;
    public $request;

    public function __construct(Client $client, Request $request)
    {
        $this->client  = $client;
        $this->request = $request;
        $this->config  = config('config');
    }

    public function curl($method = 'GET', $url, $params = [])
    {
        // Get Client Uuid
        if (is_null(Session::get('userName'))) {
            $client_uuid = Cache::store('redis')->get('client_uuid');
        } else {
            $client_uuid = Cache::store('redis')->get(Session::get('userName') . '.client_uuid');
        }

        // Set Client Uuid Header
        $params['headers']['Client-Uuid'] = $client_uuid;
        $output = [];

        try {
            // Write Log Request
            $this->writeLogRequest($this->request, $client_uuid, $url, $params);

            $result     = $this->client->request($method, $url, $params);
            $output     = json_decode($result->getBody(), true);
            $outputData = json_encode($output);

            // Write Log Response
            $this->writeLogResponse($this->request, $result, $outputData, $client_uuid, $url);

        } catch (\Exception $e) {

            if ($e->getResponse()) {
                $output = json_decode((string) $e->getResponse()->getBody(), true);
            }
        }

        return $output;
    }

    public function curlRaw($method = 'GET', $url, $params = [])
    {
        // Get Client Uuid
        if (is_null(Session::get('userName'))) {
            $client_uuid = Cache::store('redis')->get('client_uuid');
        } else {
            $client_uuid = Cache::store('redis')->get(Session::get('userName') . '.client_uuid');
        }

        // Set Client Uuid Header
        $params['headers']['Client-Uuid'] = $client_uuid;

        $output = [];

        try {
            // Write Log Request
            $this->writeLogRequest($this->request, $client_uuid, $url, $params);

            $result = $this->client->request($method, $url, $params);
            $output = $result->getBody();

            // Write Log Response
            $this->writeLogResponse($this->request, $result, $output, $client_uuid , $url);

        } catch (\Exception $e) {
            if ($e->getResponse()) {
                $output = (string)$e->getResponse()->getBody();
            }
        }

        return $output;
    }

    public function eposCurl($method = 'GET', $url, $params = [])
    {
        // Get Client Uuid
        if (is_null(Session::get('userName'))) {
            $client_uuid = Cache::store('redis')->get('client_uuid');
        } else {
            $client_uuid = Cache::store('redis')->get(Session::get('userName') . '.client_uuid');
        }

        // Set Client Uuid Header
        $params['headers']['Client-Uuid'] = $client_uuid;

        // Write Log Request
        $this->writeLogRequest($this->request, $client_uuid, $url, $params);

        $result = $this->client->request($method, $url, $params);
        $output = $result->getBody();

        // Write Log Response
        $this->writeLogResponse($this->request, $result, $output, $client_uuid , $url);
        return $result;
    }

    public function test($method = 'GET', $url, $params = [])
    {
        $result = $this->client->request($method, $url, $params);
        return $result->getBody();
    }

    protected function writeLogRequest($request, $client_uuid, $url, $params = false)
    {
        $env       = \App::environment();
        $level     = 'INFO';
        $service   = 'admin';
        $client_id = $request->ip();
        $activity  = $request->path();
        $action    = $request->route()->getActionName() . '_curl_request_to_' . $url;

        $input = $request->input();
        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        if (isset($input['password_confirmation'])) {
            $input['password_confirmation'] = Hash::make($input['password_confirmation']);
        }

        $data = [
            'environment'       => $env,
            'level'             => $level,
            'service'           => $service,
            'activity'          => $activity,
            'client_ip'         => $client_id,
            'client_id'         => null,    // Optimal
            'client_uuid'       => $client_uuid,
            'client_name'       => null,    // Optimal
            'activity_name'     => $action,
            'activity_message'  => (isset($params) && isset($params['body']) && !empty($params['body'])) ? json_encode($params['body']) : json_encode($input)
        ];

        // Write log
        if (env('ALLOW_CENTRALIZE_LOG')) {
            $log = new Log($this->config['path_centralize_log']);
            $log->write($data);
        }
    }

    protected function writeLogResponse($request, $response, $responseData, $client_uuid, $url)
    {
        $env       = \App::environment();
        $level     = $response->getStatusCode() >= 400? 'ERROR' : 'INFO';
        $service   = 'admin';
        $client_id = $request->ip();
        $activity  = $request->path();
        $action    = $request->route()->getActionName() . '_curl_response_form_' . $url;

        if(is_object($responseData)){
            $activity_message = json_encode($response->getBody()->getContents());
            $response->getBody()->rewind();
        } else {
            $activity_message = $responseData;
        }

        $data = [
            'environment'       => $env,
            'level'             => $level,
            'service'           => $service,
            'activity'          => $activity,
            'client_ip'         => $client_id,
            'client_id'         => null,    // Optimal
            'client_uuid'       => $client_uuid,
            'client_name'       => null,    // Optimal
            'activity_name'     => $action,
            'activity_message'  => $activity_message
        ];

        // Write log
        if (env('ALLOW_CENTRALIZE_LOG')) {
            $log = new Log($this->config['path_centralize_log']);
            $log->write($data);
        }
    }
}