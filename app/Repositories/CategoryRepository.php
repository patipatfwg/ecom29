<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Exception\RequestException;
use App\Services\MyServices;
use App\Repositories\BaseRepository;
use App\Services\Guzzle;
use App;
use Lang;
use Excel;
use Cache;
use App\Library\Unit;
class CategoryRepository extends BaseRepository
{
    protected $guzzle;
    protected $messages;
    protected $filter = ['categories.id', 'contents.content'];
    protected $type;
    protected $minutes = 1440;
    private $orderBy = [
        '', '', 'id', 'name.th', 'name.en', '', '', 'priority'
    ];

    protected $content_type = 'category';
    protected $level = [
        ['A', 'B', 'C'], // level 0
        ['D1_1', 'D1_2', 'D1_3', 'D2', 'D3', 'D4', 'D5', 'D6', 'A', 'B'], // level 1
        ['A'], // level 2
    ];

    public function __construct(Guzzle $guzzle, MyServices $myServices)
    {
        $this->urlCategory  = config('api.makro_category_api');
        $this->urlAttribute = config('api.makro_attribute_api');
        $this->messages     = config('message');
        $this->guzzle       = $guzzle;
        $this->myServices   = $myServices;
        $this->type         = config('config.type_category_product');
        $this->_unit        = new Unit;
    }

    /** Start Protected Function */
    protected function generateCategoryArray($data)
    {
        $categoryListOutput = [];

        // Create root -> child category array
        foreach ($data as $rootCategory) {
            $categoryListOutput = $this->enqueueCategory($rootCategory, $categoryListOutput);
        }

        // Create category full name (parent_name > name)
        foreach ($categoryListOutput as $key => $value) {
            $name = $this->generateCategoryParentToChildName($value, "", $categoryListOutput);
            $categoryListOutput[$key]['full_name'] = $name;
        }

        return $categoryListOutput;
    }

    protected function enqueueCategory($category, $categoryArray, $language = 'th')
    {
        $categoryArray[$category['id']] = [
            'id' => $category['id'],
            'parent_id' => $category['parent_id'],
            'type' => $category['type'],
            'level' => $category['level'],
            'name' => $category['name'][$language]
        ];

        if (isset($category['children']) && count($category['children']) > 0) {
            foreach ($category['children'] as $children) {
                $categoryArray = $this->enqueueCategory($children, $categoryArray);
            }
        }

        return $categoryArray;
    }

    protected function generateCategoryParentToChildName($category, $parent_name, $categoryListOutput)
    {
        if (isset($category['parent_id']) && $category['parent_id'] != null) {
            $parent_name = $this->generateCategoryParentToChildName($categoryListOutput[$category['parent_id']], $parent_name, $categoryListOutput);
        }
        return ($parent_name != "") ? $parent_name . " > " . $category['name'] : $category['name'];
    }
    /** End Protected Function */

    /** Start Private Function */
    private function postCreateCategory($inputs)
    {
        $this->deleteCache();

        $inputs['type'] = $this->type;
        $url = $this->urlCategory . 'categories';

        $params = [
            'json' => $inputs
        ];

        $result = $this->guzzle->curl('POST', $url, $params);

        return $result;
    }

    private function postUpdateCategory($inputs, $category_id)
    {
        $this->deleteCache();
        
        $inputs['type'] = $this->type;
        $url = $this->urlCategory . 'categories/' . $category_id;

        $params = [
            'json' => $inputs
        ];

        return $this->guzzle->curl('PUT', $url, $params);
    }

    private function getDeleteCategory($_id)
    {
        $this->deleteCache();

        $url = $this->urlCategory . 'categories/' . $_id;
        $result = $this->guzzle->curl('DELETE', $url);
        return $result;
    }

    private function getCategoryTreeParent($category_id = '', $level = '')
    {
        $params = [
            'headers' => [
                'x-language' => $this->myServices->getLanguageHeader('category'),
            ],
        ];

        $url = $this->urlCategory . 'categories/treeparent/category/' . $category_id . '/level/' . $level;
        $result = $this->guzzle->curl('GET', $url, $params);

        return $result;
    }

