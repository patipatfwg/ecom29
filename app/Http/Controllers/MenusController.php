<?php
namespace App\Http\Controllers;

use App\Models\Menus;
use App\Repositories\MenusRepository;
use App\Http\Requests\MenusRequest;
use Illuminate\Http\Request;
use Validator;

class MenusController extends \App\Http\Controllers\BaseController
{
    protected $redirect = [
        'login' => '/',
        'index' => 'menu'
    ];

    protected $view = [
        'index' => 'menus.index'
    ];

    public function __construct(MenusRepository $menuRepository)
    {
        parent::__construct();
        $this->messages       = config('message');
        $this->menuRepository = $menuRepository;
    }

    /**
     * page index
     */
    public function index()
    {
        return view($this->view['index'], [
            'menus' => $this->menuRepository->nestable()
        ]);
    }

    /**
     * Method for post create
     */
    public function store(Request $request, MenusRequest $formRequest, Validator $validator)
    {
        $checkValidator = $validator::make($request->all(), $formRequest->rules(), []);

        // check error validator
        if ($checkValidator->fails()) {
            return redirect()->back()->withErrors($checkValidator->errors())->withInput();
        } else {
            // send to insert
            $result = $this->menuRepository->insertDB($request->input());

            if ($result['success']) {
                $request->session()->flash('messages', [
                    'type' => 'success',
                    'text' => $result['messages']
                ]);
                return redirect($this->redirect['index']);
            } else {
                return redirect()->back()->withErrors($result['messages'])->withInput();
            }
        }
    }

    /**
     * Method for post move nestable
     */
    public function move(Request $request)
    {
        // send to insert
        $result = $this->menuRepository->moveDB($request->input());
        if ($result['success']) {
            $request->session()->flash('messages', [
                'type' => 'success',
                'text' => $result['messages']
            ]);
            return redirect($this->redirect['index']);
        } else {
            return redirect()->back()->withErrors($result['messages'])->withInput();
        }
    }

    /**
    * Method for show data by id
    */
    public function edit($id, Request $request)
    {
        if ($request->ajax()) {
            $result = $this->menuRepository->editDB($id);
            return response()->json($result);
        }

        abort(404);
    }

    /**
    * Method for put update
    */
    public function update($id, Request $request, MenusRequest $formRequest, Validator $validator)
    {
        $checkValidator = $validator::make($request->all(), $formRequest->rules(), []);

        // check error validator
        if ($checkValidator->fails()) {
            return redirect()->back()->withErrors($checkValidator->errors())->withInput();
        } else {
            $result = $this->menuRepository->updateDB($id, $request->input());
            if ($result['success']) {
                $request->session()->flash('messages', [
                    'type' => 'success',
                    'text' => $result['messages']
                ]);
                return redirect($this->redirect['index']);
            } else {
                return redirect()->back()->withErrors($result['messages'])->withInput();
            }
        }
    }

    /**
    * Method for get delete
    */
    public function destroy($id, Request $request)
    {
        if ($request->ajax()) {
            $result = $this->menuRepository->deleteDB($id);
            return response()->json($result);
        }
    }
}
?>