<?php
namespace App\Http\Middleware;

use Closure;
use Cookie;
use Cache;
use Illuminate\Support\Facades\Hash;
use MakroLog\LogProvider as Log;

class LogRequest
{
    public $config;

    public function __construct()
    {
        $this->config = config('config');
    }

    protected function writeLogRequest($request, $client_uuid)
    {
        $env       = \App::environment();
        $level     = 'INFO';
        $service   = 'admin';
        $client_id = $request->ip();
        $activity  = $request->path();
        $action    = $request->route()->getActionName() . '_request';

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
            'activity_message'  => json_encode($input)
        ];

        // Write log
        if (env('ALLOW_CENTRALIZE_LOG')) {
            $log = new Log($this->config['path_centralize_log']);
            $log->write($data);
        }
    }

    protected function writeLogResponse($request, $response, $client_uuid)
    {
        $env       = \App::environment();
        $level     = $response->getStatusCode() >= 400? 'ERROR' : 'INFO';
        $service   = 'admin';
        $client_id = $request->ip();
        $activity  = $request->path();
        $action    = $request->route()->getActionName() . '_response';

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
            'activity_message'  => $request->format() == 'html'? 'HTML_CONTENT' : $response->content()
        ];

        // Write log
        if (env('ALLOW_CENTRALIZE_LOG')) {
            $log = new Log($this->config['path_centralize_log']);
            $log->write($data);
        }
    }

    public function handle($request, Closure $next)
    {
        // Client Uuid
        $client_uuid = $request->hasHeader('Client-Uuid')? $request->header('Client-Uuid') : microtime(true);

        // Caching Client Uuid
        if (is_null($request->session()->get('userName'))) {
            Cache::store('redis')->forever('client_uuid', $client_uuid);
        } else {
            Cache::store('redis')->forever($request->session()->get('userName') . '.client_uuid', $client_uuid);
        }

        // Write Log Request
        $this->writeLogRequest($request, $client_uuid);

        $response = $next($request);

        // Write Log Response
        $this->writeLogResponse($request, $response, $client_uuid);

        return $response;
    }
}