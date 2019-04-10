<?php
namespace App\Repositories;

use App;
use App\Services\Guzzle;
use DateTime;
use Excel;
use Lang;
use App\Library\Unit;
class ContentsRepository
{
	private $url;
	private $messages;

	protected $dataTableColumns = ['checkbox', 'number', 'id', 'name.th','name.en', 'priority', 'status', 'edit', 'delete'];

	public function __construct(Guzzle $guzzle)
	{
		$this->guzzle 	    = $guzzle;
		$this->messages 	= config('message');
		$this->url 		    = env('CURL_API_CONTENT');
		$this->urlTag 	    = env('CURL_API_TAG');
		$this->urlCategory  = env('CURL_API_CATEGORY');
		$this->_unit        = new Unit;
	}

	public function getContentDataById($content_id)
	{
		$output = $this->getContentDetail($content_id);
		return $output;
	}

	public function getContentDetail($content_id)
	{	
		$url = $this->url .'contents/' . $content_id;
		$languages = config('language.content');	
		$options = [
			'headers' => [
				'x-language' => implode('|', $languages)
			]
		];

		$data = $this->guzzle->curl('GET', $url, $options);

		$output = [];

		if (isset($data['status']) && isset($data['status']['code'])) {
			if ($data['status']['code'] == 200) {
				$output = $data['data']['records'][0];
			}
		}

		return $output;
	}

	public function createContent($data)
	{
		$url = $this->url. "contents";
		$options = [
			'form_params' => $data
		];
		return $this->guzzle->curl('POST', $url, $options);
	}

	public function updateContent($id, $data)
	{
		$url = $this->url. "contents/" . $id;
		$options = [
			'json' => $data
		];
		return $this->guzzle->curl('PUT', $url, $options);
	}

	public function deleteContent($ids)
	{
		if (isset($ids)&&!empty($ids)) {
			$url = $this->url. "contents/" . $ids;
            $result = $this->guzzle->curl('DELETE', $url, []);
            if($result['status']['code'] == 200){

                // Delete Multiple Brand Response
                if(isset($result['data']['deleted']) && isset($result['data']['errors'])){
                    return array(
                        'status' => true,
                        'deleted' => $result['data']['deleted'],
                        'errors'  => $result['data']['errors']
                    );
                }
                else{
                    return array('status' => true, 'messages' => Lang::get('validation.delete.success'));
                }
            }
            else{
                return array('status' => false, 'messages' => $result['errors']['message']);
            }
        }
        return array('status' => false, 'messages' => Lang::get('validation.delete.fail'));
	}

	public function getCurlDataContent(array $params)
	{
		try {
			$queryData = [];

			$queryData['offset'] = $params['start'];
			$queryData['limit'] = $params['length'];

			if($params['order'][0]['dir'] != "false"){
				// Map order name with column index
				if($params['order'][0]['column'] == 'name_th' || $params['order'][0]['column'] == 'name_en'){
					$orderField = $params['order'][0]['column'];
					$value = str_replace('_','.',$orderField);
				}
				else{
					$value = $params['order'][0]['column'];
				}

				$queryData['order'] = $value . '|' . $params['order'][0]['dir'];
			}

			if(isset($params['full_text']) && !empty($params['full_text'])){
				// Search by name,slug field
				$queryData['fields'] = 'name.th,name.en,slug';
				$queryData['search'] = $params['full_text'];
			}

			if(isset($params['category_id']) && !empty($params['category_id'])){
				$queryData['category_id'] = $params['category_id'];
			}

			if(isset($params['date']) && !empty($params['date'])){
				$date = convertDateTime($params['date'], 'd/m/Y', 'Y-m-d 00:00:00');
				$queryData['start_date'] = sprintf('[,%s]', $date);
				$queryData['end_date'] = sprintf('[%s]', $date);
			}

			if(isset($params['status']) && !empty($params['status'])){
				$queryData['status'] = $params['status'];
			}

			$url = $this->url. "contents";

			$options = [
				'headers' => [
					'x-language' => 'th|en'
				],
				'query' => $queryData
			];

			$result = $this->guzzle->curl('get', $url, $options);
		}
		catch (\Exception $e) {
			$result = json_decode((string) $e->getResponse()->getBody(), true);
			$result['data'] = [
				'total'    => 0,
				'contents' => []
			];
		}

		return $result;
	}
	public function getContentData($params)
	{
		$url = $this->url . '/contents';
		$result = $this->guzzle->curl('get', $url, $params);
		return $result;
	}

