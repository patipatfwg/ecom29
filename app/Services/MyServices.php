<?php

namespace App\Services;

use App;

class MyServices
{
    protected $OPERATOR    = "operator";
    protected $VALUE       = "value";
    protected $DELIMITER   = ".";
    private $permissionKey = "permission-";
    private $pipeDelimeter = "|";

    //for create condition list
    //$keys is List of field name you want to add condition
    //$operators is List of operator
    //$param Sample
    //$param = [
    //    'key' => 'value'
    //]
    public function createCondition($keys, $operators, $param)
    {
        $conditions = array();
        $index      = 0;

        foreach ($keys as $key) {
            $conditions[$key] = [];

            //add Detail
            $conditions[$key][$this->OPERATOR] = $operators[$index];
            $conditions[$key][$this->VALUE]    = $param[$key];
            //plus index
            $index++;
        }

        return $conditions;
    }

    //methos for check duplication
    public function checkDuplicate($collectionObj, $keys, $operators, $param)
    {
        $isDuplicate = [false];
        $conditions  = $this->createCondition($keys, $operators, $param);
        //add condition to module
        $collection  = $this->addConditiontoCollection($conditions, $collectionObj);
        $datas       = $collection->get()->toArray();

        if ($datas) {
            $isDuplicate = [true, $datas[0]];
        }

        return $isDuplicate;
    }

    //for add condition to collection
    //sample condition
    //$condition = [
    //  "name" => [
    //      "operator" => "LIKE",
    //      "value"    => "John"
    //   ]
    //];
    public function addConditiontoCollection($conditions, $collectionObj)
    {
        if ($conditions) {

            foreach ($conditions as $key => $obj) {
                $operator = $obj[$this->OPERATOR];
                $value    = $obj[$this->VALUE];

                if ( ! empty($operator)) {
                    //add where
                    $collectionObj = $collectionObj->where($key, $operator, $value);
                }
            }
        }

        return $collectionObj;
    }

    public function sortByKey($collectionObj, $key, $type='desc')
    {
        $collectionObj = $collectionObj->orderBy($key , $type);

        return $collectionObj;
    }

    public function getValueFormObj($keys, $obj)
    {
        $key  = $keys[0];

        //keep in $obj
        if (isset($obj[$key])) {
            $obj  = $obj[$key];

            if (count($keys) > 1) {
                //cut first
                $keys = array_slice($keys, 1);
                return $this->getValueFormObj($keys, $obj);
            }
        } else {
            return "";
        }

        return $obj;

    }

    public function getListDataFromArr($datas, $key)
    {
        $output = [];
        foreach ($datas as $data) {
            $keyList  = explode($this->DELIMITER, $key);
            $output[] = $this->getValueFormObj($keyList, $data);
        }

        return $output;
    }

    public static function convertDateTime($dateStr, $dateFormat="d-m-Y")
    {
        if (empty($dateStr) || $dateStr == "0000-00-00") {
            return "";
        } else {
            return date($dateFormat, strtotime($dateStr));
        }
    }

    public function getDefaultValue($type)
    {
        $defaultVal = "";

        switch ($type) {
            case "int" :
                $defaultVal = 0;
                break;
            case "string" :
                $defaultVal = "";
                break;
            default :
                $defaultVal = "";
                 break;
        }
        return $defaultVal;
    }

    //Method for generate value list
    public function generateValueList($length, $type)
    {
        //define outputs
        $outputs = [];
        //create value
        $defaultVal = $this->getDefaultValue($type);

        for ($i=1; $i<=$length; $i++) {
            //add to outputs
            $outputs[] = $defaultVal;
        }
        return $outputs;
    }

    public function getAllDate($startDate, $endDate)
    {
        $date = date("Y-m-d", strtotime($startDate));
        $end  = date("Y-m-d", strtotime($endDate));

        while (strtotime($date) <= strtotime($end)) {
            $labels[] = $date;
            $date     = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }

        return $labels;
    }

    //Method for get index from value => find data from labels
    public function getIndexFromLables($labels, $date)
    {
        foreach ($labels as $key => $value) {

            if ($date == $value) {
                return $key;
            }
        }

        return null;
    }

    // public function getPermissionPublic($param)
    // {
    //     $permission = '';
    //     $dataId     = '';

    //     $categoryRepo = \App::make('App\Repositories\ExclusiveCategoryRepository');
    //     $category     = $categoryRepo->getExclusiveCategory(['id'], ['='], ['id' => $param["category_id"]]);
    //     print_r($category);exit();

    //     if (isset($category) && ! empty($category)) {

    //         foreach ($category[0]['departments'] as $value) {
    //             $permission .= $value['id'] . $this->pipeDelimeter;
    //         }
    //     }

    //     if ( ! empty($permission)) {
    //         //add pine at start
    //         $permission = $this->pipeDelimeter . $permission;
    //     }

    //     return $permission;
    // }

