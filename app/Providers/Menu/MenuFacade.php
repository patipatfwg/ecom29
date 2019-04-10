<?php
namespace App\Providers\Menu;

use Illuminate\Support\Facades\Facade;

/**
 * Menu
 *
 * @category Provider
 * @author   Egg Digital
 */

class MenuFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'menus';
    }
}