    private function postParent($inputs = [])
    {
        $this->deleteCache();

        $result = [];

        if (!empty($inputs['category_id']) && !empty($inputs['parent_id'])) {
            $url = $this->urlCategory . 'categories/bindparent';
            $params = [
                'json' => $inputs,
            ];

            $result = $this->guzzle->curl('POST', $url, $params);

        }

        return $result;
    }

    /**
     * Method for set data order
     */
    private function setOrderData(array $params)
    {
        $order = [];

        if (isset($params['order'])) {
            foreach ($params['order'] as $kData => $vData) {
                $order[] = $this->orderBy[$vData['column']] . '|' . $vData['dir'];
            }
        }

        return implode(',', $order);
    }

    private function setBreadcrumb($data, $language)
    {
        $output = array();

        if (isset($data['id']) && !empty($data['id'])) {

            $category_id = $data['id'];
            $result = $this->getCategoryById($category_id);
            $category['id'] = $category_id;
            $category['name'] = isset($result['name']) ? $result['name'][$language] : '-';
            $output[] = $category;

            if (isset($data['parent'])) {
                $result = $this->setBreadcrumb($data['parent'][0], $language);
                $output = array_merge($result, $output);
            }
        }

        return $output;
    }

    protected function addAttribute($category_id, array $inputs)
    {
        if (empty($inputs['attribute'])) {
            return true;
        }

        try {
            $arrAttribute = $this->getParamsAttribute($inputs);
            $url = $this->urlAttribute . 'attributes/content';
            $params = [
                'json' => [
                    'content_id' => $category_id,
                    'content_type' => $this->content_type,
                    'attribute' => $arrAttribute,
                ],
            ];

            $result = $this->guzzle->curl('POST', $url, $params);

            if (isset($result['status']) && $result['status']['code'] == 200) {
                return true;
            }
        } catch (RequestException $e) {
            return false;
        }
    }

    protected function getParamsAttribute($data)
    {
        if (isset($data['attribute']) && !empty($data['attribute'])) {

            foreach ($data['attribute'] as $value) {
                $att[]['attribute_id'] = $value;
            }
        }

        return $att;
    }

    /**
     * get Sub Attribute All
     *
     * @return array
     */
    private function getSubAttribute($data)
    {
        $output = [];

        if (!empty($data) && isset($data['id']) && count($data['id']) > 0) {
            $params = [
                'query' => [
                    'attribute_ids' => implode(',', $data['id']),
                ],
                'headers' => [
                    'x-language' => $this->myServices->getLanguageHeader('category'),
                ],
            ];

            $url = $this->urlAttribute . 'attributes/subattributes';
            $result = $this->guzzle->curl('GET', $url, $params);

            if (isset($result['status']) && $result['status']['code'] == 200) {
                $output = $this->getAttributeData($data, $result);
            }
        }

        return $output;
    }

    /**
     * get Attribute All Data
     *
     * @return array
     */
    private function getAttributeData($data, $api)
    {
        $output = [];

        if (isset($data['id']) && count($data['id']) > 0) {

            foreach ($data['id'] as $kAttr => $vAttr) {
                $output[$kAttr] = [
                    'id' => $vAttr,
                    'name' => $data['name'][$kAttr],
                    'sub_attribute_name' => $this->getSubAttributeData($api, $vAttr),
                ];
            }
        }

        return $output;
    }

    /**
     * get Attribute Selected All Data
     *
     * @return array
     */
    private function setAttributeSelected($api)
    {
        $output = $this->getAttribute();

        if (!empty($api)) {

            foreach ($api as $kData => $vData) {

                foreach ($output as $kAll => $vAll) {

                    if ($vAll['id'] == $vData['attribute']['attribute_id']) {
                        $output[$kAll]['selected'] = true;
                    }
                }
            }
        }

        return $output;
    }

