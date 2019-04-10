<?php
namespace App\Repositories;

use App\Services\Guzzle;

class TagRepository
{
    public function __construct(Guzzle $guzzle)
    {
        $this->guzzle               = $guzzle;
        $this->messages             = config('message');
        $this->url                  = env('CURL_API_TAG');
    }

    public function getTags($contentId){
        $output = [];
        
		$url = $this->url;
        $options = [
            'headers' => [
                'x-language' => 'th|en'
            ],
            'query' => [
                'content_id' => $contentId
            ]
        ];

		$result = $this->guzzle->curl('get', $url."tags", $options);
        
		if (isset($result['status']) && $result['status']['code'] == 200 && !empty($result['data']['records']) ) {
			foreach($result['data']['records'] as $tag){
                
                //$language = $tag['name'];
                
                //$name = $tag['name'];
                $id = $tag['id'];
                $tags = $tag['name'];
            }
            $tags['id'] = $id;
            
            $output = $tags;

		}

		return $output;
    }

    public function createTag($data){

        $languages = config('language.content');
        foreach ($languages as $language) {
            foreach($data as $kData => $vData)
            {
                if($kData == 'name_' . $language) {
                    if(!empty($vData))
                        $lang = explode(',',$vData);
                    else
                        $lang = [];
                    unset($data['name_' . $language]);
                    $data['name'][$language] = $lang;
                }
            }
        }
		$url = $this->url;
        $options = [
            'json' => $data
        ];

		$result = $this->guzzle->curl('post', $url."tags", $options);

        return $result;
    }

    public function updateTag($contentId , $data){

        $languages = config('language.content');
        foreach ($languages as $language) {
            foreach($data as $kData => $vData)
            {
                if($kData == 'name_' . $language) {
                    if(!empty($vData))
                        $lang = explode(',',$vData);
                    else
                        $lang = [];
                    unset($data['name_' . $language]);
                    $data['name'][$language] = $lang;
                }
            }
        }

        $url = $this->url;
        $options = [
            'json' => $data
        ];

		$result = $this->guzzle->curl('PUT', $url."tags/".$contentId, $options);

        return $result;
    }

    public function deleteTag($tagId){

        $url = $this->url ;
        $result = $this->guzzle->curl('delete', $url."tags/".$tagId);
        return $result;
    }

}
