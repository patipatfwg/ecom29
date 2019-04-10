<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use Config;
use Carbon\Carbon;

class LoginRepository
{
    protected $user;
    protected $messages;

    public function __construct(Users $user)
    {
        $this->user    = $user;
        $this->messages = config('message');
    }

    /**
     * check login user bank
     *
     * @param  Array $params [username, password]
     * @return true, false
    */
    public function checkLogin($params)
    {
        $output = [
            'status'   => false,
            'messages' => $this->messages['login_fail']
        ];

        try {
            //get username
            $model = Users::where([
                ['username','=', $params['username']],
                ['deleted_at', '=', '']
            ])->first();

            // check hash password
            if ($model !== null && Hash::check($params['password'], $model->password)) {
                $model->key_login = md5($model->username . '-' . strtotime('now'));
                if($model->save()){
                    $output = [
                        'status' => true,
                        'data'   => [
                            'id'           => $model->_id,
                            'username'     => $model->username,
                            'keyLogin'     => md5($model->username . '-' . strtotime('now')),
                            'makroStoreId' => $model->makro_store_id
                        ]
                    ];
                }
            }
        } catch (Exception $e) {
            $output = [
                'status'  => false,
                'messages' => $this->messages['database']['cannot_connect']
            ];
        }

        return $output;
    }
}