    /**
     * get Sub Attribute All Data
     *
     * @return array
     */
    private function getSubAttributeData($api, $vAttr)
    {
        $output = [];

        if (isset($api['data']['records']) && count($api['data']['records']) > 0) {

            foreach ($api['data']['records'] as $kApi => $vApi) {

                foreach ($vApi as $kData => $vData) {

                    if (isset($vData['attribute_id']) && $vData['attribute_id'] === $vAttr) {
                        $output[] = $vData['sub_attribute_name'];
                    }
                }
            }
        }

        return $output;
    }

    /** End Private Function */

    public function getBreadcrumbCategory($category_id = '', $level = '', $language = 'th')
    {
        $data = [];
        $result = $this->getCategoryTreeParent($category_id, $level);

        if (!isset($result['error'])) {
            $data = $this->setBreadcrumb($result['data'], $language);
        }

        return $data;
    }

    public function createCategory($inputs)
    {
        $params = array();

        if (isset($inputs['input'])) {

            foreach ($inputs['input'] as $value) {
                $params = array_merge($params, $value);
            }
        }

        $params['parent_id'] = (isset($inputs['parent_id']) && !empty($inputs['parent_id'])) ? $inputs['parent_id'] : null;
        $params['status'] = (isset($inputs['status']) && $inputs['status'] == 'on') ? 'active' : 'inactive';
        $params['is_show_level_b'] = (isset($inputs['is_show_level_b']) && $inputs['is_show_level_b'] == 'on') ? 'Y' : 'N';
        $params['seo_subject'] = (isset($inputs['seo_subject']) && !empty($inputs['seo_subject'])) ? $inputs['seo_subject'] : $inputs['input']['en']['name_en'];
        $params['seo_explanation'] = (isset($inputs['seo_explanation']) && !empty($inputs['seo_explanation'])) ? $inputs['seo_explanation'] : $inputs['input']['en']['name_en'];
        $params['priority'] = (isset($inputs['priority']) && !empty($inputs['priority'])) ? $inputs['priority'] : '99';
        $params['slug'] = (isset($inputs['slug']) && !empty($inputs['slug'])) ? $inputs['slug'] : $inputs['input']['en'];

        $result = $this->postCreateCategory($params);

        if (isset($result['error'])) {
            return array('success' => false, 'messages' => $result['error']['message']);
        }

        $category_id = $result['data']['id'];

        if (isset($input['parent_id']) && $input['parent_id'] != 0) {
            $data['category_id'] = $category_id;
            $data['parent_id'] = $input['parent_id'];
            $result = $this->postParent($data);

            if (isset($result['error'])) {
                return array('success' => false, 'messages' => $result['error']['message']);
            }
        }

        if ($this->addAttribute($category_id, $inputs) && $this->addImage($category_id, $inputs)) {
            return array('success' => true, 'messages' => Lang::get('validation.create.success'));
        }

        return array('success' => false, 'messages' => Lang::get('validation.create.fail'));
    }

    public function updateCategory($inputs)
    {
        $params = array();

        if (isset($inputs['input'])) {

            foreach ($inputs['input'] as $value) {
                $params = array_merge($params, $value);
            }
        }
        
        $params['parent_id'] = (isset($inputs['parent_id']) && !empty($inputs['parent_id'])) ? $inputs['parent_id'] : null;
        $params['status'] = (isset($inputs['status']) && $inputs['status'] == 'on') ? 'active' : 'inactive';
        $params['is_show_level_b'] = (isset($inputs['is_show_level_b']) && $inputs['is_show_level_b'] == 'on') ? 'Y' : 'N';
        $params['seo_subject'] = (isset($inputs['seo_subject']) && !empty($inputs['seo_subject'])) ? $inputs['seo_subject'] : '';
        $params['seo_explanation'] = (isset($inputs['seo_explanation']) && !empty($inputs['seo_explanation'])) ? $inputs['seo_explanation'] : '';
        $params['priority'] = (isset($inputs['priority']) && !empty($inputs['priority'])) ? $inputs['priority'] : '99';
        $params['slug'] = (isset($inputs['slug']) && !empty($inputs['slug'])) ? $inputs['slug'] : $inputs['input']['en'];
        $result = $this->postUpdateCategory($params, $inputs['category_id']);

        if (isset($result['error'])) {
            return array('success' => false, 'messages' => $result['error']['message']);
        }

        if ($this->addAttribute($inputs['category_id'], $inputs) && $this->addImage($inputs['category_id'], $inputs)) {
            return array('success' => true, 'messages' => Lang::get('validation.update.success'));
        }

        return array('success' => false, 'messages' => Lang::get('validation.update.fail'));
    }

