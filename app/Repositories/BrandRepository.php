<?php

namespace App\Repositories;

use App\Services\Guzzle;
use App;
use Excel;
use Lang;
use Cache;
use App\Library\Unit;
class BrandRepository
{
    protected $messages;
    protected $guzzle;
    protected $type = 'brand';
    protected $typeId = 1;
    protected $default_priority = 99; // Priority (1-16)
    protected $filter = ['categories.id', 'contents.content'];
    protected $orderBy = ['', '', 'id', 'name.th', 'name.en', 'priority'];

    public function __construct(Guzzle $guzzle)
    {
        $this->urlCategory = env('CURL_API_CATEGORY');
        $this->urlAttribute = env('CURL_API_ATTRIBUTE');
        $this->messages = config('message');
        $this->guzzle = $guzzle;
        $this->_unit  = new Unit;
    }

    protected function prepareRequestBody($input)
    {
        $params = array();

        $appLocale = App::getLocale();
        $languages = config('language.brand');

        // Set Name
        foreach ($languages as $language) {
            if (isset($input['name'][$language]) && !empty($input['name'][$language])) {
                $params['name_' . $language] = $input['name'][$language];
            }
        }

        // Set SEO Subject
        if (isset($input['seo_subject']) && !empty($input['seo_subject'])) {
            $params['seo_subject'] = $input['seo_subject'];
        } else {
            $params['seo_subject'] = $input['name'][$appLocale];
        }

        // Set SEO Explanation
        if (isset($input['seo_explanation']) && !empty($input['seo_explanation'])) {
            $params['seo_explanation'] = $input['seo_explanation'];
        } else {
            $params['seo_explanation'] = $input['name'][$appLocale];
        }

        // Set images
        if (isset($input['images']) && !empty($input['images'])) {
            $params['images'] = $input['images'];
        }

        // Set priority
        $params['priority'] = (isset($input['priority'])) ? $input['priority'] : $this->default_priority;

        // Set Status
        $params['status'] = (isset($input['status'])) ? 'active' : 'inactive';

        // Set Slug
        $params['slug'] = (isset($input['slug'])) ? $input['slug'] : $input['name']['en'];

        return $params;
    }

    public function createBrand($input)
    {
        $params = $this->prepareRequestBody($input);

        return $result = $this->postCreateBrand($params);
    }

    private function postCreateBrand($params)
    {
        $this->deleteCache();

        $params['type'] = $this->type;

        if (isset($params['images']) && !empty($params['images'])) {
            $image = $params['images'];
        }

        unset($params['images']);

        // Add Brand
        $url = $this->urlCategory . 'categories';
        $options = [
            'json' => $params
        ];
        $result = $this->guzzle->curl('POST', $url, $options);
        if ($result['status']['code'] == 200) {

            $brand_id = $result['data']['id'];

            // Add Brand Image
            if (isset($image)) {
                return $this->postCreateBrandImage($brand_id, $image);
            } else {
                return $result;
            }
        }

        return $result;
    }

    private function postCreateBrandImage($brand_id, $images)
    {
        $url = $this->urlCategory . 'categories/image';
        $options = [
            'json' => [
                'category_id' => $brand_id,
                'images' => $images
            ]
        ];
        return $result = $this->guzzle->curl('POST', $url, $options);
    }

    public function getDataBrand(array $params)
    {
        $search = implode('&', [
            'order=' . $this->setOrderData($params),
            'offset=' . $params['start'],
            'limit=' . $params['length'],
            'name=' . $params['full_text'],
            'category_id=' . $params['brand_id'],
            'status=active,inactive',
            'type=' . 'brand'
        ]);
        $options = [
            'headers' => [
                'x-language' => 'en|th'
            ]
        ];

        $result = $this->guzzle->curl('GET', $this->urlCategory . 'categories/?' . $search, $options);
        $output = [
            'draw' => $params['draw'],
            'recordsTotal' => isset($result['data']['records']) ? count($result['data']['records']) : 0, //record total
            'recordsFiltered' => isset($result['data']['records']) ? $result['data']['pagination']['total_records'] : 0, //record per page
            'data' => isset($result['data']['records']) ? $this->setDataTable($result['data']['records'], $params) : [],
            'input' => $params
        ];

        return json_encode($output);
    }

