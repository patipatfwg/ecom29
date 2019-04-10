<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Exception\RequestException;
use App\Services\Guzzle;

class ProductRelatedRepository
{
    private $guzzle;
    protected $messages;
    protected $client;
   // protected $filter = ['categories.id','contents.content'];
   // protected $type  = 'category';
    public $language;

    public function __construct(Guzzle $guzzle)
    {
        $this->urlProduct = env('CURL_API_PRODUCT');
        $this->messages   = config('message');
        $this->guzzle     = $guzzle;
    }
    /**
     * Get Category Breadcrumb
     *
     * @param  string $name
     * @return array
    */
    private function getProductRelated($product_id){

        $params = [ 
            'query' => [
                'product_id'   => $product_id
            ] 
        ];

        $url = $this->urlProduct.'product/related'; 
        $products = $this->guzzle->curl('GET', $url, $params);
        $strId = "";
        if($products['status']['code'] == 200 && count($products['data'])>0) { 
            $i = 1;
            foreach ($products['data'] as $key => $product) {
                if($i>0){
                    $strId .= ",".$product['related_id'];  
                } else {
                    $strId = $product['related_id'];  
                }
                $i++; 
            } 
            $params = [ 
                'headers' => [
                    'x-language'    => $this->language
                ] 
            ];
            $url = $this->urlProduct.'/product/'.$strId;
            $result = $this->guzzle->curl('GET', $url, $params);
            return $result['data'];

        } else {
            return [];
        }
    }

    public function getProductRelatedByProduct($product_id,$language) {

        $this->language = app()->getLocale();
        $data = self::getProductRelated($product_id);
        $result = [];
        if( count($data)> 0) { 
            $i = 1;
            foreach ($data as $key => $product) {

                $result[$key]['no'] = $i ;
                $result[$key]['product_code'] = $product['product_code'];
                $result[$key]['online_sku'] = @$product['online_sku'];
                $result[$key]['product_name'] = $product['name_'.$this->language];
                $result[$key]['action'] = "<a class='btn btn-default' 
                onClick=\"javascript:relatedProductDel('".$product['product_id']."','".$product_id."');\">
                <i class='icon-bin'></i></a>";
                $i++;                 
            }

            $outputs = [
                'draw'            => '',
                'recordsTotal'    => count($result),       //count page
                'recordsFiltered' => count($result),        //count all
                'data'            => $result,
                'input'           => ['product_id'=>$product_id]
            ];
           
        } else {
            $outputs = [
                'draw'            => '',
                'recordsTotal'    => 0,  
                'recordsFiltered' => 0,  
                'data'            => [],
                'input'           => ['product_id'=>$product_id]
            ];
        } 
        return $outputs;
    }
 
    public function createRelated($product_id,$related_ids){

        $url = $this->urlProduct.'product/related'; 
        foreach ($related_ids as $key => $related_id) {
            
            $params = [ 
                    'form_params' => [
                        'product_id'   => $product_id,
                        'related_id'   => $related_id,    
                    ] 
                ];

            $response = $this->guzzle->curl('POST', $url, $params);  
        }

        if($response['status']['code'] == 200 ) { 
            return $result['msg'] = 'successfully added.';
        } else {
            return $result['msg'] = 'Something went wrong.';
        }

    }

    public function deleteRelated($product_id,$related_id){

        $params = [];

        $url = $this->urlProduct.'product/'.$product_id.'/related/'.$related_id; 

        $response = $this->guzzle->curl('DELETE', $url, $params);

        if($response['status']['code'] == 200 ) { 
            return $result['msg'] = 'successfully deleted.';
        } else {
            return $result['msg'] = 'Something went wrong.';
        }
    }

    public function getProductsBySearch($product_name,$product_id){

        $this->language = app()->getLocale();
        $products = self::getProducts($product_name);

        if(count($products['products']) > 0){
            $i=0;
            foreach ($products['products'] as $key => $product) {
                if($product['id']!=$product_id){
                    $result[$i]['chk'] = "<input type='checkbox' name='chk_id[".$i."]' 
                    id='chk_id".$i."' value='".$product['id']."' class='chkProductId'>" ;
                    $result[$i]['product_code'] = @$product['product_code'];
                    $result[$i]['online_sku']   = @$product['online_sku'];
                    $result[$i]['product_name'] = $product['name'];
                    $result[$i]['action']       = "";
                    $i++;    
                }
            }
            $outputs = [
                'draw'            => '',
                'recordsTotal'    => count($result),       //count page
                'recordsFiltered' => count($result),        //count all
                'data'            => $result,
                'input'           => ['product_id'=>$product_id]
            ];
        } else {
            $outputs = [
                'draw'            => '',
                'recordsTotal'    => 0,  
                'recordsFiltered' => 0,  
                'data'            => [],
                'input'           => ['product_id'=>$product_id]
            ];
        }
        return $outputs;
    }

    private function getProducts($product_name){

        $params = [ 
            'query' => [
                'product_name'   => $product_name,
                'offset'         => '',
                'limit'          => ''
            ],
            'headers' => [
                'x-language'     => $this->language,
                'access-token'   => 'f8079146bd37dfded0a9554217d72c42'
            ]
        ];

        $url = $this->urlProduct.'product/search'; 
        $products = $this->guzzle->curl('GET', $url, $params);

        if($products['status']['code'] == 200) { 
            return $products['data'];  
        }  else {
            return [];
        }
    }

    /**
     * Search product relate
     * @param array $params
     */
    public function getProductsSearch(array $params)
    {
        $result = $this->guzzle->curl('GET', $this->urlProduct . 'products/relate/' . $params['text']);

        $outputs = [];
        if (isset($result['data']) && !empty($result['data'])) {
            $outputs = $result['data'];
        }

        return $outputs;
    }
}