    protected function addImage($category_id, $inputs)
    {
        try {
            $arrImages = $this->getParamsImage($inputs);
            
            // if (empty($arrImages)) {
            //     return true;
            // }

            $url = $this->urlCategory . 'categories/image';
            $params = [
                'json' => [
                    'category_id' => $category_id,
                    'images' => $arrImages,
                ],
            ];

            $result = $this->guzzle->curl('POST', $url, $params);

            if (isset($result['status']) && $result['status']['code'] == 200) {
                return true;
            }
        } catch (RequestException $e) {
            return false;
        }
    }

    protected function getParamsImage($data)
    {
        $images = [];

        $position = $this->level[$data['level']];

        if (!empty($position)) {
            foreach ($this->level[$data['level']] as $value) {

                if (isset($data['image_position_' . $value]) && !empty($data['image_position_' . $value])) {
                    $dataUpload = $this->myServices->uploadImage('image_position_' . $value);
                    $image = $dataUpload['image'];
                } else {
                    $image = (isset($data['temp_position_' . $value]) && !empty($data['temp_position_' . $value])) ? $data['temp_position_' . $value] : '';
                }

                if (!empty($image)) {
                    $images[] = [
                        'image' => $image,
                        'position' => $value,
                        'url' => (!empty($data['url_position_' . $value])) ? $data['url_position_' . $value] : null,
                    ];
                }
            }
        }

        return $images;
    }

    /**
     * get Attribute All
     *
     * @return array
     */
    public function getAttribute()
    {
        $data = [];

        $url = $this->urlAttribute . 'attributes';
        $result = $this->guzzle->curl('GET', $url, []);

        // if (isset($api['data']) && count($api['data']) > 0) {
        if (isset($result['status']) && $result['status']['code'] == 200) {
            if (!empty($result['data']['records'])) {
                foreach ($result['data']['records'] as $kData => $vData) {
                    $data['id'][] = $vData['id'];
                    $data['name'][] = $vData['name'];
                }
            }
        }

        return $this->getSubAttribute($data);
    }

    /**
     * get Attribute Selected All Data
     *
     * @return array
     */
    public function getAttributeSelected($category_id)
    {
        $data = [];

        if (!empty($category_id)) {
            $params = [
                'query' => [
                    'content_id' => $category_id,
                    'content_type' => $this->content_type,
                ],
                'headers' => [
                    'x-language' => $this->myServices->getLanguageHeader('attribute'),
                ],
            ];

            $url = $this->urlAttribute . 'attributes/content';
            $result = $this->guzzle->curl('GET', $url, $params);

            if (isset($result['status']) && $result['status']['code'] == 200) {
                $data = $this->setAttributeSelected($result['data']['records']);
            }
        }

        return $data;
    }

    public function deleteCategory($_id)
    {
        if (isset($_id)) {

            $result = $this->getDeleteCategory($_id);

            if ($result['status']['code'] == 200) {

                if (isset($result['data']['deleted']) && isset($result['data']['errors'])) {
                    return array(
                        'success' => true,
                        'deleted' => $result['data']['deleted'],
                        'errors' => $result['data']['errors']
                    );
                } else {
                    return array('success' => true, 'messages' => Lang::get('validation.delete.success'));
                }
            } else {
                return array('success' => false, 'messages' => $result['error']['message']);
            }
        }

        return array('success' => false, 'messages' => Lang::get('validation.delete.fail'));
    }

