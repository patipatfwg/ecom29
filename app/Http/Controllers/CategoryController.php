<?php
namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\PositionsRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ReportRepository;
use App\Repositories\StoreRepository;
use App\Repositories\CategoryBusinessRepository;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Events\CategoryUpdated;
use Illuminate\Http\Request;
use App\Services\MyServices;
use Validator;
use Response;
use Session;
use Image;
use App;
use Lang;
use App\Listeners\ProductUpdatedEventListener;
use App\Events\ProductUpdated;
use App\Services\Guzzle;
use App\Library\HtmlPurifier;
class CategoryController extends \App\Http\Controllers\BaseController
{
	public $position = [
        'page' => 'category',
        'title' => 'Product Category'
    ];

	protected $redirect = [
		'login' => '/',
		'index' => 'category'
	];

	protected $view = [
		'index'  => 'category.index',
		'create' => 'category.create',
		'show'   => 'category.show',
		'edit'   => 'category.edit',
		'product'=> 'category.product'
	];

	private $checkRules = [
        [
			'input.th.name_th' => 'required',
			'input.en.name_en' => 'required',
			'image_position_A' => 'image|mimes:jpg,jpeg,png|dimensions:width=855,height=380',
			'image_position_B' => 'image|mimes:jpg,jpeg,png|dimensions:width=285,height=380',
		],
		[
			'input.th.name_th' => 'required',
			'input.en.name_en' => 'required',
		    'image_position_D1_1' => 'image|mimes:jpg,jpeg,png|dimensions:width=285,height=380',
		    'image_position_D1_2' => 'image|mimes:jpg,jpeg,png|dimensions:width=285,height=380',
		    'image_position_D1_3' => 'image|mimes:jpg,jpeg,png|dimensions:width=285,height=380',
		    'image_position_D2'   => 'image|mimes:jpg,jpeg,png|dimensions:width=380,height=190',
		    'image_position_D3'   => 'image|mimes:jpg,jpeg,png|dimensions:width=190,height=190',
		    'image_position_D4'   => 'image|mimes:jpg,jpeg,png|dimensions:width=190,height=190',
		    'image_position_D5'   => 'image|mimes:jpg,jpeg,png|dimensions:width=190,height=190',
		    'image_position_D6'   => 'image|mimes:jpg,jpeg,png|dimensions:width=190,height=190',
		    'image_position_A'    => 'image|mimes:jpg,jpeg,png|dimensions:width=1140,height=380',
		],
		[
			'input.th.name_th' => 'required',
			'input.en.name_en' => 'required',
            'image_position_A' => 'image|mimes:jpg,jpeg,png|dimensions:width=1140,height=380'
		]
	];

	protected $language = 'th';

	public function __construct(CategoryRepository $repository,CategoryBusinessRepository $CategoryBusinessRepository, MyServices $myServices, PositionsRepository $PositionsRepository, ProductRepository $ProductRepository, StoreRepository $StoreRepository,ReportRepository $ReportRepository,Guzzle $guzzle)
	{
		parent::__construct();

		$this->messages   = config('message');
		$this->repository = $repository;
		$this->myServices = $myServices;
		$this->positionsRepository  = $PositionsRepository;
		$this->productRepository 	= $ProductRepository;
		$this->storeRepository 		= $StoreRepository;
		$this->reportRepository 	= $ReportRepository;
		$this->categoryBusinessRepository 	= $CategoryBusinessRepository;
		$this->guzzle 				= $guzzle;
		$this->htmlPurifier         = new HtmlPurifier;
	}

	public function index()
	{
		return view($this->view['index'], [
			'language'  => config('language.category'),
			'status'    => 0,
			'parent_id' => ''
		]);
	}

	public function create($parent_id = null)
	{
		return view($this->view['create'], [
			'language'   => config('language.category'),
			'category'   => $this->repository->getRootCategory(),
			'attribute'  => $this->repository->getAttribute(),
			'parent_id'  => isset($parent_id) ? $parent_id : null,
			'level'      => isset($parent_id) ? $this->repository->getLevelCategory($parent_id) : 0,
			'breadcrumb' => isset($parent_id) ? $this->repository->getBreadcrumbCategory($parent_id, 0, 'th') : [],
		]);
	}

	public function product($category_id , $type)
	{
		return view($this->view['product'], [
			'category_id'	=> $category_id,
			'product'		=> $this->seleteProductSearch(),
			'stores' 		=> $this->getStore(),
			'type'			=> $type,
			'name'    		=> $type == 'product' ? "Product Category" : "Business Category",
			'breadcrumb'    => $type == 'product' ? $this->repository->getBreadcrumbCategory($category_id, 0, 'th') : $this->categoryBusinessRepository->getBreadcrumbCategory($category_id, 0, 'th'),
		]);
	}

