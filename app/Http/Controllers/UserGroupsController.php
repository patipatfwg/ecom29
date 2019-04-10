<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PermissionRepository;
use App\Repositories\MenusRepository;
use App\Http\Requests\UserGroupsRequest;
use Response;
use Route;

class UserGroupsController extends \App\Http\Controllers\BaseController
{

	protected $redirect = [
		'login' => '/',
		'index' => 'user_group'
	];

	protected $view = [
		'index'  => 'user_group.index',
		'create' => 'user_group.create',
		'edit'   => 'user_group.edit'
	];

	private $always_allow_routes = ['dashboard', 'debugbar', 'uploadfile'];

	private $permission_mapping = [
		'list'   => [
			'read'  => ['index', 'data'],
			'write' => ['destroy']
		],
		'detail' => [
			'read'  => ['show', 'create', 'edit'],
			'write' => ['store', 'update']
		]
	];

	public function __construct(PermissionRepository $permissionRepository, MenusRepository $menusRepository)
	{
		parent::__construct();
		$this->messages = config('message');
		$this->permissionRepository = $permissionRepository;
		$this->menusRepository = $menusRepository;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{	
		$permission = $this->getAllRole();
		return view($this->view['index']);
	}

	public function getAjaxUserGroups(Request $request)
	{
		$param = $request->input();
		$allPermission = $this->getAllRole();
		return $this->permissionRepository->getUserGroups($param, $allPermission);
	}

	protected function getMenuList()
	{
		$routes = Route::getRoutes();
		$menus = [];

		foreach ($routes as $route) {
			$name = $route->getName();
			$names = explode('.', $name);
			if (!empty($names[0]) && !in_array($names[0], $this->always_allow_routes)) {
				if (empty($menus[$names[0]])) {
					if($names[0] == 'category'){
						$namecategory = 'product_category';
						$menus[$names[0]] = [
							'name'     => 'Manage ' . convertToDisplayWords($namecategory),
							'sub_menu' => []
						];
					}else if($names[0] == 'category_business'){
						$namecategory = 'business_category';
						$menus[$names[0]] = [
							'name'     => 'Manage ' . convertToDisplayWords($namecategory),
							'sub_menu' => []
						];
					}else{
						$menus[$names[0]] = [
							'name'     => 'Manage ' . convertToDisplayWords($names[0]),
							'sub_menu' => []
						];
					}
				}

				if (!empty($names[1])) {
					$in_mapping = false;
					foreach ($this->permission_mapping as $key => $mapping) {
						foreach ($mapping as $mapped_action => $permission_list) {
							if (in_array($names[1], $this->permission_mapping[$key][$mapped_action])) {

								if (empty($menus[$names[0]]['sub_menu'][$key])) {
									$menus[$names[0]]['sub_menu'][$key] = [
										'name'       => convertToDisplayWords($names[0]) . ' ' . convertToDisplayWords($key),
										'permission' => []
									];
								}

								$menus[$names[0]]['sub_menu'][$key]['permission'][$mapped_action] = false;
								ksort($menus[$names[0]]['sub_menu'][$key]['permission']);
								$in_mapping = true;
							}
						}
					}

					if (!$in_mapping) {
						if (empty($menus[$names[0]]['sub_menu'][$names[0]])) {
							$menus[$names[0]]['sub_menu'][$names[0]] = [
								'name'       => convertToDisplayWords($names[0]),
								'permission' => []
							];
						}

						$menus[$names[0]]['sub_menu'][$names[0]]['permission'][$names[1]] = false;
					}
				}
			}
		}

		return $menus;
	}

	protected function getAllRole()
	{
		$permission = [];
		$routes = Route::getRoutes();
		foreach($routes as $route){
			$name = $route->getName();
			
			if(empty($name)){
				continue;
			}
			
			$names = explode('.', $name);
			$routeName = $names[0];
			
			if(!in_array($routeName, $this->always_allow_routes)){
				if(!in_array($routeName, $permission)){
					array_push($permission, $routeName);
				}
			}
		}
		return $permission;
	}

	protected function getRole($inputs)
	{
		$routes = Route::getRoutes();

		$role = [
			'name'       => $inputs['name'],
			'status'     => isset($inputs['status']) ? 'active' : 'inactive',
			'permission' => []
		];

		$permission = [];

		if (!empty($inputs['permission'])) {
			$permission = $inputs['permission'];
		}

		foreach ($routes as $route) {
			$name = $route->getName();

			if (!empty($name)) {
				$names = explode('.', $name);

				if (!in_array($names[0], $this->always_allow_routes)) {
					if (empty($role['permission'][$names[0]])) {
						$role['permission'][$names[0]] = [];
					}

					if (empty($role['permission'][$names[0]][$names[1]]) && !empty($names[1])) {
						$role['permission'][$names[0]][$names[1]] = false;
					}
				}
			}
		}

		foreach ($permission as $resource_key => $resources) {
			if (!in_array($resource_key, $this->always_allow_routes)) {
				foreach ($resources as $key => $actions) {
					foreach ($actions as $action_key => $value) {
						if (!empty($this->permission_mapping[$key][$action_key])) {
							foreach ($this->permission_mapping[$key][$action_key] as $mapped_action) {
								$role['permission'][$resource_key][$mapped_action] = true;
							}
						} else {
							$role['permission'][$resource_key][$action_key] = true;
						}
					}
				}
			}
		}

		return $role;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function create()
	{
		return view($this->view['create'], [
			'menus' => $this->getMenuList()
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(UserGroupsRequest $request)
	{
		$inputs = $request->input();

		$role = $this->getRole($inputs);

		$result = $this->permissionRepository->createUserGroup($role);

		if (!isset($result['status']['code']) || $result['status']['code'] != 200) {
			return Response::json(array('status' => false, 'error' => $result['error']['message']));
		}

		return Response::json(array('status' => true, 'message' => "success"));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
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
		$menus = $this->getMenuList();
		$result = $this->permissionRepository->getUserGroup($id);

		foreach ($result['permission'] as $resource_key => $actions) {
			if (!in_array($resource_key, $this->always_allow_routes, TRUE)) {
				foreach ($actions as $action_key => $value) {
					if ($value) {
						$in_mapping = false;
						foreach ($this->permission_mapping as $key => $mapping) {
							foreach ($mapping as $mapped_action => $permission_list) {
								if (in_array($action_key, $permission_list,TRUE) && isset($menus[$resource_key])) {
									$menus[$resource_key]['sub_menu'][$key]['permission'][$mapped_action] = true;
									$in_mapping = true;
								}
							}
						}

						if (!$in_mapping && isset($menus[$resource_key])) {
							$menus[$resource_key]['sub_menu'][$resource_key]['permission'][$action_key] = true;
						}
					}
				}
			}

		}

		return view($this->view['edit'], [
			'userGroupId' => $id,
			'menus'       => $menus,
			'name'        => $result['name'],
			'status'	  => $result['status']
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
	public function update(UserGroupsRequest $request, $id)
	{
		$inputs = $request->input();

		$role = $this->getRole($inputs);

		$result = $this->permissionRepository->updateUserGroup($id, $role);

		if (!isset($result['status']['code']) || $result['status']['code'] != 200) {
			return Response::json(array('status' => false, 'error' => $result['error']['message']));
		}

		return Response::json(array('status' => true, 'message' => "success"));
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
			$result = $this->permissionRepository->deleteUserGroup($id);
		}

		return $result;
	}
}