    /**
     * Method for datetable
     */
    public function setDataTable($data, array $params)
    {
        //loop get data
        $dataTable = [];
        if (count($data) > 0) {
            foreach ($data as $kData => $vData) {
                $numberData = ($kData + 1) + $params['start'];
                $dataTable[] = [
                    'id' => $vData['id'],
                    'number' => $numberData,
                    'name_th' => $vData['name']['th'],
                    'name_en' => $vData['name']['en'],
                    'priority' => $vData['priority'],
                    'seo_subject' => $vData['seo_subject'],
                    'seo_explanation' => $vData['seo_explanation'],
                    'slug' => $vData['slug'],
                    'status' => $vData['status'],
                    'edit' => url('/brand/' . $vData['id'] . '/edit')
                ];
            }
        }

        return $dataTable;
    }

    private function setOrderData(array $params)
    {
        $order = [];
        $orderBy = $this->orderBy;
        if (isset($params['order']) && count($params['order']) > 0) {
            foreach ($params['order'] as $kData => $vData) {
                if (isset($vData['column']) && isset($orderBy[$vData['column']]) && $vData['column'] !== '0') {
                    $order[] = $orderBy[$vData['column']] . '|' . $vData['dir'];
                }
            }
        }

        return implode(',', $order);
    }

    public function getBrandById($brand_id)
    {
        $data = array();

        if (isset($brand_id)) {
            $url = $this->urlCategory . 'categories/' . $brand_id;
            $options = [
                'headers' => [
                    'x-language' => 'en|th'
                ],
                'query' => [
                    'status' => 'active,inactive'
                ]
            ];

            $result = $this->guzzle->curl('GET', $url, $options);
            if ($result['status']['code'] == 200) {
                $data = $result['data'][0];
            } else {
                dd($result);
            }
        }
        return $data;
    }

    public function getBreadcrumbBrand($brand_id = '', $level = '', $language = 'th')
    {
        $data = array();
        $result = $this->getBrandTreeParent($brand_id, $level);

        if (!isset($result['error'])) {
            $data = $this->setBreadcrumb($result['data'], $language);
        }

        return $data;
    }

    private function setBreadcrumb($data, $language)
    {
        $output = array();

        if (isset($data['id']) && !empty($data['id'])) {
            $brand_id = $data['id'];
            $result = $this->getBrandById($brand_id);
            $brand['id'] = $brand_id;
            $brand['name'] = $result['name_' . $language];
            $output[] = $brand;

            if (isset($data['parent'])) {
                $result = $this->setBreadcrumb($data['parent'][0], $language);
                $output = array_merge($result, $output);
            }
        }

        return $output;
    }

    private function getBrandTreeParent($brand_id = '', $level = '')
    {
        $url = $this->urlCategory . 'treeparent?brand_id=' . $brand_id . '&level=' . $level;
        $type = 'GET';
        $result = self::curlApi($url, $type);

        return $result;
    }

