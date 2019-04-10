<?php
namespace App\Http\Controllers;

use App\Repositories\BankRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Validator;
use App;

class BankController extends \App\Http\Controllers\BaseController
{
    protected $redirect = [
        'login' => '/',
        'index' => 'bank'
    ];

    protected $view = [
        'index' => 'bank.index'
    ];

    public function __construct(BankRepository $bankRepository)
    {
        parent::__construct();
        $this->messages          = config('message');
        $this->bankRepository = $bankRepository;
    }

    /**
     * Method for any index
     */
    public function anyData(Request $request)
    {
        return $this->bankRepository->getBank($request->input());
    }

    /**
     * Method for report excel
     */
    public function report(Request $request)
    {
  
        $result = $this->bankRepository->getDataBankReport($request->input());
        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return view($this->view['index']);
        }
    }

    /**
     * Method for change status
    */
    public function updateStatus($id, Request $request){
        $params = $request->input();
        return $result = $this->bankRepository->setStatus($id,$params);
    }

    /**
     * page index
     */
    public function index()
    {
        return view($this->view['index']);
    }
}
?>
