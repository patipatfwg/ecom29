<?php
namespace App\Http\Controllers;

use App\Repositories\CategoryBusinessRepository;
use App\Repositories\PositionsRepository;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Events\CategoryUpdated;
use App\Services\MyServices;
use Validator;
use Response;
use Session;
use Image;
use App;
use Lang;
class CategoryBusinessController extends \App\Http\Controllers\BaseController
{
	public $position = [
        'page' => 'category_business',
        'title' => 'Business Category'
    ];

    protected $redirect = [
		'login' => '/',
		'index' => 'category_business'
	];

	protected $view = [
		'index'  => 'category_business.index',
		'create' => 'category_business.create',
		'show'   => 'category_business.show',
		'edit'   => 'category_business.edit'
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

    public function __construct(CategoryBusinessRepository $repository, MyServices $myServices, PositionsRepository $PositionsRepository)
	{
		parent::__construct();

		$this->messages 		   = config('message');
		$this->repository 		   = $repository;
		$this->myServices 		   = $myServices;
		$this->positionsRepository = $PositionsRepository;
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

            $path = (isset($inputs['parent_id']) && !empty($inputs['parent_id'])) ? '/category_business/' . $inputs['parent_id'] : '/category_business';

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

            $path = (isset($inputs['parent_id']) && !empty($inputs['parent_id'])) ? '/category_business/' . $inputs['parent_id'] : '/category_business';

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

		$category_ids = $param['category_ids'];

		foreach ($category_ids as $category_id) {
			event(new CategoryUpdated($category_id));
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
}
?>
