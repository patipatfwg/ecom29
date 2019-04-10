<?php
namespace App\Repositories;

use App\Models\Menus;
use App\Library\Nestable;
use Html;

class MenusRepository
{
    protected $menus;
    protected $messages;

    public function __construct(Menus $menus, Nestable $nestable)
    {
		$this->model    = $menus;
		$this->nestable = $nestable;
		$this->messages = config('message');
    }

    /**
     * Method for theme nestable
     */
    private function nestableAsHtml($menus)
    {
        $html = '<ol class="dd-list">';
        foreach ($menus as $kMenu => $vMenu) {
            $html .= '<li class="dd-item dd3-item" data-id="' . $vMenu['_id'] . '">';
            $html .= '<div class="dd-handle dd3-handle">&nbsp;</div>';
            $html .= '<div class="dd3-content">';
                $html .= '<div class="pull-left">' . Html::entities($vMenu->name) . '</div>';
                $html .= '<div class="pull-right">';
                $html .= '<ul class="icons-list">
	                <li><a class="text-primary _update" data-id="' . $vMenu['_id'] . '" href="#"><i class="icon-pencil"></i></a></li>
	                <li><a class="text-danger _delete" data-id="' . $vMenu['_id'] . '" href="#"><i class="icon-bin"></i></a></li>
                </ul>';
                $html .= '</div>';
            $html .= '</div>';
            if (isset($vMenu->children) && count($vMenu->children) > 0) {
                $html .= $this->nestableAsHtml($vMenu->children);
            }

            $html .= '</li>';
        }

        $html .= '</ol>';

        return $html;
    }

    /**
     * Method for index query parent db
     */
    private function parentDB()
    {
        try {
            return $this->model->getParents();
        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
            return false;
        }
    }

    /**
     * Method for convert to nestable
     */
    public function nestable()
    {
        $model = $this->parentDB();
        if ($model) {
            return $this->nestableAsHtml($model);
        }

        return false;
    }

    /**
     * Method for get data by id
     */
    public function editDB($id)
    {
        $model = $this->findById($id);
        if ($model) {
            return [
                'success' => true,
                'model'   => $model
            ];
        }

        return [
            'success' => false,
            'messages' => $this->messages['database']['dataNotFound']
        ];
    }

    /**
     * Method for insert to db
     */
    public function insertDB(array $params)
    {
        $output = [
            'success'  => false,
            'messages' => $this->messages['database']['add_error']
        ];

        try {

            $model         = $this->model;
            $model->icon   = Html::entities($params['icon']);
            $model->name   = Html::entities($params['name']);
            $model->url    = Html::entities($params['url']);
            $model->parent = 0;
			$model->order  = $this->model->count();

            if ($model->save()) {
                $output = [
                    'success'  => true,
                    'messages' => $this->messages['database']['success']
                ];
            }

        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
            $output = [
                'success'  => false,
                'messages' => $this->messages['database']['cannot_connect']
            ];
        }

        return $output;
    }

    /**
     * Method for insert to db
     */
    public function updateDB($id, array $params)
    {
        $output = [
            'success'  => false,
            'messages' => $this->messages['database']['dataNotFound']
        ];

        try {

            $model = $this->findById($id);

            if ($model) {

                $output = [
                    'success'  => false,
                    'messages' => $this->messages['database']['update_error']
                ];

                $model->icon = Html::entities($params['icon']);
                $model->name = Html::entities($params['name']);
                $model->url  = Html::entities($params['url']);

                if ($model->save()) {
                    $output = [
                        'success'  => true,
                        'messages' => $this->messages['database']['success']
                    ];
                }

            }

        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
            $output = [
                'success'  => false,
                'messages' => $this->messages['database']['cannot_connect']
            ];
        }

        return $output;
    }

    /**
     * Method for get by id
     */
    private function findById($id)
    {
        try {
            return $this->model->find($id);
        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
            return false;
        }
    }

    /**
     * Method for clean data move nestable
     */
    public function moveDB(array $params)
    {
        $nestable = $this->nestable->parseJsonArray(json_decode($params['nestable-output'], true));

        if ($this->updateMoveDB($nestable)) {
            return [
                'success'  => true,
                'messages' => $this->messages['database']['success']
            ];
        }

        return [
            'success'  => false,
            'messages' => $this->messages['database']['update_error']
        ];
    }

    /**
     * Method for update move nestable
     */
    private function updateMoveDB(array $params)
    {
    	$output = false;

        try {

            if (is_array($params) && count($params) > 0) {
                foreach ($params as $kParam => $vParam) {

					$model = $this->findById($vParam['id']);
                    if ($model) {
                        $model->order  = $kParam;
                        $model->parent = $vParam['parent'];
                        $model->save();
                    }
                }

                $output = true;
            }

        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
            $output = false;
        }

        return $output;
    }

    /**
     * Method for delete menus
     */
    public function deleteDB($id)
    {
        $output = [
            'success'  => false,
            'messages' => $this->messages['database']['dataNotFound']
        ];

        $model = $this->findById($id);
        if ($model) {

            $output = [
                'success'  => false,
                'messages' => $this->messages['database']['delete_error']
            ];

            if ($this->deleteMenuTree($model) && $model->delete()) {
                $output = [
                    'success'  => true,
                    'messages' => $this->messages['database']['success']
                ];
            }
        }

        return $output;
    }

    /**
     * Method for delete menus tree
     */
    public function deleteMenuTree($model)
    {
        if (count($model->children) > 0) {
            foreach ($model->children as $kChild => $vChild) {

                if (count($vChild->children) > 0) {
                    $this->deleteMenuTree($vChild);
                }

                $vChild->delete();
            }
        }

        return true;
    }

    public function getMenuList()
    {
        $menuList    = $this->model->get()->toArray();
        $mappingList = [
            '_id' => 'id',
            'url' => 'name'
        ];

        $output = $this->formatMenuList($menuList, $mappingList);

        return $output;
    }

    private function formatMenuList($datas, $mappingList)
    {
        $outputs = [];
        foreach ($datas as $data) {
            $tmp = [];
            foreach ($mappingList as $key => $asKey) {
                $tmp[$asKey] = $data[$key];
            }

            $outputs[] = $tmp;
        }

        return $outputs;
    }
}
