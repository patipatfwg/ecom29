<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Exception\RequestException;
use App\Repositories\BannerRepository;
use App\Services\Guzzle;
use App;
use Cache;

class BannerRepository extends BaseRepository
{
    protected $guzzle;
    protected $messages;

    public function __construct(Guzzle $guzzle)
    {
        $this->url  = config('api.makro_banner_api');
        $this->messages     = config('message');
        $this->guzzle       = $guzzle;
    }

    /**
     * Update Banner
     *
    */
    public function postUpdateBanner($inputs, $banner_id)
    {
        $url = $this->url . 'banners/' . $banner_id;

        $params = [
            'json' => $inputs
        ];
        return $this->guzzle->curl('PUT', $url, $params);
    }

    /**
     * Delete Banner
     *
    */
    public function getDeleteBanner($ids)
    {
        $url = $this->url . 'banners/' . $ids;
		$result = $this->guzzle->curl('DELETE', $url);
		if(isset($result['status'])&&$result['status']['code']==200) {
			return array('status' => true, 'deleted' => $result['data']['deleted'], 'errors' => $result['data']['errors']);
		}

		return array('status' => false, 'messages' => $result['message']);
    }

    /**
     * Crete Banner
     *
    */
    public function createBanner($params)
    {
        $options = [
            'json' => $params
        ];
        $url = $this->url. 'banners';

        $result = $this->guzzle->curl('POST', $url, $options);
        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            $bannerId = $result['data']['id'];
            return array('status' => true, 'bannerId' => $bannerId);
        } else {
            return array('status' => false, 'messages' => isset($result['error']['message'])?$result['error']['message']:'');
        }
    }

    public function getBanner($id)
    {
        $url = $this->url . 'banners/' . $id;
        $output = [];
        $result = $this->guzzle->curl('GET', $url);
        if ($result['status']['code'] == '200') {
            $output = $result['data']['records'][0];
        }
        return $output;
    }

    public function getBanners($inputs)
    {
        $language = App::getLocale();
        $params = [
            'query' => [
                'offset' => $inputs['start'],
                'limit' => $inputs['length'],
                'column' => $inputs['order'][0]['column'],
                'dir' => $inputs['order'][0]['dir'],
                'date' => isset($inputs['launch_date_input']) ? $inputs['launch_date_input'] : '',
                'search' => isset($inputs['search_text_input']) ? $inputs['search_text_input'] : '',
                'field' => 'name'
            ]
        ];
        
        $url = $this->url . 'banners';
        $result = $this->guzzle->curl('GET', $url, $params);

        // loop get data
        $dataModel = [];
        $count_page = 0;
        $count_all = 0;
        
        if (isset($result['status']) && isset($result['status']['code'])) {
            if ($result['status']['code'] == 200) {
                foreach ($result['data']['records'] as $kData => $vData) {
                    if(isset($vData['target']))
                    {
                        $target = '<a href="'.$vData['redirect_url'].'" target="'.$vData['target'].'">Link</a>';
                    }else {
                        $target = '<a href="'.$vData['redirect_url'].'">Link</a>';
                    }
                    $dataModel[] = [
                        'id' => $vData['id'],
                        'name' => $vData['name'],
                        'imageUrl' => $vData['image_url'],
                        'redirectUrl' => $target,
                        'position' => $vData['position'],
                        'createAt' => convertDateTime($vData['created_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
                        'updateAt' => convertDateTime($vData['updated_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
                        'edit' => url('/banner/' . $vData['id'] . '/edit')
                    ];
                }
                $count_page = count($result['data']['records']); //count page
                $count_all = $result['data']['pagination']['total_records']; //count all
            }
        }

        $output = [
            'recordsTotal' => $count_page, //count page
            'recordsFiltered' => $count_all, //count all
            'data' => $dataModel,
            'input' => $inputs
        ];

        return json_encode($output);

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
     * Method for set search text all
     */
    private function setSearchText(array $params)
    {
        $search = [];

        if (isset($params['search']) && is_array($params['search'])) {

            foreach ($params['search'] as $kData => $vData) {

                if ($vData['name'] !== '_token') {
                    $search[] = $vData['name'] . '=' . $vData['value'];
                }
            }
        }

        return $search;
    }

    public function getDataBanners(array $inputs)
    {
        $params = [
            'query' => [
                'offset' => $inputs['start'],
                'limit' => $inputs['length']
            ]
        ];
        $url = $this->url . 'banners';
        $result = $this->guzzle->curl('GET', $url, $params);
        return isset($result['data']['records']) ? $result['data']['records'] : [];

    }
}