	public function getDataContent(array $params)
	{
		$language = App::getLocale();
		$result = $this->getCurlDataContent($params);

		if(isset($result['status']['code']) && $result['status']['code'] == 200){
			$output = [
				'draw'            => $params['draw'],
				'recordsTotal'    => count($result['data']['records']), //count page
				'recordsFiltered' => $result['data']['pagination']['total_records'], //count all
				'data'            => $this->setDataTable($result['data']['records'], $params, $language),
				'input'           => $params
			];
		}
		else{
			$output = [
				'status' => false,
				'message' => $result['errors'][0]['message']
			];
		}

		return json_encode($output);
	}

	public function setDataTable($data, array $params, $language)
	{
		$dataTable = [];
		if (count($data) > 0) {
			foreach ($data as $kData => $vData) {
				$numberData = ($kData + 1) + $params['start'];
				$dataTable[] = [
					'number'     => $numberData,
					'id'         => $vData['id'],
					'name_th'    => $vData['name']['th'],
					'name_en'    => $vData['name']['en'],
					'slug'       => (isset($vData['slug']) && !empty($vData['slug']))? $vData['slug'] : '-',
					'created_at' => convertDateTime($vData['created_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
					'priority'   => $vData['priority'],
					'status'     => $vData['status'],
					'removable'  => $vData['removable']
				];
			}
		}

		return $dataTable;
	}

	public function setPriorityContent($data)
	{
		
		try {
			$url = $this->url. "contents/updatePriority";
			$options = [
				'json' => $data
			];

			$result = $this->guzzle->curl('PUT', $url, $options);

			return ['success' => true, 'data' => $result['data']];

		} catch (\Exception $e) {
			$result = json_decode((string) $e->getResponse()->getBody(), true);
			$result['data'] = [
				'total'    => 0,
				'contents' => []
			];

			return ['success' => false, 'data' => ['id' => $data]];
		}
	}

	public function setStatusContent($data)
	{
		try {
			$url = $this->url. "contents/setStatus";
			$options = [
				'json' => $data
			];
			$result = $this->guzzle->curl('PUT', $url, $options);
			return ['success' => true, 'data' => $result['data']];
		} 
		catch (\Exception $e) {
			$result = json_decode((string) $e->getResponse()->getBody(), true);
			$result['data'] = [
				'total'    => 0,
				'contents' => []
			];
			return ['success' => false, 'data' => []];
		}
	}

	public function getDataContentReport($inputs) 
	{
		$result = $this->getCurlDataContent($inputs);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $content = $result['data']['records'];
            $start = $result['data']['pagination']['offset'] + 1;

            return Excel::create('content_report_' . date('YmdHis'), function($excel) use ($content,$start) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Content', function($sheet) use ($content,$start) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Slug',
                        'Content Name (TH)',
                        'Content Name (EN)',
						'Create Date',
						'Priority',
						'Published'
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($content as $kData => $vData) {
                        ++$row;

                        $data = [
                            $start,
                            $this->_unit->removeFirstInjection($vData['slug']),
							$this->_unit->removeFirstInjection($vData['name']['th']),
							$this->_unit->removeFirstInjection($vData['name']['en']),
							$this->_unit->removeFirstInjection($vData['created_at']),
							$this->_unit->removeFirstInjection($vData['priority']),
							($vData['status']=='active')? 'Publish' : 'Unpublish'
                        ];

                        $start++;

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }
	}
}