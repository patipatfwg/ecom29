<?php
namespace App\Http\Controllers;

use App\Repositories\ContentCategoriesRepository;
use Illuminate\Http\Request;
// use Illuminate\Http\UploadedFile;

use Validator;
use App\Http\Requests\ContentCategoryRequest;
use Response;
use Session;

use App\Services\MyServices;
use Image;
use App;

class ContentCategoriesController extends \App\Http\Controllers\BaseController
{
    protected $redirect = [
        'login' => '/',
        'index' => 'content_category'
    ];

    protected $view = [
        'index'  => 'content_category.index',
        'create' => 'content_category.create',
        'show'   => 'content_category.show',
        'edit'   => 'content_category.edit'
    ];

    protected $language = 'th';

    public function __construct(ContentCategoriesRepository $repository, MyServices $myServices)
    {
        parent::__construct();

        $this->messages   = config('message');
        $this->repository = $repository;
        $this->myServices = $myServices;
    }

    /**
     * page index
     */
    public function index()
    {
        return view($this->view['index'], [
            'language'  => config('language.category'),
            'status'    => 0,
            'parent_id' => ''
        ]);
    }

    /**
     * page create
     */
    public function create($parent_id = null)
    {
        return view($this->view['create'], [
            'language'   => config('language.category'),
            'category'   => $this->repository->getRootCategory(),
            'parent_id'  => isset($parent_id) ? $parent_id : null,
            'level'      => isset($parent_id) ? $this->repository->getLevelCategory($parent_id) : 0,
            'breadcrumb' => isset($parent_id) ? $this->repository->getBreadcrumbCategory($parent_id, 0, 'th') : [],
        ]);
    }

    /**
     * Method for post create
     */
    public function show($_id, Request $request)
    {
        return view($this->view['index'], [
            'category_id' => $_id,
            'breadcrumb'  => $this->repository->getBreadcrumbCategory($_id , 0, 'th'),
            'status'      => 1,
            'parent_id'   => $_id,
        ]);
    }

    /**
     * Method for post create
     */
    public function store(ContentCategoryRequest $request)
    {
        $inputs = $request->input();
        $params = array_merge($inputs, $request->files->all());
        $result = $this->repository->createCategory($params);

        if ($result['success']) {
            $path = '';

            if (isset($inputs['parent_id']) && ! empty($inputs['parent_id'])) {
                $path = '/' . $inputs['parent_id'];
            }

            return array("status"=>true);
        } else {
            return array("status"=>false);
        }
    }

    /**
     * Method for post update
     */
    public function update(ContentCategoryRequest $request)
    {
        $inputs = $request->input();
        $params = array_merge($inputs, $request->files->all());
        $result = $this->repository->updateCategory($params);

        if ($result['success']) {
            $path = '';

            if (isset($inputs['parent_id']) && ! empty($inputs['parent_id'])) {
                $path = '/' . $inputs['parent_id'];
            }

            return array("status"=>true);
        } else {
            return array("status"=>false);
        }
    }

    /**
     * Method for post create
     */
    public function edit($id, Request $request)
    {
        $result = [];

        $category = $this->repository->getCategoryById($id);

        if ( ! empty($category)) {
            $category['name_en']        = (isset($category['name_en']) && ! empty($category['name_en'])) ? $category['name_en'] : '';
            $category['description_en'] = (isset($category['description_en']) && ! empty($category['description_en'])) ? $category['description_en'] : '';

            return view($this->view['edit'], [
                'category_id' => $id,
                'language'    => config('language.category'),
                'category'    => $category,
                'parent_id'   => $category['parent_id'],
                'breadcrumb'  => isset($category['parent_id']) ? $this->repository->getBreadcrumbCategory($category['parent_id'], 0, 'th') : [],
            ]);
        }

        return Response::json($result);
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
            return redirect($this->redirect['index']);
        } else {
            return redirect()->back()->withErrors($result['messages'])->withInput();
        }
    }

    /**
    * Method for get delete
    */
    public function destroy($id, Request $request)
    {
        $result = $this->repository->deleteCategory($id);

        return Response::json($result);
    }

    /**
     * Method for any index
     */
    public function anyData(Request $request)
    {
        return $this->repository->getDataCategory($request->input());
    }

    /**
     * save status approve, buyer, show && hide && delete
     */
    public function setStatus(Request $request)
    {
        $param = $request->input();

        return $this->repository->setStatusCategory($param);
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

        if ( ! $result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);

            return redirect($this->redirect['index']);
        }
    }
}
?>
