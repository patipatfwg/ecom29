<?php
namespace App\Http\Controllers;

use App\Repositories\MembersRepository;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfileAddressRequest;
use App\Http\Requests\BusinessRequest;
use App\Http\Requests\BusinessAddressRequest;
use Illuminate\Http\Request;
use Validator;
use Response;

class MembersController extends \App\Http\Controllers\BaseController
{
    protected $redirect = [
        'login' => '/',
        'index' => 'member'
    ];

    protected $view = [
        'index' => 'member.index',
        'edit'  => 'member.edit'
    ];

    public function __construct(MembersRepository $membersRepository)
    {
        parent::__construct();
        $this->messages          = config('message');
        $this->membersRepository = $membersRepository;
    }

    /**
     * Method for any index
     */
    public function anyData(Request $request)
    {
        return $this->membersRepository->getDataMember($request->input());
    }

    /**
     * Method for report excel
     */
    public function report(Request $request)
    {
        $result = $this->membersRepository->getDataMemberReport($request->input());
        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return redirect($this->redirect['index']);
        }
    }

    /**
     * page index
     */
    public function index()
    {
        $stores = $this->membersRepository->getStoresFilter();

        $current_store = \Session::get('makroStoreId');

        return view($this->view['index'], [
                'stores' => $stores,
                'current_store' => $current_store
            ]
        );
    }

    /**
     * Method for get update
     */
    public function edit($id, Request $request)
    {
        $result = $this->membersRepository->getMember($id);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {

            //get provinces
            $address = $this->membersRepository->getAddress($id, $result, 'TH');
            $stores  = $this->membersRepository->getStores();

            return view($this->view['edit'], [
                'address' => $address,
                'stores'  => $stores,
                'result'  => $result['data']['records'][0]
            ]);

        } else {

            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => $result['errors']['message']
            ]);

            return redirect($this->redirect['index']);

        }
    }

    /**
     * Method for put update
     */
    public function update($id, Request $request, Validator $validator)
    {
        $input = $request->all();

        $rule  = [
            'profile'          => new ProfileRequest(),
            'profile_address'  => new ProfileAddressRequest(),
            'tax'              => new BusinessRequest(),
            'bill'             => new BusinessAddressRequest()
        ];

        if (isset($input['mode']) &&
            ($input['mode'] === 'profile'
                || $input['mode'] === 'profile_address'
                || $input['mode'] === 'tax'
                || $input['mode'] === 'bill'
            )) {

            return $this->updateAll($id, $request, $rule[$input['mode']], $validator);

        } else {
            abort(404);
        }
    }

    /**
     * Method for update my profile or shop profile
     */
    private function updateAll($id, $request, $formRequest, $validator)
    {
        $checkValidator = $validator::make($request->all(), $formRequest->rules(), $formRequest->messages());

        // check error validator
        if ($checkValidator->fails()) {

            $errors   = $checkValidator->messages ();
            $messages = implode("\n", $errors->all());

            return Response::json([
                'status'   => false,
                'messages' => $messages
            ]);

        } else {

            $result = $this->membersRepository->updateDB($id, $request->input());

            if (isset($result['status']) && $result['status']) {

                $request->session()->flash('messages', [
                    'type' => ($result['status']) ? 'success' : 'error',
                    'text' => $result['messages']
                ]);

                return Response::json(['status' => true]);

            } else {

                return Response::json([
                    'status'   => false,
                    'messages' => $result['messages']
                ]);
            }
        }
    }

    /**
     * Method for post address
     */
    public function address(Request $request)
    {
        $result = [];
        $input  = $request->input();

        if (isset($input['id']) && isset($input['type'])) {

            if ($input['type'] === 'districts') {

                $data = $this->membersRepository->getDistricts($input['id']);

            } else if ($input['type'] === 'sub_district') {

                $data = $this->membersRepository->getSubDistricts($input['id']);
            } else if ($input['type'] === 'postcode'){
                $postcode = $this->membersRepository->getPostcode($input['id']);
                return json_encode($postcode);
            }

            if (count($data) > 0) {
                foreach ($data as $kData => $vData) {
                    $select[] = [
                        'id'   => $kData,
                        'text' => $vData
                    ];
                }

                $result = json_encode($select);
            }
        }

        return $result;
    }

    public function delete(Request $request)
    {
        return Response::json($this->membersRepository->deleteMember($request->input('id')));
    }
}
?>