	private function seleteProductSearch()
	{
		$datas = [];

		$product = $this->getSelectProduct();
		if (!empty($product)) {
			foreach ($product as $value) {
				$datas[$value['item_id']] = $value['name_show'];
			}
		}

		return $datas;
	}
	public function saveProduct($category_id , $type , Request $request)
	{
		$output = [];

		if ($request->has('product') && !empty($category_id)) {

			$content_type			= 'product';
			$params['category_id']  = $category_id;
			$params['content_type'] = $content_type;

			$params_getProducts['item_id'] = implode(',',$request->input('product'));
			$params_getProducts['start']   = 0;
			$params_getProducts['length']  = 99999;

			$result = $this->getProducts($params_getProducts);

			$dataProduct = [];
			if (!empty($result)) {
				foreach ($result as $value) {
					$params['content_id'][] 	= $value['id'];
					$dataProduct[$value['id']]  = $value['item_id'];
				}
			}

			if (isset($params['content_id'])) {
				$result = $this->repository->addContentCategory($params);
				if (isset($result['status']['code']) && $result['status']['code'] == 200 && !empty($result['data'])) {
					$success   = '-';
					$duplicate = '-';
					if (!empty($result['data']['success'])) {
						$success   = '';
						$str_line = '';
						foreach ($result['data']['success'] as  $content_id) {
							$success .= $str_line . $dataProduct[$content_id];
							$str_line = ',';
							// update to Queue
							$this->updateQueue($content_id , $content_type);
						}
					}
					if (!empty($result['data']['duplicate'])) {
						$duplicate = '';
						$str_line = '';
						foreach ($result['data']['duplicate'] as  $content_id) {
							$duplicate .= $str_line . $dataProduct[$content_id];
							$str_line = ',';
						}
					}

					Session::flash('alert', [
						'type' => 'success',
						'text' => "<p>Success : " . $success ."</p><p>Duplicate : " . $duplicate ."</p>"
					]);
					return redirect('category/'.$category_id.'/'.$type);
				}
			}
		}

		Session::flash('alert', [
			'type' => 'fail',
			'text' => 'Not Select Data'
		]);
		return redirect('category/'.$category_id.'/'.$type);
	}
	public function aJaxProductSearch(Request $request)
	{
		$datas = [];

		if ($request->has('text_search')) {
			$text_search = $request->input('text_search');

			$params['fields'] = 'name.th,item_id';
			$params['name']	  = $text_search;
			$params['start']  = 0;
			$params['length'] = 5;

			$datas = $this->getSelectProduct($params);

		}
		return Response::json($datas);
	}
	public function aJaxDeleteProduct($category_id , $product_id , Request $request)
	{
		$status = false;
		if (!empty($product_id)) {
			$category_id  = $category_id;
			$content_id   = $product_id;
			$content_type = 'product';
			$result = $this->repository->deleteContentCategoryByCategoryId($category_id, $content_id, $content_type);

			if (isset($result['status']['code']) && $result['status']['code'] == 200 && !empty($result['data'])) {
				// update to Queue
				$this->updateQueue($content_id , $content_type);
				$status = true;
			}
		}
		return Response::json(['status' => $status]);
	}
	public function aJaxProductByCategory($category_id , Request $request)
	{
		$datas = [];
		if ($request->has('store_id') && $request->input('store_id') != '' && !empty($category_id)) {
			$store_id = $request->input('store_id');

			$content_ids = [];

			$result = $this->getContentsByCategory($category_id);

			if (isset($result['status']['code']) && $result['status']['code'] == 200 &&
				isset($result['data']) && !empty($result['data'])) {

				foreach ($result['data'] as $value) {
					if (isset($value['contents']) && !empty($value['contents'])) {
						foreach ($value['contents'] as $val) {
							if (isset($val['content_type']) && $val['content_type'] == 'product') {
								$content_ids[] = $val['content_id'];
							}
						}
					}
				}
			}
			// get detail product
			$products = [];
			if (!empty($content_ids)) {
				$params_getProducts['ids']     = $content_ids;
				$params_getProducts['start']  = 0;
				$params_getProducts['length']   = 99999;
				$products = $this->getProducts($params_getProducts);
			}

			// get product by store
			if ($store_id !== 'all') {
				$item_ids = '';
				$str_line = '';
				foreach ($products as $value) {
					if (!empty($value['item_id'])) {
						$item_ids .= $str_line . $value['item_id'];
						$str_line = ',';
					}
				}
				$params_getProductByStore['item_ids'] 	     = $item_ids;
				$params_getProductByStore['makro_store_ids'] = $store_id;
				$product_by_store = $this->getProductByStore($params_getProductByStore);

				if (!empty($product_by_store)) {
					$result_store_byitem_id = [];

					foreach ($product_by_store as $value) {
						$result_store_byitem_id[] = $value['item_id'];
					}

					$product_by_store = $this->selectProductByItemId($products , $result_store_byitem_id);
				}
				$products = $product_by_store;
			}
			$datas = $this->setDataTable($products);
		}
		return Response::json($datas);
	}

