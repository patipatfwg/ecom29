<?php
namespace App\Repositories;

use App\Services\Guzzle;
use App\Models\Users;
use Lang;
use Carbon\Carbon;


class PermissionRepository {

	protected $guzzle;
	protected $messages;
	protected $type = 'role';

	public function __construct(Guzzle $guzzle)
	{
		$this->urlPermission = config('api.makro_permission_api');
		$this->guzzle = $guzzle;
	}

	protected function getUserPermission($data)
	{
		$permission = [];
		foreach ($data as $key => $value) {
			foreach ($value as $vKey => $vData) {
				if(!empty($vData)){
					array_push($permission, $key);
					break;
				}
			}
		}
		return $permission;
	}

	protected function getPermissionModules($userPermission, $allPermission)
	{
		$allowAllPermission = true;
		foreach($allPermission as $permission){
			if(!in_array($permission, $userPermission)){
				$allowAllPermission = false;
				break;
			}
		}

		if($allowAllPermission){
			return 'All';
		}
		else{
			$module = [];
			foreach($userPermission as $permission){
				array_push($module, convertToDisplayWords($permission));
			}
			return implode(',', $module);
		}
	}

	public function can($username, $route)
	{
		$url = $this->urlPermission . 'users/' . $username . '/can/' . str_replace('.', '/', $route);
		$result = $this->guzzle->curl('GET', $url);

		/**update last accessed for user**/
		if ($username != "admin") {
			if (isset($result['status']['text']) && $result['status']['text'] == "Success") {
				$user = Users::where('username', '=', $username)->first();
				if ($user) {
					$user->last_accessed = Carbon::now();
					$user->save();
				}
			}

		} else {
			$user = Users::where('username', '=', $username)->first();
			if ($user) {
				$user->last_accessed = Carbon::now();
				$user->save();
			}
		}

		return isset($result['data']) ? $result['data'] : true;
	}

	public function canList($username)
	{
		$url = $this->urlPermission . 'users/' . $username;
		$result = $this->guzzle->curl('GET', $url);
		return $result;
	}

	public function getUserGroup($id)
	{
		$url = $this->urlPermission . 'roles/' . $id;
		$result = $this->guzzle->curl('GET', $url);

		$output = [];

		if (isset($result['status']) && isset($result['status']['code'])) {
			if ($result['status']['code'] == 200) {
				$output = $result['data'];
			}
		}

		return $output;
	}

	public function getUserGroups($inputs = [], $allPermission = [])
	{
		$params = [
			'query' => [
				'name'   => $inputs['full_text'],
				'offset' => $inputs['start'],
				'limit'  => $inputs['length']
			]
		];

		if(isset($inputs['order']) && $inputs['order'][0]['dir'] != 'false') {
			$params['query']['order'] = $inputs['order'][0]['column'].'|'.$inputs['order'][0]['dir'];
		}

		$url = $this->urlPermission . 'roles';
		$result = $this->guzzle->curl('get', $url, $params);

		// loop get data
		$dataModel = [];
		$count_page = 0;
		$count_all = 0;

		if (isset($result['status']) && !empty($result['status']) && $result['status']['code'] == 200) {

			if (isset($result['data']) && !empty($result['data'])) {

				foreach ($result['data'] as $kData => $vData) {

					$userPermission = $this->getUserPermission($vData['permission']);
					$module = $this->getPermissionModules($userPermission, $allPermission);

					$numberData = ($kData + 1) + $inputs['start'];
					$dataModel[] = [
						'id'        => $vData['_id']['$oid'],
						'number'    => $numberData,
						'name' => $vData['name'],
						'module'    => $module,
						'amount'    => $this->getUserGroupAmount($vData['_id']['$oid']),
						'edit'      => url('/user_group/' . $vData['_id']['$oid'] . '/edit'),
						'delete'	  => $vData['_id']['$oid']
					];
				}
				$count_page = count($result['data']); //count page
				$count_all = $result['total']['totalRecord']; //count all
			}
		}

		$output = [
			'draw'            => $inputs['draw'],
			'recordsTotal'    => $count_page, //count page
			'recordsFiltered' => $count_all, //count all
			'data'            => $dataModel,
			'input'           => $inputs
		];

		return json_encode($output);
	}

	public function createUserGroup($data)
	{
		$param = [
			'json' => $data
		];

		$url = $this->urlPermission . 'roles';

		return $this->guzzle->curl('POST', $url, $param);
	}

	public function updateUserGroup($id, $data)
	{
		$param = [
			'json' => $data
		];

		$url = $this->urlPermission . 'roles/' . $id;

		return $this->guzzle->curl('PUT', $url, $param);
	}

	public function deleteUserGroup($_id)
	{
		if (isset($_id)) {
			$result = $this->doDeleteUserGroup($_id);

			if(isset($result['status']['code']) && $result['status']['code'] == 200){
				return array('success' => true, 'messages' => Lang::get('validation.delete.success'));
			}
			else{
				return array('success' => false, 'messages' => $result['error']['message']);
			}
		}
	}

	public function getUserGroupAmount($roleId)
	{
		$url = $this->urlPermission . 'roles/' . $roleId . '/usage';
		$result = $this->guzzle->curl('get', $url, []);
		if (isset($result['status']['code']) && $result['status']['code'] == 200) {
			return $result['data']['total'];
		}
	}

	private function doDeleteUserGroup($user_group_id)
	{
		$url = $this->urlPermission . 'roles/' . $user_group_id;
		$result = $this->guzzle->curl('delete', $url, []);
		return $result;
	}

	public function checkLoginByKey($username, $keyLogin)
	{
		$users = Users::where('username', '=', $username)->first();
		return ($users->key_login === $keyLogin) ? true : false;
	}
}
