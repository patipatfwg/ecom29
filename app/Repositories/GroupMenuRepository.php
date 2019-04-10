<?php
namespace App\Repositories;

use App\Repositories\PaymentRepository;
use App\Services\Guzzle;
use App;
use Excel;
use App\Library\Unit;
class GroupMenuRepository
{

	public function __construct(Guzzle $guzzle,PaymentRepository $paymentRepository)
	{
		$this->paymentRepository = $paymentRepository;
		$this->url 	  = env('CURL_API_GROUP');
		$this->guzzle = $guzzle;
		$this->_unit  = new Unit;
	}

	public function getGroupMenuData($params)
	{
		$url = $this->url . '/groups';
		$result = $this->guzzle->curl('get', $url, $params);
		return $result; 
	}

	public function getGroupMenus($inputs)
	{
		$params = [
			'query' => [
				'fields' => 'title.th,title.en',
				'search' => $inputs['full_text'],
				'offset' => $inputs['start'],
				'limit'  => $inputs['length'],		
			]
		];

		if($inputs['order'][0]['dir'] != 'false') {
			if(preg_match('/group_name_[a-z]{2}/', $inputs['order'][0]['column'])) {
				$language = substr($inputs['order'][0]['column'], -2);
				$fieldName = "title.$language";
			} else {
				$fieldName = $inputs['order'][0]['column'];
			}	
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
		}

		$result = $this->getGroupMenuData($params);
		
		$dataTable = [];
		$count_page = 0;
		$count_all = 0;
		if (isset($result['status']) && !empty($result['status']) && $result['status']['code'] == 200) {
			$dataTable = $this->setDataTable($result['data']['records'], $inputs);
			$count_page = count($result['data']['records']); //count page
			$count_all = $result['data']['pagination']['total_records']; //count all
		}

		$output = [
			'draw'            => $inputs['draw'],
			'recordsTotal'    => $count_page, //count page
			'recordsFiltered' => $count_all, //count all
			'data'            => $dataTable,
			'input'           => $inputs
		];
		
		return json_encode($output);
	}
	public function getGroupMenusById($group_id)
	{
		$url = $this->url . 'groups/' . $group_id;
		$result = $this->guzzle->curl('get', $url);
		$output = [];

        if ($result['status']['code'] == '200') {
            $output = $result['data']['records'][0];
        }
		return $output;
	}