    /**
     * get Root Category
     *
     * @param  Array $params [username, password]
     * @return array
     */
    public function getRootCategory()
    {
        $data = [];

        $params = [
            'query' => [
                'type' => $this->type,
            ],
            'headers' => [
                'x-language' => $this->myServices->getLanguageHeader('category'),
            ],
        ];

        $url = $this->urlCategory . 'categories/rootcategory';
        $result = $this->guzzle->curl('GET', $url, $params);

        if (isset($result['status']) && $result['status']['code'] == 200) {
            $data = $result['data'];
        }

        return $data;
    }

    public function getRootCategoryIncludeChild($type, $status = null)
    {
        $data = [];

        $params = [
            'query' => [
                'type' => $type,
                'include_child' => 1
            ],
            'headers' => [
                'x-language' => $this->myServices->getLanguageHeader('category'),
            ],
        ];

        if (!is_null($status)) {
            $params['query']['status'] = $status;
        }

        $url = $this->urlCategory . 'categories/rootcategory';

        $result = Cache::store('redis')->remember('root_categories.' . $type, $this->minutes, function () use ($url, $params) {
            return $this->guzzle->curl('GET', $url, $params);
        });

        if (isset($result['status']) && $result['status']['code'] == 200) {
            $data = $result['data'];
        }

        return $data;
    }

    public function getAllCategoryWithNormalize($type)
    {
        $categories = $this->getRootCategoryIncludeChild($type);
        $data = $this->generateCategoryArray($categories);

        return $data;
    }

    public function getLevelCategory($category_id)
    {
        $params = [
            'query' => [
                'type' => $this->type,
                'category_id' => $category_id,
                'status' => 'active,inactive',
            ],
            'headers' => [
                'x-language' => $this->myServices->getLanguageHeader('category'),
            ],
        ];

        $url = $this->urlCategory . 'categories';
        $result = $this->guzzle->curl('GET', $url, $params);

        if (isset($result['status']) && $result['status']['code'] == 200) {
            $data = $result['data']['records'][0];

            return $data['level'] + 1;
        }

        return 0;
    }

    public function getCategoryById($category_id)
    {
        $output = [];
        if (!empty($category_id)) {
            $params = [
                'query' => [
                    'type' => $this->type,
                    'category_id' => $category_id,
                    'status' => 'active,inactive',
                ],
                'headers' => [
                    'x-language' => $this->myServices->getLanguageHeader('category'),
                ],
            ];
            
            $url = $this->urlCategory . 'categories';
            
            $result = $this->guzzle->curl('GET', $url, $params);
            
            if (isset($result['status']) && $result['status']['code'] == 200) {
                $data = $result['data']['records'][0];
                $output = $this->getDataImage($data);
 
            }
        }
 
        return $output;
    }

    protected function getDataImage($params)
    {
        if (isset($params['image_detail']) && !empty($params['image_detail'])) {

            foreach ($params['image_detail'] as $key => $value) {
                $params['image_detail']['position_' . $value['position']] = $value;
                unset($params['image_detail'][$key]);
            }
        }

        return $params;
    }

    /**
     * Method for set search text all
     */
    private function setSearchText(array $params)
    {
        $search = [];

        if (isset($params['search']) && is_array($params['search'])) {

            foreach ($params['search'] as $kData => $vData) {

                if ($vData['name'] !== '_token') {
                    $search[] = $vData['name'] . '=' . $vData['value'];
                }
            }
        }

        return $search;
    }

    /**
     * Method for set data search
     */
    private function setSearch(array $params)
    {
        $search = implode('&', array_merge([
            'order=' . $this->setOrderData($params),
            'offset=' . array_get($params, 'start', 0),
            'limit=' . array_get($params, 'length', 10),
            'status=active,inactive'
        ], $this->setSearchText($params)));

        return $search;
    }

