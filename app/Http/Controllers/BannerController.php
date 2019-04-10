<?php
namespace App\Http\Controllers;

use App\Repositories\BannerRepository;
use App\Repositories\PositionsRepository;
use Illuminate\Http\Request;
use App\Http\Requests\BannerRequest;
use App\Http\Requests\BannerUpdateRequest;
use Validator;
use Response;
use Session;
use Image;
use App;

class BannerController extends \App\Http\Controllers\BaseController
{
	public $position = [
        'page' => 'banner',
        'title' => 'Banner'
    ];

	protected $redirect = [
		'login' => '/',
		'index' => 'banner'
	];

	protected $view = [
		'index'  => 'banner.index',
		'create' => 'banner.create',
		'show'   => 'banner.show',
		'edit'   => 'banner.edit'
	];

	protected $language = 'th';

	public function __construct(BannerRepository $BannerRepository, PositionsRepository $PositionsRepository)
	{
		parent::__construct();

		$this->messages 			= config('message');
		$this->bannerRepository 	= $BannerRepository;
		$this->positionsRepository 	= $PositionsRepository;
	}

	public function index()
	{
		return view($this->view['index'], [
			'language'  => config('language.banner'),
			'status'    => 0,
			'parent_id' => ''
		]);
	}

	public function getData(Request $request)
    {
		$param = $request->input();
        return $this->bannerRepository->getBanners($param);
    }

	public function create()
	{
		return view($this->view['edit'], [
			'language'  => config('language.banner'),
			'slug_input_name' => 'banner_name',
			'event' => 'Create'
		]);
	}

	public function destroy($ids)
	{
		return $this->bannerRepository->getDeleteBanner($ids);
	}

	public function store(BannerRequest $request)
	{
		$inputs = $request->input();

        if ($request->hasFile('thumb')) {
            $result = $this->bannerRepository->uploadImage('thumb');
            $inputs['image_url'] = $result['image'];
        }

		$inputs['name'] = $inputs['banner_name'];
        $bannerResult = $this->bannerRepository->createBanner($inputs);

        if ($bannerResult['status'] == false) {
            return $bannerResult;
        }

		return $bannerResult;
	}

	public function update(BannerUpdateRequest $request, $id)
	{
		$inputs = $request->input();

        if ($request->hasFile('thumb')) {
            $result = $this->bannerRepository->uploadImage('thumb');
            $inputs['image_url'] = $result['image'];
        } else {
			$inputs['image_url'] = $inputs['thumb_old'];
		}
		$inputs['name'] = $inputs['banner_name'];
        $bannerResult = $this->bannerRepository->postUpdateBanner($inputs, $id);

		return $bannerResult;
	}

	public function editBanner($id)
	{
        $bannerData = $this->bannerRepository->getBanner($id);

        return view($this->view['edit'], [
			'language'        => config('language.campaign'),
			'bannerId'        => $id,
			'bannerData'      => $bannerData,
			'slug_input_name' => 'banner_name',
			'event'           => 'Edit'
        ]);
	}
}
?>