    public function getPermissionDatas($param)
    {
        $output = "";

        foreach ($param as $key => $value) {

            if (strrpos($key, $this->permissionKey) !== false) {

                if ( ! empty($output)) {
                    $output .= $this->pipeDelimeter;
                }
                //keep to output
                $output .= str_replace($this->permissionKey, "", $key);
            }
        }

        if ( ! empty($output)) {
            //add pine at start and end
            $output = $this->pipeDelimeter.$output.$this->pipeDelimeter;
        }

        return $output;
    }

    public function getPermission($param)
    {
        if ( ! empty($param["permission"])) {
            $list = explode($this->pipeDelimeter, $param["permission"]);

            foreach ($list as $departmentId) {

                if ( ! empty($departmentId)) {
                    $param[$this->permissionKey.$departmentId] = true;
                }
            }

            //remove permission
            unset($param["permission"]);
        }

        return $param;
    }

    public function getContactByEggId($arrEggId)
    {
        $contactConfig = config('api.platform_contact');

        //call contact platform for query egg_id by department
        $curlParams = [
            'apiKey' => $contactConfig['apikey'],
            'egg_id' => $this->getEggToStr($arrEggId, '|')
        ];

        //set url for contact api
        $this->curlServices = App::make('App\Services\CurlServices');
        $this->curlServices->setUrl($contactConfig['url']);
        $result = $this->curlServices->callApi('post', 'apis/contact/egg/get', $curlParams);

        if ($result->status->code != 200) {
            $response['error'][0] = 'missionFail';

            return $response;
        }

        $contact = [];

        foreach ($result->data as $value) {
            $contact[$value->egg_id] = $value;
        }

        return $contact;
    }

    private function getEggToStr($eggs, $str = ',')
    {
        $eggStr = "";

        foreach ($eggs as $egg) {

            if ( ! empty($eggStr)) {
                $eggStr .= $str;
            }
            $eggStr .= $egg;
        }

        return $eggStr;
    }

    // public function getDepartmentOfOfficial($oaDetail)
    // {
    //     $department = '';

    //     // $originailDepartment = |1|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31|32|33|34|35|36|37|38|
    //     $originailDepartment = $oaDetail['permission'];

    //     if ($oaDetail['permission_type'] == 'private') {
    //         // make department array by explode '|'
    //         $arrDepartment = explode("|", substr($originailDepartment, 1, strlen($originailDepartment) - 2));

    //         // get name by id department array
    //         $this->departmentRepo = App::make('App\Repositories\DepartmentRepository');
    //         $departmentName       = $this->departmentRepo->getDepartmentNameByID($arrDepartment);

    //         if ( ! empty($departmentName)) {

    //             foreach ($departmentName as $value) {
    //                 $arrDepartmentName[] = $value['name'];
    //             }

    //             $department = implode("|", $arrDepartmentName);
    //         }
    //     }

    //     return $department;
    // }

    public function getDepartmentOfOfficial($oaDetail)
    {
        $department = '';

        if ($oaDetail['permission_type'] == 'private') {
            // $originailDepartment = |1|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31|32|33|34|35|36|37|38|
            //$originailDepartment = $oaDetail['permission'];

            // // make department array by explode '|'
            // $arrDepartment = explode("|", substr($originailDepartment, 1, strlen($originailDepartment) - 2));

            // // get name by id department array
            // $this->departmentRepo = App::make('App\Repositories\DepartmentRepository');
            // $departmentName       = $this->departmentRepo->getDepartmentNameByID($arrDepartment);

            // if ( ! empty($departmentName)) {

            //     foreach ($departmentName as $value) {
            //         $arrDepartmentName[] = $value['name'];
            //     }

            //     $department = implode("|", $arrDepartmentName);
            // }

            $department = $oaDetail['permission'];
        }

        return $department;
    }

    //Method for convert encoding
    public function convertEncoding($from, $to, $msg)
    {
        if (strtolower(mb_detect_encoding($msg)) != strtolower($to)) {
            $msg = iconv($from, $to, $msg);
        }
        return $msg;
    }

    public function hexUnicodeConvert($content)
    {
        $str = preg_replace_callback('/([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $content);
        return $str;
    }

    public function getLanguageHeader($content = '')
    {
        $result = '';

        if ( ! empty($content)) {
            $language = config('language.' . $content);

            if ( ! empty($language)) {
                $result = implode("|", $language);
            }
        }

        return $result;
    }

    /*
    *
    */
    public function uploadImage($filename)
    {
        //Define output
        $outputs = [
            'success' => false,
            'image'   => ''
        ];

        //get cdn service
        $cdbService = App::make('App\Services\CdnServices');

        try {
            $shortUrl = $cdbService->_uploadFileData($filename);
        } catch (Exception $e) {
            $shortUrl = array();
        }

        if ( ! empty($shortUrl)) {
            $outputs['success'] = true;
            $outputs['image']   = $shortUrl;
        }

        return $outputs;
    }
}