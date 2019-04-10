<?php
namespace App\Providers\Menu;

use Route;
use App\Models\Menus;

/**
 * Menu
 *
 * @category Provider
 * @author   Egg Digital
 */

class MenuBuilder
{
    protected $menus;
    protected $messages;

    /**
     * Method for get model menu
     */
    private function getModel()
    {
        return new Menus;
    }

    /**
     * Method for index query parent db
     */
    private function parentDB()
    {
        try {
            return $this->getModel()->getParents();
        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
            return false;
        }
    }

    /**
     * Method for menu to array
     */
    private function menuArray($menus, $permission)
    {
        $menuArray = [];

        if(!empty($permission)) {
            // $permission['config/payment_method'] = $permission['payment_option'];

            foreach ($menus as $kMenu => $vMenu) {
                ######## Fix for EPOS, Report, Pament Option ###########
                $menu_id = (isset($vMenu->name)) ? 'mnu_' . preg_replace('/\s{1,}/', '_', str_replace('-', '', strtolower(trim($vMenu->name)))) : '';
                if(preg_match("#/#", $vMenu->url)) {
                    $prefix = explode("/", $vMenu->url);
                    if($prefix[0] == 'epos'){
                        if((isset($permission['EPOS'][$prefix[1].'_search']) && $permission['EPOS'][$prefix[1].'_search'])
                            || (isset($permission['EPOS'][$prefix[1].'_order_search']) && $permission['EPOS'][$prefix[1].'_order_search'])
                            || (isset($permission['EPOS'][$prefix[1].'_order_export']) && $permission['EPOS'][$prefix[1].'_order_export'])
                            || ($prefix[1] == 'invoice_generate' && isset($permission['EPOS']['invoice_generate']) && $permission['EPOS']['invoice_generate'])
                        ) {

                            $menuArray[] = [
                                'url'  => (!empty($vMenu->url)) ? $vMenu->url : false,
                                'text' => $vMenu->name,
                                'icon' => $vMenu->icon,
                                'id'   => $menu_id
                            ];
                        }
                        continue;
                    } else if($prefix[0] == 'config'){

                        if(isset($permission['payment_option']) && $permission['payment_option'][array_keys($permission['payment_option'])[0]]){
                            $menuArray[] = [
                                'url'  => (!empty($vMenu->url)) ? $vMenu->url : false,
                                'text' => $vMenu->name,
                                'icon' => $vMenu->icon,
                                'id'   => $menu_id
                            ];
                        }
                        continue;
                    } else if($prefix[0] == 'report' || $prefix[0] == 'delivery_fee') {
                        if(isset($permission[$prefix[0]][$prefix[1]]) && $permission[$prefix[0]][$prefix[1]]) {
                            $menuArray[] = [
                                'url'  => (!empty($vMenu->url)) ? $vMenu->url : false,
                                'text' => $vMenu->name,
                                'icon' => $vMenu->icon,
                                'id'   => $menu_id
                            ];
                        }
                        continue;
                    }
                }
                ######## END Fix for EPOS, Report, Pament Option ###########

                ######## Fix for Category ###############
                if(isset($permission['category_business']['index']) && $permission['category_business']['index']) {
                    $permission['category']['index'] = true;
                }
                ######## End Category ###################
                if((isset($permission[$vMenu->url]['index']) && $permission[$vMenu->url]['index'])
                    || ($vMenu->name == 'E-POS' && $this->hasSubPermission('EPOS', $permission))
                    || ($vMenu->url == 'report' && $this->hasSubPermission('report', $permission))
                    || ($vMenu->url == 'delivery_fee' && $this->hasSubPermission('delivery_fee', $permission))
                ){

                    if (isset($vMenu->children) && count($vMenu->children) > 0) {
                        $menuArray[] =  [
                            'url'     => (!empty($vMenu->url)) ? $vMenu->url : false,
                            'text'    => $vMenu->name,
                            'icon'    => $vMenu->icon,
                            'submenu' => $this->menuArray($vMenu->children, $permission),
                            'id'      => $menu_id
                        ];
                    } else {
                        $vMenu->name = str_replace(' ', '   ', $vMenu->name);
                        $menuArray[] = [
                            'url'  => (!empty($vMenu->url)) ? $vMenu->url : false,
                            'text' => $vMenu->name,
                            'icon' => $vMenu->icon,
                            'id'   => $menu_id
                        ];
                    }
                }
            }
        }

        return $menuArray;
    }

    protected function hasSubPermission($menu, $permission) {

        if (!empty($permission[$menu])) {
            foreach($permission[$menu] as $val){
                if($val) {
                    return true;
                }
            }
        }

        return false;
    }

    public function render($permission)
    {
        $menus = [];
        $model = $this->parentDB();

        if ($model && !empty($permission['permission_menus'])) {
            $menus = $this->menuArray($model, $permission['permission_menus']);
        }

        $urlCurrent = Route::getFacadeRoot()->current()->uri();
        $urlFirst   = explode('/', $urlCurrent);

        return view('layouts.theme.menus', [
            'menus'      => $menus,
            'urlFirst'   => $urlFirst,
            'urlCurrent' => $urlCurrent
        ]);
    }
}