    /**
     * Method for curl api category
     */
    public function getDataCategory(array $params)
    {
        $getUrl = $this->setSearch($params);
        $headers = [
            'headers' => [
                'x-language' => $this->myServices->getLanguageHeader('category')
            ]
        ];

        $result = $this->guzzle->curl('GET', $this->urlCategory . 'categories?' . $getUrl, $headers);

        $output = [
            'draw' => isset($params['draw']) ? $params['draw'] : [],
            'recordsTotal' => isset($result['data']) ? count($result['data']['records']) : 0, //count page
            'recordsFiltered' => isset($result['data']) ? $result['data']['pagination']['total_records'] : 0, //count all
            'data' => isset($result['data']) ? $this->setDataTable($result['data']['records'], $params) : [],
            'input' => $params
        ];

        return json_encode($output);
    }

    /**
     * Method for datatable
     */
    public function setDataTable($data, array $params)
    {
        //loop get data
        $dataTable = [];
        $language = App::getLocale();

        if (count($data) > 0) {

            $start = isset($params['start']) ? $params['start'] : 0;

            foreach ($data as $kData => $vData) {

                $numberData = ($kData + 1) + $start;

                if ($vData['status'] == 'active') {
                    $status = '<i class="icon-eye text-teal"></i>';
                } elseif ($vData['status'] == 'inactive') {
                    $status = '<i class="icon-eye-blocked text-grey-300"></i>';
                }

                if ($vData['level'] < 2) {
                    $child = '<a href="' . url('/category/' . $vData['id']) . '" ><i class="icon-tree6" ></i></a>';
                } elseif ($vData['level'] >= 2) {
                    $child = '<i class="icon-tree6" ></i>';
                }

                //check child data
                $checkChild = $this->guzzle->curl('GET', $this->urlCategory . 'categories/tree/category/' . $vData['id'] . '/level/0');
                $setCheckbox = isset($checkChild['data']['children']) ? '-' : '<input class="ids click-all check" type="checkbox" name="category_ids[]" value="' . $vData['id'] . '" class="check">';
                $setDelete = isset($checkChild['data']['children']) ? '<i class="icon-trash" title="This product category has sub product category.">' : '<a onclick="deleteItems(\'' . $vData['id'] . '\')"><i class="icon-trash text-danger"></a>';
                $btn_add_product = '<a href="/category/'.$vData['id'].'/product" target="_blank"><i class="icon-link"></i></a>';

                $dataTable[] = [
                    'checkbox' => $setCheckbox,
                    'number' => $numberData,
                    'category_id' => $this->checkEmpty($vData['id'], $params['search'][2]['value']),
                    'category_name_th' => $this->checkEmpty($vData['name']['th'], $params['search'][1]['value']),
                    'category_name_en' => $this->checkEmpty($vData['name']['en'], $params['search'][1]['value']),
                    'level' => $vData['level'],
                    'status' => $status,
                    'priority' => '<input type="text" maxlength="2" onKeyPress="CheckNum()" class="form-control input-width-priority-80 text-center priority_number" name="priority[' . $vData['id'] . ']" value="' . $vData['priority'] . '">',
                    'edit' => '<a href="' . url('/category/' . $vData['id'] . '/edit') . '"><i class="icon-pencil"></i></a>',
                    'delete' => $setDelete,
                    'child' => $child,
                    'btn_add_product' => $btn_add_product
                ];
            }
        }

        return $dataTable;
    }

    /**
     * Method for check empty
     */
    protected function checkEmpty($data, $search = '')
    {
        if (!empty($data)) {
            return $this->highlight($search, $data);
        }

        return '-';
    }

    public function setStatusCategory($ids)
    {
        $this->deleteCache();

        $datas = $ids['category_ids'];
        $values = array_values($datas);
        $values = implode(",", $values);

        try {
            $url = $this->urlCategory . 'categories/status';

            $params = [
                'form_params' => [
                    'category_id' => $values,
                    'status' => $ids['status']
                ],
            ];

            $result = $this->guzzle->curl('PUT', $url, $params);

            return ['success' => true, 'data' => $result['data']];
        } catch (\Exception $e) {
            $result['data'] = [
                'total' => 0,
                'contents' => []
            ];

            return ['success' => false, 'data' => ['id' => $params]];
        }
    }

