<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AttributeRepository;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Requests\AttributeRequest;
use App\Events\ProductUpdated;
use Response;

class AttributeController extends BaseController
{
    protected $view = [
        'index'  => 'attribute.index',
        'addnew' => 'attribute.addnew'
    ];

    public function __construct(AttributeRepository $attributeRepository)
    {
        parent::__construct();
        $this->attributeRepository = $attributeRepository;
    }

    public function getIndex(Request $request)
    {
        $result     = $request->all();
        $textSearch = array_get($result, 'q', '');
        $limit      = (!empty($result['length'])) ? $result['length'] : 10;
        $offset     = (!empty($result['start'])) ? $result['start'] : 0;

        if (empty($textSearch)) {
            $return = $this->attributeRepository->getAllAttribute($limit, $offset);
        } 
        else {
            $return = $this->attributeRepository->getSearchAttribute($limit, $offset, $textSearch);
        }

        $pagination = $return['data']['pagination'];
        $results    = $return['data']['records'];

        $attribute = '';
        $row       = [];

        if ($results) {

            foreach ($results as $value) {
                $attr_id                    = $value['id'];
                $row[$attr_id]['attr_id']   = $value['id'];
                $row[$attr_id]['attr_name_th'] = $value['name']['th'];
                $row[$attr_id]['attr_name_en'] = $value['name']['en'];
                $row[$attr_id]['attr_sub']  = '';

                $attribute .= ', '.$attr_id;
            }

            $attribute = substr($attribute, 1);
            $resultsub = $this->attributeRepository->getSubAttribute($attribute);
   
            if ($resultsub) {

                foreach ($resultsub['records'] as $value) {
                    $name                       = '';
                    $attr_id                    = $value['attribute_id'];
                    $sub_attribute[$attr_id][]  = $value['name'];

                    foreach ($sub_attribute[$attr_id] as $rs) {
                        $name   .= ', ' . $rs['th'];
                    }

                    $name = substr($name, 1);
                    $row[$attr_id]['attr_sub']   = $name;
                }
            }
        }
        //dd($row);
        $records = [
            'textsearch' => $textSearch,
            'data'       => $row,
            'datacount'  => $pagination['total_records']
        ];

        return view($this->view['index'], $records);
    }

    public function getAddData(Request $request)
    {
        $arrDefault = [['name' => ''], ['name' => '']];
        $arrDefault = json_encode($arrDefault);

        $arrName = ['th' => ['name' => '','subattr' => [['subname' => '']]],'en' => ['name' => '','subattr' => [['subname' => '']]]];
        $arrName = json_encode($arrName);
        $default_img = \URL::asset('/assets/images/no-img.png');
        
        $records = [
                'menu_action' => 'Create',
                'form_action' => '/attribute/save',
                'attr_id'     => 0,
                'attr_name'   => $arrName,
                'attr_sub'    => $arrDefault,
                'attr_default_img' => $default_img,
                'language'    => config('language.attribute'),
            ];

        return view($this->view['addnew'], $records);
    }

    public function postSaveData(AttributeRequest $request)
    {
        $result = $request->all();
        $sub_attr_th = [];
        $sub_attr_en = [];
        $images = [];
        for($i=0;$i<count($result['sub_attr_th']);$i++)
        {
            $sub_attr_th[] = $result['sub_attr_th'][$i]['name'];
            $sub_attr_en[] = $result['sub_attr_en'][$i]['name'];
            if($request->hasFile("file_$i"))
            {
                $uploadResult = $this->attributeRepository->uploadImage("file_$i");
                $images[] = $uploadResult['image'];
            }
            else
            {
                $images[] = '';
                
            }
        }
        
        $dataSave = [
            'attribute_schema' => 'string',
            'attribute_type' => 'select',
            'attribute' => [
                    'name_th' => $result['name_th'],
                    'name_en' => $result['name_en'],
                    'short_name_th' => $result['name_th'],
                    'short_name_en' => $result['name_en'],
                    'sub_attr_th' => $sub_attr_th,
                    'sub_attr_en' => $sub_attr_en,
                    'images' => $images
            ]
        ];
        $result     = $this->attributeRepository->saveAttribute($dataSave);
        
        if(isset($result['status'])&&$result['status']==200) {
            return Response::json( [ 'status'=> $result['status'] , 'messages' => 'create success' ] );
        }
        
        return Response::json($result);
        
    }