	private function getProductByStore($params)
	{
		$products = [];
		$product_by_store = $this->productRepository->getStore($params);
		if (isset($product_by_store['data']['records']) && !empty($product_by_store['data']['records'])) {
			$products = $product_by_store['data']['records'];
		}
		return $products;
	}

	private function selectProductByItemId($products , $item_ids)
	{
		$product_by_store = [];
		if (!empty($item_ids)) {
			foreach ($products as $key => $value) {
				if (isset($value['item_id']) && !empty($value['item_id']) && in_array($value['item_id'],$item_ids,true)) {
					$product_by_store[] = $value;
				}
			}
		}
		return $product_by_store;
	}
	private function setDataTable($datas)
	{
		$data_table = [];
		$number = 1;
		foreach ($datas as $value) {
			$data_table[] = [
				'number'  => $number,
				'item_id' => $value['item_id'],
				'name_th' => $value['name']['th'],
				'name_en' => $value['name']['en'],
				'status'  => $value['published_status'] == 'Y' ? '<i class="icon-eye text-teal">&nbsp;</i>' : '<i class="icon-eye-blocked text-grey-300"></i>',
				'delete'  => '<a onclick="deleteItems(\'' . $value['id'] . '\')"><i class="icon-trash text-danger"></a>'
			];
			$number++;
		}
		return $data_table;
	}

	private function getContentsByCategory($store_id)
	{
		return $this->repository->getContentsByCategory([$store_id]);
	}
	private function getStore()
	{
		$store = [];
		$result = $this->reportRepository->getStores();
		if (!empty($result)) {
			$store['all'] = 'All';
			foreach ($result as $key => $value) {
				$store[$key] = $value;
			}
		}

		return $store;
	}

	private function getSelectProduct($params = [])
	{
		$datas = [];

		if (!isset($params['start'])) {
			$params['start'] = 0;
		}

		if (!isset($params['length'])) {
			$params['length'] = 10;
		}

		$result = $this->getProducts($params);

		if (!empty($result)) {

			foreach ($result as $key => $value) {
				if (isset($value['item_id']) && isset($value['name']['th'])) {
					$datas[] = [
						'item_id'   => $value['item_id'],
						'name_th'   => $value['name']['th'],
						'name_show' => $value['item_id'] . ' ( '.$value['name']['th'].' )'
					];
				}
			}
		}

		return $datas;
	}
	private function getProducts($params)
	{
		$datas  = [];
		$result = $this->productRepository->getProducts($params);

		if (isset($result['data']['records']) && !empty($result['data']['records'])) {
			$datas = $result['data']['records'];
		}
		return $datas;
	}

	public function show($_id, Request $request)
	{
		return view($this->view['index'], [
			'category_id' => $_id,
			'breadcrumb'  => $this->repository->getBreadcrumbCategory($_id, 0, 'th'),
			'status'      => 1,
			'parent_id'   => $_id,
		]);
	}

	/**
	 * create save db
	 */
	public function store(Request $request, CategoryCreateRequest $formRequest, Validator $validator)
	{
        //input post and file upload
		$inputs = $request->input();

		$params = array_merge($inputs, $request->files->all());

        //check validate
		$checkValidator = $validator::make($request->all(), $this->checkRules[$params['level']], $formRequest->messages());

        //check error validator
        if ($checkValidator->fails()) {

            $error = '';
            foreach ($checkValidator->errors()->all() as $vError) {
            	$error .= $vError . "\n";
            }

            return [
                'status'   => false,
                'validate' => true,
                'messages' => $error
            ];
        }

	    $result = $this->repository->createCategory($params);

	    if (isset($result['success']) && $result['success']) {

            $path = (isset($inputs['parent_id']) && !empty($inputs['parent_id'])) ? '/category/' . $inputs['parent_id'] : '/category';

		    return [
				'status'   => $result['success'],
				'pathUrl'  => $path,
				'messages' => Lang::get('validation.create.success')
		    ];
	    }

	    return [
			'status'   => false,
			'messages' => isset($result['messages']) ? $result['messages'] : Lang::get('validation.create.fail')
	    ];
    }

