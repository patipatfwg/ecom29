<?php
namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Http\Requests\MaintenanceRequest;
use App,Validator;
class MaintenanceController extends \App\Http\Controllers\BaseController
{

    protected $redirect = [
        'login' => '/',
        'index' => 'maintenance',
    ];

    protected $view = [
        'index' => 'maintenance.index',
    ];

    protected $config_type = 'Payment Method';

    public function __construct()
    {
        parent::__construct();

        $this->_messages          = config('message');
        $this->_configRepository  = App::make('App\Repositories\ConfigRepository');
        $this->_bss_cache_service = App::make('App\Services\Bss\CacheServices');

    }

    public function index()
    {
        return view($this->view['index'], [
            'maintenance' => $this->getConfigsMaintenance()
        ]);

    }

    private function getConfigsMaintenance()
    {
        $data = [];

        $params = [
            'config_type' => 'Setting',
            'name'        => 'ma_page',
            'status'      => 'active,inactive',
        ];

        $result = $this->_configRepository->getConfigs($params);
        if (!empty($result['0'])) {
            $data = $result['0'];
        }

        return $data;
    }

    public function update(MaintenanceRequest $request, Validator $validator)
    {
        $checkValidator = $validator::make($request->all(), $request->rules(), []);
        // check error validator
        if ($checkValidator->fails()) {
            return redirect()->back()->withErrors($checkValidator->errors())->withInput();
        } else {

            $params = [
                'start_datetime' => $request->has('start_date') ? date('Y-m-d H:i:s', strtotime(str_replace("/", "-", $request->input('start_date').":00"))) : '',
                'end_datetime'   => $request->has('end_date') ? date('Y-m-d H:i:s', strtotime(str_replace("/", "-", $request->input('end_date').":00"))) : '',
                'value'  => $request->has('disable_value') ? $request->input('disable_value') : '',
                'status' => $request->has('status') ? 'active' : 'inactive',
            ];

            $result = $this->getConfigsMaintenance();

            // Update
            if (!empty($result)) {
                $status = $this->_configRepository->updateConfig($result['id'], $params);

                // clear cache
                $this->_bss_cache_service->flushCache('Setting');

                $request->session()->flash('alert', [
                    'type' => 'success',
                    'title' => 'Save'
                ]);
                return redirect($this->redirect['index']);
            }

            return redirect()->back()->withErrors($this->_messages['database']['update_error'])->withInput();
        }
    }

}