    public function deleteBrand($_id)
    {
        if (isset($_id)) {

            $result = $this->getDeleteBrand($_id);

            if ($result['status']['code'] == 200) {

                // Delete Multiple Brand Response
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

    private function getDeleteBrand($_id)
    {
        $this->deleteCache();

        $url = $this->urlCategory . 'categories/' . $_id;
        $result = $this->guzzle->curl('DELETE', $url);
        return $result;
    }

    public function uploadImage($filename)
    {
        //Define output
        $outputs = [
            'success' => false,
            'image' => ''
        ];

        //get cdn service
        $cdbService = App::make('App\Services\CdnServices');

        try {
            $shortUrl = $cdbService->_uploadFileData($filename);
        } catch (Exception $e) {
            $shortUrl = array();
        }


        if (!empty($shortUrl)) {
            $outputs['success'] = true;
            $outputs['image'] = $shortUrl;

        }

        return $outputs;
    }

    public function updateBrand($input, $brand_id)
    {
        $params = $this->prepareRequestBody($input);

        return $result = $this->postUpdateBrand($params, $brand_id);
    }

    public function updateBrandMultiple($inputs)
    {
        $this->deleteCache();

        $params = [];
        foreach ($inputs as $input) {
            array_push($params, [
                'category_id' => $input['category_id'],
                'type' => $this->type,
                'seo_subject' => $input['seo_subject'],
                'seo_explanation' => $input['seo_explanation'],
                'slug' => $input['slug'],
                'status' => $input['status'],
                'priority' => $input['priority']
            ]);
        }

        $url = $this->urlCategory . 'categories';
        $result = $this->guzzle->curl('PUT', $url, [
            'json' => $params
        ]);

        if ($result['status']['code'] == 200) {
            return [
                'success' => true,
                'code' => $result['status']['code'],
                'updated' => $result['data']['updated'],
                'errors' => $result['data']['errors']
            ];
        } else {
            return array('success' => false, 'messages' => 'Update Error');
        }
    }

    private function postUpdateBrand($params, $brand_id)
    {
        $this->deleteCache();

        $params['type'] = $this->type;

        // Add Brand Image
        if (isset($params['images']) && !empty($params['images'])) {

            $result = $this->postCreateBrandImage($brand_id, $params['images']);

            // Return Error When Create Image Failed
            if ($result['status']['code'] != 200) {
                return $result;
            }
        }

        unset($params['images']);

        $url = $this->urlCategory . 'categories/' . $brand_id;
        $result = $this->guzzle->curl('PUT', $url, [
            'json' => $params
        ]);

        return $result;
    }

    public function getDataBrandReport(array $params)
    {
        $search = implode('&', [
            'order=' . $this->setOrderData($params),
            'offset=' . $params['start'],
            'limit=' . $params['length'],
            'name=' . $params['full_text'],
            'category_id=' . $params['brand_id'],
            'status=active,inactive',
            'type=' . 'brand'
        ]);
        $options = [
            'headers' => [
                'x-language' => 'en|th'
            ]
        ];
        $result = $this->guzzle->curl('GET', $this->urlCategory . 'categories/?' . $search, $options);

        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            $categories = $result['data'];

            return Excel::create('brand_report_' . date('YmdHis'), function ($excel) use ($categories) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Brand', function ($sheet) use ($categories) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Brand ID',
                        'Brand Name (TH)',
                        'Brand Name (EN)',
                        'SEO Title Page',
                        'SEO META Description',
                        'Priority',
                        'Slug',
                        'Published'
                    ]);

                    $sheet->row(1, function ($row) {
                        $row->setBackground('#dddddd');
                    });
                    $row = 1;
                    $publish_status = '';
                    foreach ($categories['records'] as $kData => $vData) {
                        ++$row;
                        if ($vData['status'] == 'active') {
                            $publish_status = 'Publish';
                        } else {
                            $publish_status = "Unpublish";
                        }
                        $data = [
                            $categories['pagination']['offset'] + $kData + 1,
                            '="'.array_get($vData, 'id', ''). '"',
                            $this->_unit->removeFirstInjection(array_get($vData, 'name.th', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'name.en', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'seo_subject', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'seo_explanation', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'priority', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'slug', '')),
                            $this->_unit->removeFirstInjection($publish_status)
                        ];

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }

        return false;
    }

    public function setStatusBrand($ids)
    {
        $this->deleteCache();

        $datas = $ids['brand_ids'];
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

    public function check_del($ids)
    {
        $option_url = 'categories/content/category?ids=' . $ids;
        $url = $this->urlCategory . $option_url;
        $result = $this->guzzle->curl('GET', $url);
        $total = 0;
        $cannotDel = [];
        $del_list = [];
        $text = "";

        if ($result['status']['code'] == 200) {
            foreach ($result['data'] as $key => $value) {

                $total = count($value['contents']);
                $data[] = array(
                    'total' => $total,
                    'messages' => '',
                    'data' => $value,
                );
                if ($total > 0) {
                    $cannotDel[] = $value['id'];
                } else {
                    $del_list[] = $value['id'];
                }
            }
        } else {
            $data = array(
                'total' => $total,
                'messages' => 'status code ' . $result['status']['code'],
                'data' => '',
            );
        }

        if (count($cannotDel) > 0) {
            $text .= 'This brand is in use ' . implode(",", $cannotDel);

        }

        if (count($del_list) > 0) {
            if (count($text) > 0)
                $text .= "\n";
            $text .= 'Brand will be deleted is/are ' . implode(",", $del_list);
        }

        $return_data = array(
            'del_list' => $del_list,
            'error_del' => $cannotDel,
            'error' => count($cannotDel),
            'data_list' => $data,
            'text' => $text
        );

        return $return_data;
    }

    protected function deleteCache()
    {
        Cache::store('redis')->forget('root_categories.brand');
    }
}