	/**
	 * edit save db
	 */
	public function update(Request $request, CategoryUpdateRequest $formRequest, Validator $validator)
	{
		//input post and file upload
		$inputs = $request->input();

		$params = array_merge($inputs, $request->files->all());

        //check validate
		$checkValidator = $validator::make($request->all(), $this->checkRules[$params['level']], $formRequest->messages());

        //check error validator
        if ($checkValidator->fails()) {

            $error = '';
            foreach ($checkValidator->errors()->all() as $vError) {
            	$error .= $vError . "\n";
            }

            return [
                'status'   => false,
                'validate' => true,
                'messages' => $error
            ];
        }

		$result = $this->repository->updateCategory($params);

	    if (isset($result['success']) && $result['success']) {

            $path = (isset($inputs['parent_id']) && !empty($inputs['parent_id'])) ? '/category/' . $inputs['parent_id'] : '/category';

		    return [
				'status'   => $result['success'],
				'pathUrl'  => $path,
				'messages' => Lang::get('validation.update.success')
		    ];
	    }

	    return [
			'status'   => false,
			'messages' => isset($result['messages']) ? $result['messages'] : Lang::get('validation.update.fail')
	    ];
	}

	public function edit($id, Request $request)
	{
		$result = [];

		$category = $this->repository->getCategoryById($id);

		if (!empty($category)) {
			$category['name_en'] = (isset($category['name_en']) && !empty($category['name_en'])) ? $category['name_en'] : '';
			$category['description_en'] = (isset($category['description_en']) && !empty($category['description_en'])) ? $category['description_en'] : '';

			return view($this->view['edit'], [
				'category_id' => $id,
				'language'    => config('language.category'),
				'category'    => $category,
				'attribute'   => $this->repository->getAttributeSelected($id),
				'parent_id'   => $category['parent_id'],
				'breadcrumb'  => isset($category['parent_id']) ? $this->repository->getBreadcrumbCategory($category['parent_id'], 0, 'th') : [],
			]);
		}
		else{
			$request->session()->flash('messages', [
				'type' => 'error',
				'text' => 'No Data'
			]);
	
			return redirect($this->redirect['index']);
		}	
	}

	/**
	 * Method for post move nestable
	 */
	public function move(Request $request)
	{
		// send to insert
		$result = $this->repository->moveNestable($request->input());
		if ($result['success']) {
			Session::flash('messages', [
				'type' => 'success',
				'text' => $result['messages']
			]);
		}
			return $result;
			/*return redirect($this->redirect['index']);
		} else {
			return redirect()->back()->withErrors($result['messages'])->withInput();
		}*/
	}

	public function destroy($id, Request $request)
	{
		$result = $this->repository->deleteCategory($id);

		return Response::json($result);
	}

	public function anyData(Request $request)
	{
		$params             = $request->input();
		$params['language'] = 'th';
		return $this->repository->getDataCategory($params);
	}

	/**
	 * save status approve, buyer, show && hide && delete
	 */
	public function setStatus(Request $request)
	{
		$param = $request->input();
		$result = $this->repository->setStatusCategory($param);
		
		if($result['success']) {
			foreach ($result['data']['success'] as $category) {
				event(new CategoryUpdated($category['id']));
			}
		}

		return $result;
	}

	/**
	 * save priority
	 */
	public function postPriority(Request $request)
	{
		$param = $request->input();
		$result = $this->repository->setPriorityCategory($param);

		$output = [
            'status' => false,
            'message' => 'update error'
        ];

        if(isset($result['status'])&&$result['status']['code']==200) {
            $output = [
				'success' => true,
                'code'    => $result['status']['code'],
				'data' => [
					'success' => $result['data']['success'],
                	'errors' => $result['data']['errors']
				]
                
            ];
        }
		
        return $output;
	}

	/**
	 * Method for report excel
	 */
	public function report(Request $request)
	{
		$result = $this->repository->getDataCategoryReport($request->input());

		if (!$result) {
			$request->session()->flash('messages', [
				'type' => 'error',
				'text' => 'No Data'
			]);

			return redirect($this->redirect['index']);
		}
	}

	private function updateQueue($content_id , $content_type)
	{
		if (!empty($content_id) && $content_type == 'product') {
			$params['content_id'] = $content_id;
			$productUpdatedEventListener = new ProductUpdatedEventListener($this->guzzle);
			$productUpdatedEventListener->handle(new ProductUpdated($params['content_id']));
		}
	}
}
?>