    public function getEditData(Request $request, $id_edit)
    {
        $id_edit   = (int) $id_edit;
        $results   = $this->attributeRepository->getAttribute($id_edit);
        $resultsub = $this->attributeRepository->getSubAttribute($id_edit);
        $attr_name = array_get($results['records'], '0', array());
        $language  = config('language.attribute');

        foreach ($language as $lang) {

            $arrName[$lang]['name']    = $attr_name['name'][$lang];
            $arrName[$lang]['subattr'] = $attr_name['name'][$lang];
            $subattr = $resultsub['records'];
            $arrSub  = [];

            foreach ($subattr as $key => $value) {
                $arrSub[$key]['sub_attribute_id'] = $value['sub_attribute_id'];
                $arrSub[$key]['subname'] = $value['name'][$lang];
                $arrSub[$key]['image_url'] = $value['sub_attribute_image_url'];
                $arrSub[$key]['old_image_url'] = $value['sub_attribute_image_url'];
            }


            $arrName[$lang]['subattr'] = $arrSub;
        }

        $arrName    = json_encode($arrName);
        $arrDefault = [['name' => ''], ['name' => '']];
        $arrDefault = json_encode($arrDefault);
        $default_img = \URL::asset('/assets/images/no-img.png');

        $records = [
            'menu_action' => $id_edit,
            'form_action' => '/attribute/update',
            'attr_id'     => $id_edit,
            'attr_name'   => $arrName,
            'attr_sub'    => $arrDefault,
            'attr_default_img' => $default_img,
            'language'    => config('language.attribute')
        ];

        return view($this->view['addnew'], $records);
    }

    public function postUpdateData(AttributeRequest $request)
    {
        $result = $request->input();
        $attr_id = $result['attr_id'];
        $language   = config('language.attribute');
        $arrData['attribute_schema']    = 'string';
        $arrData['attribute_type']      = 'select';
        foreach($language as $lang) {
            $arrData['attribute_name'][$lang] = $result["name_$lang"];
        }

        if (isset($result['del_id']) && $result['del_id'] != '') {
            $delData['status']  = 'inactive';
            $dataDel            = json_encode($delData);
            foreach ($result['del_id'] as $key => $value) {
                $this->attributeRepository->delSubAttribute($value, $dataDel);
            }
        }
        $i = 0;
        foreach ($language as $lang) {
            foreach ($result['sub_attr_' . $lang] as $key => $row) {
                if (isset($row['sub_id']) && $row['sub_id'] != '') {
                    $arrData['sub_attribute'][$key]['sub_attribute_id']    = $row['sub_id'];
                    $arrData['sub_attribute'][$key]['name_' . $lang]       = $row['name'];
                }
                else {
                    $arrData['sub_attribute'][$key]['name_' . $lang]       = $row['name'];
                }
                if($request->hasFile("file_$i")) {
                    $uploadResult = $this->attributeRepository->uploadImage("file_$i");
                    $arrData['sub_attribute'][$key]['image_url'] = $uploadResult['image'];
                } else if(isset($result['images']["file_$i"])&&($result['images']["file_$i"]['old']!=$result['images']["file_$i"]['url_if_exist'])){
                    $arrData['sub_attribute'][$key]['image_url'] = '';
                }
                $i++;
            }
        }
        
        $dataUpdate   = json_encode($arrData);
        $result     = $this->attributeRepository->updateAttribute($attr_id, $dataUpdate);
        if($result['status']['code']==200) {
            $attributeContentData = $this->attributeRepository->getAttributeContentByAttributeId($attr_id);
            if(!empty($attributeContentData['records'])) {
                foreach($attributeContentData['records'] as $attributeContent) {
                    if($attributeContent['content_type']=='product') {
                        $product_id[] = $attributeContent['content_id'];
                    }
                }
                event(new ProductUpdated(implode(',',$product_id)));
            }
            return Response::json(['status' => true , 'messages' => 'update success']);

        }
        return Response::json(['status' => false , 'messages' => $result['errors']['message']]);
    }

