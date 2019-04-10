<?php
namespace App\Http\Controllers\EPOS;

use Illuminate\Support\Facades\View;

class MockBaseController extends EPOSBaseController
{
    // follow spec
    function getOrderDetail() {

        // get post
        // $entityBody = file_get_contents('php://input');
        // process...
        // dd($entityBody);

        // return to client
        $content = View::make('epos.xml.order.detail', [])->render();

        return response($content, 200)->header('Content-Type', 'text/xml');
    }
}
