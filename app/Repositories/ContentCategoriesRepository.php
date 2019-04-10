<?php
namespace App\Repositories;

use App\Services\MyServices;
use App\Services\Guzzle;
use Excel;

class ContentCategoriesRepository extends CategoryRepository
{
    private $orderBy = [
        '', '', 'id', 'name.th', 'name.en', 'level', '', 'priority'
    ];

    public function __construct(Guzzle $guzzle, MyServices $myServices)
    {
        parent::__construct($guzzle, $myServices);
        $this->type = config('config.type_category_content');
    }

    protected function addAttribute($category_id, array $inputs)
    {
        return true;
    }

    protected function addImage($category_id, $inputs)
    {
        return true;
    }

    public function setDataTable($data, array $params)
    {
        //loop get data
        $dataTable = [];

        if (count($data) > 0) {

            foreach ($data as $kData => $vData) {
                $numberData = ($kData + 1) + $params['start'];

                if ($vData['status'] == 'active') {
                    $status = '<i class="icon-eye text-teal"></i>';
                } elseif ($vData['status'] == 'inactive') {
                    $status = '<i class="icon-eye-blocked text-grey-30"></i>';
                }

                if ($vData['level'] < 2) {
                    $child = '<a href="' . url('/content_category/' . $vData['id']) . '" ><i class="icon-tree6" ></i></a>';
                } elseif ($vData['level'] >= 2) {
                    $child = '<i class="icon-tree6" ></i>';
                }

                $dataTable[] = [
                    'checkbox' => '<input class="ids click-all check" type="checkbox" name="category_ids[]" value="' . $vData['id'] . '" class="check">',
                    'number' => $numberData,
                    'category_id' => $this->checkEmpty($vData['id'], $params['search'][2]['value']),
                    'category_name_th' => $this->checkEmpty($vData['name']['th'], $params['search'][1]['value']),
                    'category_name_en' => $this->checkEmpty($vData['name']['en'], $params['search'][1]['value']),
                    'level' => $vData['level'],
                    'status' => $status,
                    'priority' => '<input type="text" maxlength="2" onKeyPress="CheckNum()" class="form-control input-width-priority-80 text-center priority_number" name="priority[' . $vData['id'] . ']" value="' . $vData['priority'] . '">',
                    'edit' => '<a href="' . url('/content_category/' . $vData['id'] . '/edit') . '"><i class="icon-pencil"></i></a>',
                    'delete' => '<a onclick="deleteItems(\'' . $vData['id'] . '\')"><i class="icon-trash text-danger"></a>',
                    'child' => $child
                ];
            }
        }

        return $dataTable;
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
     * Method for set data order
     */
    private function setOrderData(array $params)
    {
        $order = [];

        if (isset($params['order'])) {
            foreach ($params['order'] as $kData => $vData) {
                // $order[] = $vData['column'] . '|' . $vData['dir'];
                $order[] = $this->orderBy[$vData['column']] . '|' . $vData['dir'];
            }
        }

        return implode(',', $order);
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
                    /*if ($vData['name'] == 'category_id') {
                        $vData['name'] = 'categories.id';
                    }*/
                    $search[] = $vData['name'] . '=' . $vData['value'];
                }
            }
        }

        return $search;
    }

    /**
     * Method for curl api category report
     */
    public function getDataCategoryReport(array $params)
    {
        $params['length'] = '9999';
        $getUrl = $this->setSearch($params);
        $params = [
            'headers' => [
                'x-language' => $this->myServices->getLanguageHeader('category'),
            ],
        ];
        $result = $this->guzzle->curl('GET', $this->urlCategory . 'categories?' . $getUrl, $params);
        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $categories = $result['data']['records'];

            return Excel::create('category_report_' . date('YmdHis'), function ($excel) use ($categories) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Category', function ($sheet) use ($categories) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Category ID',
                        'Name TH',
                        'Name EN',
                        'Level',
                        'Priority',
                        'SEO Subject',
                        'SEO Explanation',
                        'Status',
                        'Type'
                    ]);

                    $sheet->row(1, function ($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($categories as $kData => $vData) {
                        ++$row;

                        $data = [
                            $kData + 1,
                            array_get($vData, 'id', ''),
                            array_get($vData, 'name.th', ''),
                            array_get($vData, 'name.en', ''),
                            array_get($vData, 'level', ''),
                            array_get($vData, 'priority', ''),
                            array_get($vData, 'seo_subject', ''),
                            array_get($vData, 'seo_explanation', ''),
                            array_get($vData, 'status', ''),
                            array_get($vData, 'type', '')
                        ];

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }

        return false;
    }
}
