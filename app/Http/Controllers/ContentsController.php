<?php
namespace App\Http\Controllers;

use App\Repositories\ContentsRepository;
use App\Repositories\TagRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ContentRequest;
use App\Http\Controllers\BaseController;
use Response;
use Validator;
use Input;
use DateTime;
class ContentsController extends \App\Http\Controllers\BaseController {
	protected $redirect = [
		'login' => '/',
		'index' => 'content'
	];

	protected $view = [
		'index'  => 'content.index',
		'edit'   => 'content.edit',
		'create' => 'content.create'
	];

	protected $category_type = 'content';
	protected $priority = 99;    // default priority

	public function __construct(ContentsRepository $contentsRepository, TagRepository $tagRepository, CategoryRepository $categoryRepository)
	{
		parent::__construct();
		$this->messages 		   = config('message');
		$this->contentsRepository  = $contentsRepository;
		$this->tagRepository 	   = $tagRepository;
		$this->categoryRepository  = $categoryRepository;
	}

	protected function setContentData($inputs)
	{
		$languages = config('language.content');

		$params = [];
		
		if(!empty($inputs['id'])){
			$params['id'] = $inputs['id'];
		}
		if(!empty($inputs['category_id']) && $inputs['category_id'] != "undefine"){
			$params['category_id'] = $inputs['category_id'];
		}
		foreach ($languages as $language) {
			$params['name_' . $language] = $inputs['name_' . $language];
			$params['description_' . $language] = $inputs['description_' . $language];
		}
		$params['priority'] = $this->priority;
		$params['seo_subject'] = $inputs['seo_subject'];
		$params['seo_explanation'] = $inputs['seo_explanation'];
		$params['start_date'] = !empty($inputs['start_date'])? convertDateTime($inputs['start_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s') : '';
		$params['end_date'] = !empty($inputs['end_date'])? convertDateTime($inputs['end_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s') : '';
		$params['status'] = isset($inputs['status']) ? 'active' : 'inactive';
		$params['slug'] = $inputs['slug'];

		return $params;
	}

	protected function setCreateTagData($contentId, $inputs ,$languages)
	{
		$params = [];
		$params['content_id'] = $contentId;
		$params['content_type'] = $this->category_type;
		foreach ($languages as $language) {		
			$params['name_' . $language] = $inputs['tags'][$language];	
		}
		return $params;
	}

	protected function getTagInputLanguages($inputs)
	{	
		$inputLanguages = [];
		$languages = config('language.content');
		foreach($languages as $language){
			if(!empty($inputs['tags'][$language])){
				array_push($inputLanguages, $language);
			}
		}
		return $inputLanguages;
	}

	public function data(Request $request)
	{	
		return $this->contentsRepository->getDataContent($request->input());
	}

	public function setStatus(Request $request)
	{
		return $this->contentsRepository->setStatusContent($request->input());
	}

	/**
	 * save priority
	 */
	public function postPriority(Request $request)
	{
		$params = $request->input();
		$data = [];

		foreach($params['priority'] as $key => $val){
            if($val != $params['priority_old'][$key]){
                $data[$key]= $val;
            }
		}

		$result = $this->contentsRepository->setPriorityContent($data);

		return $result;
	}

	/**
	 * page index
	 */
	public function index()
	{
		$categoryList = $this->categoryRepository->getAllCategoryWithNormalize($this->category_type);

		return view($this->view['index'], [
			'language'     => config('language.content'),
			'categoryList' => $categoryList
		]);
	}

	/**
	 * Method for post update
	 */
	public function update(ContentRequest $request)
	{
		$inputs = $request->input();

		$inputs['seo_subject'] 		= $this->setDefaultSEO_Subject( $inputs['seo_subject'], $inputs['name_en'], $inputs['name_th'] );
		$inputs['seo_explanation']  = $this->setDefaultSEO_Explanation( $inputs['seo_explanation'], $inputs['description_en'], $inputs['description_th'] );

		$contentId = $inputs['id'];

		$tagInputLanguages = $this->getTagInputLanguages($inputs);

		if(empty($inputs['tag_id']) && !empty($tagInputLanguages)){
			// Create tag
			$params = $this->setCreateTagData($contentId, $inputs, $tagInputLanguages);
			$result = $this->tagRepository->createTag($params);
			if(isset($result['status']['code']) && $result['status']['code'] != 200){
				return Response::json(array('status' => false, 'error' => $result['error']['message']));
			}
		}
		else if(!empty($inputs['tag_id']) && !empty($tagInputLanguages)){
			// Update tag
			$params = $this->setCreateTagData($contentId, $inputs, $tagInputLanguages);
			$result = $this->tagRepository->updateTag($inputs['tag_id'], $params);
			if(isset($result['status']['code']) && $result['status']['code'] != 200){
				return Response::json(array('status' => false, 'error' => $result['error']['message']));
			}
		}
		else if(!empty($inputs['tag_id']) && empty($tagInputLanguages)){
			// Delete tag
			$tagId = $inputs['tag_id'];
			$result = $this->tagRepository->deleteTag($tagId);
			if(isset($result['status']['code']) && $result['status']['code'] != 200){
				return Response::json(array('status' => false, 'error' => $result['error']['message']));
			}
		}

		if(!empty($inputs['content_category_id']) && $inputs['category_id'] != "undefine"){
			// Delete Old Content Category
			$result = $this->categoryRepository->deleteContentCategory($inputs['content_category_id']);
			if($result['status']['code'] != 200){
				return Response::json(array('status' => false, 'error' => $result['errors'][0]['message']));
			}

			// Add New Content Category
			$result = $this->categoryRepository->addContentCategory([
				'category_id'  => $inputs['category_id'],
				'content_type' => $this->category_type,
				'content_id'   => $contentId
			]);
			if($result['status']['code'] != 200){
				return Response::json(array('status' => false, 'error' => $result['errors'][0]['message']));
			}
		}
		else if(!empty($inputs['content_category_id']) && $inputs['category_id'] == "undefine"){
			// Delete Content Category
			$result = $this->categoryRepository->deleteContentCategory($inputs['content_category_id']);
			if($result['status']['code'] != 200){
				return Response::json(array('status' => false, 'error' => $result['errors'][0]['message']));
			}
		}
		else if(empty($inputs['content_category_id']) && $inputs['category_id'] != "undefine"){
			// Add Content Category
			$result = $this->categoryRepository->addContentCategory([
				'category_id'  => $inputs['category_id'],
				'content_type' => $this->category_type,
				'content_id'   => $contentId
			]);
			if($result['status']['code'] != 200){
				return Response::json(array('status' => false, 'error' => $result['errors'][0]['message']));
			}
		}

		$params = $this->setContentData($inputs);
		$result = $this->contentsRepository->updateContent($contentId, $params);

		if (!isset($result['status']['code']) || $result['status']['code'] != 200) {
			return Response::json(array('status' => false, 'error' => $result['errors'][0]['message']));
		}

		return Response::json(array('status' => true, 'message' => "success"));
	}

	public function edit($id, Request $request)
	{
		$content = $this->contentsRepository->getContentDataById($id);
		$content['start_date'] = $content['start_date'];
		$content['end_date'] = $content['end_date'];
		$tags = $this->tagRepository->getTags($id);
		$categoryList = $this->categoryRepository->getAllCategoryWithNormalize($this->category_type);

		$contentCategory = $this->categoryRepository->getContentCategoryByContent($id, $this->category_type);

		return view($this->view['edit'], [
			'language'      => config('language.content'),
			'categoryList'  => $categoryList,
			'tags'          => $tags,
			'contentId'     => $id,
			'contentDetail' => $content,
			'contentCategory' => $contentCategory
		]);
	}

	public function create(Request $request)
	{
		$categoryList = $this->categoryRepository->getAllCategoryWithNormalize($this->category_type);
		return view($this->view['create'], [
			'language'     => config('language.content'),
			'categoryList' => $categoryList
		]);
	}

	public function store(ContentRequest $request)
	{
		$inputs = $request->input();

		$inputs['seo_subject'] 		= $this->setDefaultSEO_Subject( $inputs['seo_subject'], $inputs['name_en'], $inputs['name_th'] );
		$inputs['seo_explanation']  = $this->setDefaultSEO_Explanation( $inputs['seo_explanation'], $inputs['description_en'], $inputs['description_th'] );
		$params = $this->setContentData($inputs);
		$result = $this->contentsRepository->createContent($params);

		if (isset($result['status']['code']) && $result['status']['code'] == 200) {
			$contentId = $result['data']['id'];
		} 
		else {
			return Response::json(array('status' => false, 'error' => $result['errors'][0]['message']));
		}

		// Add Content to Category
		if(!empty($inputs['category_id']) && $inputs['category_id'] != "undefine"){
			$result = $this->categoryRepository->addContentCategory([
				'category_id'  => $inputs['category_id'],
				'content_type' => $this->category_type,
				'content_id'   => $contentId
			]);
			if($result['status']['code'] != 200){
				return Response::json(array('status' => false, 'error' => $result['errors'][0]['message']));
			}
		}

		// Tag
		$tagInputLanguages = $this->getTagInputLanguages($inputs);
		if(!empty($tagInputLanguages)){
			$params = $this->setCreateTagData($contentId, $inputs, $tagInputLanguages);
			$result = $this->tagRepository->createTag($params);
			if (!isset($result['status']['code']) || $result['status']['code'] != 200) {
				return Response::json(array('status' => false, 'error' => $result['errors'][0]['message']));
			}
		}

		return Response::json(array('status' => true, 'messages' => "save success"));
	}

	public function destroy($ids, Request $request)
	{
		$result =  $this->contentsRepository->deleteContent($ids);
		return $result;
	}

		/**
	 * Method for report excel
	 */
	public function report(Request $request)
	{
		$result = $this->contentsRepository->getDataContentReport($request->input());

		if (!$result) {
			$request->session()->flash('messages', [
				'type' => 'error',
				'text' => 'No Data'
			]);

			return redirect($this->redirect['index']);
		}
	}
}
