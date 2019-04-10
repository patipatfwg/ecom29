<?php
namespace App\Repositories;

use App;
use App\Services\Guzzle;
use Excel;

class CronRepository
{
    public function __construct(Guzzle $guzzle)
	{
		$this->guzzle = $guzzle;
		$this->messages = config('message');
		$this->url = env('CURL_API_CRONJOB');
	}

	 /**************************
     * Manage Member Cronjob
     **************************/

	public function memberErrorStatus(){

		return [
			'all' => 'All',
			'null' => 'Member',
			'member' => 'Order',
			'order'	=> 'OMS',
			'oms'	=> 'Coupon',
		];	
	}

	public function getMembers($inputs)
	{
 
 		$member = [];
		if(isset($inputs['member_card_no']) && $inputs['member_card_no']!=""){
			$member['member_card_no'] = $inputs['member_card_no'];
		}	

		if(isset($inputs['error_status']) && $inputs['error_status']!=""){
			$member['_tags'] =   $inputs['error_status'];
		}	

		$params = [
			'query' => [
				'offset' => (isset($inputs['start']))?$inputs['start']: 0,
				'limit'  => (isset($inputs['length']))?$inputs['length']: 20,
			]+ $member
		];
 
		if(isset($inputs['order']) && $inputs['order'][0]['dir'] != 'false') {
			$fieldName = $inputs['order'][0]['column'];
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
		}

		$url = $this->url . 'members';
 		$result = $this->guzzle->curl('GET', $url, $params);

 		return $result; 

	}

    public function dataTableMembers($inputs)
    {
 		
		$result = $this->getMembers($inputs);
		$dataTable = [];
		$count_page = 0;
		$count_all = 0;

		if (isset($result['status']) && !empty($result['data']['records']) && count($result['data']['records'])>0) {
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
 
    public function setDataTable($data,$params)
    {
        $dataTable = [];
		$language = App::getLocale();
		
		foreach ($data as $kData => $vData) {

			$numberData = ($kData + 1) + $params['start'];
			$dataTable[] = [
				'id'                	=> $vData['id'],
				'file'      			=> $vData['file'],
                'member_card_no'    	=> $vData['member_card_no'],
				'ecommerce_customer_id' => $vData['ecommerce_customer_id'],
				'tag'					=> $vData['tags'],
				'service'				=> $this->serviceName($vData['tags']),
                'message'               => $vData['message'],
				'count'            		=> $vData['count'],
				'created_at'            => $vData['created_at'],
				'updated_at'            => $vData['updated_at'],
			];
		}

		return $dataTable;
	}
	
	public function serviceName($tags)
	{
		switch ($tags) {
			case 'member':
				$service = 'Order';
				break;
			case 'order':
				$service = 'OMS';
				break;
			case 'oms':
				$service = 'Coupon';
				break;
			default:
				$service = 'Member';
				break;
		}
		return $service;
	}
	
    public function update(array $params)
    {
		$url = $this->url . 'members';
		
        $result = $this->guzzle->curl('PUT', $url, [
            'json' => $params
        ]);

        return $result;
    }

     /****************************
     * End Manage Member Cronjob
     ****************************/

}
?>