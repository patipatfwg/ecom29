<?php
namespace App\Http\Controllers;

class DashboardController extends \App\Http\Controllers\BaseController
{
    protected $redirect = [
        'login' => '/',
        'index' => 'dashboard'
    ];

    protected $view = [
        'index' => 'dashboard.index'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view($this->view['index']);
    }
}
?>