	public function setDataTable($data, array $params)
	{
		$dataTable = [];
		$language = App::getLocale();


		foreach ($data as $kData => $vData) {
			$numberData = ($kData + 1) + $params['start'];
			$dataTable[] = [
				'_id'     => isset($vData['content']) && !empty($vData['content'])? '' : $vData['id'],
				'number' => $numberData,
				'slug' => $vData['slug'],
				'group_name_th' => $vData['title']['th'],
				'group_name_en' => $vData['title']['en'],
				'status' => $vData['status'],
				'edit'   => url('/group_menu/' . $vData['id'] . '/edit?title='.$vData['title'][$language]),
				'add_hilight'   => url('/group_menu/' . $vData['id'] . '/content?title='.$vData['title'][$language]),
				'delete' => isset($vData['content']) && !empty($vData['content'])? '' : $vData['id']
			];
		}

		return $dataTable;
	}
	public function createGroupMenu($params)
	{
        $options = [
            'json' => $params
        ];
        $url = $this->url . 'groups/';
        $result = $this->guzzle->curl('POST', $url, $options);

        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            $group_id = $result['data']['records'][0]['id'];
            return array('status' => true, 'group_id' => $group_id);
        } else {
            return array('status' => false, 'messages' => $result['errors']['message']);
        }
	}

	public function createGroupHilightMenu($params)
	{
		
        $options = [
            'json' => $params
        ];
        $url = $this->url . 'groupshilight/';
        $result = $this->guzzle->curl('POST', $url, $options);

        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            $group_id = $result['data']['records'][0]['id'];
            return array('status' => true, 'group_id' => $group_id);
        } else {
            return array('status' => false, 'messages' => $result['errors']['message']);
        }
	}

	public function deleteGroupMenu($ids)
	{
		$url = $this->url . 'groups/' . $ids;
		$result = $this->guzzle->curl('DELETE', $url);
		if(isset($result['status'])&&$result['status']['code']==200) {
			return array('status' => true, 'deleted' => $result['data']['deleted'], 'errors' => $result['data']['errors']);
		}

		return array('status' => false, 'messages' => $result['message']);
	}

	public function deleteGroupHilightMenu($ids)
	{
		$id = explode(',',$ids);
		foreach($id as $value)
		{
			$url = $this->url . 'groupshilight/' . $value;
			$result = $this->guzzle->curl('DELETE', $url);
			
		}
		if (isset($result['status']['code']) && $result['status']['code'] == 200) {
			return array('status' => true);
		} else {
			return array('status' => false, 'messages' => $result['error']['message']);
		}
		
	}

	public function getHilightMenu($id,$inputs)
	{
		$params = [
			'query' => [
				'offset' => $inputs['start'],
				'limit'  => $inputs['length'],
				'group_id' => $id,
				'order' => 'priority|asc'
			]
		];
		if($inputs['order'][0]['dir']!='false') {
			if($inputs['order'][0]['column']=='hilight_name') {
				$lang = App::getLocale();
				$fieldName = "name.$lang";
			} else {
				$fieldName = $inputs['order'][0]['column'];
			}	
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
		}
		$url = $this->url . 'groupshilight';
		$result = $this->guzzle->curl('get', $url, $params);
		
		$params = [
			'query' => [
				'offset' => $inputs['start'],
				'limit'  => $inputs['length'],
			]
		];

		$urlgroup = $this->url . 'groups/' . $id;
		$resultgroup = $this->guzzle->curl('get', $urlgroup, $params);
		
		$dataTable = [];
		$count_page = 0;
		$count_all = 0;
		if (isset($result['status']) && !empty($result['status']) && $result['status']['code'] == 200) {
			$dataTable = $this->setDataHilightTable($result['data']['records'], $inputs,$id , $resultgroup['data']['records']);
			$count_page = count($result['data']['records']); //count page
			$count_all = $result['data']['pagination']['total_records']; //count all
		}

		$output = [
			'draw'            => $inputs['draw'],
			'recordsTotal'    => $count_page, //count page
			'recordsFiltered' => $count_all, //count all
			'data'            => $dataTable,
			'input'           => $inputs
		];

		return json_encode($output);
	}

	public function setDataHilightTable($data, array $params,$id,$group)
	{
		$dataTable = [];
		$language = App::getLocale();
		$title = array_column($group,'title');
		
		foreach ($data as $kData => $vData) {
			$numberData = ($kData + 1) + $params['start'];
			$dataTable[] = [
				'id'     => $vData['id'],
				'number' => $numberData,
				//'hilight_id' => $vData['id'],
				'value' => $vData['value'],
				'hilight_name_th' => $vData['name']['th'],
				'hilight_name_en' => $vData['name']['en'],
				'priority' => isset($vData['priority'])? $vData['priority'] : 99,
				'status' => $vData['status'],
				'edit'   => url('/group_menu/' . $id . '/menu/' . $vData['id'] . '/edit?title=' . $title[0][$language] ),
				'delete' => $vData['id']
			];

		}
		

		return $dataTable;
	}

	public function getHilightMenuById($hilightid)
	{
		$url = $this->url . 'groupshilight/' . $hilightid;
		$result = $this->guzzle->curl('get', $url);
		$output = [];

        if ($result['status']['code'] == '200') {
            $output = $result['data']['records'][0];
        }
		return $output;
	}
	
	public function updateHilightMenuPriority($params)
	{
		$options = [
			'json' => $params
		];
		$url = $this->url . 'groupshilight/priority/';
		$result = $this->guzzle->curl('PUT', $url, $options);
		return $result;
	}

	public function updateHilightMenuStatus($params)
	{
		$options = [
			'json' => $params
		];
		$url = $this->url . 'groupshilight/status/';
		$result = $this->guzzle->curl('PUT', $url, $options);
		return $result;
	}

	public function updateHilightMenu(array $params)
	{
		$options = [
            'json' => $params
        ];

        $url = $this->url . 'groupshilight/' . $params['hilight_id'];
        $result = $this->guzzle->curl('PUT', $url, $options);
        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            return array('status' => true);
        } else {
            return array('status' => false, 'messages' => $result['error']['message']);
        }
	}

	public function updateGroupMenuStatus($params)
	{

		$options = [
			'json' => $params
		];
		$url = $this->url . 'groups/status/';
		$result = $this->guzzle->curl('PUT', $url, $options);
		return $result;
	}

	public function updateGroupMenu($params)
	{
		$options = [
			'json' => $params
		];
		$url = $this->url . 'groups/' . $params['group_id'];
		$result = $this->guzzle->curl('PUT', $url, $options);
		if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            return array('status' => true);
        } else {
            return array('status' => false, 'messages' => $result['error']['message']);
        }
	}
	public function getGroupMenuReport($inputs) {

		$params = [
			'query' => [
				'fields' => 'title.th,title.en',
				'search' => $inputs['full_text'],
				'offset' => $inputs['start'],
				'limit'  => $inputs['length'],
			]
		];

		$result = $this->getGroupMenuData($params);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $group_menus = $result['data']['records'];
            $start = $result['data']['pagination']['offset'] + 1;

            return Excel::create('group_menu_report_' . date('YmdHis'), function($excel) use ($group_menus,$start) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Group Menu', function($sheet) use ($group_menus,$start) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Group ID',
                        'Slug',
                        'Group Menu Name (TH)',
                        'Group Menu Name (EN)',
						'Published'
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($group_menus as $kData => $vData) {
                        ++$row;

                        $data = [
                            $start,
                            $this->_unit->removeFirstInjection($vData['id']),
                            $this->_unit->removeFirstInjection($vData['slug']),
							$this->_unit->removeFirstInjection($vData['title']['th']),
							$this->_unit->removeFirstInjection($vData['title']['en']),
							($vData['status']=='active')? 'Publish' : 'Unpublish'
                        ];

                        $start++;

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }

	}

	// Export Sub Group

	public function getGroupHilightMenuReport($id,$inputs) {

		$params = [
			'query' => [
				'offset' => $inputs['start'],
				'limit'  => $inputs['length'],
				'group_id' => $id,
				'order' => 'priority|asc'
			]
		];

		$url = $this->url . 'groupshilight';
		$result = $this->guzzle->curl('get', $url, $params);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $group_menus = $result['data']['records'];
            $start = $result['data']['pagination']['offset'] + 1;
			
            return Excel::create('sub_group_menu_report_' . date('YmdHis'), function($excel) use ($group_menus,$start) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Sub Group Menu', function($sheet) use ($group_menus,$start) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Group ID',
                        'Slug/Link',
                        'Menu Name (TH)',
                        'Menu Name (EN)',
						'Priority',
						'Published'
                
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($group_menus as $kData => $vData) {
                        ++$row;
						
                        $data = [
                            $start,
                            $vData['id'],
                            $vData['value'],
                            $vData['name']['th'],
							$vData['name']['en'],
							$vData['priority'],
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