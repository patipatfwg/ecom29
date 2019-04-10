<?php 

namespace App\Services;

class MyResponse
{

    public function getOutput($format='array', $params)
    {
        switch ($format) {
            case 'array':
                $this->setArrayFormat($params);
                break;
            case 'json':
                $this->setJsonFormat($params);
                break;
            default:
                $this->setArrayFormat($params);
        }
    }

    protected function setJsonFormat($params)
    {
        echo json_encode($params);
    }

    protected function setArrayFormat($params)
    {
        return $params;
    }

    protected function authentication()
    {
        return true;
    }  
    
}
