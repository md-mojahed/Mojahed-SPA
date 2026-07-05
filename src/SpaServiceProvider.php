<?php

namespace Mojahed\Spa;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Mojahed\Spa\Commands\SpaSetupCommand;
use Mojahed\Spa\Commands\SpaPublishCommand;

class SpaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/spa.php',
            'spa'
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'spa');

        $this->registerDirectives();
        $this->registerComponents();
        $this->registerPublishing();
        $this->registerCommands();
    }

    protected function registerDirectives(): void
    {
        // @spacss — injects CSS link tags
        Blade::directive('spacss', function () {
            $path = config('spa.asset_path', 'assets/spa');
            $version = config('spa.asset_version', '');
            $v = $version ? "?v={$version}" : '';

            return "<?php echo '<link rel=\"stylesheet\" href=\"' . asset('{$path}/css/bootstrap.min.css') . '{$v}\">
<link rel=\"stylesheet\" href=\"' . asset('{$path}/css/spa.css') . '{$v}\">'; ?>";
        });

        // @spajs — injects JS script tags
        Blade::directive('spajs', function () {
            $path = config('spa.asset_path', 'assets/spa');
            $version = config('spa.asset_version', '');
            $v = $version ? "?v={$version}" : '';

            return "<?php echo '<script src=\"' . asset('{$path}/js/alpine.min.js') . '{$v}\" defer></script>
<script src=\"' . asset('{$path}/js/bootstrap.bundle.min.js') . '{$v}\"></script>
<script src=\"' . asset('{$path}/js/axios.min.js') . '{$v}\"></script>
<script src=\"' . asset('{$path}/js/sweetalert2.min.js') . '{$v}\"></script>
<script src=\"' . asset('{$path}/js/spa.js') . '{$v}\"></script>
<script>
    axios.defaults.headers.common[\"X-Requested-With\"] = \"XMLHttpRequest\";
    axios.defaults.headers.common[\"X-CSRF-TOKEN\"] = \"' . csrf_token() . '\";
    axios.defaults.headers.common[\"X-SPA-Request\"] = \"true\";
</script>'; ?>";
        });

        // @spadata or @spadata('customName')
        Blade::directive('spadata', function ($expression) {
            $name = $expression ? trim($expression, "'\"") : 'spa';
            return "x-data=\"{$name}()\"";
        });
    }

    protected function registerComponents(): void
    {
        $components = [
            'spa-modal'     => \Mojahed\Spa\Components\SpaModal::class,
            'spa-offcanvas' => \Mojahed\Spa\Components\SpaOffcanvas::class,
            'spa-target'    => \Mojahed\Spa\Components\SpaTarget::class,
            'spa-btn'       => \Mojahed\Spa\Components\SpaBtn::class,
            'spa-link'      => \Mojahed\Spa\Components\SpaLink::class,
            'spa-form'      => \Mojahed\Spa\Components\SpaForm::class,
            'spa-loader'    => \Mojahed\Spa\Components\SpaLoader::class,
        ];

        foreach ($components as $alias => $class) {
            Blade::component($alias, $class);
        }
    }

    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {

            // Config
            $this->publishes([
                __DIR__ . '/../config/spa.php' => config_path('spa.php'),
            ], 'spa-config');

            // Views (for customization)
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/spa'),
            ], 'spa-views');

            // Assets (JS/CSS)
            $this->publishes([
                __DIR__ . '/../resources/dist' => public_path(config('spa.asset_path', 'assets/spa')),
            ], 'spa-assets');
        }
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SpaSetupCommand::class,
                SpaPublishCommand::class,
            ]);
        }
    }
}
