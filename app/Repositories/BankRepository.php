<?php
namespace App\Repositories;

use App;
use App\Services\Guzzle;
use Excel;

class BankRepository
{
    public function __construct(Guzzle $guzzle)
	{
		$this->guzzle = $guzzle;
		$this->messages = config('message');
		$this->url = env('CURL_API_PAYMENT');
	}

    public function getBank($inputs)
    {
        $params = [
			'query' => [
                'config_type' => 'Bank',
				'name'        => (isset($inputs['full_text'])) ? $inputs['full_text'] : "",
				'offset'      => $inputs['start'],
				'limit'       => $inputs['length'],
                'status'      => 'active,inactive'
			]
		];

        if($inputs['order'][0]['dir'] != 'false') {
			if(preg_match('/bank_name_[a-z]{2}/', $inputs['order'][0]['column'])) {
				$language = substr($inputs['order'][0]['column'], -2);
				$fieldName = "name.$language";
			} else {
				$fieldName = $inputs['order'][0]['column'];
			}	
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
		}
		$result = $this->getDataBank($params);
        
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

    public function getDataBank($params)
	{
		$url = $this->url . 'configs';
		$result = $this->guzzle->curl('GET', $url, $params);
		return $result; 
	}

    public function setDataTable($data,$params)
    {
        $dataTable = [];
		$language = App::getLocale();
		foreach ($data as $kData => $vData) {
			$numberData = ($kData + 1) + $params['start'];
			$dataTable[] = [
				'id'                => $vData['id'],
				'bank_name_th'      => $vData['name']['th'],
                'bank_name_en'      => $vData['name']['en'],
				'logo'              => $vData['logo'],
                'fee'               => $vData['transaction_fee'],
                'status'            => $vData['status'],
			];
		}

		return $dataTable;
    }

    // Export Coupon History

	public function getDataBankReport($inputs) 
    {

        $params = [
			'query' => [
                'config_type'   => 'Bank',
				'name'          => $inputs['full_text'],
				'offset'        => $inputs['start'],
				'limit'         => $inputs['length'],
                'status'        => 'active,inactive'
			]
		];

        if($inputs['order'][0]['dir'] != 'false') {
			if(preg_match('/bank_name_[a-z]{2}/', $inputs['order'][0]['column'])) {
				$language = substr($inputs['order'][0]['column'], -2);
				$fieldName = "name.$language";
			} else {
				$fieldName = $inputs['order'][0]['column'];
			}	
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
		}
		$result = $this->getDataBank($params);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $start = $result['data']['pagination']['offset'] + 1;
            $bank = $result['data']['records'];
            return Excel::create('bank_report_' . date('YmdHis'), function($excel) use ($bank,$start) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Bank', function($sheet) use ($bank,$start) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'Bank Name (TH)',
                        'Bank Name (EN)',
                        'Logo',
                        'Fee',
						'Status'
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($bank as $kData => $vData) {
                        ++$row;
						
                        $data = [
                            $vData['name']['th'],
                            $vData['name']['en'],
                            $vData['logo'],
                            $vData['transaction_fee'],
                            (array_get($vData, 'status', '')=='active'?'Publish':'Unpublish')
                        ];

                        $start++;

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }
	}

    public function setStatus($id,$inputs){
        $url = $this->url. 'configs/status';
        $coupon = [
            'id' => $id,
            'status' => $inputs['status']
        ];

        $options = [
            'json' => $coupon
        ];
        $result = $this->guzzle->curl('PUT',$url,$options);
        return $result;
    }

}
?>