    public function getAjaxAttribute(Request $request)
    {
        $result     = $request->all();
        
        if(!strpos($result['order'][0]['column'], 'attr_name_')) {
            $columnLang = explode('attr_name_',$result['order'][0]['column']);
            if(isset($columnLang[1])) {
                $result['order'][0]['column'] = 'name.'.$columnLang[1];
            } 
            
        }

        $order = $result['order'][0]['column'].'|'.$result['order'][0]['dir'];
        $textSearch = (isset($result['search'][0]['value'])) ? $result['search'][0]['value'] : '';
        $limit      = (!empty($result['length'])) ? $result['length'] : 10;
        $offset     = (!empty($result['start'])) ? $result['start'] : 0;

        if (empty($textSearch)) {
            $return = $this->attributeRepository->getAllAttribute($limit, $offset, $order);
        }
        else {
            $return = $this->attributeRepository->getSearchAttribute($limit, $offset, $textSearch, $order);
        }
        
        $pagination = $return['data']['pagination'];
        $results    = $return['data']['records'];
        $attribute  = '';
        $row        = [];
        
        if ($results) {
            foreach ($results as $value) {
                $attr_id          = $value['id'];

                $row[$attr_id]['attr_id']   = $value['id'];
                $row[$attr_id]['attr_name_th'] = $value['name']['th'];
                $row[$attr_id]['attr_name_en'] = $value['name']['en'];
                $row[$attr_id]['update_at'] = convertDateTime($value['update_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                $row[$attr_id]['have_products'] = isset($value['have_products'])? $value['have_products'] : 'N';
                $row[$attr_id]['total_product_using'] = isset($value['total_product_using'])? $value['total_product_using'] : 0;
                $row[$attr_id]['attr_sub']  = '';

                $attribute .= ', '.$attr_id;
            }

            $attribute = substr($attribute, 1);
            $resultsub = $this->attributeRepository->getSubAttribute($attribute);

            if ($resultsub) {
                foreach ($resultsub['records'] as $value) {
                    $name                       = '';
                    $attr_id                    = $value['attribute_id'];
                    $sub_attribute[$attr_id][]  = $value['name'];

                    foreach ($sub_attribute[$attr_id] as $rs) {
                        $name   .= ', ' . $rs['th'];
                    }

                    $name = substr($name, 1);
                    $row[$attr_id]['attr_sub']   = $name;
                }
            }
        }

        $dataModel = [];
        //print_r($row); die;
        if (count($row)) {
            $i = $pagination['offset'] + 1;
            foreach ($row as $vData) {
                if($vData['have_products']=='N'){
                    $have_products = 'This attribute is no usage.';
                    // $have_products = '<span class="badge badge-default" style="padding-bottom: 2px;">NO</span>';
                    $del_link = '<a onclick="deleteItems(\''.$vData['attr_id'].'\')" ><i class="icon icon-trash text-danger"></i></a>';
                } else {
                    $have_products = 'This attribute is in use.';
                    //$have_products = '<span class="badge bg-teal-400 badge-raised" style="padding-bottom: 2px;">YES ';
                    //$have_products .= '<span class="badge badge-warning">'.$vData['total_product_using'].'</span></span>';
                    $del_link = '<i class="icon icon-trash" title="'.$have_products.'"></i>';
                }
                
                $dataModel[] = [
                    '_id'   => $vData['attr_id'],
                    'attr_no'   => $i++,
                    'attr_name_th' => $vData['attr_name_th'],
                    'attr_name_en' => $vData['attr_name_en'],
                    'update_at' => $vData['update_at'],
                    'attr_sub'  => $vData['attr_sub'],
                    'have_products'  => $have_products,
                    'delete'    => $del_link,
                    'action'    => '<a href="/attribute/'.$vData['attr_id'].'"><i class="icon icon-pencil"></i></a>'
                ];
            }
        }

        $output = [
            'draw'            => '',
            'recordsTotal'    => count($row),
            'recordsFiltered' => $pagination['total_records'],
            'data'            => $dataModel,
            'input'           => $result
        ];

        return json_encode($output);
    }

    public function getAjaxAttributeMain(Request $request)
    {
        $result = $request->all();
        $textSearch = array_get($result, 'q', '');

        if ($textSearch == '') {
            $return = $this->attributeRepository->getAllAttribute();

        } 
        else {
            $return = $this->attributeRepository->getSearchAttribute($textSearch);

        }

        $pagination = $return['pagination'];
        $results    = $return['data'];

        $attribute = '';
        $row       = array();

        if ($results) {
            foreach ($results as $value) {
                $attr_id          = $value['id'];

                $row[$attr_id]['attr_id']   = $value['id'];
                $row[$attr_id]['attr_name'] = $value['name']['th'];

                $attribute .= ', '.$attr_id;
            }
        }

        if (count($row)) {

            $textSelect = '<select id="mainAttribute" class="form-control">';
            $textSelect .= '<option value="">Choose Attribute</option>';

            foreach ($row as $value) {
                $textSelect .= '<option value="'.$value['attr_id'].'" ';
                $textSelect .= ' >'.$value['attr_name'].'</option>';
            }

            $textSelect .= '</select>';
        }

        return $textSelect;
    }

    public function getAjaxProductAttribute(Request $request)
    {
        $result     = $request->all();

        $params = [
                'product_id' => $result['product_id']
            ];

        $return  = $this->attributeRepository->getProductAttribute($params);

        $attribute  = '';
        $arrChecked = array();

        foreach ($return as $value) {
            $attr_id    = $value['attribute_id'];
            $attribute .= ', '.$attr_id;

            $arrChecked[$attr_id] = $value['attribute_value_id'];
        }

        $attribute = substr($attribute, 1);

        $resultname = $this->attributeRepository->getNameAttribute($attribute);
        $resultsub  = $this->attributeRepository->getSubAttribute($attribute);

        $attr = array();

        foreach ($resultsub as $result) {

            $attr_id = $result['attribute_id'];

            $idx = 0;
            foreach ($result['sub_attribute'] as $value) {

                $attr[$attr_id][$idx]['id']             = $value['sub_id'];
                $attr[$attr_id][$idx]['attribute_name'] = $value['sub_name']['th'];

                $idx++;
            }
        }

        foreach ($attr as $key => $result) {

            $textSelect = '<select id="attr_'.$key.'" class="bootstrap-select">';

            $textSelect .= '<option value=""></option>';

            foreach ($result as $value) {
                $textSelect .= '<option value="'.$value['id'].'" ';

                if ($arrChecked[$key] == $value['id']) {
                    $textSelect .= 'selected';
                }

                $textSelect .= ' >'.$value['attribute_name'].'</option>';
            }

            $textSelect .= '</select>';

            $arrSelect[$key] = $textSelect;
        }

        $attribute = '';
        $row       = array();

        if ($resultname) {
            foreach ($resultname as $vData) {
                $dataModel[] = [
                    'attr_id'         => $vData['id'],
                    'attribute'       => $vData['name']['th'],
                    'attribute_value' => $arrSelect[$vData['id']],
                    'action'          => '<a href="/attribute/'.$vData['id'].'"><i class="icon icon-bin"></i></a>'
                ];
            }
        }

        unset($dataModel);

        $dataModel[0] = [
                    'attr_id'         => '',
                    'attribute'       => '',
                    'attribute_value' => '',
                    'action'          => ''
                ];

        $output = [
            'draw'            => '',
            'recordsTotal'    => count($dataModel),
            'recordsFiltered' => count($dataModel),
            'data'            => $dataModel,
            'input'           => ''
        ];

        return json_encode($output);
    }

    public function postAjaxProductAttribute(Request $request)
    {
        $dataSave = $request->all();
        $dataSave = json_encode($dataSave);

        $result   = $this->attributeRepository->saveProductAttribute($dataSave);

        return $result;
    }

    private function genSubAttr($result)
    {
        $sub_attribute = array();
        $sub_attr      = array_get($result, 'sub_attr', []);

        foreach ($sub_attr as $value) {
            $sub_attribute[] = ['name' => $value];
        }

        return $sub_attribute;
    }
     /**
	 * Method for report excel
	 */
	public function exportAttributes(Request $request)
	{
		$result = $this->attributeRepository->getDataAttributeReport($request->input());
		if (!$result['status']) {
			$request->session()->flash('messages', [
				'type' => 'error',
				'text' => $result['message']
			]);

			return redirect('/attribute');
		}
	}

    public function deleteData($id){
        $result = $this->attributeRepository->deleteAttribute($id);
        return $result;
        if (!$result['status']) {
			$request->session()->flash('messages', [
				'type' => 'error',
				'text' => $result['message']
			]);

			return $result;
		}
    }
}
