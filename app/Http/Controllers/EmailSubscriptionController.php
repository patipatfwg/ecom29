<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Requests\Request;
use App\Repositories\EmailSubscriptionRepository;
use Illuminate\Support\Facades\Redirect;

class EmailSubscriptionController extends \App\Http\Controllers\BaseController
{

    public function __construct(EmailSubscriptionRepository $emailSubscriptionRepository)
    {
        parent::__construct();
        $this->messages             = config('message');
        $this->emailSubscriptionRepository  = $emailSubscriptionRepository;
    }


    protected $redirect = [
        'login' => '/',
        'index' => 'email_subscription'
    ];

    protected $view = [
        'index' => 'email_subscription.index'
    ];

    public function index()
    {
        return view($this->view['index']);
    }

    public function anyData(Request $request)
    {
        return $this->emailSubscriptionRepository->getSubscribe($request->input());
    }

    public function report(Request $request)
    {
        $result = $this->emailSubscriptionRepository->getSubscriptionReport($request->input());
        if (!$result) {
			$request->session()->flash('messages', [
				'type' => 'error',
				'text' => 'No Data'
			]);

			return redirect($this->redirect['index'])->with('input',[
			    'date-start'=> $request->input('search')[1]['value'],
                'date-end'=> $request->input('search')[2]['value']
            ]);
		}
        return $result;
    }

}

