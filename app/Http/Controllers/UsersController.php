<?php

namespace App\Http\Controllers;

use App\Repositories\UsersRepository;
use App\Repositories\StoreRepository;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;

class UsersController extends \App\Http\Controllers\BaseController
{
	protected $redirect = [
		'login' => '/',
		'index' => 'user'
	];

	protected $view = [
		'index'     => 'user.index',
		'edit'      => 'user.edit',
		'tableForm' => 'user._table_form'
	];

	public function __construct(UsersRepository $usersRepository,StoreRepository $storeRepository)
	{
		parent::__construct();
		$this->messages = config('message');
		$this->usersRepository = $usersRepository;
		$this->storeRepository = $storeRepository;

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view($this->view['index']);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$roleData = $this->usersRepository->getRoleData();

		$getStoreParams  = [
            'limit' => '99999',
            'offset' => 0,
            'order' => 'makro_store_id|ASC',
        ];
		$storeData = $this->storeRepository->getStores($getStoreParams);
		
		$stores = [];
		foreach($storeData['data']['records'] as $val){
				$stores[$val['makro_store_id']] = $val['name']['th']." (".$val['makro_store_id'].")";
		}
		$stores = ['0' => 'All store'] + $stores;
		return view($this->view['edit'], [
			'userGroupData' => $roleData['data'],
			'stores'		=> $stores,
			'storeData'		=> isset($storeData['data']['records'])? $storeData['data']['records'] : []
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(UserRequest $request)
	{
		$inputs = $request->input();
		$params = [
			"username"   => $inputs['username'],
			"password"   => $inputs['password'],
			"name"       => $inputs['name'],
			"surname"    => $inputs['surname'],
			"email"      => $inputs['email'],
			"mobile"     => $inputs['mobile'],
			"userGroup" => $inputs['userGroup'],
			"makro_store_id" => ($inputs['makro_store_id']==0)?'':$inputs['makro_store_id']
		];

		return $this->usersRepository->createUser($params);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$getStoreParams  = [
            'limit' => '99999',
            'offset' => 0,
            'order' => 'makro_store_id|ASC',
        ];
		$storeData = $this->storeRepository->getStores($getStoreParams);
		
		$roleData = $this->usersRepository->getRoleData();
		$userData = $this->usersRepository->getUsers(['id' => $id]);

		$arrId = [];
		foreach ($userData['data'][0]['authorize'] as $role) {
			$arrId[] = $role['id'];
		}
		$arrId = implode(',', $arrId);
		
		$stores = [];
		$current_store = [];
		foreach($storeData['data']['records'] as $val){
			if($userData['data'][0]['makro_store_id'] == $val['makro_store_id']){
				$current_store[$val['makro_store_id']] = $val['name']['th']." (".$val['makro_store_id'].")";
			} else {
				$stores[$val['makro_store_id']] = $val['name']['th']." (".$val['makro_store_id'].")";
			}
		}
		if(!empty($current_store)){
			$stores = $current_store + $stores + ['0' => 'All store'];
		} else {
			$stores = ['0' => 'All store'] + $stores;
		}
		
		return view($this->view['edit'], [
			'id'             => $id,
			'userGroupData' => $roleData['data'],
			'userData'       => $userData['data'][0],
			'arrId'          => $arrId,
			'current_store'	=> $current_store,
			'stores'		=> $stores,
			'storeData'		 => isset($storeData['data']['records'])? $storeData['data']['records'] : []
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(UserUpdateRequest $request, $id)
	{
		$inputs = $request->input();
		
		$params = [
			"name"       => $inputs['name'],
			"surname"    => $inputs['surname'],
			"email"      => $inputs['email'],
			"mobile"     => $inputs['mobile'],
			"userGroup" => $inputs['userGroup'],
			"makro_store_id" => ($inputs['makro_store_id']==0)?'':$inputs['makro_store_id']
		];

		if (!empty($inputs['password'])) {
			$params['password'] = $inputs['password'];
		}

		return $this->usersRepository->updateUser($params, $id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$ids = explode(',', $id);

		foreach ($ids as $id) {
			$result = $this->usersRepository->deleteUser($id);
		}

		return $result;
	}

	public function getData(Request $request)
	{
		return $this->usersRepository->getDataUser($request->input());
	}

	/**
	 * Method for report excel
	 */
	public function exportUser(Request $request)
	{
		$result = $this->usersRepository->getUserReport($request->input());

		if (!$result) {
			$request->session()->flash('messages', [
				'type' => 'error',
				'text' => 'No Data',
			]);

            return view($this->view['index'], [
                'full_text'             => $request->input('full_text')
            ]);
		}

        return $result;
    }
}
