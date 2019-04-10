<?php
namespace App\Repositories;

use GuzzleHttp\Exception\RequestException;
use App\Repositories\StoreRepository;
use App\Services\Guzzle;
use App\Services\MyServices;
use URL;
use App;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Session;
use Excel;
use App\Library\Unit;
class UsersRepository
{

	public function __construct(Guzzle $guzzle, StoreRepository $storeRepository)
	{
		$this->guzzle 			= $guzzle;
		$this->urlPermission 	= env('CURL_API_PERMISSION');
		$this->curlServices 	= App::make('App\Services\CurlServices');
		$this->messages 		= config('message');
		$this->storeRepository 	= $storeRepository;
		$this->_unit        	= new Unit;
	}

	public function updateUser($params, $id)
	{
		$output = [
			'status' => false,
			'error'  => $this->messages['database']['update_error']
		];

		$model = new Users();
		$user = $model->find($id);

		if ($user == null) {
			$output = [
				'status' => false,
				'error'  => $this->messages['database']['dataNotFound']
			];

			return $output;
		}

		foreach ($params as $key => $value) {

			if ($key == 'password') {
				$user->$key = Hash::make($value);

			} else if ($key == 'userGroup') {

				$roleData = [
					'roles' => explode(',', $params['userGroup'])
				];
				$url = $this->urlPermission;
				$type = 'putJson';
				$this->curlServices = App::make('App\Services\CurlServices');
				$this->curlServices->setUrl($url);
				$this->curlServices->callApi($type, 'users/' . $user->username, $roleData, true);

			} else {
				$user->$key = $value;
			}
		}

		if (!$user->save()) {
			return $output;
		}

		$output = [
			'status'   => true,
			'messages' => $this->messages['database']['success']
		];

		return $output;
	}

	public function deleteUser($id)
	{
		$output = [
			'success'  => false,
			'messages' => $this->messages['database']['update_error']
		];

		$model = new Users();
		$user = $model->find($id);

		if ($user == null) {
			$output = [
				'success'  => false,
				'messages' => $this->messages['database']['dataNotFound']
			];

			return $output;
		}
		$ts = new \DateTime();
		$str = $ts->format('Y-m-d H:i:s');
		$user->deleted_at = $str;
		
		if (!$user->save()) {
			return $output;
		}

		$url = $this->urlPermission . 'users/' . $user->username ;
		$result = $this->guzzle->curl('delete', $url, []);

		$output = [
			'success'  => true,
			'messages' => $this->messages['database']['success']
		];

		return $output;
	}

