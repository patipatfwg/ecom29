<?php
namespace App\Repositories;

use App\Services\MyServices;
use App\Services\Guzzle;
use App;

class CategoryBusinessRepository extends CategoryRepository
{
    public function __construct(Guzzle $guzzle, MyServices $myServices)
	{
		parent::__construct($guzzle, $myServices);
		$this->type = config('config.type_category_business');
	}
        /**
     * Method for datetable
     */
    public function setDataTable($data, array $params)
    {
        //loop get data
        $dataTable = [];
        $language = App::getLocale();

        if (count($data) > 0) {

            foreach ($data as $kData => $vData) {
                $numberData  = ($kData + 1) + $params['start'];

                if ($vData['status'] == 'active') {
                  $status = '<i class="icon-eye text-teal"></i>';
                } elseif ($vData['status'] == 'inactive') {
                  $status = '<i class="icon-eye-blocked text-grey-300"></i>';
                }

                if ($vData['level'] < 2) {
                  $child = '<a href="' . url('/category_business/' . $vData['id'] ) . '" ><i class="icon-tree6" ></i></a>';
                } elseif ($vData['level'] >= 2) {
                  $child = '<i class="icon-tree6" ></i>';
                }
                $checkChild  = $this->guzzle->curl('GET', $this->urlCategory . 'categories/tree/category/' . $vData['id'] . '/level/0');
                $setCheckbox = isset($checkChild['data']['children']) ? '-' : '<input class="ids click-all check" type="checkbox" name="category_ids[]" value="' . $vData['id'] . '" class="check">';
                $setDelete   = isset($checkChild['data']['children']) ? '<i class="icon-trash" title="This business category has sub business category.">' : '<a onclick="deleteItems(\'' . $vData['id'] . '\')"><i class="icon-trash text-danger"></a>';
                $btn_add_product = '<a href="/category/'.$vData['id'].'/business" target="_blank"><i class="icon-link"></i></a>';
                $dataTable[] = [
                    'checkbox'      => $setCheckbox,
                    'number'        => $numberData,
                    'category_id'   => $this->checkEmpty($vData['id'], $params['search'][2]['value']),
                    'category_name_th' => $this->checkEmpty($vData['name']['th'], $params['search'][1]['value']),
                    'category_name_en' => $this->checkEmpty($vData['name']['en'], $params['search'][1]['value']),
                    'level'         => $vData['level'],
                    'status'        => $status,
                    'priority'      => '<input type="text" maxlength="2" onKeyPress="CheckNum()" class="form-control input-width-priority-80 text-center priority_number" name="priority[' . $vData['id'] . ']" value="' . $vData['priority'] . '">',
                    'edit'          => '<a href="' . url('/category_business/' . $vData['id'] . '/edit' ) . '"><i class="icon-pencil"></i></a>',
                    'delete'        => $setDelete,
                    'child'         => $child,
                    'btn_add_product' => $btn_add_product
                ];
            }
        }
    
        return $dataTable;
    }
}
?>