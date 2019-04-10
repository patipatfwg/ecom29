<?php
namespace App\Providers\Menu;

use App\Providers\Menu\MenuBuilder;
use Illuminate\Support\ServiceProvider;

/**
 * Menu
 *
 * @category Provider
 * @author   Egg Digital
 */

class MenuServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('menus', function ($app) {
            return new MenuBuilder();
        });
    }
}