	public function getUsers($params)
	{
		$model = new Users();

		if (!isset($params['id']) && isset($params['full_text'])) {
			$data = [];
			if ($params['full_text'] != '') {
				$userAllData = $model->where([
							['deleted_at', '=', ''],
							['mobile', 'like', '%' . $params['full_text'] . '%']
						])->orWhere([
							['deleted_at', '=', ''],
							['email', 'like', '%' . $params['full_text'] . '%']
						])->orWhere([
							['deleted_at', '=', ''],
							['name', 'like', '%' . $params['full_text'] . '%']
						])->orWhere([
							['deleted_at', '=', ''],
							['username', 'like', '%' . $params['full_text'] . '%']
						])->get();
			} else {
				$userAllData = $model->where('deleted_at', '=', '')->get();
			}
			$data['list'] = count($userAllData);
			$orderKey = ['created_at', 'created_at', 'username', 'name', 'mobile', 'email', '','_id','created_at', '', '',''];
			if ($params['full_text'] != '') {
				$userData = $model->where([
							['deleted_at', '=', ''],
							['mobile', 'like', '%' . $params['full_text'] . '%']
						])->orWhere([
							['deleted_at', '=', ''],
							['email', 'like', '%' . $params['full_text'] . '%']
						])->orWhere([
							['deleted_at', '=', ''],
							['name', 'like', '%' . $params['full_text'] . '%']
						])->orWhere([
							['deleted_at', '=', ''],
							['username', 'like', '%' . $params['full_text'] . '%']
						])->offset((int) $params['start'])->take((int) $params['length'])->orderBy($orderKey[(int) $params['order'][0]['column']], $params['order'][0]['dir'])->get();
			} else {
				$userData = $model->where('deleted_at', '=', '')->offset((int) $params['start'])->take((int) $params['length'])->orderBy($orderKey[(int) $params['order'][0]['column']], $params['order'][0]['dir'])->get();
			}
		} else {
			    $userData = [$model->find($params['id'])];
		}

		$getStoreParams  = [
			'limit' => '99999',
			'offset' => 0,
			'order' => 'makro_store_id|ASC',
		];

        $stores = [];
		$storeData = $this->storeRepository->getStores($getStoreParams);
        if (isset($storeData['data']['records']) && !empty($storeData['data']['records'])) {
		    foreach($storeData['data']['records'] as $val){
			    $stores[$val['makro_store_id']] = $val['name']['th'] . " (" . $val['makro_store_id'] . ")";
		    }
        }

		foreach ($userData as $user) {

			$authorize = [];
			$url = $this->urlPermission;
			$type = 'get';
			$this->curlServices = App::make('App\Services\CurlServices');
			$this->curlServices->setUrl($url);
			$result = $this->curlServices->callApi($type, 'users/' . $user['username'], [], true);

			if (!isset($result['data'])) {
				$authorize = [];
			} else {
				$roleData = $result['data']['roles'];
				foreach ((array) $roleData as $role) {
					$authorize[] = [
						'id'   => $role['_id']['$oid'],
						'name' => $role['name']
					];
				}
			}
			if (empty($user['makro_store_id']) || !isset($user['makro_store_id'])) {
				$current_store = 'All store';
				$user['makro_store_id'] = '';
			} else {
				if(isset($user['makro_store_id']) && isset($stores[$user['makro_store_id']])) {
					$current_store = $stores[$user['makro_store_id']];
				} else {
					$current_store = '';
				}
			}

			$data[] = [
				'id'          => $user['_id'],
				'username'    => $user['username'],
				'name'        => $user['name'],
				'surname'     => $user['surname'],
				'mobile'      => $user['mobile'],
				'email'       => $user['email'],
				'regis_date'  => !(is_null($user['created_at']))? $user['created_at']->toDateTimeString() : '',
				'authorize'   => $authorize,
				'employee_id' => $user['_id'],
				'password'    => $user['password'],
				'makro_store_id' => $user['makro_store_id'],
				'makro_store_name' => $current_store
			];
		}

		return ['data' => $data];
	}

	public function createUser($params)
	{
		$output = [
			'status' => false,
			'error'  => $this->messages['database']['add_error']
		];

		try {
			$checkData = Users::raw()->findOne([
				'username' => [
					'$eq' => $params['username']
				],
				'deleted_at' => [
					'$eq' => ''
				]
			]);

			if ($checkData != null) {
				$output = [
					'status' => false,
					'error'  => $this->messages['database']['duplicate']
				];

				return $output;
			}

			$params['deleted_at'] = '';
			$user = new Users();

			foreach ($params as $key => $value) {
				if ($key == 'password') {
					$user->$key = Hash::make($value);
				} else if ($key == 'userGroup') {
					$roleData = [
						'username' => $params['username'],
						'roles'    => explode(',', $params['userGroup'])
					];
					$url = $this->urlPermission;
					$type = 'postJson';
					$this->curlServices = App::make('App\Services\CurlServices');
					$this->curlServices->setUrl($url);
					$this->curlServices->callApi($type, 'users', $roleData, true);
				} else {
					$user->$key = $value;
				}
			}

			if (!$user->save()) {
				return $output;
			}

			$output = [
				'status'   => true,
				'messages' => $this->messages['database']['success']
			];

			return $output;

		} catch (\Exception $e) {
			return $output;
		}
	}

