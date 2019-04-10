<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Services\Guzzle;
use Excel;
use App\Library\Unit;

class EmailSubscriptionRepository extends BaseRepository
{
    private $urlSubscription;
    private $guzzle;

    public function __construct(Guzzle $guzzle)
    {
        $this->urlSubscription  = env('CURL_API_SUBSCRIPTION');
        $this->guzzle       = $guzzle;
        $this->_unit        = new Unit;
    }

    public function getDateSearch($data)
    {
        list($date, $time) = explode(" ", $data);
        list($d, $m, $y) = explode("/", $date);
        return $y . '-' . $m . '-' . $d . ' ' . $time;
    }


    /**
     * Method for set data order
     */
    private function setOrderData(array $params)
    {
        $order = [];
        if (isset($params['order']) && count($params['order']) > 0) {
            foreach ($params['order'] as $kData => $vData) {
                if (isset($vData['column']) && isset($vData['dir'])) {
                    $order[] = $vData['column'] . '|' . $vData['dir'];
                }
            }
        } else {
            $order[] = 'created_at|desc';
        }

        return implode(',', $order);
    }


    /**
     * Method for set data search
     */
    private function setSearch(array $params)
    {
        $datetime_start = (isset($params['search']['1']['value']) && $params['search']['1']['value'] != '') ? $this->getDateSearch($params['search']['1']['value']) : date('d/m/Y 00:00:00');
        $datetime_end = (isset($params['search']['2']['value']) && $params['search']['2']['value'] != '') ? $this->getDateSearch($params['search']['2']['value']) : date('d/m/Y 23:59:00');
        $search = implode('&',[
            'order=' . $this->setOrderData($params),
            'offset=' . array_get($params, 'start', 0),
            'limit=' . array_get($params, 'length', 10),
            'datetime_start=' . $datetime_start,
            'datetime_end=' . $datetime_end
        ]);

        return $search;
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
                $numberData  = ($kData + 1) + $params['start'];

                $dataTable[] = [
                    'number'                  => $numberData,
                    'email'                   => $this->checkEmpty($vData['email'], ''),
                    'status'                  =>$vData['status'],
                    'updated_at'              =>$vData['updated_at'],
                    'created_at'              =>strtoupper(date('d/m/Y H:i:s', strtotime($vData['created_at'])))
                ];
            }
        }

        return $dataTable;
    }

    /**
     * Method for check empty
     */
    private function checkEmpty($data, $search = '')
    {
        if (!empty($data)) {
            return $this->highlight($search, $data);
        }

        return  '';
    }

    public function getSubscribe(array $params)
    {
        $getSubscribeUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET',$this->urlSubscription.'subscribe?'.$getSubscribeUrl);

        $output = [
            'draw'            => $params['draw'],
            'recordsTotal'    => isset($result['data']['records']) ? count($result['data']['records']) : 0, //count page
            'recordsFiltered' => isset($result['data']['pagination']['total_records']) ? $result['data']['pagination']['total_records'] : 0, //count all
            'data'            => isset($result['data']['records']) ? $this->setDataTable($result['data']['records'], $params) : [],
            'input'           => $params
        ];

        return json_encode($output);
    }


    /**
     * Method for curl api member report
     */
    public function getSubscriptionReport(array $params)
    {
        $getUrl = $this->setSearch($params);

        $result = $this->guzzle->curl('GET', $this->urlSubscription . 'subscribe?' . $getUrl);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {

            $users  = $result['data']['records'];

            return Excel::create('email_subscription_' . date('YmdHis'), function($excel) use ($users) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Member', function($sheet) use ($users) {

                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:K1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Subscription Date',
                        'Email'
                    ]);
                    $sheet->row(1, function($row) {
                        $row->setBackground('#000000');
                    });

                    $row = 1;
                    foreach ($users as $kData => $vData) {
                        ++$row;

                        $data = [
                            $kData + 1,
                            '="'.date('d/m/Y H:i:s',strtotime($vData['created_at'])). '"',
                            $this->_unit->removeFirstInjection(array_get($vData, 'email', ''))
                        ];

                        $sheet->row($row, $data);
                    }
                });
            })->export('csv');
        }

        return false;
    }



}