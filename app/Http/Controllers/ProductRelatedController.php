<?php
namespace App\Http\Controllers;

use App\Repositories\ProductRelatedRepository;
use Illuminate\Http\Request;

class ProductRelatedController extends \App\Http\Controllers\BaseController
{
    public $repository;

    protected $redirect = [
        'login' => '/',
        'index' => 'product'
    ];

    public function __construct(ProductRelatedRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function getProductRelated(Request $request, $product_id)
    {
        $outputs = [];
        $outputs = $this->repository->getProductRelatedByProduct($product_id,$request->language);
        return json_encode($outputs);
    }

    public function create(Request $request, $product_id)
    {
        $response = $this->repository->createRelated($product_id,$request->related_ids);
        return json_encode($response);
    }

    public function destroy($product_id, $related_id)
    {
        $response = $this->repository->deleteRelated($product_id,$related_id);
        return json_encode($response);
    }

    public function getProductsBySearch(Request $request, $product_id)
    {
        $outputs = [];
        $outputs = $this->repository->getProductsBySearch($request->name,$product_id);
        return json_encode($outputs);
    }

    /**
     * Search product relate
     */
    public function postProducts(Request $request)
    {
        $outputs = $this->repository->getProductsSearch($request->input());
        return $outputs;
    }
}
?>