	public function getRoleData()
	{
		$url = $this->urlPermission;
		$type = 'get';
		$this->curlServices = App::make('App\Services\CurlServices');
		$this->curlServices->setUrl($url);
		$result = $this->curlServices->callApi($type, 'roles', [ 'status' => 'active' ], true);
		$datas = [];

		if(isset($result['data']))
			foreach ($result['data'] as $data) {
				$datas[$data['_id']['$oid']] = $data['name'];
			}

		return [
			'data' => $datas
		];
	}

	public function getDataUser($params)
	{
		$result = $this->getUsers($params);
		$allRecNum = $result['data']['list'];
		unset($result['data']['list']);
		
		$output = [
			'draw'            => $params['draw'],
			'recordsTotal'    => count($result['data']), //count page
			'recordsFiltered' => $allRecNum, //count all
			'data'            => $this->setDataTable($result['data'], $params),
			'input'           => $params
		];
		return json_encode($output);
	}

	public function setDataTable($data, array $params)
	{
		$dataTable = [];

		if (count($data) > 0) {
			foreach ($data as $kData => $vData) {
				$editIcon = '<a href="' . URL::to('user/' . $vData['id']) . '/edit" class="btn btn-xs" data-placement="top" data-original-title="Edit" title="Edit" ><i class="icon-pencil"></i></a>';
				if(Session::get('userId')!=$vData['id'])
				{
					$removeIcon = '<a onclick="deleteItems(\'' . $vData['id'] . '\')"><i class="icon-trash text-danger"></a>';
					$checkboxHTML = '<input class="ids check" type="checkbox" name="user_ids[]" value="' . $vData['id'] . '" >';
				}	
				else
				{
					$removeIcon = '<i class="icon-trash">';
					$checkboxHTML = '-';
				}

				$numberData = ($kData + 1) + $params['start'];
				$roleList = [];
				foreach ($vData['authorize'] as $role) {
					$roleList[] = "<span class=\"tag label label-info\" style=\"padding-right:15px;\">" . $role['name'] . "</span>";
				}
				$dataTable[] = [
					'checkbox'   => $checkboxHTML,
					'number'     => $numberData,
					'username'   => $vData['username'],
					'name'       => $vData['name'],
					'regis_date' => convertDateTime($vData['regis_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
					'mobile'     => $vData['mobile'],
					'email'      => $vData['email'],
					'makro_store_name' => $vData['makro_store_name'],
					'id'         => $vData['id'],
					'authorize'  => "<div class=\"bootstrap-tagsinput\">" . implode("", $roleList) . "</div>",
					'edit'       => $editIcon,
					'delete'     => $removeIcon
				];
			}
		}

		return $dataTable;
	}

	public function getUserReport($inputs) {

		$result = $this->getUsers($inputs);
        if (isset($result['data']['list']) && $result['data']['list'] > 0) {
			unset($result['data']['list']);
            $user = $result['data'];
            $start = $inputs['start'] + 1;
            return Excel::create('user_report_' . date('YmdHis'), function($excel) use ($user,$start) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('User', function($sheet) use ($user,$start) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Username',
                        'Name',
                        'Mobile Number',
                        'Email',
						'Store',
						'Employee ID',
						'Registration Date',
						'Roles'
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($user as $kData => $vData) {
                        ++$row;
						$roleList = [];
						foreach ($vData['authorize'] as $role) {
							$roleList[] = $role['name'];
						}
						$authorize = implode(",",$roleList);
                        $data = [
                            $start,
                            $this->_unit->removeFirstInjection($vData['username']),
                            $this->_unit->removeFirstInjection($vData['name']),
							sprintf('="%s"', array_get($vData, 'mobile', '')),
							$this->_unit->removeFirstInjection($vData['email']),
							$this->_unit->removeFirstInjection($vData['makro_store_name']),
							$this->_unit->removeFirstInjection($vData['employee_id']),
							(!empty($vData['regis_date'])) ? '="'.convertDateTime($vData['regis_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s').'"' : "",
							$this->_unit->removeFirstInjection($authorize)
                        ];

                        $start++;

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }else {

        return false;

        }

	}
}