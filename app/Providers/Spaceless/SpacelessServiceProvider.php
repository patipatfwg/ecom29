<?php
namespace App\Providers\Spaceless;

use Illuminate\Support\ServiceProvider;
use Blade;

/**
 * Clean Code
 *
 * @category Provider
 * @author   Egg Digital
 */

class SpacelessServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('spaceless', function() {
            return '<?php ob_end_clean(); ob_start(); ob_implicit_flush(false); ?>';
        });

        Blade::directive('endspaceless', function() {
            return "<?php echo trim(preg_replace('/>\s+</', '><', ob_get_clean())); ?>";
        });
    }

    public function register() {

    }
}