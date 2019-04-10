<?php namespace App\Services;
/**
 * @codeCoverageIgnore
 */

use App\Services\CurlServices;
use App\Services\Keygen;
use Config;
use CurlFile;
use Illuminate\Support\Facades\Storage;

class CdnServices extends CurlServices
{
    protected $keyGen;
    private $_privateKey;
    private $_serviceName;
    private $_serviceId;
    private $_key;

    public function __construct(Keygen $keyGen)
    {
        parent::__construct();

        $this->setUrl(Config::get('api.cdn_api.url'));

        $this->keyGen = $keyGen;

        $this->_privateKey  = Config::get('api.cdn_api.private_key');
        $this->_serviceName = Config::get('api.cdn_api.service_name');
        $this->_serviceId   = Config::get('api.cdn_api.service_id');
    }

    private function _getPrivateKey()
    {
        return $this->_privateKey;
    }

    private function _getServiceName()
    {
        return $this->_serviceName;
    }

    private function _getServiceId()
    {
        return $this->_serviceId;
    }

    public function setKey($key)
    {
        $this->_key = $key;
    }

    public function getKey()
    {
        return $this->_curlGetKey();
    }

    private function _curlGetKey()
    {
        $response = $this->callApi('get', 'apis/getkey', ['service_id' => $this->_getServiceId()]);

        if (isset($response) && ! empty($response)) {

            if ($response->status->code == 200) {
                $this->_key = $response->data->key;
            }
        }

        return $this->_key;
    }

    private function _encodeKey()
    {
        return $this->keyGen->encode(
            $this->_curlGetKey() . $this->_getPrivateKey(),
            8
            );
    }

    private function createUriForUpload($uri)
    {
        $params = [
            'apikey'     => $this->_encodeKey(),
            'service_id' => $this->_getServiceId()
        ];
        return $uri . '?' . http_build_query($params);
    }

    private function createFileParam($fileName)
    {
        $filename   = $_FILES[$fileName]['name'];
        $filedata   = $_FILES[$fileName]['tmp_name'];
        $filesize   = $_FILES[$fileName]['size'];
        $filetype   = $_FILES[$fileName]['type'];
        
        $uploadfile = new CurlFile($filedata, $filetype, $filename);
        
        $inputfile = [
            'file' => $uploadfile
        ];

        return [$inputfile, $filesize];
    }

    private function createFileParamByDump($fileName)
    {
        $name_tmp = explode('/', (string)$fileName);
        $filename = $name_tmp[1];
        $filedata = (string)$fileName;
        $filesize = Storage::size($fileName);
        $filetype = mime_content_type($fileName);
        
        $uploadfile = new CurlFile($filedata, $filetype, $filename);
        
        $inputfile = [
            'file' => $uploadfile
        ];

        return [$inputfile, $filesize];
    }

    public function uploadByDump($fileName, $formatArray=true)
    {
        $fileInfo = $this->createFileParamByDump($fileName);
        $response = $this->callUploadFile('postFile', $this->createUriForUpload('apis/upload'), $fileInfo[0], $fileInfo[1], $formatArray);
        
        return $response;
    }

    public function _uploadFileDataByDump($name)
    {
        $short_url = "";
        $uploadResult = $this->uploadByDump($name);
        
        if ($uploadResult["status"]["code"] == 200) {
            $short_url = $uploadResult["data"]["short_url"];
        } else {
            return $uploadResult;
        }

        return $short_url;
    }

    public function upload($fileName, $formatArray=true)
    {
        
        if ( !isset($_FILES[$fileName]) || empty($_FILES[$fileName]) ) {
            return false;
        }
        
        $fileInfo = $this->createFileParam($fileName);
        $response = $this->callUploadFile('postFile', $this->createUriForUpload('apis/upload'), $fileInfo[0], $fileInfo[1], $formatArray);
        
        return $response;
    }

    public function _uploadFileData($name)
    {
        $short_url = "";
        $uploadResult = $this->upload($name);
        
        if ($uploadResult["status"]["code"] == 200) {
            $short_url = $uploadResult["data"]["short_url"];
        } else {
            return $uploadResult;
        }

        return $short_url;
    }

    public function manageUpload($param, $keyNew, $keyOld)
    {
        //define output
        $output = "";

        if ((isset($param[$keyNew])) && ( ! empty($param[$keyNew]))) {
            $output = $this->_uploadFileData($keyNew);
        } else if ( ! empty($param[$keyOld])) {
            $output = $param[$keyOld];
        }

        return $output;
    }

    public function deleteByTime($params)
    {
        $curlParams    = [
            'short_url'   => $params['short_url'],
            'delete_time' => $params['delete_time']
        ];
        $response = $this->callApi('deleteWithHeader', '/apis/deletefile', $curlParams); // postDelete

        return $response;
    }

    public function getFileInfo($filename, $key)
    {
        //get file size
        $file = $_FILES[$filename];

        return $file[$key];
    }
}
