<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Requests\CronmemberRequest;
use App\Repositories\CronRepository;
use Validator;
use App;

class CronController extends BaseController
{
    protected $redirect = [
        'login' => '/',
        'index' => 'cron'
    ];

    protected $view = [
        'member.index' => 'cron.member.index'
    ];

    public function __construct(CronRepository $cronRepository)
    {
        parent::__construct();
        $this->messages          = config('message');
        $this->cronRepository    = $cronRepository;
    }

    /**************************
     * Manage Member Cronjob
     **************************/
    /**`
     * Method for get dataTable
     */
    public function dataTableMembers(CronmemberRequest $request)
    {
        $inputs = $request->input();
        $dataTable = $this->cronRepository->dataTableMembers($inputs); 
        return $dataTable;
    }
    /**
     * Method for update count
    */
    public function updateMemberCount(Request $request)
    {   
        $params = $request->input();         
        return $result = $this->cronRepository->update($params);
    }
    /**
     * Method for update count
    */
    public function updateMemberCountById($id)
    {
        $params['member_ids'][] = $id;
        return $result = $this->cronRepository->update($params);
    }
    /**
     * Method for member index
     */
    public function member()
    {
        $error_status = $this->cronRepository->memberErrorStatus();
        return view($this->view['member.index'], [
            'error_status' => $error_status
        ]);
    }

    /****************************
     * End Manage Member Cronjob
     ****************************/
}
?>
