<?php
namespace App\Repositories;

use Config;
use App;

use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Exception\RequestException;
use App\Services\Guzzle;
use Cache;
use Excel;
use App\Library\Unit;
class AttributeRepository
{
    public function __construct(Guzzle $guzzle)
    {
        $this->urlAttribute = env('CURL_API_ATTRIBUTE');

        $this->url  = config('api.makro_attribute_api');
        $this->messages     = config('message');
        $this->guzzle       = $guzzle;
        $this->_unit        = new Unit;
    }

    public function getAllAttribute($limit=999999, $offset=0 , $order = 'name.th|asc')
    {
        $url    = Config::get('api.makro_attribute_api') . 'attributes?limit=' . $limit . '&offset=' . $offset . '&attribute_type=select&order=' . $order;
        $result = self::curlGet($url);
        $result = json_decode($result, 1);

        $return['data'] = $result['data'];

        return $return;
    }

    public function getSubAttribute($main_attr_id)
    {
        $url    = Config::get('api.makro_attribute_api') . 'attributes/subattributes?attribute_ids=' . $main_attr_id;

        $result = self::curlGet($url);
        $result = json_decode($result, 1);
        $data   = $result['data'];

        return $data;
    }

    public function getAttributeContent($id,$type)
    {
        $url    = Config::get('api.makro_attribute_api') . 'attributes/content?content_id=' . $id . '&type=' . $type;

        $result = self::curlGet($url);
        $result = json_decode($result, 1);
        $data   = $result['data'];

        return $data;
    }

    public function getAttributeContentByAttributeId($id)
    {
        $url    = Config::get('api.makro_attribute_api') . 'attributes/content?attribute_id=' . $id;

        $result = self::curlGet($url);
        $result = json_decode($result, 1);
        $data   = $result['data'];

        return $data;
    }

    public function addAttributeContent($dataSave)
    {
        $options = [
            'json' => $dataSave
        ];
        $url = $this->url.'attributes/content';
        $result = $this->guzzle->curl('POST', $url, $options);
        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            $content_id = $dataSave['content_id'];
            return array('status' => true, 'content_id' => $content_id);
        } else {
            return array('status' => false, 'messages' => $result['error']['message']);
        }
        return $result;
    }

    public function getSearchAttribute($limit=999999, $offset=0, $textSearch,$order = 'name.th|asc')
    {
        $url    = Config::get('api.makro_attribute_api') . 'attributes?limit=' . $limit . '&offset=' . $offset . '&attribute_type=select&order=' . $order . '&name=' . $textSearch;
        $result = self::curlGet($url);
        $result = json_decode($result, 1);
        $return['data'] = $result['data'];

        return $return;
    }

    public function getAttribute($attribute)
    {
        $url    = Config::get('api.makro_attribute_api') . 'attributes/' . $attribute;
        $result = self::curlGet($url);
        $result = json_decode($result, 1);
        $data   = $result['data'];

        return $data;
    }

    public function deleteAttribute($attribute)
    {
        $result = [];
        $url    = $this->url. 'attributes/'.$attribute;
        $result = $this->guzzle->curl('DELETE', $url, []);

        return $result;
    }

    public function saveAttribute($dataSave)
    {
        $options = [
            'json' => $dataSave
        ];
        $url = $this->url.'attributes';
        $result = $this->guzzle->curl('POST', $url, $options);
        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            $attributeId = $result['data']['records'][0]['id'];
            return array('status' => true, 'attributeId' => $attributeId);
        } else {
            return array('status' => false, 'messages' => $result['errors']['message']);
        }
        return $result;
    }

    public function updateAttribute($attr_id, $dataUpdate)
    {
        $url    = Config::get('api.makro_attribute_api') . 'attributes/' . $attr_id;
        $result = self::curlPut($url, $dataUpdate);
        $json   = json_decode($result, 1);
        return $json;
    }

    public function delSubAttribute($sub_attr_id, $dataUpdate)
    {
        $url    = Config::get('api.makro_attribute_api') . 'attributes/subattributes/' . $sub_attr_id;
        $result = self::curlPut($url, $dataUpdate);
        $json   = json_decode($result, 1);

        return $json;
    }

    private function curlGet($url)
    {
        $result = $this->guzzle->curlRaw('GET', $url);
        return $result;
    }

    private function curlPost($url, $data)
    {
        $result = $this->guzzle->curlRaw('POST', $url , [
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => $data 
        ]);

        return $result;
    }

    private function curlPut($url, $data)
    {
        $result = $this->guzzle->curlRaw('PUT', $url , [
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => $data 
        ]);

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

    /**
     * Method for curl api attribute report
     */
    public function getDataAttributeReport(array $inputs)
    {
        $output = [
            'status' => false,
            'message' => ''
        ];

        $textSearch = $inputs['search'][0]['value'];
        $limit      = (!empty($inputs['length'])) ? $inputs['length'] : 10;
        $offset     = (!empty($inputs['start'])) ? $inputs['start'] : 0;

        $url    = Config::get('api.makro_attribute_api') . 'attributes?limit=' . $limit . '&offset=' . $offset . '&attribute_type=select&name=' . $textSearch;

        $result = self::curlGet($url);
        $result = json_decode($result, 1);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $attributes = $result['data']['records'];

            return Excel::create('attribute_report_' . date('YmdHis'), function($excel) use ($attributes) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Attribute', function($sheet) use ($attributes) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');
                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Attribute Name (TH)',
                        'Attribute Name (EN)',
                        'Attribute Values (TH)',
                        'Attribute Values (EN)',
                        'Last Update'
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($attributes as $kData => $vData) {
                        ++$row;
                        $sub_attr_name_th = '';
                        $sub_attr_name_en = '';
                        $resultsub = $this->getSubAttribute(array_get($vData, 'id', ''));
                        if ($resultsub) {
                            foreach ($resultsub['records'] as $value) {
                                $sub_attr_name_th = '';
                                $sub_attr_name_en = '';
                                $attr_id                    = $value['attribute_id'];
                                $sub_attribute[$attr_id][]  = $value['name'];

                                foreach ($sub_attribute[$attr_id] as $rs) {
                                    $sub_attr_name_th   .= ', ' . $rs['th'];
                                    $sub_attr_name_en   .= ', ' . $rs['en'];
                                }

                                $sub_attr_name_th = substr($sub_attr_name_th, 1);
                                $sub_attr_name_en = substr($sub_attr_name_en, 1);
                            }
                        }
                        $data = [
                            $kData + 1,
                            $this->_unit->removeFirstInjection(array_get($vData, 'name.th', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'name.en', '')),
                            $this->_unit->removeFirstInjection($sub_attr_name_th),
                            $this->_unit->removeFirstInjection($sub_attr_name_en),
                            !empty($vData['update_at'])? '="'.convertDateTime($vData['update_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s').'"' : ''
                        ];

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        } else  if (isset($result['data']) && $result['data']['pagination']['total_records']==0) {
            $output['message'] = 'No Data';
            return $output;
        }
        $output['message'] = 'Error Repost';
        return $output;
    }

}
