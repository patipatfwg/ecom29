<?php
namespace App\Http\Controllers;

use App\Repositories\BrandRepository;
use App\Repositories\PositionsRepository;
use Illuminate\Http\Request;
use App\Http\Requests\BrandCreateRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Events\CategoryUpdated;
use Validator;
use Response;
use Session;
use Image;
use App;

class BrandController extends \App\Http\Controllers\BaseController
{
    public $position = [
        'page' => 'brand',
        'title' => 'Brand'
    ];

    protected $redirect = [
        'login' => '/',
        'index' => 'brand'
    ];

    protected $view = [
        'index' => 'brand.index',
        'create' => 'brand.create',
        'show' => 'brand.show',
        'edit' => 'brand.edit'
    ];

    protected $brand_image_positions = ['A', 'B', 'thumb'];

    public function __construct(BrandRepository $repository, PositionsRepository $PositionsRepository)
    {
        parent::__construct();
        $this->messages            = config('message');
        $this->repository          = $repository;
        $this->positionsRepository = $PositionsRepository;
    }

    /**
     * page index
     */
    public function index(Request $request)
    {
        $status = array();
        $isStatus = Session::has('status');
        if ($isStatus) {
            $status = Session::get('status');
        }

        return view($this->view['index'], [
            'language' => config('language.brand'),
            'status' => ['status' => $isStatus, 'msg' => $status]
        ]);
    }

    /**
     * page index
     */
    public function getAjaxBrand(Request $request)
    {
        $param = $request->input();
        return $this->repository->getDataBrand($param);
    }

    /**
     * page create
     */
    public function create($parent_id = null)
    {
        return view($this->view['create'], [
            'language' => config('language.brand')
        ]);
    }

    public function save(Request $request)
    {
        $params = $request->input();

        if ($request->file('thumb')) {
            $img = $this->repository->uploadImage('thumb');
            $params['image'] = $img['image'];
        }

        $result = $this->repository->createBrand($params);

        return redirect('brand')->with('status', $result);
    }

    /**
     * Method for post create
     */
    public function show($_id, Request $request)
    {
        return view($this->view['show'], [
            'brand_id' => $_id,
            'breadcrumb' => $this->repository->getBreadcrumbBrand($_id, 0, 'th')
        ]);
    }

    /**
     * Method for post create
     */
    public function store(BrandCreateRequest $request)
    {
        $params = $request->input();

        // Set up images input
        $images = [];
        foreach ($params['images'] as $key => $value) {
            $file = $value['input_file_name'];

            // Upload Image to CDN
            if ($request->file($file)) {
                $img = $this->repository->uploadImage($file);
                if ($img['success'] == true) {
                    array_push($images, [
                        'position' => $value['type'],
                        'image' => $img['image'],
                        'url' => !empty($value['url']) ? $value['url'] : ''
                    ]);
                }
            }
        }

        unset($params['images']);

        if (!empty($images)) {
            $params['images'] = $images;
        }

        $result = $this->repository->createBrand($params);

        if ($result['status']['code'] == 200) {
            return Response::json(['status' => true]);
        } else {
            return Response::json(['status' => false, 'messages' => $result['error']['message']]);
        }
    }

    /**
     * Method for post update
     */
    public function update($id, BrandUpdateRequest $request)
    {
        $params = $request->input();

        $images = [];
        foreach ($params['images'] as $key => $value) {
            $file = $value['input_file_name'];

            // Upload Image to CDN
            if ($request->file($file)) {
                $img = $this->repository->uploadImage($file);
                if ($img['success'] == true) {
                    array_push($images, [
                        'position' => $value['type'],
                        'image' => $img['image'],
                        'url' => !empty($value['url']) ? $value['url'] : ''
                    ]);
                }
            } else if (!empty($value['old'])) {
                array_push($images, [
                    'position' => $value['type'],
                    'image' => $value['old'],
                    'url' => !empty($value['url']) ? $value['url'] : ''
                ]);
            }
        }

        unset($params['images']);

        if (!empty($images)) {
            $params['images'] = $images;
        }

        $result = $this->repository->updateBrand($params, $id);

        if ($result['status']['code'] == 200) {
            return Response::json(['status' => true]);
        } else {
            return Response::json(['status' => false, 'messages' => $result['error']['message']]);
        }
    }

    /**
     * Method for post create
     */
    public function edit($id, Request $request)
    {
        $brand = $this->repository->getBrandById($id);

        $default = [
            'name_en' => '',
            'description_en' => ''
        ];

        if (isset($brand['image_detail']) && !empty($brand['image_detail'])) {

            foreach ($brand['image_detail'] as $image) {

                if (in_array($image['position'], $this->brand_image_positions)) {
                    $position = $image['position'];
                    $brand['images'][$position]['image'] = $image['image'];
                    $brand['images'][$position]['url'] = isset($image['url']) ? $image['url'] : '';
                }

            }

            unset($brand['image_detail']);
        }

        return view($this->view['edit'], [
            'brand_id' => $id,
            'language' => config('language.brand'),
            'brand' => array_merge($default, $brand),
        ]);

        return Response::json($result);
    }

    /**
     * Method for get delete
     */
    public function destroy($id, Request $request)
    {
        if ($request->ajax()) {
            $result = $this->repository->deleteBrand($id);
            return Response::json($result);
        }
    }

    public function updatePriority(Request $request)
    {
        if ($request->ajax()) {
            $inputs = $request->input();
            $result = $this->repository->updateBrandMultiple($inputs);
            return Response::json($result);
        }
    }

    public function exportBrand(Request $request)
    {
        $param = $request->input();
        $param['language'] = 'th';
        $result = $this->repository->getDataBrandReport($param);

        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);

            return redirect($this->redirect['index']);
        }
    }

    public function setStatus(Request $request)
    {
        $param = $request->input();
        $result = $this->repository->setStatusBrand($param);

        if ($result['success']) {
            foreach ($result['data']['success'] as $brand) {
                event(new CategoryUpdated($brand['id']));
            }
        }

        return $result;
    }

    public function check_del($ids)
    {
        $result = $this->repository->check_del($ids);
        return $result;
    }
}
?>