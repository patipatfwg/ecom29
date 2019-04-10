<?php
namespace App\Http\Controllers;

use App\Repositories\LoginRepository;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Validator;
use Session;
use App;

class LoginController extends \App\Http\Controllers\BaseController
{
    protected $messages;
    protected $loginRepo;

    protected $redirect = [
        'login'     => '/',
        'dashboard' => 'dashboard'
    ];

    protected $view = [
        'login' => 'login',
        'login_form' => 'login_form',
    ];

    public function __construct(LoginRepository $loginRepo)
    {
        parent::__construct();

        $this->messages   = config('message');
        $this->loginRepo  = $loginRepo;
    }

    /**
    * get login
    */
    public function getLogin(Request $request)
    {
        if ($request->session()->has('userId')) {
            return redirect($this->redirect['dashboard']);
        } else {
            return view($this->view['login']);
        }
    }

    /**
    * get login form this is to avoid csrf token caching
    */
    public function getLoginForm()
    {
        return view($this->view['login_form']);
    }

    /**
    * post check login
    */
    public function postLogin(Request $request, LoginRequest $formRequest, Validator $validator)
    {
        $checkValidator = $validator::make($request->all(), $formRequest->rules(), []);

        // check error validator
        if ($checkValidator->fails()) {
            return redirect()->back()->withErrors($checkValidator->errors())->withInput();

        } else {
            // get input form
            $inputs = $request->input();

            // send to insert
            //start_measure('login', 'Check Login');
            $result = $this->loginRepo->checkLogin($inputs);

            //stop_measure('login');

            if ($result['status']) {
                return $this->afterLogin($result);
            } else {
                return redirect()->back()->withErrors($result['messages'])->withInput();
            }
        }
    }

    /**
     * redirect to page input after update/add data to db
     */
    private function afterLogin($result)
    {
        if(isset($result['data']['id']) && isset($result['data']['username'])) {

            // $id = get_object_vars($result['data']['id']);

            //session admin
            Session::put('userId', $result['data']['id']);
            Session::put('userName', $result['data']['username']);
            Session::put('keyLogin', $result['data']['keyLogin']);
            Session::put('makroStoreId', $result['data']['makroStoreId']);

            //redirect to main page
            return redirect($this->redirect['dashboard']);
        } else {
            //redirect error login
            return redirect()->back()->withErrors($this->messages['login_fail'])->withInput();
        }
    }

    /**
     * logout
     */
    public function getLogout()
    {
        if(!empty(Session::get('userName'))) {
            //clear session
            Session::flush();
        }
        return redirect($this->redirect['login']);
    }
}
