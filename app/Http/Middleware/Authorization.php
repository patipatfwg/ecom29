<?php
namespace App\Http\Middleware;

use App\Repositories\PermissionRepository;

use Closure;
use Route;
use Session;

class Authorization {
	private $messages;
	private $permissionRepository;

	public function __construct(PermissionRepository $permissionRepository)
	{
		$this->messages = config('message');
		$this->permissionRepository = $permissionRepository;
	}

	public function handle($request, Closure $next)
	{
		$username = $request->session()->get('userName');
		$route = Route::getCurrentRoute()->getName();

		if(!empty($request->session()->get('keyLogin')) && $this->permissionRepository->checkLoginByKey($username, $request->session()->get('keyLogin'))){
			$permission = $this->getPermission($username);
			$request->session()->put('permission_menus' , $permission);

			if ($this->permissionRepository->can($username, $route)) {
				return $next($request);
			} else {
				$list = [
					'permission' => 'Permission Deny'
				];
				if ($request->ajax())
				{
					return response()->json($list, 422);
				}
			}

			return redirect('/dashboard')->withErrors($this->messages['permission']);
		}
		
		Session::flush();
		return redirect('/')->withErrors($this->messages['conflict_login_error']);
	}
	private function getPermission($username)
	{
		$permissions = [];

		if (!empty($username)) {
			$permission = $this->permissionRepository->canList($username);
			if (!empty($permission['data']['permissions'])) {
				$permissions = $permission['data']['permissions'];
			}
			$permissions['dashboard']['index'] = true;
		}

		return $permissions;
	}

}