    public function setPriorityCategory($params)
    {
        $this->deleteCache();

        try {
            $datas = $params['priority'];
            $url = $this->urlCategory . 'categories/priority';
            $param = [
                'form_params' => $datas,
            ];

            $result = $this->guzzle->curl('PUT', $url, $param);

            return $result;

        } catch (\Exception $e) {
            $result['data'] = [
                'total' => 0,
                'contents' => []
            ];

            return ['success' => false, 'data' => ['id' => $params]];
        }
    }

    /**
     * Method for curl api category report
     */
    public function getDataCategoryReport(array $params)
    {
        $params['length'] = '9999';
        $getUrl = $this->setSearch($params);

        $category_type_report = null;
        $category_type = null;
        if (isset($params['search'][4]['value'])) {
            $category_type_report = $params['search'][4]['value'] . "_";
            $category_type = ucfirst($params['search'][4]['value']) . " ";
        }

        $params = [
            'headers' => [
                'x-language' => $this->myServices->getLanguageHeader('category'),
            ],
        ];

        $result = $this->guzzle->curl('GET', $this->urlCategory . 'categories?' . $getUrl, $params);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $categories = $result['data']['records'];
            $report_name = $category_type_report . 'category_report_';
            return Excel::create($report_name . date('YmdHis'), function ($excel) use ($categories, $category_type) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Category', function ($sheet) use ($categories, $category_type) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Category ID',
                        $category_type . 'Category Name (TH)',
                        $category_type . 'Category Name (EN)',
                        'Level',
                        'Parent ID',
                        'Priority',
                        'SEO Title Page',
                        'SEO META Description',
                        'Published',
                        //'Type'
                    ]);

                    $sheet->row(1, function ($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($categories as $kData => $vData) {
                        ++$row;

                        $data = [
                            $kData + 1,
                            '="'.array_get($vData, 'id', '').'"',
                            $this->_unit->removeFirstInjection(array_get($vData, 'name.th', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'name.en', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'level', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'parent_id', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'priority', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'seo_subject', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'seo_explanation', '')),
                            (array_get($vData, 'status', '') == 'active' ? 'Publish' : 'Unpublish'),
                            //array_get($vData, 'type', '')
                        ];

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }

        return false;
    }

    public function getContentsByCategory($categoryIds)
    {
        $url = $this->urlCategory . 'categories/content/category';
        $options = [
            'query' => [
                'ids' => implode(',', $categoryIds)
            ]
        ];
        return $this->guzzle->curl('GET', $url, $options);
    }

    public function getCategoryByContent($contentId, $contentType)
    {
        $url = $this->urlCategory . 'categories/content/' . $contentId . '/type/' . $contentType;

        return $this->guzzle->curl('GET', $url);
    }

    public function getContentCategoryByContent($contentId, $contentType)
    {
        $result = $this->getCategoryByContent($contentId, $contentType);
        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            $categoryId = $result['data'][0]['id'];
        } else {
            return null;
        }

        $result = $this->getContentsByCategory(array($categoryId));
        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            $contents = $result['data'][0]['contents'];
        } else {
            return null;
        }

        $key = array_search($contentId, array_column($contents, 'content_id'));
        return $contents[$key];
    }

    public function addContentCategory($params)
    {
        $url = $this->urlCategory . 'categories/content';
        $options = [
            'json' => [
                'category_id' => $params['category_id'],
                'content_type' => $params['content_type'],
                'content_id' => $params['content_id']
            ]
        ];
        return $this->guzzle->curl('POST', $url, $options);
    }

    public function deleteContentCategory($contentCategoryId)
    {
        $url = $this->urlCategory . 'categories/content/' . $contentCategoryId;
        return $this->guzzle->curl('DELETE', $url, []);
    }

    public function deleteContentCategoryByCategoryId($category_id, $content_id, $content_type)
    {
        $url = $this->urlCategory . "categories/category/$category_id/content/$content_id/type/$content_type";
        return $this->guzzle->curl('DELETE', $url, []);
    }

    protected function deleteCache()
    {
        Cache::store('redis')->forget('root_categories.' . $this->type);
    }
}