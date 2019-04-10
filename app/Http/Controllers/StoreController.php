<?php
namespace App\Http\Controllers;

use App\Repositories\StoreRepository;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfileAddressRequest;
use App\Http\Requests\BusinessRequest;
use App\Http\Requests\BusinessAddressRequest;
use Illuminate\Http\Request;
use Validator;
use Response;

class StoreController extends \App\Http\Controllers\BaseController
{
    protected $redirect = [
        'login' => '/',
        'index' => 'store'
    ];

    protected $view = [
        'index' => 'store.index',
        'edit'  => 'store.edit'
    ];

    public function __construct(StoreRepository $storeRepository)
    {
        parent::__construct();
        $this->messages          = config('message');
        $this->storeRepository = $storeRepository;
    }

    /**
     * Method for any index
     */
    public function anyData(Request $request)
    {   
        $params = $request->input();
        return $this->storeRepository->getStore($params);
    }

    /**
     * Method for report excel
     */
    public function report(Request $request)
    {
        $result = $this->storeRepository->getDataStoreReport($request->input());
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
        $region = $this->storeRepository->getRegionSearch();
        return view($this->view['index'],[
            'region' => $region
        ]);
    }

    /**
     * Method for get update
     */
    public function edit($id, Request $request)
    {
        $store = $this->storeRepository->getStoreById($id);
        if(isset($store['data']['records'][0]['region'])) {
            $storeRegionById = $this->storeRepository->getRegionById($store['data']['records'][0]['region']);
            $storeRegion = $storeRegionById['data']['records'][0]['name']['th'] . ' (' . $storeRegionById['data']['records'][0]['name']['en'] . ')';
            $store['data']['records'][0]['region'] = $storeRegion;
        }
        $address = $this->storeRepository->getAddress($id, $store, 'TH');
        $region = $this->storeRepository->getRegion();

        return view($this->view['edit'], [
            'id' => $id,
            'store' => $store['data']['records'][0],
            'address' => $address,
            'region'  => $region,
            'language' => config('language.coupon'),
        ]);
        
    }

    /**
     * Method for put update
     */
    public function update($id, Request $request)
    {
        $input = $request->all();
        if(isset($input['Status']))
        {
            $input['Status'] = 'active';
        }else {
            $input['Status'] = 'inactive';
        }
        
        if(isset($input['Delivery']))
        {
            $input['Delivery'] = 'Y';
        }else {
            $input['Delivery'] = 'N';
        }

        $store = [
            'name_th'           => $input['store_name']['th'],
            'name_en'           => $input['store_name']['en'],
            'contact_phone'     => $input['contact_phone'],
            'contact_fax'       => $input['contact_fax'],
            'status'            => $input['Status'],
            'have_delivery'     => $input['Delivery'],
            'region'            => $input['region'],
        ];

        $address = [
            'id' => $input['store_id'],
            'address' => [
                'th' => $input['address_line_1']['th'],
                'en' => $input['address_line_1']['en']
            ],
            'address2' => [
                'th' => $input['address_line_2']['th'],
                'en' => $input['address_line_2']['en']
            ],
            'subdistrict' => [
                'id' => isset($input['store_sub_district'])? $input['store_sub_district'] : ''
            ],
            'district' => [
                'id' => isset($input['store_districts'])? $input['store_districts'] : ''
            ],
            'province' => [
                'id' => isset($input['store_province'])? $input['store_province'] : ''
            ],
            'postcode' => isset($input['store_postcode'])? $input['store_postcode'] : '',
            'location' => [
                'lat' => $input['latitude'],
                'long' => $input['longitude']
            ]
        ];
        
        $resultStore = $this->storeRepository->updateStore($id,$store);
        if($resultStore['status']['code'] == 200) {
            $resultAddress = $this->storeRepository->updateAddress($address);
            return $resultAddress;
        }
        return $resultStore;

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

                $data = $this->storeRepository->getDistricts($input['id']);

            } else if ($input['type'] === 'sub_district') {

                $data = $this->storeRepository->getSubDistricts($input['id']);
            } else if ($input['type'] === 'postcode'){
                $postcode = $this->storeRepository->getPostcode($input['id']);
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

    /**
     * Method for change status
    */
    public function updateStatus($id, Request $request){
        $params = $request->input();
        return $result = $this->storeRepository->setStatus($id, $params);
    }
}
?>