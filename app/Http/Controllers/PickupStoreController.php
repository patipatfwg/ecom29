<?php
namespace App\Http\Controllers;

use App\Repositories\PickupStoreRepository;
use App\Repositories\StoreRepository;
use Illuminate\Http\Request;
use Response;
use App;

class PickupStoreController extends \App\Http\Controllers\BaseController
{
    protected $redirect = [
        'login'         => '/',
        'index'         => 'pickupstore',
        'coupon_report' => 'coupon_report',
        'usage_report'  => 'usage_report'
    ];

    protected $view = [
        'index'         => 'pickupstore.index',
        'edit'          => 'pickupstore.edit',
    ];

    public function __construct(PickupStoreRepository $reportRepository, StoreRepository $storeRepository)
    {
        parent::__construct();
        $this->messages           = config('message');
        $this->reportRepository   = $reportRepository;
        $this->storeRepository    = $storeRepository;
        $this->_bss_cache_service = App::make('App\Services\Bss\CacheServices');
    }

    /**
     * Method for any index
     */
    public function anyData(Request $request)
    {
        return $this->reportRepository->getDataStore($request->input());
    }

    /**
     * page index
     */
    public function index()
    {
        $getStoreParams  = [
            'limit' => 9999,
            'offset' => 0,
            'order' => 'makro_store_id|ASC',
        ];

        $stores = $this->storeRepository->getStoreSelect($getStoreParams);

        return view($this->view['index'],[
            'stores' => ['All'=>'All'] + $stores,
            'status' => [
                'All' => 'All',
                'Y'   => 'Y',
                'N'   => 'N'
            ],
            'message' => $this->messages
        ]);
    }
    public function saveDataEdit(Request $request)
    {
        $params = $fails = [];
        if ($request->has('datas')) {
            foreach ($request->input('datas') as $id => $value) {
                $stores = $this->storeRepository->updateStore($id, $value);
                if (empty($stores['data'])) {
                    $fails[] = $id;
                }
            }
            // clear cache by service
            $this->_bss_cache_service->flushCacheByService('api-store');
        }

        return Response::json(array('status' => true , 'fail' => $fails));
    }
}
